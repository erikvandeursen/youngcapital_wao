<html>
<head>
	<meta charset="utf-8">
	<title>admin</title>
	<link rel="stylesheet" href="../../css/admin-style.css">
	<script src='../../js/tinymce/tinymce.min.js'></script>
	<script>
	tinymce.init({
		selector: '.tinymcetextarea',
		theme: 'modern',
		plugins: 'link preview print'
	});

	function jsdeletepost(id, title) {
		if (confirm("Deze post: " + title + " verwijderen?")) {
			window.location.href = 'index.php?jsdeletepost' + id;
		}
	}
	</script>
</head>
<body>
<a href="../../index.php">index.php</a>
<?php
include 'config.blog.php';
$sqlc = new BlogAdmin();

// check conditional voor editen blogposts	
	if (isset($_GET['id'])){
		$blog_data = $sqlc->get_blog_post($_GET['id']);
	} elseif (isset($_GET['id']) && $_POST['admblogsubmit']){
		// update deblog met een update query;
		$sqlc->updateBlogPost();
	} else {
		$blog_data = array();
	}

?>

<div class="wrapper">

<h1>Editor</h1>
<p>Hier editor inladen om nieuwe teksten toe te voegen en te bewerken</p>

<form method="post" action="<?php $sqlc->saveBlogPost(); ?>"
	<h2>Titel</h2>
	<input type="text" name="admblogtitle" value = "<?php if (isset($_GET['id'])) { echo $blog_data['title']; } ?>" ></input><br>
	<h2>Intro</h2>
	<textarea class="tinymcetextarea" name="admblogintro" ><?php if (isset($_GET['id'])) { echo $blog_data['intro']; } ?></textarea>
	<h2>Content</h2>
	<textarea class="tinymcetextarea" name="admblogcontent"><?php if (isset($_GET['id'])) { echo $blog_data['content']; } ?></textarea>
	<input type="submit" name = "admblogsubmit" value="Opslaan op pagina"></input>
</form>

<h2>Instellingen</h2>
<p>Diverse instellingen voor de editor</p>
<form method="post" action="">
<span>Weergave datum/tijd:</span>
<input type="radio" name="admdatetime" value="datetime" checked> Datum en tijd</input>
<input type="radio" name="admdatetime" value="dateonly"> Datum</input>
<input type="submit" value="Opslaan"></input>
</form>

<h1>Archief</h1>
<p>Eerder aangemaakte posts</p>
<table>
	<tr>
		<th>#</th>
		<th>titel</th>
		<th>aangemaakt</th>
		<th>gewijzigd</th>
		<th>views</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
	<?php
	$sqlc->getAllBlogPosts();
	?>
</table>
</div>