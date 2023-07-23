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