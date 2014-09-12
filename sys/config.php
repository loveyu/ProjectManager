<?php
if(PHP_VERSION < 5.3){
	//此处存在不确定因素，在5.4下开发
	die(_("PHP Version must be greater than 5.3"));
}

//设置时区
date_default_timezone_set("PRC");

/**
 * 调试模式，基本未使用
 */
define('_Debug_', true);

/**
 * 转义是否开启标志
 */
define('MAGIC_QUOTES_GPC', PHP_VERSION < 6 && get_magic_quotes_gpc());

/**
 * 路由器分割字符
 */
define('ROUTER_SPLIT_CHAR', '/');

/**
 * COOKIE加密密钥
 */
define('COOKIE_KEY', 'pXyGxCgkUK")H-:F1+8QY<3|muQ\z^Nu');

/**
 * COOKIE前缀
 */
define('COOKIE_PREFIX', 'LC_');

/**
 * 基本路径
 */
define("_BasePath_", dirname(__DIR__));

/**
 * 系统路径
 */
define("_SysPath_", __DIR__);

/**
 * 核心路径
 */
define("_CorePath_", _SysPath_ . "/core");

/**
 * 页面路径
 */
define("_PagePath_", _SysPath_ . "/page");

/**
 * 功能函数路径
 */
define("_HelperPath_", _SysPath_ . "/helper");

/**
 * 视图路径
 */
define("_ViewPath_", _SysPath_ . "/view");

/**
 * 类库路径
 */
define("_LibPath_", _BasePath_ . "/sys/lib");

//设置运行错误信息
if(_Debug_){
	error_reporting(E_ALL);
} else{
	error_reporting(0);
}

//加载接口文件
require_once(_CorePath_ . "/interface.php");
?>