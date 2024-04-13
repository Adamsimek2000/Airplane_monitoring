<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Indulási repülőterek</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Indulási repülőterek</h1>
        <?php include 'menu.html'; ?>
        <a href="insert-repterindul.php">Új indulási repülőtér beszúrása</a>       
        </div>
            <?php
                $querySelect=sprintf("SELECT id, Varos, Nev FROM Repterindul");
                $resultSelect=mysqli_query($link, $querySelect) or die(mysqli_error($link));
            ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Város</th>
                        <th>Név</th>
                        <th>Szerkesztés</th>
                        <th>Törlés</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row=mysqli_fetch_array($resultSelect)): ?>
                        <tr>
                            <td><?=$row['id']?></td>
                            <td><?=$row['Varos']?></td>
                            <td><?=$row['Nev']?></td>
                            <td><a href="edit-repterindul.php?repterindulid=<?=$row['id']?>">Szerkesztés</td>
                            <td><a href="delete-repterindul.php?repterindulid=<?=$row['id']?>">Törlés</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php closeDB($link); ?>
    </body>
</html>

