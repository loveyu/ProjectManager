<?php
return array(
	'sql' => array(
		'database_type' => 'mysql',
		//服务器类型 支持 mysql sqlite pgsql mssql sybase
		'server' => 'localhost',
		//服务器地址
		'username' => 'root',
		//用户名
		'password' => '123456',
		//密码
		'database_file' => '',
		//数据库文件,	SqLite 专有文件
		'charset' => 'utf8',
		//编码
		'database_name' => 'pm',
		//数据库名
		'option' => array( //PDO选项
			PDO::ATTR_CASE => PDO::CASE_NATURAL
		),
	),
	'cookie' => array(
		//是否对COOKIE值进行加密，其中密钥在系统配置config.php中
		'encode' => true
	),
	//用于存放系统设置
	'system' => array(),
);