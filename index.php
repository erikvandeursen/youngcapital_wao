<?php
require_once('/classes/public/blog.public.php');
$configblog = new ConfigBlog;
?>
<html doctype="html">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/style.css"></link>
	 <link href='https://fonts.googleapis.com/css?family=Fira+Sans:300,700,400' rel='stylesheet' type='text/css'>
	 <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
	 <link href='https://fonts.googleapis.com/css?family=Karma' rel='stylesheet' type='text/css'>
	 <script type="text/javascript" src="/libs/jquery-1.11.3/jquery-1.11.3.min.js"></script>
	 <script>
		 $(window).scroll(function () {
		 	console.log($(this).scrollTop());
		 	if ($(this).scrollTop() > 85) {
		 		$('#subnav').addClass("sticky");
		 	} else {
		 		$('#subnav').removeClass("sticky");
		 	}
		 });
	 </script>
	<title>evd.nl blog</title>
</head>
<body>
	<div id="frame"></div>
	<div id="nav">
		<div id="subnav">
			<div id="mark">
				<a href="index.php">erik van deursen</a>
			</div>
			<div id="menu">
				<ul>
					<li><a href="admin/index.php">login</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div id="wrapper">
		<?php
		$configblog->retrieveAllBlogPost();
		?>
		<div id="footer">
		</div>
	</div>
</body>
</html>