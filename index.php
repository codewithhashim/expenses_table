<?php


if(isset($_POST['einnahme'])) {
    $con = mysqli_connect('localhost', 'root', '','income');
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $datum = $_POST['datum'];
        $titel = $_POST['titel'];
        $betrag = $_POST['betrag'];
    
    }
    
    $sql = "INSERT INTO `expenses` (`datum`, `titel`, `betrag`) VALUES ('$datum', '$titel', '$betrag' ) ";
    $rs = mysqli_query($con, $sql);
if($rs)
{
	echo "Contact Records Inserted";
}
    
}

