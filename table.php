<?php  
    include ('config.php');
    $dbHost="localhost";  
    $dbName=$DB['dbname'];  
    $dbUser=$DB['username'];     
    $dbPassword=$DB['password'];
    try{  
        $dbConn= new PDO("mysql:host=$dbHost;dbname=$dbName",$dbUser,$dbPassword);  

     $UsersTable = "CREATE TABLE `group` (
    `id` VARCHAR(100) PRIMARY KEY,
    `name` VARCHAR(200) DEFAULT NULL,
    `date` VARCHAR(200) DEFAULT NULL
    ) default charset = utf8mb4";
    $dbConn->query($UsersTable);
    Echo "Table created successfully";     
        
       
    } catch(Exception $e){  
    Echo "Connection failed" . $e->getMessage();  
    }  
// this command close the connection.  
    $dbConn = null;   
?>  
