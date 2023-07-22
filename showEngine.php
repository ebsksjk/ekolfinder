<DOCTYPE! html>

<?php
    require('DBConnect.php');
?>
<html>
    <head>
        <title>Loks anschauen</title>
        <link rel="stylesheet" href="styles/main.css">
        <!--<link rel="stylesheet" href="styles/table.css">-->
        <link rel="stylesheet" href="styles/engines.css">
    </head>
    </body>
        <?php
            $filename = '';
            $baureihe = '';

            foreach($conn->query("SELECT imagePath, Baureihe, Ordnungsnummer from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                $filename = $row['imagePath'];
                $baureihe = $row["Baureihe"] . ' ' . $row['Ordnungsnummer'];
            }
        ?>
        <div class="flexy">
        <div class="main">
            <h1 class="title">Loks ansehen: </h1>

            <table class="test-table" >
                <tr>
                    <th>Bild</th>
                    <th>Baureihe</th>
                    <th>Name</th>
                    <th>Besitzer</th>
                    <th>Bei Besitzer seit</th>
                    <th>Bei Besitzer bis</th>
                    <th>Lackierung seit</th>
                    <th>Lackierung bis</th>
                </tr>
                <tr>
                    <td>
                        <?php
                            echo "<image src='data/images/" .
                            trim($filename) . 
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
                    <td>
                        <?php 
                            foreach($conn->query("SELECT joinedCompany from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                echo $row['joinedCompany'];
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            foreach($conn->query("SELECT leftCompany from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                echo $row['leftCompany'];
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            foreach($conn->query("SELECT liverySince from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                echo $row['liverySince'];
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            foreach($conn->query("SELECT liveryUntil from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                echo $row['liveryUntil'];
                            }
                        ?>
                    </td>
            </table>
        </div>
            <div class="other-engines">
                <table class="engine-table">
                        <?php
                            $actBR = "00";
                            foreach($conn->query("SELECT Baureihe FROM Engines ORDER BY Baureihe;") as $row){
                                if($row['Baureihe']!== $actBR){
                                    $actBR = $row['Baureihe'];
                                    echo "</details></tr>";
                                    echo "<tr><details><summary>" . $row['Baureihe'] . "</summary>";
                                    foreach($conn->query("SELECT EngineID, Baureihe, Ordnungsnummer FROM Engines WHERE Baureihe='" . $actBR . "' ORDER BY Baureihe, Ordnungsnummer ;") as $row){
                                        $baureihe = $row['Baureihe'] . " " . $row['Ordnungsnummer'];
                                        echo "<a href='" . $_SERVER["PHP_SELF"] . "?ID=" . $row['EngineID'] . "'>" . $baureihe .  "</a>";
                                        echo "<br/>";
                                    }
                                }
                            }
                        ?>
                </table>
            </div>
        </div>
    <body>
</html>