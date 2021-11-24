<?php

$users[1]['score']=100;
$users[2]['score']=200;
$users[3]['score']=300;
$users[4]['score']=400;
//var_dump($user);

$users=array_reverse($users,true);
var_dump($users);

echo "<hr>";
$users_1=array_slice($users,0,3);
var_dump($users_1);
echo "<br>保留键名：";
$users_2=array_slice($users,0,3,true);
var_dump($users_2);
