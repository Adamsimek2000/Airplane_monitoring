<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Járat módosítása</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Járat módosítása</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php $modified=false;
            if(isset($_POST['newEdit']))
            {
                if(isset($_POST['jaratIdentifier']) and isset($_POST['repterindul']) and isset($_POST['reptererkezik']) and isset($_POST['repulogep']))
                {
                    $JaratID=$_POST['jaratIdentifier'];
                    $repterindul=$_POST['repterindul'];
                    $reptererkezik=$_POST['reptererkezik'];

                    $repulogep=$_POST['repulogep'];
                    $queryOldRepulogep=sprintf("SELECT DISTINCT RepulogepId FROM Jarat WHERE id=%d", $JaratID);
                    $rowOldRepulogep=mysqli_fetch_array(mysqli_query($link, $queryOldRepulogep));
                    if($rowOldRepulogep['RepulogepId']!==$repulogep)
                    {
                        $queryOldJaratban=sprintf("UPDATE Repulogep SET Jaratban=0 WHERE id=%d", $rowOldRepulogep['RepulogepId']);
                        mysqli_query($link, $queryOldJaratban);
                    }               

                    $queryOldRepterindulId=sprintf("UPDATE Repulogep SET RepterindulId=%d WHERE id=%d", $repterindul, $rowOldRepulogep['RepulogepId']);
                    mysqli_query($link, $queryOldRepterindulId);

                    $queryEditJarat=sprintf("UPDATE Jarat SET RepterindulId=%d, ReptererkezikId=%d, RepulogepId=%d WHERE id=%d", $repterindul, $reptererkezik, $repulogep, $JaratID);
                    mysqli_query($link, $queryEditJarat);
                    $queryNewJaratban=sprintf("UPDATE Repulogep SET Jaratban=1 WHERE id=%d", $repulogep);
                    mysqli_query($link, $queryNewJaratban);

                    $modified=true;
                    header("Location: jarat.php");
                }
                else
                {
                    die("Legalább az egyik mező nincs kitöltve értelmes értékkel!");
                }
            
            }
            
            //$_POST ellenőrzése
            if($modified==false)
            {
                if(!isset($_POST['modifiedRepterindul']) or !isset($_POST['jaratId']) or !isset($_POST['newrepterindul']))
                {
                    die("Nem adta meg a módosított indulási repülőteret!");
                }
                $jaratId=$_POST['jaratId'];
                $ujrepterindul=$_POST['newrepterindul'];
                $queryRepterindul="SELECT id FROM Repterindul";
                $resultRepterindul=mysqli_query($link, $queryRepterindul) or die(mysqli_error($link));
                $match=false;
                while($rowRepterindul=mysqli_fetch_array($resultRepterindul))
                {
                    if($rowRepterindul['id']===$ujrepterindul)
                    {
                        $match=true;
                    }    
                }
                if($match===false)
                {
                    die("Nem létező indulási repülőteret adott meg!");
                }
                /*$queryOldRepterindul=sprintf("SELECT DISTINCT RepterindulId FROM Jarat WHERE id=%d", $jaratId);
                $rowOldRepterindul=mysqli_fetch_array(mysqli_query($link, $queryOldRepterindul));
                if($rowOldRepterindul['RepterindulId']===$ujrepterindul)
                {
                    $sameRepterindul=true;
                }*/             
            }
            //Módosítást kezelő rész folytatása: Elozo repulogep Jaratban bitje és RepterindulId-je
        
        ?>
        </div>
        <form action="edit-jarat.php" method="post">
            <h1>Új járat létrehozása</h1>
                <div>
                    <input type="hidden" name="jaratIdentifier" value="<?=$jaratId?>" />
                </div>
                <div>
                    <input type="hidden" name="repterindul" value="<?=$ujrepterindul?>" />
                </div>                
                <div>
                <?php $noOtherReptererkezik=false; ?>
                    <label for="reptererkezik">Érkezési repülőtér:</label>
                    <select name="reptererkezik" id="reptererkezik">
                    <?php
                        $queryRepterindulNumber=sprintf("SELECT count(id) AS \"NumberOfAvailableReptererkezik\" FROM Reptererkezik");
                        $rowRepterindulNumber=mysqli_fetch_array(mysqli_query($link, $queryRepterindulNumber));
                        //Van-e egyáltalán (lehet, hogy a jelenlegit kitörölték) vagy másik indulási repülőtér, amire módosítani lehetne?
                        if($rowRepterindulNumber['NumberOfAvailableReptererkezik']==0 or $rowRepterindulNumber['NumberOfAvailableReptererkezik']==1): ?>
                            <option value="">Nincs elérhető (másik) érkezési repülőtér</option>
                            <?php $noOtherReptererkezik=true;
                        else:
                        $queryReptererkezik="SELECT id FROM Reptererkezik";
                        $resultReptererkezik=mysqli_query($link, $queryReptererkezik) or die(mysqli_error($link));
                        while($rowReptererkezik=mysqli_fetch_array($resultReptererkezik)):
                            $queryReptererkezikNev=sprintf("SELECT DISTINCT concat(Reptererkezik.Varos, \" \", Reptererkezik.Nev) AS \"ReptererkezikNev\", id FROM Reptererkezik WHERE id=%d", $rowReptererkezik['id']);
                            $rowReptererkezikNev=mysqli_fetch_array(mysqli_query($link, $queryReptererkezikNev));
                            //Nem lehet ugyanaz az indulási és az érkezési reptér
                            //De a módosított érkezési reptér lehet ugyanaz, mint korábban(lehet, hogy azt nem szeretnénk megváltoztatni)
                            $queryRepterindulNev=sprintf("SELECT DISTINCT concat(Repterindul.Varos, \" \", Repterindul.Nev) AS \"RepterindulNev\" FROM Repterindul WHERE id=%d", $ujrepterindul);
                            $rowRepterindulNev=mysqli_fetch_array(mysqli_query($link, $queryRepterindulNev));
                            if($rowRepterindulNev['RepterindulNev']!==$rowReptererkezikNev['ReptererkezikNev']):
                    ?>
                        <option value="<?=$rowReptererkezik['id']?>"><?=$rowReptererkezikNev['ReptererkezikNev']?></option>
                        <?php endif; ?>
                    <?php endwhile; ?>
                    <?php endif; ?>
                    </select>
                </div>
                <div>
                <?php $noAvailableRepulogep=false; ?>
                    <label for="repulogep">Repülőgép:</label>
                    <select name="repulogep" id="repulogep">
                    <?php
                        $queryRepulogepNumber=sprintf("SELECT count(id) AS \"NumberOfAvailableRepulogep\" FROM Repulogep WHERE RepterindulId=%d and Szervizben=0 and Jaratban=0", $ujrepterindul);
                        $rowRepulogepNumber=mysqli_fetch_array(mysqli_query($link, $queryRepulogepNumber));
                        if($rowRepulogepNumber['NumberOfAvailableRepulogep']==0): ?>
                            <option value="">Nincs elérhető repülőgép</option>
                            <?php $noAvailableRepulogep=true;
                        else:
                            $queryRepulogep=sprintf("SELECT id FROM Repulogep WHERE RepterindulId=%d and Szervizben=0 and Jaratban=0", $ujrepterindul);
                            $resultRepulogep=mysqli_query($link, $queryRepulogep);
                            while($rowRepulogep=mysqli_fetch_array($resultRepulogep)):
                                $queryRepulogepNev=sprintf("SELECT DISTINCT concat(\"ID=\", Repulogep.id, \" Név: \",Repulogep.Gyarto, \" \", Repulogep.Tipus) AS \"RepulogepNev\", id FROM Repulogep WHERE id=%d", $rowRepulogep['id']);
                                $rowRepulogepNev=mysqli_fetch_array(mysqli_query($link, $queryRepulogepNev));
                        ?>
                            <option value="<?=$rowRepulogep['id']?>"><?=$rowRepulogepNev['RepulogepNev']?></option>
                        <?php endwhile; ?>
                        <?php endif; ?>
                    </select>                                                                
                </div>
                <div>
                <?php
                    if($noOtherReptererkezik===false and $noAvailableRepulogep===false): ?>                    
                    <input type="submit" value="Elküld" name="newEdit" />
                    <?php endif; ?>
                </div>
        </form>
        </div>
    </body>
</html>

<?php
    closeDB($link);
?>