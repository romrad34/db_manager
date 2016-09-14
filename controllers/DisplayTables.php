<?php
session_start();
$db = null;

if (isset($_POST['dbname']))
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

    $_SESSION['tables_rows']=[];
    $rows=[];
    $result = $db->query("SHOW TABLES");
    if ($result->rowCount() ==0)
    {
        echo 'err102';
        return;
    }

    while ($rows = $result->fetch(PDO::FETCH_NUM)) 
       {  
           array_push($_SESSION['tables_rows'],$rows[0]);
       }
    echo json_encode($_SESSION['tables_rows']);
}

?>