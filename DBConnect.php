<?php
$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/admin/private/.env", "r");
        
    $credentials = explode("->", fgets($file));
    //---------------------------------------------------------------------//
        $usrname 	= trim($credentials[1]);
        $credentials = explode("->", fgets($file));
        $passwrd 	= trim($credentials[1]);
        $credentials = explode("->", fgets($file));
        $DSN 		= trim($credentials[1]);
    //---------------------------------------------------------------------//

    fclose($file);

    $conn;

    try {
        $conn = new PDO($DSN, $usrname, $passwrd);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die("Die Verbindung zur Datenbank konnte nicht hergestellt werden.");
    }
?>