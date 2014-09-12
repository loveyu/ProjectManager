<?php
require_once("../../../sys/config.php");
cfg()->load('../../../config/all.php'); //加载其他配置文件
lib()->load('hook', 'user')->add('Hook', new \ULib\Hook())->add(); //加载自定义类
$user = new \ULib\User(true);
if(!$user->login_status()){
	u()->load_404();
	exit;
}