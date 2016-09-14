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

//we check the field contains only letters and numbers
 if(!preg_match('/[^A-Za-z0-9.#\\-$]/', $_POST['new_field_name']))
 {
    if(!empty($_POST['new_field_name']))
    {
        $st = $db->prepare("DESCRIBE ".$_POST['table']);
        $st->execute();
        $st = $st->fetchAll(PDO::FETCH_COLUMN);
        $compare = $st;

            foreach($compare as $key){
                if($key === $_POST['new_field_name']){
                    die('Field name already exists. Please select a different name.');
                }
            }

        $st = $db->prepare("ALTER TABLE ".$_POST['table']." ADD ".$_POST['new_field_name']." ".$_POST['new_field_type'].$_POST['new_field_width']." ".$_POST['isnullfield']);
        $st->execute();  

        echo("ALTER TABLE ".$_POST['table']." ADD ".$_POST['new_field_name']." ".$_POST['new_field_type'].$_POST['new_field_width'])." ".$_POST['isnullfield'];
  }    
} 
else
{ 
    echo 'error103';
}   

?>