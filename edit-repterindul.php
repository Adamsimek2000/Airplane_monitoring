<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Indulási repülőtér szerkesztése</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Indulási repülőtér szerkesztése</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php
            $modified=false;
            //Módosítást kezelő rész
            if(isset($_POST['Repterindul']))
            {
                if(isset($_POST['id']) and isset($_POST['Varos']) and isset($_POST['Nev']))
                {
                    $id=$_POST['id'];
                    $varos=mysqli_real_escape_string($link, $_POST['Varos']);
                    $nev=mysqli_real_escape_string($link, $_POST['Nev']);

                    $queryOldRepterindul=sprintf("SELECT Varos, Nev FROM Repterindul WHERE id=%d", $id); //A módosítani kívánt indulási repülőtér nevének lekérdezése
                    $rowOldRepterindul=mysqli_fetch_array(mysqli_query($link, $queryOldRepterindul));
                    $queryReptererkezik=sprintf("SELECT ReptererkezikId FROM Jarat"); //Megnézzük, hogy egyezik-e egy jelenleg használatban lévő érkezési reptérrel
                    $resultReptererkezik=mysqli_query($link, $queryReptererkezik) or die(mysqli_error($link));
                    while($rowReptererkezik=mysqli_fetch_array($resultReptererkezik)):
                        $queryReptererkezikNev=sprintf("SELECT DISTINCT Varos, Nev FROM Reptererkezik WHERE id=%d", $rowReptererkezik['ReptererkezikId']);
                        $rowReptererkezikNev=mysqli_fetch_array(mysqli_query($link, $queryReptererkezikNev));
                    
                        if($rowReptererkezikNev['Varos']===$rowOldRepterindul['Varos'] and $rowReptererkezikNev['Nev']===$rowOldRepterindul['Nev']):
                            die("Ez a repülőtér érkezési repülőtérként szerepel az egyik, jelenleg teljesített járatnál!");
                        endif;
                    endwhile;

                    //Ha nem használjuk, de egyben érkezési reptér is lehet: ott is módosítjuk az új értékre
                    $querySameReptererkezik=sprintf("SELECT id, count(id) AS \"ExistsInReptererkezik\" FROM Reptererkezik WHERE Varos='%s'and Nev='%s'", $rowOldRepterindul['Varos'], $rowOldRepterindul['Nev']);
                    $rowSameReptererkezik=mysqli_fetch_array(mysqli_query($link, $querySameReptererkezik));
                    if($rowSameReptererkezik['ExistsInReptererkezik']==1) //Nem típus szerinti azonosság
                    {
                        $queryModifyReptererkezik=sprintf("UPDATE Reptererkezik SET Varos='%s', Nev='%s' WHERE id=%d", $varos, $nev, $rowSameReptererkezik['id']);
                        mysqli_query($link, $queryModifyReptererkezik);
                    }
               
                    $queryRepterindul=sprintf("UPDATE Repterindul SET Varos='%s', Nev='%s' WHERE id=%d",$varos, $nev, $id);
                    mysqli_query($link, $queryRepterindul);
                    $modified=true;
                    header("Location: repterindul.php");
                }
                else
                {
                    die("Legalább az egyik mező nincs kitöltve értelmes értékkel!");
                }
            }
            if($modified==false)
            {
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
                    $queryJaratban=sprintf("SELECT count(Jaratban) AS \"NumberOfJaratDepartingHere\" FROM Repulogep WHERE RepterindulId=%d", $repterindulId);
                    $rowJaratban=mysqli_fetch_array(mysqli_query($link, $queryJaratban));
                    if($rowJaratban['NumberOfJaratDepartingHere']>0)
                    {
                        die("Olyan indulási repülőteret nem lehet szerkeszteni, ahonnan jelenleg teljesített járat felszállt!");
                    }
                }
            }
        ?>
        </div>

        <form action="edit-repterindul.php" method="post">
            <h1>Indulási repülőtér szerkesztése</h1>
            <div>
                <input type="hidden" name="id" value="<?=$repterindulId?>"/>
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
                <input type="submit" value="Elküld" name="Repterindul"/>
            </div>
        </form>
        </div>
    </body>
</html>

<?php
closeDB($link)
?>