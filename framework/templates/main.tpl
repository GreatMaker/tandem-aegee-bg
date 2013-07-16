<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><%TITLE></title>
    <link rel="stylesheet" href="include/css/style.css" type="text/css" />
	<%CSS>
	<script src="include/js/jquery-1.10.2.min.js" type="text/javascript"></script>
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
		<a href="index.php?page=home" title="Tandem Bergamo"><img src="img/logo_tandem.png" alt="Tandem Bergamo" border="0" /></a>
		<div id="header-social">
			<%SOCIAL>
		</div>
	</div> <!-- header -->

	<div id="nav">
		<ul>
        	<%MENU>
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
			<a href="http://www.aegeebergamo.eu" title="AEGEE-Bergamo"><img src="img/logo_aegee-bergamo_mini.png" alt="AEGEE-Bergamo" border="0" /></a>&nbsp;
			<a href="http://www.unibg.it" title="UniBG"><img src="img/logo_UNIbg_mini.png" alt="UniBG" border="0" /></a>
		</div>
	</div> <!-- footer-content -->

	<div class="footer-width footer-bottom">
		<p>&copy;2013 Tandem Project Bergamo by <a href="http://www.aegeebergamo.eu">AEGEE-Bergamo</a></p>
	</div> <!-- footer-bottom -->
</div> <!-- footer -->
</body>
</html>