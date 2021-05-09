<?php
include ('config.php');
include ('jdf.php');
$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) die;
function ToDie($MySQLi){
$MySQLi->close();
die;
}
if(!file_exists('BotAdmins')) file_put_contents('BotAdmins','[]');
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$msg = $message->text;
$tc = $message->chat->type;
$chat_id = $message->chat->id;
$from_id = $message->from->id;
$first_name = $message->from->first_name;
$message_id = $message->message_id;
$BotAdmins = json_decode(file_get_contents('BotAdmins'),true);
if(!in_array(ADMIN_ID,$BotAdmins)){
$BotAdmins[] = ADMIN_ID;
file_put_contents('BotAdmins',json_encode($BotAdmins,true));
}
if($update and $tc == 'private' and !in_array($from_id,$BotAdmins)){
GoldDev('DeleteMessage',[
'chat_id' => $from_id,
'message_id' => $message_id,
]);
ToDie($MySQLi);
}
if($msg == '/start' and $tc == 'private' and in_array($from_id,$BotAdmins)){
GoldDev('sendMessage',[
'chat_id' => $from_id,
'text'=> 'سلام ادمین گرامی!
به ربات مدیریت گروه هایتان خوش آمدید.
برای ارسال پیام به تمامی گروه های نصب شده کافیست پیام خود را ارسال نمایید. (میتواند شامل تمام رسانه ها نیز باشد!)

دستورات مخصوص مدیر اصلی :
<pre>/add</pre>
<pre>/del</pre>
<pre>/admins</pre>

برای مثال برای ادمین کردن فرد کافیست آیدی عددی فرد را بهمراه دستور add بصورت زیر به ربات ارسال کنید 👇🏻
<b>/add 122546658</b>
برای حذف فرد از ادمینی 👇🏻
<b>/del 122546658</b>
برای دریافت لیست ادمین ها 👇🏻
<b>/admins</b>',
'reply_to_message_id'=>$message_id,
'parse_mode' => 'HTML',
]);
ToDie($MySQLi);
}
if($tc !== 'private' and isset($update->message->new_chat_member)){
if($update->message->new_chat_member->username !== str_replace('@','',BOT_USERNAME)) ToDie($MySQLi);
// if(!in_array($from_id,$BotAdmins)){
// GoldDev('sendMessage',[
// 'chat_id' => $chat_id,
// 'text'=> 'من باید توسط ادمین های ربات به گروه ادد شوم!',
// 'reply_to_message_id'=>$message_id,
// 'parse_mode' => 'HTML',
// ]);
// GoldDev('leaveChat',[
// 'chat_id'=>$chat_id,
// ]);
// ToDie($MySQLi);
// }
$GroupID = $chat_id;
$GroupName = $message->chat->title;
$NowDate = jdate('l').' '.jdate('j').' '.jdate('F').' '.jdate('Y').' | '.jdate('H').':'.jdate('i').':'.jdate('s');
$HisDataBase = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `group` WHERE `id` = '{$GroupID}' LIMIT 1"));
if(!$HisDataBase){
$MySQLi->query("INSERT INTO `group` (`id`,`name`,`date`) VALUES ('{$GroupID}','{$GroupName}','{$NowDate}')");
$MenTionUser = "[$first_name](tg://user?id=$from_id)";
GoldDev('sendMessage',[
'chat_id' => $chat_id,
'text'=> "ربات اطلاع رسانی زرین پال جهت اعلام  سریع اطلاع رسانی ها به گروه اضافه شد.",
'reply_to_message_id'=>$message_id,
'parse_mode' => 'MarkDown',
]);
foreach($BotAdmins as $id){
GoldDev('sendMessage',[
'chat_id'=> $id,
'text'=> "گروه ( $GroupName ) با موفقیت به دیتابیس ربات اضافه شد!
شناسه عددی گروه : $GroupID
ادمین نصب کننده : $MenTionUser
تاریخ نصب 👇🏻
$NowDate",
'parse_mode'=>'MarkDown',
]);
}
}else{
GoldDev('sendMessage',[
'chat_id' => $chat_id,
'text'=> 'گروه از قبل در دیتابیس ربات وجود داشت لذا نیازی به نصب دوباره گروه نیست.',
'reply_to_message_id'=>$message_id,
'parse_mode' => 'MarkDown',
]);
}
ToDie($MySQLi);
}
if(explode(' ',$msg)[0] == '/add' and explode(' ',$msg)[1] !== null){
if ($from_id !== ADMIN_ID) ToDie($MySQLi);
$HisID = explode(' ',$msg)[1];
$MenTionUser = "[این کاربر با موفقیت در ربات شما ادمین شد!](tg://user?id=$HisID)";
GoldDev('sendMessage',[
'chat_id' => $from_id,
'text'=> $MenTionUser,
'reply_to_message_id'=>$message_id,
'parse_mode' => 'MarkDown',
]);
GoldDev('sendMessage',[
'chat_id' => $HisID,
'text'=> 'شما با موفقیت در ربات ادمین شدید!
لطفا یک مرتبه ربات را استارت کنید.',
'parse_mode' => 'MarkDown',
]);
if(!in_array($HisID,$BotAdmins)){
$BotAdmins[] = (int)$HisID;
file_put_contents('BotAdmins',json_encode($BotAdmins,true));
}
ToDie($MySQLi);
}
if(explode(' ',$msg)[0] == '/del' and explode(' ',$msg)[1] !== null){
if ($from_id !== ADMIN_ID) ToDie($MySQLi);
$HisID = explode(' ',$msg)[1];
$MenTionUser = "[این کاربر با موفقیت از لیست ادمین های ربات خارج شد!](tg://user?id=$HisID)";
GoldDev('sendMessage',[
'chat_id' => $from_id,
'text'=> $MenTionUser,
'reply_to_message_id'=>$message_id,
'parse_mode' => 'MarkDown',
]);
GoldDev('sendMessage',[
'chat_id' => $HisID,
'text'=> 'شما از لیست ادمین های ربات خارج شدید.
لطفا یک مرتبه ربات را استارت کنید.',
'parse_mode' => 'MarkDown',
]);
if(in_array($HisID,$BotAdmins)){
$index = 0;
foreach($BotAdmins as $key){
if($BotAdmins[$index] == $HisID) break;
$index++;
}
unset($BotAdmins[$index]);
file_put_contents('BotAdmins',json_encode($BotAdmins,true));
}
ToDie($MySQLi);
}
if($msg == '/admins'){
if ($from_id !== ADMIN_ID) ToDie($MySQLi);
$c = 1;
$MyStr = 'لیست ادمین های ربات :'."\n";
foreach($BotAdmins as $key){
$MyStr .= $c.'- '."[$key](tg://user?id=$key)"."\n";
$c++;
}
GoldDev('sendMessage',[
'chat_id' => $from_id,
'text'=> $MyStr,
'reply_to_message_id'=>$message_id,
'parse_mode' => 'MarkDown',
]);
ToDie($MySQLi);
}
if($update and in_array($from_id,$BotAdmins) and $tc == 'private'){
if(isset($message->document)){
$file_id = $message->document->file_id;
$SendType = 'document';
$ArrType = 'document';
}
elseif(isset($message->voice)){
$file_id = $message->voice->file_id;
$SendType = 'voice';
$ArrType = 'voice';
}
elseif(isset($message->video)){
$file_id = $message->video->file_id;
$SendType = 'video';
$ArrType = 'video';
}
elseif(isset($message->video_note)){
$file_id = $message->video_note->file_id;
$SendType = 'videonote';
$ArrType = 'video_note';
}
elseif(isset($message->audio)){
$file_id = $message->audio->file_id;
$SendType = 'audio';
$ArrType = 'audio';
}
elseif(isset($message->sticker)){
$file_id = $message->sticker->file_id;
$SendType = 'sticker';
$ArrType = 'sticker';
}
elseif(isset($message->photo)){
$photo = $message->photo;
$file_id = $photo[count($photo)-1]->file_id;
$SendType = 'photo';
$ArrType = 'photo';
}
elseif(isset($message->gif)){
$file_id = $message->gif->file_id;
$SendType = 'gif';
$ArrType = 'gif';
}
else{
$file_id = $msg;
$SendType = 'message';
$ArrType = 'text';
}
$oldtime = microtime(true);
$GetAllUsers = mysqli_fetch_all(mysqli_query($MySQLi, "SELECT `id` FROM `group`"));
GoldDev('sendMessage',[
'chat_id' => $from_id,
'text'=> 'عملیات ارسال پیام شروع شد ...',
'reply_to_message_id'=>$message_id,
'parse_mode' => 'HTML',
]);
foreach($GetAllUsers as $userTosend){
if(isset($update->message->caption))
GoldDev('send'.$SendType,[
'chat_id'=> $userTosend[0],
$ArrType => $file_id,
'caption' => $update->message->caption
]);
else
GoldDev('send'.$SendType,[
'chat_id'=> $userTosend[0],
$ArrType => $file_id,
]);
}
$newtame = microtime(true);
$MyTime = round((($newtame - $oldtime)), 2);
GoldDev('sendMessage',[
'chat_id'=>$from_id,
'text'=> "عملیات ارسال با موفقیت به اتمام رسید.
زمان اجرای عملیات : $MyTime ثانیه",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
]);
ToDie($MySQLi);
}




















ToDie($MySQLi);
