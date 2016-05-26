<?php
require_once('../../includes/prepend.php');
require_once('includes/header.php');
require_once('../../includes/header.php');

?>
    <title>Staab's Comic Viewer</title>
    <script type="text/javascript">
        // getPageScroll() by quirksmode.com
        function getPageScroll() {
            var xScroll, yScroll;
            if (self.pageYOffset) {
              yScroll = self.pageYOffset;
              xScroll = self.pageXOffset;
            } else if (document.documentElement && document.documentElement.scrollTop) {
              yScroll = document.documentElement.scrollTop;
              xScroll = document.documentElement.scrollLeft;
            } else if (document.body) {// all other Explorers
              yScroll = document.body.scrollTop;
              xScroll = document.body.scrollLeft;
            }
            return new Array(xScroll,yScroll)
        }
    </script>
    <style type="text/css">
    body {
        color: #ddd;
        font-family: helvetica;
        background-color: #333;
        padding: 10px 200px;
    }
    .wrapper {
        width: 1000px;
    }
    a {
        color: white;
    }
    .clear-comics {
        position: fixed;
        bottom: 20px;
        left: 10px;
    }
    </style>
</head>
<body>
    <a href="#" class="clear-comics">
        Clear Old Comics
    </a>
    <div class="wrapper">
        <h1>Staab's Comic Viewer</h2>
            <p>
                To start viewing comics, just enter your username (it serves to bookmark your place in all your comics), and choose a comic from the list, then click Go! 
            </p>
            <p>
                It'll take a bit to load the first comic, but from there on, everything should be seamless (unless you read for a really long time. Then it'll start to lag. If that happens, just hit the "clear" button on the bottom left, and you should be at the same spot you left off on).
            </p>
        <form id="comic_params" action="#">
            <input type="text" name="name"></input>
            <select name="comic">
                <option value="pearlsbeforeswine">Pearls Before Swine</option>
                <option value="dilbert">Dilbert</option>
            </select>
            <input type="submit" value="Go!" />
        </form>
        <a id="older_comics" href="#">View older comics</a>
        <div class="content">
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){


            //
            //
            // GLOBAL VARIABLES
            var name = "",
                comic = 'dilbert',
                date = '',
                fetchingComic = false, //Necessary because document.scroll fires tons of events
                $getNextComicCall = null;
            //
            //
            //


            function getStartDate(){
                $.ajax({
                    url: "get_start_date.php",
                    data: {
                        Name: name,
                        Comic: comic
                    },
                    success: function(res){
                        date = '';
                        getNextComic(res);
                    }
                });
            }
            function getNextComic(newDate){
                if(newDate){
                    date = newDate
                }

                fetchingComic = true;

                // Another global variable, so we can abort it if we need to.
               $getNextComicCall = $.ajax({
                    url: "get_img.php",
                    data: {
                        Comic: comic,
                        ComicDate: date
                    },
                    success: function(res){
                        res = JSON.parse(res);
                        date = res.date; //global variable
                        updateBookmark();
                        fetchingComic = false;
                        $("<p>"+res.date+"</p>").appendTo('.content');
                        $("<img src='"+res.img+"'><br>").appendTo('.content');
                        if(isNextComicNeeded() == true){
                            getNextComic();
                        }
                    }
                });
            }
            function isNextComicNeeded(){
                var scroll = getPageScroll();
                var height = $('body').height();
                if(scroll[1] > height - 2000){
                    return true;
                }
                return false;
            }
            function updateBookmark(rewind){
                $.ajax({
                    url: "update_bookmark.php",
                    data: {
                        Name: name,
                        Comic: comic,
                        ComicDate: date,
                        Rewind: rewind
                    }
                })
            }
            $("a.clear-comics").click(function(){
                if($getNextComicCall){
                    $getNextComicCall.abort();
                }
                $('.content').empty();
                getStartDate();
            });
            $("a#older_comics").click(function(){
                if($getNextComicCall){
                    $getNextComicCall.abort();
                }
                updateBookmark(true);
                $('.content').empty();
                getStartDate();
            });
            $("form#comic_params").submit(function(e){
                e.preventDefault();
                if($getNextComicCall){
                    $getNextComicCall.abort();
                }
                $('.content').empty();
                //update some global variables
                name = $(this).find("[name='name']").val();
                comic = $(this).find("[name='comic']").val();
                getStartDate();
            });
            $(document).scroll(function(){
                if(isNextComicNeeded() === true && !fetchingComic){
                    getNextComic();
                }
            });
        });
    </script>
</body>
</html>