<?php

// PUBLIC: alle configuratie instellingen voor blog-pagina

class ConfigBlog {

	// eventuele properties hier


	
	public function retrieveAllBlogPost() {
	// haalt alle blogposts op uit database
	/* ToDo:
		- Mogelijkheid previews te maken op basis van max. aantal tekens, link naar pagina maken voor volledige post
		- HTML elementen op pagina aanpassen naar while-loop
		- ...
	*/
		
		// try query
		try {
			// nieuwe PDO
			$stmt = new PDO("mysql:host=localhost;dbname=demo", 'root', '');
			$stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// query aanmaken
			$query = $stmt->prepare("SELECT modify_date, title, intro, content FROM `evdnl_blog_posts_yc` ORDER BY `modify_date` DESC");
			$query->execute();

			// loopen door resultaten en alle posts weergeven
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				echo '<div class="posttitle">'.$row['title'].'</div>';
				echo '<div id="first">'.$row['intro'].'</div>';
				echo '<div id="content">'.$row['content'].'</div>';
				echo '<div class="postdate">'.$row['modify_date'].'</div>';
			}

			// db connectie sluiten
			$query = NULL;
		}

		// catch error
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}


	// weergave datum / datum + tijd
	public function modifyDateTime() {
		///
	}

	

}

?>