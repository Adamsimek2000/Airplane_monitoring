<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Érkezési repülőterek</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Érkezési repülőterek</h1>
        <?php include 'menu.html'; ?>
        <a href="insert-reptererkezik.php">Új érkezési repülőtér beszúrása</a>       
        </div>
            <?php
                $querySelect=sprintf("SELECT id, Varos, Nev FROM Reptererkezik");
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
                            <td><a href="edit-reptererkezik.php?reptererkezikid=<?=$row['id']?>">Szerkesztés</td>
                            <td><a href="delete-reptererkezik.php?reptererkezikid=<?=$row['id']?>">Törlés</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php closeDB($link); ?>
    </body>
</html>