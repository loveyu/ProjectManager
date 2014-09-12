<?php
namespace CLib;
if(!defined('_CorePath_'))
	exit;
/**
 * 数据库连接
 */

if(!class_exists('medoo')){
	require('medoo.php');
}

class Sql extends \medoo{
	/**
	 * 数据库连接状态
	 * @var bool
	 */
	private $status = false;
	/**
	 * 连接的异常信息
	 * @var string
	 */
	private $ex_message = "";

	/**
	 * 构造器，对生成异常进行捕获
	 * @param array $setting 数据库配置信息
	 */
	function __construct($setting){
		if(!is_array($setting)){
			return;
		}
		try{
			parent::__construct($setting);
			$this->status = true;
		} catch(\Exception $ex){
			$this->ex_message = $ex->getMessage();
		}
	}

	/**
	 * 返回数据库连接状态
	 * @return bool
	 */
	public function status(){
		return $this->status;
	}

	/**
	 * 获取异常信息
	 * @return string
	 */
	public function ex_message(){
		return $this->ex_message;
	}

	/**
	 * 返回数据库的查询次数
	 * @return int
	 */
	public function get_query_count(){
		return $this->count_number;
	}
}