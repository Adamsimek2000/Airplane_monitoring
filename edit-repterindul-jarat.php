<?php //Segédoldal edit-jarat.php oldalhoz: Indulási reptértől függ a többi attribútum lehetséges értéke
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Új járat beszúrása</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Új járat beszúrása</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php
            if (!isset($_GET['jaratid'])) {
                die("Nem adta meg, melyik járatot szeretné szerkeszteni!");
            }
            //Paraméterátadás GET-tel:ellenőrizni kell, hogy érvényes érték-e
            $jaratId=$_GET['jaratid'];
            $queryJarat="SELECT id FROM Jarat";
            $resultJarat=mysqli_query($link, $queryJarat) or die(mysqli_error($link));
            $match=0;
            while($rowJarat=mysqli_fetch_array($resultJarat))
            {
                if($rowJarat['id']===$jaratId)
                {
                    $match=1;
                }    
            }
            if($match===0)
            {
                die("Nem létező járatot adott meg!");
            }
        ?>
        </div>
        <form action="edit-jarat.php" method="post">
            <h1>Járat módosítása</h1>
            <div>
                <input type="hidden" value="<?=$jaratId?>" name="jaratId" />
            </div>
            <div>
            <?php $noOtherRepterindul=false; ?>
                <label for="newrepterindul">Indulási repülőtér:</label>
                <select name="newrepterindul" id="newrepterindul">
                <?php
                    $queryRepterindulNumber=sprintf("SELECT count(id) AS \"NumberOfAvailableRepterindul\" FROM Repterindul");
                    $rowRepterindulNumber=mysqli_fetch_array(mysqli_query($link, $queryRepterindulNumber));
                    //Van-e egyáltalán (lehet, hogy a jelenlegit kitörölték) vagy másik indulási repülőtér, amire módosítani lehetne?
                    if($rowRepterindulNumber['NumberOfAvailableRepterindul']==0 or $rowRepterindulNumber['NumberOfAvailableRepterindul']==1): ?>
                        <option value="">Nincs elérhető (másik) indulási repülőtér</option>
                        <?php $noOtherRepterindul=true;
                    else:
                    $queryRepterindul="SELECT id FROM Repterindul";
                    $resultRepterindul=mysqli_query($link, $queryRepterindul) or die(mysqli_error($link));
                    while($rowRepterindul=mysqli_fetch_array($resultRepterindul)):
                        $queryRepterindulNev=sprintf("SELECT DISTINCT concat(Repterindul.Varos, \" \", Repterindul.Nev) AS \"RepterindulNev\", id FROM Repterindul WHERE id=%d", $rowRepterindul['id']);
                        $rowRepterindulNev=mysqli_fetch_array(mysqli_query($link, $queryRepterindulNev));
                        //Lehet ugyanaz az új (módosított) indulási reptér, mint az eredeti(lehet, hogy azt nem szeretnénk megváltoztatni)

                        /*$queryOldRepterindul=sprintf("SELECT DISTINCT RepterindulId FROM Jarat WHERE id=%d", $jaratId);
                        $rowOldRepterindul=mysqli_fetch_array(mysqli_query($link, $queryOldRepterindul));
                        $queryOldRepterindulNev=sprintf("SELECT DISTINCT concat(Repterindul.Varos, \" \", Repterindul.Nev) AS \"RepterindulNev\" FROM Repterindul WHERE id=%d", $rowOldRepterindul['RepterindulId']);
                        $rowOldRepterindulNev=mysqli_fetch_array(mysqli_query($link, $queryOldRepterindulNev));*/
                        //if($rowRepterindul['id']!==$rowOldRepterindul['RepterindulId']): ?>
                        
                            <option value="<?=$rowRepterindul['id']?>"><?=$rowRepterindulNev['RepterindulNev']?></option>

                        
                        <?php //endif; ?>
                <?php endwhile; ?>
                <?php endif; ?>
                </select>
            </div>                
            <div>
                <?php if($noOtherRepterindul===false): ?>
                    <input type="submit" value="Elküld" name="modifiedRepterindul"/>
                <?php endif; ?>
            </div>
        </form>
        </div>
    </body>
</html>

<?php
    closeDB($link);
?>