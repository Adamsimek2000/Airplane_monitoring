<?php
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
        $created=false;
        if(isset($_POST['ujjarat']))
        {
            if(isset($_POST['repterindul']) and isset($_POST['reptererkezik']) and isset($_POST['repulogep']) and $_POST['repulogep'])
            {
                $repterindul=$_POST['repterindul'];
                $reptererkezik=$_POST['reptererkezik'];
                $repulogep=$_POST['repulogep'];
                $queryJarat=sprintf("INSERT INTO Jarat(RepterindulId, ReptererkezikId, RepulogepId) VALUES(%d, %d, %d)", $repterindul, $reptererkezik, $repulogep);
                mysqli_query($link, $queryJarat);
                //A járat teljesítéséhez kiválasztott repülőgép Jaratban jelzőbitjének 1-re állítása
                $queryUpdateRepulogep=sprintf("UPDATE Repulogep SET Jaratban=1 WHERE id=%d", $repulogep);
                mysqli_query($link, $queryUpdateRepulogep);
                $created=true;

                header("Location: jarat.php");
            }
            else
            {
                die("Legalább az egyik mező nincs kitöltve értelmes értékkel!");
            }
        }
        if($created==false)
        {
            if (!isset($_GET['newrepterindul'])) {
                die("Nem adta meg, melyik repülőtérről indoljon a járat!");
            }
            //Paraméterátadás GET-tel:ellenőrizni kell, hogy érvényes érték-e
            $ujrepterindul=$_GET['newrepterindul'];
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
        }
        ?>
        </div>
        <form action="insert-jarat.php" method="post">
            <div>
                <input type="hidden" name="repterindul" value="<?=$ujrepterindul?>" />
            </div>                
            <div>
                <label for="reptererkezik">Érkezési repülőtér:</label>
                <select name="reptererkezik" id="reptererkezik">
                <?php
                    $queryReptererkezik="SELECT id FROM Reptererkezik";
                    $resultReptererkezik=mysqli_query($link, $queryReptererkezik) or die(mysqli_error($link));
                    while($rowReptererkezik=mysqli_fetch_array($resultReptererkezik)):
                        $queryReptererkezikNev=sprintf("SELECT DISTINCT concat(Reptererkezik.Varos, \" \", Reptererkezik.Nev) AS \"ReptererkezikNev\", id FROM Reptererkezik WHERE id=%d", $rowReptererkezik['id']);
                        $rowReptererkezikNev=mysqli_fetch_array(mysqli_query($link, $queryReptererkezikNev));
                        //Nem lehet ugyanaz az indulási és az érkezési reptér
                        $queryRepterindulNev=sprintf("SELECT DISTINCT concat(Repterindul.Varos, \" \", Repterindul.Nev) AS \"RepterindulNev\" FROM Repterindul WHERE id=%d", $ujrepterindul);
                        $rowRepterindulNev=mysqli_fetch_array(mysqli_query($link, $queryRepterindulNev));
                        if($rowRepterindulNev['RepterindulNev']!==$rowReptererkezikNev['ReptererkezikNev']):
                ?>
                    <option value="<?=$rowReptererkezik['id']?>"><?=$rowReptererkezikNev['ReptererkezikNev']?></option>
                    <?php endif; ?>
                <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="repulogep">Repülőgép:</label>
                <select name="repulogep" id="repulogep">
                <?php
                    $queryRepulogepNumber=sprintf("SELECT count(id) AS \"NumberOfAvailableRepulogep\" FROM Repulogep WHERE RepterindulId=%d and Szervizben=0 and Jaratban=0", $ujrepterindul);
                    $rowRepulogepNumber=mysqli_fetch_array(mysqli_query($link, $queryRepulogepNumber));
                    if($rowRepulogepNumber['NumberOfAvailableRepulogep']==0): ?>
                    {
                        <option value="">Nincs elérhető repülőgép</option>

                    }
                    <?php else:
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
                <input type="submit" value="Elküld" name="ujjarat" />
            </div>
        </form>
        </div>
    </body>
</html>

<?php
closeDB($link)
?>