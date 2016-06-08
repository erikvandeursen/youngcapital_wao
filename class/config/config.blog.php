<?php

// alle connecties met db beheren

class BlogAdmin {

	// db gegevens
	/* ToDo:
		- in externe file definen en hier requiren
	*/
	private $host = 'localhost';
	private $name = 'demo';
	private $user = 'root';
	private $pass = '';


	// ADMIN: maak bestand en filepath aan voor saven en ophalen SEO-vriendelijke URL
	public function createBlogPostFile() {

		$admblogtitle = trim(htmlentities($_POST["admblogtitle"]));
		$admblogintro = trim(htmlentities($_POST["admblogintro"]));
		$admblogcontent = trim(htmlentities($_POST["admblogcontent"]));

		if (!empty($_POST["admblogtitle"])) {
			try {
			
				// try query
				$stmt = new PDO("mysql:host=localhost;dbname=demo", 'root', '');
				$query = $stmt->prepare("SELECT create_date, dashedtitle FROM `evdnl_blog_posts_yc` ORDER BY id DESC LIMIT 0, 1");
				$query->execute();
				$row = $query->fetch(PDO::FETCH_ASSOC);
			
				// sluit PDO connectie
				$query = NULL;

				$filepath =  "../../posts/" . substr($row['create_date'], 0, 10) . '-' . strtolower(preg_replace('/[[:space:]]+/', '-', $_POST['admblogtitle'])) . '.php';
				//$filepath =   . $filename;
				file_put_contents($filepath, $admblogintro, FILE_APPEND);
			}
			// catch error
			catch (PDOException $e) {
					echo $e->getMessage();
			}
		}
	}


	// ADMIN: schrijft blog post weg in database
	/* ToDo:
		- voorkomen dat page refresh POST opnieuw verzend en titel & content wegschrijft in db
		- volledige escaping van $admblogtitle en $admblogcontent maken
		- tijdsstempel voor aanmaken meegeven
	*/
	public function saveBlogPost() {
		
		//$this->verifyID($toid);
		$html_tags = '<b><p><br></br><u><ul><li><table><tr><th><td><i>';

		// controleer of submit gepost is
		if (isset($_POST['admblogsubmit'])) {
			
			// check: velden voor titel en content zijn niet leeg
			if (!empty($_POST["admblogtitle"] && $_POST['admblogcontent'])) {

				// schrijf post inputs weg naar variabelen
				$admblogtitle = strip_tags($_POST["admblogtitle"], $html_tags);
				$admblogintro = strip_tags($_POST["admblogintro"], $html_tags);
				$admblogcontent = strip_tags($_POST["admblogcontent"], $html_tags);
				$admurl = strtolower(preg_replace('/[[:space:]]+/', '-', $_POST['admblogtitle']));
				
				// maak PDO connectie om weg te schrijven + set attributes voor error meldingen
				$stmt = new PDO("mysql:host=localhost;dbname=demo", 'root', '');
				$stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				// try query
				try {
						$query = $stmt->prepare("INSERT INTO `evdnl_blog_posts_yc` (create_date, modify_date, title, intro, content, dashedtitle) VALUES(CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '$admblogtitle', '$admblogintro', '$admblogcontent', '$admurl')");
						$query->execute();
						$this->createBlogPostFile();

						// sluit PDO connectie
						$query = NULL;
					}	
					
					// catch error
					catch (PDOException $e) {
						echo $e->getMessage();
				}
			}
		}
	}

	// ADMIN: mogelijkheid via admin overzicht posts te verwijderen
	public function deleteBlogPost() {
		try {

			// PDO iniatialiseren met attributes
			$stmt = new PDO("mysql:host=localhost;dbname=demo", 'root', '');
			$stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// mogelijkheid inbouwen waarschuwingsvenster voor verwijderen te openen


			
			$query = $stmt->prepare('SELECT id, create_date, title, intro, content FROM `evdnl_blog_posts_yc`');
			
			while($row = $query->fetch()) {
				// confirmation dialog
				echo '<tr>';
				echo '<td>' . $row['title'] . '</td>';
				echo '<td>' . date('jS M Y', strtotime($row['title'])) . '</td>';
				echo '<td><a href="javascript:jsdeletepost(' . $row['id']; 
				echo $row['title'];
				echo ')">Delete</a>';
				echo '</td>';
				echo '</tr>';

				// rij / id ophalen om te verwijderen
				if (isset($_GET['jsdeletepost'])) {
					$todelete = $stmt->prepare('DELETE FROM `evdnl_blog_posts_yc` WHERE id = "$row" LIMIT 1');
					$todelete->execute($row);

					header('Location: index.php?action=deleted');
					exit;
					}

				// file verwijderen
				// unlink();

				// rij uit db verwijderen

				}
				/*
				
				*/
		}

		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function get_blog_post($blogid)
	{

		$stmt = new PDO("mysql:host=localhost;dbname=demo", 'root', '');
		$stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$query = "SELECT * FROM `evdnl_blog_posts_yc` WHERE `id` = '" . $blogid . "' LIMIT 1 ; ";
		//$query = $stmt->prepare($query);

		foreach ($stmt->query($query) as $key => $value) {
			$blog_data = $value;
		}

		return $blog_data;

	}

	// ADMIN: haalt overzicht aan oudere blogposts op uit db
	public function getAllBlogPosts() {
		// try-catch db connectie
		try {
			
			// PDO iniatialiseren met attributes
			$stmt = new PDO("mysql:host=localhost;dbname=demo", 'root', '');
			$stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// query om alle posts op te halen (id, aanmaakdatum, wijzigingsdatum, titlel en titel-met-strepen)
			$query = $stmt->prepare('SELECT id, create_date, modify_date, title, dashedtitle FROM `evdnl_blog_posts_yc`');
			$query->execute();
			
			// loop door de resultaten en geef deze als HTML tabel weer op de pagina 
			// definieer file path

			//echo"<pre>"; var_dump($query->fetch(PDO::FETCH_ASSOC)); echo "</pre>"; die('i je moeders schoot');

			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$filepath = '../../posts/' .  substr($row['create_date'], 0, 10) . '-' . $row['dashedtitle'] . '.php';
				// echo tabel
				echo '<tr>';
				echo '<td><a href="' . $filepath . '">'.$row['id'].'</a></td>';
				echo '<td>'.$row['title'].'</td>';
				echo '<td>'.$row['create_date'].'</td>';
				echo '<td>'.$row['modify_date'].'</td>';
				echo '<td>' . 0 . '</td>';
				echo '<td><a href="' . $filepath . '"><img src="../../img/admin/globe-grid.png" width="25" height="25" alt="live"></img></a></td>';
				echo '<td><a href=?id=' . $row['id'] . '><img src="../../img/admin/writing.png" width="25" height="25" alt="bewerk"></img></a></td>';
				echo '<td><a href="' . $this->deleteBlogPost() . '"><img src="../../img/admin/trash.png" width="25" height="25" alt="verwijderen"></img></a></td>';
				echo '</tr>';
			}
			
			// sluit db connectie
			$query = NULL;
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	// ADMIN: bewerk een blogpost, wordt aangeroepen vanuit config.page.blog.php
	public function updateBlogPost() {

		$getid = $_GET['id'];
		$updtitle = $_POST['admblogtitle'];
		$updintro = $_POST['admblogintro'];
		$updcontent = $_POST['admblogcontent'];

		try {
			// PDO initialiseren met attributes
			$stmt = new PDO("mysql:host=localhost;dbname=demo", 'root', '');
			$stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// query om post op te halen
			$query = $stmt->prepare(
					"UPDATE `evdnl_blog_posts_yc` SET title='$updtitle', intro='$updintro', content='$updcontent' WHERE `id` = '$getid' LIMIT 1;
					 UPDATE `evdnl_blog_posts_yc` SET modify_date=CURRENT_TIMESTAMP WHERE `id` = '$getid' LIMIT 1"
					);
			$query->execute();

			echo $stmt->rowCount() . " records UPDATED successfully";

			// header aanroepen om leeg formulier te tonen
		}
		catch (PDOException $e) {
			$e->getMessage();
		}
	}


}

?>