<?php require_once('config.php'); ?><!DOCTYPE HTML>
<html lang="de">
<body>
<script src="./assets/jquery.min.js"></script>
<head>
<meta charset="UTF-8">
<title>SVXReflector Dashboard </title>
</head>
<script type="text/javascript">
$(document).ready(function() {
    $("#create_html").load("create_html.php");
    var refreshId = setInterval(function() {
        $("#create_html").load('create_html.php?' + 1*new Date());
    },<?php echo(((REFRESH_DLY>0)?REFRESH_DLY:1)*1000); ?>);
});
</script>
<div id="create_html"></div>
</body>

