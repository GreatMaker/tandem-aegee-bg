<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><%TITLE></title>
    <link rel="stylesheet" href="include/css/style.css" type="text/css" />
	<%CSS>
	<script src="include/js/jquery-1.9.1.min.js" type="text/javascript"></script>
	<%JS>
	<%HEAD>
	<script>
		$(document).ready(function()
		{
			<%JQUERY>
		});
	</script>
</head>
<body>
<div id="container">
	<div id="header">
		<h1><a href="/">inception</a></h1>
		<h2>your website slogan here</h2>
		<div class="clear"></div>
	</div> <!-- header -->

	<div id="nav">
		<ul>
        	<li><a href="index.html">Home</a></li>
            <li><a href="examples.html">Examples</a></li>
            <li><a href="#">Products</a></li>
            <li><a href="#">Solutions</a></li>
            <li><a href="#">Contact</a></li>
		</ul>
	</div> <!-- navigation -->

	<div id="body">
		<div id="content">
			<%MAIN>
		</div> <!-- content -->

		<div class="sidebar">
			<ul>
<%SIDEBAR>
			</ul>
		</div> <!-- sidebar -->

    	<div class="clear"></div>
	</div> <!-- body -->
</div> <!-- container -->

<div id="footer">
	<div class="footer-content">
		<div class="footer-width">

            <span class="sitename">sitename</span>
                <p class="footer-links">
                <a href="index.html">Home</a> |
                <a href="examples.html">Examples</a> |
                <a href="#">Products</a> |
                <a href="#">Solutions</a> |
                <a href="#">Contact</a>

            </p>
		</div>
	</div> <!-- footer-content -->

	<div class="footer-width footer-bottom">
		<p>&copy;2012 Tandem Project Bergamo by <a href="http://www.aegeebergamo.eu">AEGEE-Bergamo</a></p>
	</div> <!-- footer-bottom -->
</div> <!-- footer -->
</body>
</html>