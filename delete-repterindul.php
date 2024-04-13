<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Indulási repülőtér törlése</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Indulási repülőtér törlése</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php
            if (!isset($_GET['repterindulid'])) {
                die("Nem adta meg, melyik indulási repülőteret szeretné szerkeszteni!");
            }
            //Paraméterátadás GET-tel:ellenőrizni kell, hogy érvényes érték-e
            $repterindulId=$_GET['repterindulid'];
            $queryRepterindul="SELECT id FROM Repterindul";
            $resultRepterindul=mysqli_query($link, $queryRepterindul) or die(mysqli_error($link));
            $match=false;
            while($rowRepterindul=mysqli_fetch_array($resultRepterindul))
            {
                if($rowRepterindul['id']===$repterindulId)
                {
                    $match=true;
                }    
            }
            if($match===false)
            {
                die("Nem létező indulási repülőteret adott meg!");
            }
            else
            {
                $queryJaratban=sprintf("SELECT count(Jaratban) AS \"NumberOfJaratFromHere\" FROM Repulogep WHERE RepterindulId=%d", $repterindulId);
                $rowJaratban=mysqli_fetch_array(mysqli_query($link, $queryJaratban));
                if($rowJaratban['NumberOfJaratFromHere']>0)
                {
                    die("Olyan indulási repülőteret nem lehet törölni, ahonnan jelenleg teljesített járat felszállt!");
                }
            }

            $queryDeleteRepterindul=sprintf("DELETE FROM Repterindul WHERE id=%d", $repterindulId);
            mysqli_query($link, $queryDeleteRepterindul);
            header("Location: repterindul.php");
        ?>
        </div>
        </div>
    </body>
</html>

<?php
closeDB($link)
?>