<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../DBConnect.php";

    if (isset($_GET['ID']) && is_numeric($_GET['ID'])) {
        $tID = intval($_GET['ID']);
    
        if (isset($_POST['delete'])) {
            try {
                $sql = "DELETE FROM trains WHERE ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$tID]);

                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } catch (Exception $e) {
                die("Fehler beim Löschen der Daten! " . $e->getMessage());
            }
        }
    }

    $znr = intval($_POST['number']);

    try {

        $sql = "UPDATE trains 
        SET Typ=(?), Trasse=(?), Nummer=(?), Von=(?), Nach=(?), Owner=(?) 
        WHERE ID=(?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['type'], $_POST['trasse'], $znr, $_POST['from'], $_POST['to'], $_POST['operator'], $_GET['ID']]);
    } catch (Exception $e) {
        die("Fehler beim Hochladen der Daten! " . $e->getMessage());
    }
}

$render = false;

if(isset($_GET['ID'])){
    $render = true;
    $type = $trasse = $nummer = $from = $to = $operator = '';

    require "../../DBConnect.php";

    foreach ($conn->query("SELECT * from trains WHERE ID='" . $_GET['ID'] . "';") as $row) {
        $type = $row['Typ'];
        $trasse = $row['Trasse'];
        $nummer = $row['Nummer'];
        $from = $row['Von'];
        $to = $row['Nach'];
        $operator = $row['Owner'];
    }
}
?>

<h1>zug bearbeiteln</h1>
<?php if($render): ?>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>?ID=<?=$_GET['ID']?>">
    <table>
        <thead>
            <td>Zugtyp</td>
            <td>Trasse</td>
            <td>Zugnummer</td>
            <td>Von</td>
            <td>Nach</td>
            <td>Betreiber</td>
        </thead>
        <tr>
            <td><input type="text" name="type" value="<?=$type?>"></td>    
            <td><input type="text" name="trasse" value="<?=$trasse?>"></td>
            <td><input type="text" name="number" value="<?=$nummer?>"></td>
            <td><input type="text" name="from" value="<?=$from?>"></td>
             <td><input type="text" name="to" value="<?=$to?>"></td> 
            <td><input type="text" name="operator" value="<?=$operator?>"></td>
        </tr>
    </table>
    <button>Zug speichern</button>
    <button name="delete" onclick="return confirm('Lok löschen?')">Lok löschen</button>
</form>
<?php else: ?>
<p>bitte einen Zug auswählen
<?php endif; ?>
<br>
<?php
    require '../../DBConnect.php';
    foreach ($conn->query("SELECT ID, Nummer from trains;") as $row) {
        echo "<a href='" . $_SERVER['PHP_SELF'] . "?ID=" . $row['ID'] ."'>" . $row['Nummer'] . "</a><br/>";
    }
?>