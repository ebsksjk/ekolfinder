<html>
    <head>
        <title>ekolfinder</title>
        <link rel="stylesheet" href="styles/index.css">
    </head>
    <body>

        <?php

            //SELECT Name FROM Stations WHERE Name LIKE 

            require('DBConnect.php');

            foreach($DBASE->query('Select t.Zugnummer, t.Name, c.Type, s1.Name AS "Start", s2.Name AS "Ende", o.ID AS "OID", o.Name AS "Betreiber", tt.Name AS "Zugtyp" from Trains AS t INNER JOIN Cargotypes AS c ON t.CargotID=c.ID 
            INNER JOIN Stops AS s1 ON t.StartID=s1.ID INNER JOIN Stops AS s2 ON t.EndID=s2.ID
            INNER JOIN Operators AS o ON t.OperatorID=o.ID
            INNER JOIN Traintypes AS tt ON t.tTypeID=tt.ID WHERE t.Zugnummer="'.$_GET['id'].'";') as $row) {
                echo "<div class='train-info'>Zugnummer: " . $row['Zugnummer'] .  
                " <br/> Name: " . $row['Name'] . 
                " <br/> Zugart: " . $row['Type'] . 
                " <br/> Von: " . $row['Start'] . 
                " <br/> Nach: " . $row['Ende'] . 
                " <br/> Betreiber: <a href='showOperator.php?id=". $row['OID'] ."'>" . $row['Betreiber'] . 
                "</a> <br/> Trasse: " . $row['Zugtyp'] . "</div>";
            }

        ?>

        <div class='train-image'>
            <?php
                foreach($DBASE->query("SELECT e.Baureihe, e.Name from Consists AS c 
                INNER JOIN Engines AS e ON c.EngineID=e.EngineID WHERE c.TrainID='".$_GET['id'] ."';") as $row){
                    echo "<div class='loco-image'>" .
                    "<img src='data/images/" . $row['Baureihe'] . ".png' />" .
                    "<p>" . $row['Baureihe'] . " - " . $row['Name'] . "</p>" . 
                    "</div>";
                }

                foreach($DBASE->query("SELECT w.Name, w.Owner from Trainsets AS ts 
                INNER JOIN Wagon AS w ON w.ID=ts.WagonID WHERE ts.TrainID='".$_GET['id'] ."' ORDER BY ts.Folge ASC;") as $row){
                    echo "<div class='wagon-image'>" .
                    "<img src='data/images/" . $row['Name'] . "_" . $row['Owner'] .".png' />" .
                    "<p>" . $row['Name'] . " - " . $row['Owner'] . "</p>" . 
                    "</div>";
                }
            ?>
        </div>

    </body>
</html>