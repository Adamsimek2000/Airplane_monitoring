<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Érkezési repülőtér törlése</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Érkezési repülőtér törlése</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php
            if (!isset($_GET['reptererkezikid'])) {
                die("Nem adta meg, melyik érkezési repülőteret szeretné szerkeszteni!");
            }
            //Paraméterátadás GET-tel:ellenőrizni kell, hogy érvényes érték-e
            $reptererkezikId=$_GET['reptererkezikid'];
            $queryReptererkezik="SELECT id FROM Reptererkezik";
            $resultReptererkezik=mysqli_query($link, $queryReptererkezik) or die(mysqli_error($link));
            $match=false;
            while($rowReptererkezik=mysqli_fetch_array($resultReptererkezik))
            {
                if($rowReptererkezik['id']===$reptererkezikId)
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
                $queryJaratban=sprintf("SELECT count(id) AS \"NumberOfJaratArrivingHere\" FROM Jarat WHERE ReptererkezikId=%d", $reptererkezikId);
                $rowJaratban=mysqli_fetch_array(mysqli_query($link, $queryJaratban));
                if($rowJaratban['NumberOfJaratArrivingHere']>0)
                {
                    die("Olyan érkezési repülőteret nem lehet törölni, ahová jelenleg teljesített járat érkezik!");
                }
            }

            $queryDeleteReptererkezik=sprintf("DELETE FROM Reptererkezik WHERE id=%d", $reptererkezikId);
            mysqli_query($link, $queryDeleteReptererkezik);
            header("Location: reptererkezik.php");
        ?>
    </div>
    </div>
    </body>
</html>

<?php
closeDB($link)
?>