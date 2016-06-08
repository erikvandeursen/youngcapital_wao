<?php

// eenvoudige Login class om admin menu af te schermen. Enkel voor demonstratie, niet productie

class ConnectDB {
	
	public function connect() {
		return new PDO("mysql:host=localhost;dbname=demo", 'root', '') ;
	}

}

?>