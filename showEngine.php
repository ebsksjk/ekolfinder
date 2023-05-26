<DOCTYPE! html>

<?php
    require('DBConnect.php');
?>
<html>
    <head>
        <title>Loks anschauen</title>
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/table.css">
    </head>
    </body>
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
                        $_GET['Baureihe'] . 
                        ".png' />";
                    ?>
                </td>
                <td>
                    <?php 
                        echo $_GET['Baureihe'];
                    ?>
                </td>
                <td>
                    <?php 
                        foreach($DBASE->query("SELECT Name from Engines WHERE Baureihe='" . $_GET['Baureihe'] ."';") as $row){
                            echo $row['Name'];
                        }
                    ?>
                </td>
                <td>
                    <?php 
                        foreach($DBASE->query("SELECT Owner from Engines WHERE Baureihe='" . $_GET['Baureihe'] ."';") as $row){
                            echo $row['Owner'];
                        }
                    ?>
                </td>
        </table>

        <table class="result-table">
            <tr>
                <?php
                    echo "<tr>";
                    foreach($DBASE->query("SELECT Baureihe from Engines;") as $row){
                        echo "<td><a href='" . $_SERVER["PHP_SELF"] . "?Baureihe=" . $row['Baureihe'] . "'>" . $row['Baureihe'] . "</a></td>";
                    }
                    echo "</tr>";
                ?>
            </tr>
        </table>
    <body>
</html>