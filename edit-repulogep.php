<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Repülőgép szerkesztése</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Repülőgép szerkesztése</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php
            $modified=false;
            //Módosítást kezelő rész ide
            if(isset($_POST['modifiedRepulogep']))
            {
                if(isset($_POST['id']) and isset($_POST['Gyarto']) and isset($_POST['Tipus']) and isset($_POST['Utasszallito']) and isset($_POST['Teherszallito']) and isset($_POST['Szervizben']) and isset($_POST['Jaratban']) and isset($_POST['newrepterindul']))
                {
                    $id=$_POST['id'];
                    $gyarto=mysqli_real_escape_string($link, $_POST['Gyarto']);
                    $tipus=mysqli_real_escape_string($link, $_POST['Tipus']);
                    $utasszallito=mysqli_real_escape_string($link, $_POST['Utasszallito']);
                    $teherszallito=mysqli_real_escape_string($link, $_POST['Teherszallito']);
                    $szervizben=mysqli_real_escape_string($link, $_POST['Szervizben']);
                    $jaratban=mysqli_real_escape_string($link, $_POST['Jaratban']);
                    $repterindul=mysqli_real_escape_string($link, $_POST['newrepterindul']);
                    $queryRepulogep=sprintf("UPDATE Repulogep SET Gyarto='%s', Tipus='%s', Utasszallito=%d, Teherszallito=%d, Szervizben=%d, Jaratban=%d, RepterindulId=%d WHERE id=%d",$gyarto, $tipus, $utasszallito, $teherszallito, $szervizben, $jaratban, $repterindul, $id);
                    mysqli_query($link, $queryRepulogep);
                    $modified=true;

                header("Location: repulogep.php");
                }
                else
                {
                    die("Legalább az egyik mező nincs kitöltve értelmes értékkel!");
                }
            }

            if($modified==false)
            {
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
                        die("Olyan repülőgépet nem lehet szerkeszteni, amelyik jelenleg járatot teljesít!");
                    }
                }
            }
            ?>
            </div>

            <form action="edit-repulogep.php" method="post">
            <h1>Új repülőgép létrehozása</h1>
            <div>
                <input type="hidden" name="id" value="<?=$repulogepId?>"/>
            </div>
            <div>
                <label for="Gyarto">Gyártó:</label>
                <input type="text" name="Gyarto" />
            </div>
            <div>
                <label for="Tipus">Típus:</label>
                <input type="text" name="Tipus" />
            </div>
            <div>
                <label for="Utasszallito">Utasszállító-e (0: nem, 1: igen):</label>
                <input type="number" name="Utasszallito" min="0" max="1" />
            </div>
            <div>
                <label for="Teherszallito">Teherszállító-e (0: nem, 1: igen):</label>
                <input type="number" name="Teherszallito" min="0" max="1" />
            </div>
            <div>
                <label for="Szervizben">Szervizben van-e (0: nem, 1: igen):</label>
                <input type="number" name="Szervizben" min="0" max="1" />
            </div>
            <div>
                <input type="hidden" name="Jaratban" value="0" />
            </div>
            <div>
            <?php $noOtherRepterindul=false; ?>
                <label for="newrepterindul">Indulási repülőtér:</label>
                <select name="newrepterindul" id="newrepterindul">
                <?php
                    $queryRepterindulNumber=sprintf("SELECT count(id) AS \"NumberOfAvailableRepterindul\" FROM Repterindul");
                    $rowRepterindulNumber=mysqli_fetch_array(mysqli_query($link, $queryRepterindulNumber));
                    //Van-e egyáltalán indulási repülőtér (lehet, hogy közben kitörölték)?
                    if($rowRepterindulNumber['NumberOfAvailableRepterindul']==0): ?>
                        <option value="">Nincs elérhető indulási repülőtér</option>
                        <?php $noOtherRepterindul=true;
                    else:
                    $queryRepterindul="SELECT id FROM Repterindul";
                    $resultRepterindul=mysqli_query($link, $queryRepterindul) or die(mysqli_error($link));
                    while($rowRepterindul=mysqli_fetch_array($resultRepterindul)):
                        $queryRepterindulNev=sprintf("SELECT DISTINCT concat(Repterindul.Varos, \" \", Repterindul.Nev) AS \"RepterindulNev\", id FROM Repterindul WHERE id=%d", $rowRepterindul['id']);
                        $rowRepterindulNev=mysqli_fetch_array(mysqli_query($link, $queryRepterindulNev)); ?>
                                                                
                        <option value="<?=$rowRepterindul['id']?>"><?=$rowRepterindulNev['RepterindulNev']?></option>
                        
                <?php endwhile; ?>
                <?php endif; ?>
                </select>
            </div>                
            <div>
                <?php if($noOtherRepterindul===false): ?>
                    <input type="submit" value="Elküld" name="modifiedRepulogep"/>
                <?php endif; ?>
            </div>
            </form>    
            </div>
    </body>
</html>

<?php
closeDB($link)
?>