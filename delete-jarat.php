<?php
include 'database.php';
$link= openDB();
?>

<html>
    <head>
        <title>Járat törlése</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <div class="keret">
        <div class="fejlec">
        <h1>Járat törlése</h1>
        <?php include 'menu.html'; 
        if (!isset($_GET['jaratid'])) {
            die("Nem adta meg, melyik járatot szeretné törölni!");
        }
        //Paraméterátadás GET-tel:ellenőrizni kell, hogy érvényes érték-e
        $jaratId=$_GET['jaratid'];
        $queryJaratId="SELECT id FROM Jarat";
        $resultJaratId=mysqli_query($link, $queryJaratId) or die(mysqli_error($link));
        $match=false;
        while($rowJaratId=mysqli_fetch_array($resultJaratId))
        {
            if($rowJaratId['id']===$jaratId)
            {
                $match=true;
            }    
        }
        if($match===false)
        {
            die("Nem létező járatot adott meg!");
        }
        //A járatot eddig tejesítő repülőgép Jaratban jelzőbitjének 0-ra állítása a járat törlése előtt
        $queryRepulogep=sprintf("SELECT RepulogepId FROM Jarat WHERE id=%d", $jaratId);
        $rowRepulogep=mysqli_fetch_array(mysqli_query($link, $queryRepulogep)) or die(mysqli_error($link));

        $queryJaratban=sprintf("UPDATE Repulogep SET Jaratban=0 WHERE id=%d", $rowRepulogep['RepulogepId']);
        mysqli_query($link, $queryJaratban);

        $queryDeleteJarat=sprintf("DELETE FROM Jarat WHERE id=%d", $jaratId);
        mysqli_query($link, $queryDeleteJarat);

        header("Location: jarat.php");
        ?>
        </div>
        </div>
    </body>
</html>

<?php
closeDB($link)
?>