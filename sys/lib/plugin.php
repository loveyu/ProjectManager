<?php
namespace ULib;
/**
 * 插件类
 */
class Plugin{
	/**
	 * @var array 插件列表
	 */
	private $list = array();
	/**
	 * @var 插件存放路径
	 */
	private $path;

	/**
	 * 构造
	 * @param $path 插件存放路径
	 */
	public function __construct($path){
		$this->path = $path;
		$this->read_dir();
		$this->run();
	}

	/**
	 * 运行所有插件
	 */
	private function run(){
		foreach($this->list as $name){
			$path = $this->path . "/" . $name . "/run.php";
			if(is_file($path)){
				include $path;
			}
		}
	}

	/**
	 * 读取插件列表
	 */
	private function read_dir(){
		$hd = opendir($this->path);
		if($hd){
			while(($name = readdir($hd)) != null){
				if(substr($name, 0, 1) != '.' && is_dir($this->path . "/" . $name)){
					$this->list[] = $name;
				}
			}
		}
	}
}

?>