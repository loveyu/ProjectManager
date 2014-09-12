<?php
namespace Core;
if(!defined('_CorePath_'))
	exit;
/**
 * 页面基础类
 */
class Page{
	/**
	 * 加载页面
	 * @return bool
	 */
	protected function __load(){
		return call_user_func_array(array(
			u(),
			'load'
		), func_get_args());
	}

	/**
	 * 加载404页面
	 */
	protected function __load_404(){
		u()->load_404();
	}

	/**
	 * 加载类库
	 * @return \Core\Lib
	 */
	protected function __lib(){
		return call_user_func_array(array(
			lib(),
			'load'
		), func_get_args());
	}

	/**
	 * 加载视图
	 */
	protected function __view($file, $param = null){
		if(is_array($file)){
			foreach($file as $v){
				if(is_file(_ViewPath_ . "/$v")){
					$this->view($v, $param);
				}
			}
		} else{
			if(is_file(_ViewPath_ . "/$file")){
				$this->view($file, $param);
			}
		}
	}

	/**
	 * 包含存在的视图文件
	 * @param $file
	 * @param $param
	 */
	private function view($file, $param){
		if(is_array($param)){
			foreach(array_keys($param) as $key){
				$tmp = "__" . $key;
				$$tmp = & $param[$key];
			}
			unset($key);
			unset($tmp);
		}
		include(_ViewPath_ . "/$file");
	}

	/**
	 * 默认页面,加载404页面
	 */
	public function main(){
		//include(_CorePath_ . "/view/default.html");
		$this->__load_404();
	}
}

?>