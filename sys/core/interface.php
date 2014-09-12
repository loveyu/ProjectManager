<?php
/**
 * 核心接口获取
 */

if(!defined('_CorePath_'))
	exit;

/**
 * 获取核心
 * @return Core\Core
 */
function c(){
	return Core\Core::getInstance();
}

/**
 * 获取URI
 * @return Core\Uri
 */
function u(){
	return c()->getUri();
}

/**
 * 获取钩子
 * @return Core\Hook
 */
function hook(){
	return c()->getHook();
}

/**
 * 获取LIB
 * @return Core\Lib
 */
function lib(){
	return c()->getLib();
}

/**
 * 获取系统LIB
 * @return Core\Lib
 */
function c_lib(){
	return c()->getCoreLib();
}

/**
 * 获取系统配置
 * @return Core\Config
 */
function cfg(){
	return c()->getConfig();
}

/**
 * 加载一个助手文件
 * @param string $helper_file 文件名，或者相对助手文件夹路径
 * @return mixed
 */
function l_h($helper_file){
	return require(_HelperPath_ . "/" . $helper_file);
}

/**
 * 加载一个系统助手文件
 * @param string $helper_file 文件名，或者相对助手文件夹路径
 * @return mixed
 */
function c_h($helper_file){
	return require(_CorePath_ . "/helper/" . $helper_file);
}

/**
 * 获取请求类
 * @return Core\Request
 */
function req(){
	return c()->getRequest();
}

/**
 * 输出调试信息
 * @param string $str
 */
function debug($str){
	if(_Debug_)
		echo "Debug:$str\n";
}


require_once(_CorePath_ . "/core.php");
require_once(_CorePath_ . "/timer.php");
require_once(_CorePath_ . "/hook.php");
require_once(_CorePath_ . "/config.php");
require_once(_CorePath_ . "/uri.php");
require_once(_CorePath_ . "/lib.php");
require_once(_CorePath_ . "/page.php");
require_once(_CorePath_ . "/request.php");
?>