<?php
/**
 * 系统必须加装的功能函数
 */
if(!defined('_CorePath_'))
	exit;
/**
 * 数据库
 * @return \CLib\Sql;
 */
function db(){
	return c_lib()->using('sql');
}

/**
 * cookie类
 *
 * @return \CLib\Cookie
 */
function ck(){
	return c_lib()->using('cookie');
}

/**
 * 项目类
 * @return \ULib\Project
 */
function project(){
	return lib()->using('project');
}

/**
 * 网站菜单类
 * @return \ULib\Menu
 */
function menu(){
	return lib()->using('menu');
}


/**
 * 用户类
 * @return \ULib\User
 */
function user(){
	return lib()->using('user');
}

/**
 * 项目管理类，不存在的情况下自己加装并初始化
 * @return  \ULib\ManageProject
 */
function m_project(){
	if(lib()->using('manage_project') == false){
		lib()->load('manage_project')->add('manage_project', new \ULib\ManageProject());
	}
	return lib()->using('manage_project');
}


/**
 * 系统设置类
 * @return \ULib\Setting
 */
function setting_lib(){
	return lib()->using('setting');
}


/**
 * 生成随机字符
 * @param int $len
 * @return string
 */
function salt($len = 40){
	$output = '';
	for($a = 0; $a < $len; $a++){
		$output .= chr(mt_rand(33, 126)); //生成php随机数
	}
	return $output;
}

/**
 * 通过加盐生成hash值
 *
 * @param $hash
 * @param $salt
 * @return string
 */
function salt_hash($hash, $salt){
	$count = count($salt);
	return _hash(substr($salt, 0, $count / 3) . $hash . $salt);
}

/**
 * 单独封装hash函数
 *
 * @param $str
 * @return string
 */
function _hash($str){
	return sha1($str);
}