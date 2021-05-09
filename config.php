<?php
date_default_timezone_set('Asia/Tehran');
error_reporting(0);
ini_set("log_errors","off");
$DB = [
'dbname' => "dbname", // نام دیتابیس را اینجا وارد کنید
'username' => "username", //یوزرنیم دیتابیس را اینجاوارد کنید
'password' => '' //پسورد دیتابیس را اینجا وارد کنید
];
define('BOT_TOKEN',''); // توکن ربات اصلی
define('BOT_USERNAME',''); // یوزرنیم ربات اصلی بدون ادساین
$BotAdmins = [
0002,
0001,
0000,
]; // آیدی عددی ادمین های ربات

function GoldDev($method,$datas=[]){
$url = "https://api.telegram.org/bot".BOT_TOKEN."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}
}