<DOCTYPE! html>

<?php
    require('DBConnect.php');
?>
<html>
    <head>
        <title>Loks anschauen</title>
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/table.css">
        <link rel="stylesheet" href="styles/engines.css">
    </head>
    </body>
        <?php
            $baureihe = '';

            foreach($conn->query("SELECT Baureihe, Ordnungsnummer from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                $baureihe = $row["Baureihe"] . ' ' . $row['Ordnungsnummer'];
            }
        ?>
        <div class="flexy">
        <div class="main">
            <h1 class="title">Loks ansehen: </h1>

            <table class="result-table">
                <tr>
                    <th>Bild</th>
                    <th>Baureihe</th>
                    <th>Name</th>
                    <th>Besitzer</th>
                </tr>
                <tr>
                    <td>
                        <?php
                            echo "<image src='data/images/" .
                            $baureihe . 
                            ".png' />";
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo $baureihe;
                        ?>
                    </td>
                    <td>
                        <?php 
                            foreach($conn->query("SELECT name from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                echo $row['name'];
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            foreach($conn->query("SELECT owner from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                echo $row['owner'];
                            }
                        ?>
                    </td>
            </table>
        </div>
            <div class="other-engines">
                <table class="engine-table">
                    <tr>
                        <?php
                            foreach($conn->query("SELECT EngineID, Baureihe, Ordnungsnummer FROM Engines ORDER BY Baureihe, Ordnungsnummer;") as $row){
                                echo "<tr><td><a href='" . $_SERVER["PHP_SELF"] . "?ID=" . $row['EngineID'] . "'>" . $row['Baureihe'] . " " . $row['Ordnungsnummer'] .  "</a></td></tr>";
                            }
                        ?>
                    </tr>
                </table>
            </div>
        </div>
    <body>
</html>