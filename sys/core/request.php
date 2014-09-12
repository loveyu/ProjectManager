<?php
namespace Core;
if(!defined('_CorePath_'))
	exit;
/**
 * 请求处理类
 */
class Request{

	/**
	 * 请求类的模式标记
	 * @var int
	 */
	private $_mode;

	/**
	 * more为0时为明文模式
	 *
	 * @param int $mode
	 */
	function __construct($mode = 0){
		$this->_mode = $mode;
	}

	/**
	 * 转义模式，使用addslashes()效果
	 *
	 * @return Request
	 */
	public function _escape(){
		return new Request(1);
	}

	/**
	 * 明文模式，将所有Html标签转义
	 *
	 * @return Request
	 */
	public function _plain(){
		return new Request(2);
	}

	/**
	 * 对数据进行转换或明文处理
	 * @param mixed $data 输入默认数据null
	 * @return mixed 根据相应模式进行转换
	 */
	private function data_convert($data = ''){
		if(null === $data)
			return null;
		if(MAGIC_QUOTES_GPC){
			switch($this->_mode){
				case 0:
					$data = self::stripslashes_deep($data);
				case 2:
					//先转换为非转义字符，然后进行HTML标签替换
					$data = self::htmlspecialchars_deep(self::stripslashes_deep($data));
			}
		} else{
			switch($this->_mode){
				case 1:
					$data = self::addslashes_deep($data);
				case 2:
					$data = self::htmlspecialchars_deep($data);
			}
		}
		return $data;
	}

	/**
	 * 对数组进行递归反转义操作
	 * @param string|array $value
	 * @return array|string
	 */
	public static function stripslashes_deep($value){
		return is_array($value) ? array_map(array(
			self,
			'stripslashes_deep'
		), $value) : stripslashes($value);
	}

	/**
	 * 对数组进行递归HTML标签转换为HTML符号
	 *
	 * @param string|array $value
	 * @return array|string
	 */
	public static function htmlspecialchars_deep($value){
		return is_array($value) ? array_map(array(
			self,
			'htmlspecialchars_deep'
		), $value) : htmlspecialchars($value);
	}

	/**
	 * 对数组进行递归转义
	 * @param string|array $value
	 * @return array|string
	 */
	public static function addslashes_deep($value){
		return is_array($value) ? array_map(array(
			self,
			'addslashes_deep'
		), $value) : addslashes($value);
	}


	/**
	 * 获取$_GET变量中的内容，参数为连续参数
	 * @return string|array|null
	 */
	public function get(){
		return hook()->apply('Request_get', $this->data_convert(self::_get($_GET, func_get_args())));
	}

	/**
	 * 获取$_POST变量中的内容，参数为连续参数
	 * @return string|array|null
	 */
	public function post(){
		return hook()->apply('Request_post', $this->data_convert(self::_get($_POST, func_get_args())));
	}

	/**
	 * 获取$_COOKIE变量中的内容，参数为连续参数
	 * @return string|array|null
	 */
	public function cookie(){
		$p = func_get_args();
		if(isset($p[0])){
			$p[0] = COOKIE_PREFIX . $p[0];
		}
		return hook()->apply('Request_cookie', $this->data_convert(self::_get($_COOKIE, $p)));
	}

	/**
	 * 获取$_REQUEST变量中的内容，参数为连续参数
	 * @return string|array|null
	 */
	public function req(){
		return hook()->apply('Request_req', $this->data_convert(self::_get($_REQUEST, func_get_args())));
	}

	/**
	 * 获取某一数组中的对应层次的元素
	 * @param array $data 原始数据
	 * @param array $list 键值列表
	 * @return mixed 对应的数据
	 */
	public static function _get($data, $list){
		foreach($list as $v){
			if(isset($data[$v])){
				$data = $data[$v];
			} else{
				return null;
			}
		}
		return $data;
	}

	/**
	 * 是否为GET请求方式
	 *
	 * @return bool
	 */
	public function is_get(){
		return strtolower($_SERVER['REQUEST_METHOD']) === 'get';
	}

	/**
	 * 是否为POST请求方式
	 *
	 * @return bool
	 */
	public function is_post(){
		return strtolower($_SERVER['REQUEST_METHOD']) === 'post';
	}


}