<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Waterfall Layout</title>
    <link rel="stylesheet" href="/waterfall/style.css" />
</head>
<body>
<div class="search-area">
    <form action="">
        <input type="text" name="search_string" placeholder="输入搜索关键字" value="<?php echo $search_string ?>">
        <input type="submit" value="搜索">
    </form>
</div>
<div id="notice" class="off"></div>
<div id="cells"></div>
<div id="loader"><span>loading</span></div>

<script type="text/template" id="template">
    <p><a href="#"><img src="{{src}}" height="{{height}}" width="{{width}}" /></a></p>
    <div>Photo by: <a class="author" target="_blank" href="{{author_url}}">{{author_name}}</a></div>
    <div class="fr">Hosted on <a class="source" target="_blank" href="{{source_url}}">Unsplash</a></div>
<!--    <span class="copy">复制链接</span>-->
    <span class="download"><a target="_blank" href="{{download}}">下载图片</a></span>
</script>

<script src="/waterfall/front.js/front.min.js"></script>
<script src="/waterfall/script.js"></script>
</body>
</html>
