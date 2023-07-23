<DOCTYPE! html>

<?php
require 'DBConnect.php';

$render = false;

if(isset($_GET['ID'])) {
    $render = true;
    $filename = $baureihe = $Name = $owner = $joinedCompany = $leftCompany = $liverySince = $liveryUntil = '';

    foreach ($conn->query("SELECT * from Engines WHERE EngineID='" . $_GET['ID'] . "';") as $row) {
        $filename = $row['imagePath'];
        $baureihe = $row["Baureihe"] . ' ' . $row['Ordnungsnummer'];
        $Name = $row['Name'];
        $owner = $row['Owner'];
        $joinedCompany = $row['joinedCompany'];
        $leftCompany = $row['leftCompany'];
        $liverySince = $row['liverySince'];
        $liveryUntil = $row['liveryUntil'];
    }
}
?>
<html>
    <head>
        <title>Loks anschauen</title>
        <link rel="stylesheet" href="styles/main.css">
        <!--<link rel="stylesheet" href="styles/table.css">-->
        <link rel="stylesheet" href="styles/engines.css">
    </head>
    </body>
        <div class="flexy">
        <div class="main">
            <h1 class="title">Loks ansehen: </h1>
            <?php if($render): ?>
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
                        <image src='data/images/<?=$filename?>.png' />
                    </td>
                    <td>
                        <?=$baureihe?>
                    </td>
                    <td>
                        <?=$Name?>
                    </td>
                    <td>
                        <?=$owner?>
                    </td>
                    <td>
                        <?=$joinedCompany?>
                    </td>
                    <td>
                        <?=$leftCompany?>
                    </td>
                    <td>
                        <?=$liverySince?>
                    </td>
                    <td>
                        <?=$liveryUntil?>
                    </td>
            </table>
            <?php else: ?>
            <p>Bitte eine Lok auswählen.</p>
            <?php endif; ?>
<?php
include "enginetable.php";
?>
    <body>
</html>