<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Érkezési repülőtér beszúrása</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Új érkezési repülőtér beszúrása</h1>
        <?php include 'menu.html'; ?>
        </div>
        <div class="informacio">
        <?php
        //Feldolgozás
        if(isset($_POST['Reptererkezik']))
        {
            if(isset($_POST['Varos']) and $_POST['Varos'] and isset($_POST['Nev']) and $_POST['Nev'])
            {
                $varos=mysqli_real_escape_string($link, $_POST['Varos']);
                $nev=mysqli_real_escape_string($link, $_POST['Nev']);

                $same=false;
                $querySame=sprintf("SELECT Varos, Nev FROM Reptererkezik");
                $resultSame=mysqli_query($link, $querySame) or die(mysqli_error($link));
                while($rowSame=mysqli_fetch_array($resultSame)):
                    if($rowSame['Varos'] ===$varos and $rowSame['Nev']===$nev):
                        $same=true;
                    endif;
                endwhile;

                if($same===true)
                {
                    die("Ez az érkezési repülőtér (ugyanilyen nevű) már szerepel az adatbázisban!");
                }
               
                $queryReptererkezik=sprintf("INSERT INTO Reptererkezik(Varos, Nev) VALUES('%s', '%s')",$varos, $nev);
                mysqli_query($link, $queryReptererkezik);

                header("Location: reptererkezik.php");
            }
            else
            {
                die("Legalább az egyik mező nincs kitöltve értelmes értékkel!");
            }

        }

        ?>
        </div>

        <form action="insert-reptererkezik.php" method="post">
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