<?php
/* Connect to db */
try {
	$dbh = new PDO('mysql:host=localhost;dbname=adenjone_grow_db','adenjone_grow_db','dalek8_name$');
} catch (Exception $e) {
	
    echo "Failed: " . $e->getMessage();
	$dbh->rollBack();

}


?>