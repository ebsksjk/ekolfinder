<html>
    <head>
        <title>ekolfinder</title>
        <link rel="stylesheet" href="styles/index.css">
    </head>
    <body>

        <?php

            //SELECT Name FROM Stations WHERE Name LIKE 

            require('DBConnect.php');

            foreach($DBASE->query('Select t.Zugnummer, t.Name, o.ID AS "OID", o.Name AS "Betreiber" from Trains AS t
            INNER JOIN Operators AS o ON t.OperatorID=o.ID
            WHERE o.ID='.$_GET['id'].';') as $row) {
                echo "<div class='train-info'>Zugnummer: <a href='showTrain.php?id=". $row['Zugnummer'] . "'>".$row['Zugnummer']."</a>
                <br/> Name: " . $row['Name'] . 
                " <br/> Betreiber: " . $row['Betreiber'] . "</div>";
            }

        ?>

    </body>
</html>