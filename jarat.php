<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Éppen teljesített járatok</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Éppen teljesített járatok</h1>
        <?php include 'menu.html'; ?>
        <a href="insert-repterindul-jarat.php">Új járat beszúrása</a>
        </div>
            <?php 
                $querySelect="SELECT id, RepterindulId, ReptererkezikId, RepulogepId FROM Jarat";
                $resultSelect=mysqli_query($link, $querySelect) or die(mysqli_error($link));
            ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Indulási repülőtér</th>
                        <th>Érkezési repülőtér</th>
                        <th>Repülőgép ID</th>
                        <th>Szerkesztés</th>
                        <th>Törlés</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row=mysqli_fetch_array($resultSelect)): ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <?php $RepterindulNev=sprintf("SELECT DISTINCT concat(Repterindul.Varos, \" \", Repterindul.Nev) AS \"RepterindulNev\" FROM Repterindul WHERE id=%d", $row['RepterindulId']);
                              $rowRepterindulNev=mysqli_fetch_array(mysqli_query($link, $RepterindulNev));
                        ?>
                        <td><?=$rowRepterindulNev['RepterindulNev']?></td>

                        <?php $ReptererkezikNev=sprintf("SELECT DISTINCT concat(Reptererkezik.Varos, \" \", Reptererkezik.Nev) AS \"ReptererkezikNev\" FROM Reptererkezik WHERE id=%d", $row['ReptererkezikId']);
                              $rowReptererkezikNev=mysqli_fetch_array(mysqli_query($link, $ReptererkezikNev));
                        ?>
                        <td><?=$rowReptererkezikNev['ReptererkezikNev']?></td>
                        <td><?=$row['RepulogepId']?></td>
                        <td><a href="edit-repterindul-jarat.php?jaratid=<?=$row['id']?>">Szerkesztés</td>
                        <td><a href="delete-jarat.php?jaratid=<?=$row['id']?>">Törlés (járat befejeződött)</td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php closeDB($link); ?>

    </body>

</html>