<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Új repülőgép beszúrása</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Új repülőgép beszúrása</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php
        //$created=false;
        //Feldolgozás
        if(isset($_POST['RepulogepRepterindul']))
        {
            if(isset($_POST['Gyarto']) and isset($_POST['Tipus']) and isset($_POST['Utasszallito']) and isset($_POST['Teherszallito']) and isset($_POST['Szervizben']) and isset($_POST['Jaratban']) and isset($_POST['newrepterindul']))
            {
                $gyarto=mysqli_real_escape_string($link, $_POST['Gyarto']);
                $tipus=mysqli_real_escape_string($link, $_POST['Tipus']);
                $utasszallito=mysqli_real_escape_string($link, $_POST['Utasszallito']);
                $teherszallito=mysqli_real_escape_string($link, $_POST['Teherszallito']);
                $szervizben=mysqli_real_escape_string($link, $_POST['Szervizben']);
                $jaratban=mysqli_real_escape_string($link, $_POST['Jaratban']);
                $repterindul=mysqli_real_escape_string($link, $_POST['newrepterindul']);
                $queryRepulogep=sprintf("INSERT INTO Repulogep(Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) VALUES('%s', '%s', %d, %d, %d, %d, %d)",$gyarto, $tipus, $utasszallito, $teherszallito, $szervizben, $jaratban, $repterindul);
                mysqli_query($link, $queryRepulogep);
                //$created=true;

                header("Location: repulogep.php");
            }
            
            else
            {
                die("Legalább az egyik mező nincs kitöltve értelmes értékkel!");   
            }
        }
           
        ?>
        </div>
        
        <form action="insert-repulogep.php" method="post">
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
                    //Van-e egyáltalán indulási repülőtér?
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
                    <input type="submit" value="Elküld" name="RepulogepRepterindul"/>
                <?php endif; ?>
            </div>
        </form>
    </body>
</html>

<?php
closeDB($link)
?>