<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{$path}}packages/404/css/main.css" type="text/css" media="screen, projection" />
    <!-- main stylesheet -->
    <link rel="stylesheet" type="text/css" media="all" href="{{$path}}packages/404/css/tipsy.css" />
    <!-- Tipsy implementation -->

    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="{{$path}}packages/404/css/ie8.css" />
    <![endif]-->

    <script type="text/javascript" src="{{$path}}js/jquery-1.10.2.min.js"></script>
    <!-- uiToTop implementation -->
    <script type="text/javascript" src="{{$path}}packages/404/js/custom-scripts.js"></script>
    <script type="text/javascript" src="{{$path}}packages/404/js/jquery.tipsy.js"></script>
    <!-- Tipsy -->

    <script type="text/javascript">
        $(document).ready(function () {

            universalPreloader();

        });

        $(window).load(function () {

            //remove Universal Preloader
            universalPreloaderRemove();

            rotate();
            dogRun();
            dogTalk();

            //Tipsy implementation
            $('.with-tooltip').tipsy({
                gravity: $.fn.tipsy.autoNS
            });

        });
    </script>


    <title>404错误:你访问的页面丢失了</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>

<!-- Universal preloader -->
<div id="universal-preloader">
    <div class="preloader">
        <img src="{{$path}}packages/404/img/universal-preloader.gif" alt="universal-preloader" class="universal-preloader-preloader" />
    </div>
</div>
<!-- Universal preloader -->

<div id="wrapper">
    <!-- 404 graphic -->
    <div class="graphic"></div>
    <!-- 404 graphic -->
    <!-- Not found text -->
    <div class="not-found-text">
        <h1 class="not-found-text">抱歉，指定的页面不存在！</h1>
    </div>
    <!-- Not found text -->

    <!-- Return button -->
    <div class="return-btn">
        <a href="../dashboard/index" class="returnhome">首页</a>
    </div>
    <div class="return-btn">
        <a href="javascript:history.go(-1);" class="returnlast">返回</a>
    </div>
    <!-- Return button -->

    <!-- top menu -->

    <div class="dog-wrapper">
        <!-- dog running -->
        <div class="dog"></div>
        <!-- dog running -->

        <!-- dog bubble talking -->
        <div class="dog-bubble">

        </div>

        <!-- The dog bubble rotates these -->
        <div class="bubble-options">
            <p class="dog-bubble">
                你迷路了呀？别担心，我是一个很好的指导！
            </p>
            <p class="dog-bubble">
                <br /> 汪汪汪!汪汪汪!
            </p>
            <p class="dog-bubble">
                <br /> 不用担心！我就可以了！
            </p>
            <p class="dog-bubble">
                我希望我有一块饼干
                <br /><img style="margin-top:8px" src="{{$path}}packages/404/img/cookie.png" alt="cookie" />
            </p>
            <p class="dog-bubble">
                <br /> 吉兹！真是相当烦人！
            </p>
            <p class="dog-bubble">
                <br /> 我是不是越来越近？
            </p>
            <p class="dog-bubble">
                或者，我只是要在圈子？...
            </p>
            <p class="dog-bubble">
                <br /> 好吧，我现在正式丢失了...
            </p>
            <p class="dog-bubble">
                我想我看到了一个
                <br /><img style="margin-top:8px" src="{{$path}}packages/404/img/cat.png" alt="cat" />
            </p>
            <p class="dog-bubble">
                什么是我们应该找的啊？
                <br/> @ _ @
            </p>
        </div>
        <!-- The dog bubble rotates these -->
        <!-- dog bubble talking -->
    </div>

    <!-- planet at the bottom -->
    <div class="planet"></div>
    <!-- planet at the bottom -->
</div>

<div style="display:none">
    <script src='http://v7.cnzz.com/stat.php?id=155540&web_id=155540' language='JavaScript' charset='gb2312'></script>
</div>
</body>

</html>