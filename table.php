<?php
include ('config.php');
$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error){
echo 'Connection failed: ' . $MySQLi->connect_error;
$MySQLi->close();
die;
}
$UsersTable = "CREATE TABLE `group` (
`id` VARCHAR(100) PRIMARY KEY,
`name` VARCHAR(200) DEFAULT NULL,
`date` VARCHAR(200) DEFAULT NULL
) default charset = utf8mb4";
if($MySQLi->query($UsersTable) === TRUE)
echo "Table created successfully";
else
echo "Error creating table : " . $MySQLi->error;
$MySQLi->close();
die;