<?php
session_start();
if (isset($_POST['table']) && isset($_POST['dbname']))
{
    try
    {
       $db = new PDO("mysql:host=".$_SESSION['host'].";dbname=".$_POST['dbname'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e)
    {
        echo ('err101');
        return;
    }
    $row_fields_a=[];
    $q = $db->prepare("DESCRIBE ". $_POST['table']);
    $q->execute();
$table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
    foreach ($table_fields as $value)
    {
        array_push($row_fields_a, $value);
    }

    echo json_encode($row_fields_a);
}

?>