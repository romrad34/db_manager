<?php
session_start();
$db = null;
$test=[];

try
{
   $db = new PDO("mysql:host=".$_SESSION['host'].";dbname=".$_POST['dbname'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}
catch (Exception $e)
{
    echo ('err101');
    return;
}
$q = $db->prepare("DESCRIBE ". $_POST['tablename']);
$q->execute();
while ($table_fields = $q->fetch(PDO::FETCH_NUM))
{  
    array_push($test, $table_fields);

}
foreach ($test as $key => $value)
{

    // once the correct field to change has been selected
   if ($test[$key][0]===$_POST['fieldname'])
   {
       //case not integer and NOT NULL
       if ($test[$key][2]==='NO' && $test[$key][3] !=='PRI' && (strpos($test[$key][1], 'i'))!==0)
       {
            $line= $_POST['newfieldname']." ".$test[$key][1]." CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
            $sql = "ALTER TABLE ".$_POST['tablename']." CHANGE ".$_POST['fieldname']." ".$line;
            break;
       }
       
           //case not integer and NULL
       elseif ($test[$key][2]==='YES' && $test[$key][3] !=='PRI' && (strpos($test[$key][1], 'i'))!==0)
       {
            $line= $_POST['newfieldname']." ".$test[$key][1]." CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL"; 
            $sql = "ALTER TABLE ".$_POST['tablename']." CHANGE ".$_POST['fieldname']." ".$line;
       }
          //case integer and NULL
        elseif ($test[$key][2]==='YES' && $test[$key][3] !=='PRI' && (strpos($test[$key][1], 'i'))===0)
        {
            $line= $_POST['newfieldname']." ".$test[$key][1]." NULL DEFAULT NULL"; 
            $sql = "ALTER TABLE ".$_POST['tablename']." CHANGE ".$_POST['fieldname']." ".$line;
        }
       
        //case integer and NOT NULL
        elseif ($test[$key][2]==='NO' && $test[$key][3] !=='PRI' && (strpos($test[$key][1], 'i'))===0)
        {
            $line= $_POST['newfieldname']." ".$test[$key][1]." NOT NULL"; 
            $sql = "ALTER TABLE ".$_POST['tablename']." CHANGE ".$_POST['fieldname']." ".$line;
        }

       //case PRIMARY NOT NULL
       elseif ($test[$key][2]==='NO' && $test[$key][3] ==='PRI')
       {
            $line= $_POST['newfieldname']." ".$test[$key][1]." NOT NULL AUTO_INCREMENT"; 
            $sql = "ALTER TABLE ".$_POST['tablename']." CHANGE ".$_POST['fieldname']." ".$line;
       }


   }

}

try
{
   $db1 = new PDO("mysql:host=".$_SESSION['host'].";dbname=".$_POST['dbname'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}
catch (Exception $e)
{
    echo ('err101');
    return;
}
    $q1=$db1->exec($sql);

?>