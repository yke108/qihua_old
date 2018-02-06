<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <title>404</title>
    <link rel="stylesheet" href="/Public/Home/css/common.css">
    <link rel="stylesheet" href="/Public/Home/css/404.css">
</head>

<body>
<div class="head">
    <div class="wrapper">
        <div class="logo-search clearfix">
            <div class="d-logo fl">
                <a href="/"><img class="logo" src="/Public/Home/images/logo.png" alt="logo"></a>
            </div>

            <div class="fl head-tag">Sign In or Register</div>

            <p class="tel fr"><span class="tel-sp"><i class="icon-tel"></i>Support:</span>4008-488-999</p>
        </div>
    </div>
</div>

<!-- 网页主体内容 -->
<div class="wrapper wrapper-nest">
    <div class="info">
        <img src="/Public/Home/images/404.jpg" alt="">
        <a href="<?php echo $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/'?>" class="back">&nbsp;</a>
    </div>
</div>
<div class="footer">

    <div class="ft-inner">

        <div class="footer-nav clearfix">
            <div class="fl home-link">
                <a href="/"><img src="/Public/Home/images/logo.png" alt=""></a>
            </div>
            <div class="fl footer-nav-link">
                <dl>
                    <dt class="dt">About Keywa</dt>
                    <dd><a class="a-link" href="">About us</a></dd>
                    <dd><a class="a-link" href="">Contact us </a></dd>
                    <dd><a class="a-link" href="">Media Report </a></dd>
                    <dd><a class="a-link" href="">Terms of Use </a></dd>
                    <dd><a class="a-link" href="">Legal stat</a></dd>
                </dl>
            </div>
            <div class="fl worktime">
                <p class="tel">4008-488-999</p>
                <div class="time">Online:  9:00 ~ 17:00   ( Mon. - Fri. ) </div>
            </div>
            <div class="fr lang-link">
                <p class="tips">Language</p>
                <p class="alink">
                    <a class="lang-sp"><i class="icon-cn"></i>中文</a>
                    <a class="lang-sp"><i class="icon-en"></i>English</a>
                </p>
            </div>
        </div>

        <div class="copyright">Keywa.com © Copyright 2016 Keywa Inc. All rights reserved. <span class="icp">ICP No.:GuangDong14005764</span></div>

    </div>
</div>

</body>


</html>
