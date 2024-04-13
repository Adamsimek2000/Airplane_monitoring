<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Repülőgép törlése</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Repülőgép törlése</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php 
            if (!isset($_GET['repulogepid'])) {
                die("Nem adta meg, melyik repülőgépet szeretné szerkeszteni!");
                }
            //Paraméterátadás GET-tel:ellenőrizni kell, hogy érvényes érték-e
            $repulogepId=$_GET['repulogepid'];
            $queryRepulogep="SELECT id FROM Repulogep";
            $resultRepulogep=mysqli_query($link, $queryRepulogep) or die(mysqli_error($link));
            $match=false;
            while($rowRepulogep=mysqli_fetch_array($resultRepulogep))
            {
                if($rowRepulogep['id']===$repulogepId)
                {
                    $match=true;
                }    
            }
            if($match===false)
            {
                die("Nem létező repülőgépet adott meg!");
            }
            else
            {
                $queryJaratban=sprintf("SELECT Jaratban FROM Repulogep WHERE id=%d", $repulogepId);
                $rowJaratban=mysqli_fetch_array(mysqli_query($link, $queryJaratban));
                if($rowJaratban['Jaratban']==1) //Nem egyező típus: az adatbázisban bináris, itt az 1 integer decimális konstans
                {
                    die("Olyan repülőgépet nem lehet törölni, amelyik jelenleg járatot teljesít!");
                }
            }

            $queryDeleteRepulogep=sprintf("DELETE FROM Repulogep WHERE id=%d", $repulogepId);
            mysqli_query($link, $queryDeleteRepulogep);
            header("Location: repulogep.php");
        ?>
        </div>
        </div>
    </body>
</html>

<?php
closeDB($link)
?>