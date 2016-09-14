<?php
session_start();
    $dbhost= "localhost";
    $dbuser = "root";
    $dbpass = 'root';
    $dbname = $_POST['dbname'];
    $dumpFile = $dbname.".sql";
    $command = "mysqldump --host=$dbhost --user=$dbuser --password=$dbpass $dbname >   $dumpFile";
    exec($command);
    echo $_POST['dbname'];
    rename($_POST['dbname'].'.sql', '../ExportBdd/'.$_POST['dbname'].'.sql');
?>