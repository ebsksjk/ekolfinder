<?php

    //SELECT Name FROM Stations WHERE Name LIKE 

    require('DBConnect.php');

    //echo $_GET['query'] . " \n";

    foreach($DBASE->query('Select t.Zugnummer, t.Name FROM Trains AS t WHERE t.Zugnummer LIKE "'.$_GET['query'].'%";') as $row) {
        echo "<div class='results'> Zugnummer: <a href='showTrain.php?id=". $row['Zugnummer'] . "'>".$row['Zugnummer']."</a>".  
        " <br/> Name: " . $row['Name'] . 
       "</div>";
    }

?>