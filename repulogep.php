<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Repülőgépek</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Repülőgépek</h1>
        <?php include 'menu.html'; ?>
        <a href="insert-repulogep.php">Új repülőgép beszúrása</a>
        </div>
        
            <?php
                $kereses=null;
                if(isset($_POST['kereses']))
                {
                    $kereses=$_POST['kereses'];
                }

            ?>
            <form action="repulogep.php" method="post">
                <div>
                    Keresés típus szerint (gyártó megadása nélkül):
                    <input type="search" name="kereses" value="<?=$kereses?>">
                    <button type="submit">Keresés</button>
                </div>
            </form>
            <?php 
                $querySelect="SELECT id, Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId FROM Repulogep";
                if($kereses!=null)
                {
                    $querySelect=$querySelect . sprintf(" WHERE Tipus LIKE '%%%s%%'", mysqli_real_escape_string($link, $kereses));
                    //Összefűzés: fontos, hogy ne írjuk egybe WHERE-t az eredeti kérés végével
                }
                $resultSelect=mysqli_query($link, $querySelect) or die(mysqli_error($link));
            ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gyártó</th>
                        <th>Típus</th>
                        <th>Utasszállító</th>
                        <th>Teherszállító</th>
                        <th>Szervizben</th>
                        <th>Járatban</th>
                        <th>Indulási repülőtér</th>
                        <th>Szerkesztés</th>
                        <th>Törlés</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row=mysqli_fetch_array($resultSelect)): ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <td><?=$row['Gyarto']?></td>
                        <td><?=$row['Tipus']?></td>
                        
                        <?php if($row['Utasszallito']==1): ?>
                        <td>igen</td>
                        <?php else: ?>
                        <td>nem</td>
                        <?php endif; ?>

                        <?php if($row['Teherszallito']==1): ?>
                        <td>igen</td>
                        <?php else: ?>
                        <td>nem</td>
                        <?php endif; ?>

                        <?php if($row['Szervizben']==1): ?>
                        <td>igen</td>
                        <?php else: ?>
                        <td>nem</td>
                        <?php endif; ?>

                        <?php if($row['Jaratban']==1): ?>
                        <td>igen</td>
                        <?php else: ?>
                        <td>nem</td>
                        <?php endif; ?>

                        <?php $RepterindulNev=sprintf("SELECT DISTINCT concat(Repterindul.Varos, \" \", Repterindul.Nev) AS \"RepterindulNev\" FROM Repterindul WHERE id=%d", $row['RepterindulId']);
                              $rowRepterindulNev=mysqli_fetch_array(mysqli_query($link, $RepterindulNev));
                        ?>
                        <td><?=$rowRepterindulNev['RepterindulNev']?></td>
                        <td><a href="edit-repulogep.php?repulogepid=<?=$row['id']?>">Szerkesztés</td>
                        
                        <td><a href="delete-repulogep.php?repulogepid=<?=$row['id']?>">Törlés</td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php closeDB($link); ?>

    </body>

</html>