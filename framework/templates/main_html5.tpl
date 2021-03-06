<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><%TITLE></title>
	<meta name="description" content="Tandem Project Bergamo is based on the idea that the best way to learn a language, is to practise it. In this project we want to provide a platform for university students to meet with native speakers from all around to put their knowledge into practise, improve their skills and in return teach someone else your own native language." />
	<meta name="keywords" content="tandem, project, bergamo, tandem bergamo, tandem project, languages, exchange, scambio, lingue" />
	<meta name="author" content="AEGEE-Bergamo" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="icon" href="http://tandem.unibg.it/favicon.ico" />
    <link rel="stylesheet" href="/include/css/style5.css" />
	<%CSS>
	<script src="/include/js/jquery-1.10.2.min.js"></script>
	<%JS>
	<%HEAD>
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-45533885-1', 'unibg.it');
	ga('send', 'pageview');

	</script>
	<script>
		$(document).ready(function()
		{
			<%JQUERY>
		});
		<%PLAINJS>
	</script>
</head>
<body>
<main>
	<header>
		<a href="index.php?page=home" title="Tandem Bergamo"><img src="/img/logo_tandem.png" alt="Tandem Bergamo" style="border: 0;" /></a>
		<div id="header-social">
			<%SOCIAL>
		</div>
	</header> <!-- header -->

	<nav>
		<ul>
        	<%MENU>
		</ul>
	</nav> <!-- navigation -->

	<section id="body">
		<aside>
			<ul>
				<%SIDEBAR>
			</ul>
		</aside> <!-- sidebar -->

		<section id="main_content">
		<%MAIN> <!-- content -->
		</section>

    	<section class="clear"></section>
	</section> <!-- body -->
</main> <!-- container -->

<footer>
	<div class="footer-content">
		<div class="footer-width">
			<div style="float: left;"><a href="http://www.aegeebergamo.eu" title="AEGEE-Bergamo"><img src="/img/logo_aegee-bergamo_mini.png" alt="AEGEE-Bergamo" style="border: 0;" /></a></div>
			<div style="float: right;"><a href="http://www.unibg.it" title="UniBG"><img src="/img/logo_UNIbg_mini.png" alt="UniBG" style="border: 0;" /></a></div>
		</div>
	</div> <!-- footer-content -->

	<div class="footer-width footer-bottom">
		<p>&copy;2013 Tandem Project Bergamo by <a href="http://www.aegeebergamo.eu">AEGEE-Bergamo</a>
		<a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" title="CC License" rel="license"><img alt="Creative Commons License" style="border-width:0; float: right;" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" /></a></p>
	</div> <!-- footer-bottom -->
</footer> <!-- footer -->
</body>
</html>