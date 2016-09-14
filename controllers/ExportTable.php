<?php
session_start();
try
{
   $db = new PDO("mysql:host=".$_SESSION['host'].";dbname=".$_POST['dbname'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}
catch (Exception $e)
{
    echo ('err101');
    return;
}
$dbname = $_POST['dbname'];
$tablename = $_POST['tablename'];
$test = $db-> query('SHOW CREATE TABLE '.$_POST['tablename']);
$test2 = array_slice($test->fetch(), 2);

file_put_contents('../ExportTable/'.$_POST['tablename'].'.sql' , $test2);
?>