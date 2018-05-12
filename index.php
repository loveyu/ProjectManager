<?php
$time = microtime();
require_once("sys/config.php");
c()->getTimer()->setBeginTime($time); //修正启动时间
unset($time); //删除时间变量
define('IS_DEMO_SITE', $_SERVER['HTTP_HOST'] === "demo.loveyu.net");
//define('IS_DEMO_SITE', true);
cfg()->load('config/all.php'); //加载其他配置文件
lib()->load('hook')->add('Hook', new \ULib\Hook())->add(); //加载自定义类
u()->home(array('Home'), array(
	'Error',
	'page_404'
)); //开始加载默认页面