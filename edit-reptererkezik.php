<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Érkezési repülőtér szerkesztése</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
    <div class="keret">
        <div class="fejlec">
        <h1>Érkezési repülőtér szerkesztése</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php
            $modified=false;
            //Módosítást kezelő rész
            if(isset($_POST['Reptererkezik']))
            {
                if(isset($_POST['id']) and isset($_POST['Varos']) and $_POST['Varos'] and isset($_POST['Nev']) and $_POST['Nev'])
                {
                    $id=$_POST['id'];
                    $varos=mysqli_real_escape_string($link, $_POST['Varos']);
                    $nev=mysqli_real_escape_string($link, $_POST['Nev']);

                    $queryOldReptererkezik=sprintf("SELECT Varos, Nev FROM Reptererkezik WHERE id=%d", $id); //A módosítani kívánt érkezési repülőtér eddigi nevének lekérdezése
                    $rowOldReptererkezik=mysqli_fetch_array(mysqli_query($link, $queryOldReptererkezik));
                    $queryRepterindul=sprintf("SELECT RepterindulId FROM Repulogep"); //Megnézzük, hogy egyezik-e egy jelenleg használatban lévő indulási reptérrel
                    $resultRepterindul=mysqli_query($link, $queryRepterindul) or die(mysqli_error($link));
                    while($rowRepterindul=mysqli_fetch_array($resultRepterindul)):
                        $queryRepterindulNev=sprintf("SELECT DISTINCT Varos, Nev FROM Repterindul WHERE id=%d", $rowRepterindul['RepterindulId']);
                        $rowRepterindulNev=mysqli_fetch_array(mysqli_query($link, $queryRepterindulNev));
                    
                        if($rowRepterindulNev['Varos']===$rowOldReptererkezik['Varos'] and $rowRepterindulNev['Nev']===$rowOldReptererkezik['Nev']):
                            die("Ez a repülőtér indulási repülőtérként szerepel az egyik repülőgépnél (járatban vagy szervizben van!");
                        endif;
                    endwhile;

                    //Ha nem használjuk, de egyben indulási reptér is: ott is módosítjuk az új értékre
                    $querySameRepterindul=sprintf("SELECT id, count(id) AS \"ExistsInRepterindul\" FROM Repterindul WHERE Varos='%s'and Nev='%s'", $rowOldReptererkezik['Varos'], $rowOldReptererkezik['Nev']);
                    $rowSameRepterindul=mysqli_fetch_array(mysqli_query($link, $querySameRepterindul));
                    if($rowSameRepterindul['ExistsInRepterindul']==1)
                    {
                        $queryModifyRepterindul=sprintf("UPDATE Repterindul SET Varos='%s', Nev='%s' WHERE id=%d", $varos, $nev, $rowSameRepterindul['id']);
                        mysqli_query($link, $queryModifyRepterindul);
                    }

                    $queryReptererkezik=sprintf("UPDATE Reptererkezik SET Varos='%s', Nev='%s' WHERE id=%d",$varos, $nev, $id);
                    mysqli_query($link, $queryReptererkezik);
                    $modified=true;
                    header("Location: reptererkezik.php");
                }
                else
                {
                    die("Legalább az egyik mező nincs kitöltve értelmes értékkel!");
                }
                
            }
            if($modified==false)
            {
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
                    die("Nem létező érkezési repülőteret adott meg!");
                }
                else
                {
                    $queryJaratban=sprintf("SELECT count(id) AS \"NumberOfJaratArrivingHere\" FROM Jarat WHERE ReptererkezikId=%d", $reptererkezikId);
                    $rowJaratban=mysqli_fetch_array(mysqli_query($link, $queryJaratban));
                    if($rowJaratban['NumberOfJaratArrivingHere']>0)
                    {
                        die("Olyan érkezési repülőteret nem lehet szerkeszteni, ahová jelenleg teljesített járat érkezik!");
                    }
                }
            }
        ?>
        </div>

        <form action="edit-reptererkezik.php" method="post">
            <h1>Érkezési repülőtér szerkesztése</h1>
            <div>
                <input type="hidden" name="id" value="<?=$reptererkezikId?>"/>
            </div>
            <div>
                <label for="Varos">Város:</label>
                <input type="text" name="Varos" />
            </div>
            <div>
                <label for="Nev">Név:</label>
                <input type="text" name="Nev" />
            </div>
            <div>
                <input type="submit" value="Elküld" name="Reptererkezik"/>
            </div>
        </form>
        </div>
    </body>
</html>

<?php
closeDB($link)
?>