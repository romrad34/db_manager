<?php
session_start();
$db = null;

if (isset($_POST['dbname']))
{
    if (!isset($_POST['multi_array'])) // If there is only one line in the create table
    {
        if($_POST['index_field']==='')
        {
            try
            {
               $db = new PDO("mysql:host=".$_SESSION['host'].";dbname=".$_POST['dbname'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

                $sql = "CREATE TABLE IF NOT EXISTS ".$_POST['table_name']." (".$_POST['name_field']." ".$_POST['type_field']." ".$_POST['size_field']." ".$_POST['isnull_field']." ".$_POST['autoincrement_field'].") CHARACTER SET utf8";

                $db->exec($sql);
                echo "La table a été créée avec succès";
                echo $sql;
            }
            catch (PDOException $e)
            {
                echo ('err101' .$e->getMessage());
                return;
            }
        }
        else 
        {
            try
            {
               $db = new PDO("mysql:host=".$_SESSION['host'].";dbname=".$_POST['dbname'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                $sql = "CREATE TABLE IF NOT EXISTS ".$_POST['table_name']." (".$_POST['name_field']." ".$_POST['type_field']." ".$_POST['size_field']." ".$_POST['isnull_field']." ".$_POST['autoincrement_field'].", PRIMARY KEY ".$_POST['index_field'].") CHARACTER SET utf8";

                $db->exec($sql);
                echo "La table a été créée avec succès";
                echo $sql;
            }
            catch (PDOException $e)
            {
                echo ('err101'.$e->getMessage());
                return;
            }
        }
    }
    else  // If it is an an array which is returned
    {
        $sql='';
        try
        {
           $db = new PDO("mysql:host=".$_SESSION['host'].";dbname=".$_POST['dbname'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }
        catch (Exception $e)
        {
            echo ('err101');
            return;
        }
        $sql_init = "CREATE TABLE IF NOT EXISTS ".$_POST['table_name']." (";
        $sql_end=") CHARACTER SET utf8";
        
        //name_field_a=0   type_field_a=1   size_field_a=2   index_field_a=3   autoincrement_field_a=4   isnull_field_a=5)
        for($i=0; $i<count($_POST['multi_array'][3]);$i++)
        {
            if ($_POST['multi_array'][3][$i]==='')
            {
                    if (count($_POST['multi_array'][0])-$i>1)
                    {
                    $sql.= $_POST['multi_array'][0][$i]." ".$_POST['multi_array'][1][$i]." ".$_POST['multi_array'][2][$i]." ".$_POST['multi_array'][5][$i]." ".$_POST['multi_array'][4][$i]." , ";
                    }
                    else
                    {
                    $sql.=$_POST['multi_array'][0][$i]." ".$_POST['multi_array'][1][$i]." ".$_POST['multi_array'][2][$i]." ".$_POST['multi_array'][5][$i]." ".$_POST['multi_array'][4][$i]; 
                    }

            }
            else 
            {
                    if (count($_POST['multi_array'][0])-$i>1)
                    {
                        $sql.=$_POST['multi_array'][0][$i]." ".$_POST['multi_array'][1][$i]." ".$_POST['multi_array'][2][$i]." ".$_POST['multi_array'][5][$i]." ".$_POST['multi_array'][4][$i].", PRIMARY KEY ".$_POST['multi_array'][3][$i]." , ";     
                    }
                else
                {
                      $sql.=$_POST['multi_array'][0][$i]." ".$_POST['multi_array'][1][$i]." ".$_POST['multi_array'][2][$i]." ".$_POST['multi_array'][5][$i]." ".$_POST['multi_array'][4][$i].", PRIMARY KEY ".$_POST['multi_array'][3][$i]; 
                }
            }
        }
        $full_sql=$sql_init.$sql.$sql_end;
        $db->exec($full_sql);
        echo ($full_sql);
    }
}
else
{
    echo ('error103');
}
?>