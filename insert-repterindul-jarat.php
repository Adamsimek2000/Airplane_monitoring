<?php //Segédoldal insert-jarat.php oldalhoz: Indulási reptértől függ a többi attribútum lehetséges értéke
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
        if(isset($_POST['uj']))
        {
            if(isset($_POST['repterindul']) and $_POST['repterindul'])
            {
                $ujrepterindul=$_POST['repterindul'];
                header("Location: insert-jarat.php?newrepterindul=$ujrepterindul");
            }
            else
            {
                die("Legalább az egyik mező nincs kitöltve értelmes értékkel!");
            }
            
        }
        ?>
        </div>

        <form action="" method="post">
            <div>
                <label for="repterindul">Indulási repülőtér:</label>
                <select name="repterindul" id="repterindul">
                <?php
                    $queryRepterindul="SELECT id FROM Repterindul";
                    $resultRepterindul=mysqli_query($link, $queryRepterindul) or die(mysqli_error($link));
                    while($rowRepterindul=mysqli_fetch_array($resultRepterindul)):
                        $queryRepterindulNev=sprintf("SELECT DISTINCT concat(Repterindul.Varos, \" \", Repterindul.Nev) AS \"RepterindulNev\", id FROM Repterindul WHERE id=%d", $rowRepterindul['id']);
                        $rowRepterindulNev=mysqli_fetch_array(mysqli_query($link, $queryRepterindulNev));
                ?>
                    <option value="<?=$rowRepterindul['id']?>"><?=$rowRepterindulNev['RepterindulNev']?></option>
                <?php endwhile; ?>
                </select>
            </div>                
            <div>
                <input type="submit" value="Elküld" name="uj" />
            </div>
        </form>
        </div>
    </body>
</html>

<?php
    if(isset($_POST['uj']) and isset($_POST['repterindul']) and $_POST['repterindul'])
    {
        $ujrepterindul=$_POST['repterindul'];
        header("Location: insert-jarat.php?newrepterindul=$ujrepterindul");
    }
?>
