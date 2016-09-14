<?php
session_start();
$db = null;

try
{
   $db = new PDO("mysql:host=".$_SESSION['host'].";dbname=".$_POST['dbname'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}
catch (Exception $e)
{
    echo ('err101');
    return;
}

    $st1 = $db->prepare("DESCRIBE ".$_POST['tablename']);
    $st1->execute();
    $st1 = $st1->fetchAll(PDO::FETCH_COLUMN);
    $compare = $st1;
    $nb_row=count($compare);
    if ($nb_row>1)
    {
        $st = $db->prepare("ALTER TABLE ".$_POST['tablename']." DROP ".$_POST['fieldname']);
        $st->execute();  
        echo("ALTER TABLE ".$_POST['tablename']." DROP ".$_POST['fieldname']);
    }
    else
    {
        echo "error105";
    }
  
?>