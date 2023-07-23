<DOCTYPE! html>

<?php
require 'DBConnect.php';

$renderTrain = false;

if (isset($_GET['ID'])) {
    $renderTrain = true;
    $type = $trasse = $number = $from = $to = $operator = '';

    foreach ($conn->query("SELECT * from trains WHERE ID ='" . $_GET['ID'] . "';") as $row) {
        $type = $row['Typ'];
        $trasse = $row['Trasse'];
        $number = $row['Nummer'];
        $from = $row['Von'];
        $to = $row['Nach'];
        $operator = $row['Owner'];
    }
}
?>
<html>
    <head>
        <title>Züge anschauen</title>
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/table.css">
        <link rel="stylesheet" href="styles/engines.css">
    </head>
    </body>
        <div class="flexy">
        <div class="main">
            <h1 class="title">Züge ansehen: </h1>
            <?php if($renderTrain) :?>
            <table class="result-table" >
                <tr>
                    <th>Zugtyp</th>
                    <th>Trasse</th>
                    <th>Zugnummer</th>
                    <th>Von</th>
                    <th>Nach</th>
                    <th>Betreiber</th>
                </tr>
                <tr>
                    <td>
                        <?=$type?>
                    </td>
                    <td>
                        <?=$trasse?>
                    </td>
                    <td>
                        <?=$number?>
                    </td>
                    <td>
                        <?=$from?>
                    </td>
                    <td>
                        <?=$to?>
                    </td>
                    <td>
                        <?=$operator?>
                    </td>
            </table>
            <?php else: ?>
            <p>Bitte einen Zug auswählen</p>
            <?php endif; ?>
    <body>
    <?php
        require 'DBConnect.php';
        foreach ($conn->query("SELECT ID, Nummer from trains;") as $row) {
            echo "<a href='" . $_SERVER['PHP_SELF'] . "?ID=" . $row['ID'] ."'>" . $row['Nummer'] . "</a>";
        }
    ?>
</html>