<?php
namespace ULib;
/**
 * Class Version_Api
 * @package ULib
 */
class Version_Api
{
	/**
	 * 最高版本信息
	 * @var array|false
	 */
	private $top_info;
	/**
	 * @var \CLib\Sql
	 */
	private $db;
	/**
	 * 当前检测版本信息
	 * @var array|false
	 */
	private $now_version;

	/**
	 * 构造一个版本控制API
	 * @param string $name 版本控制名称
	 * @param string|int $version 版本号
	 * @param string $type 版本号类型 [version|version_code|build_version]
	 * @throws \Exception 数据库未连接异常
	 */
	function __construct($name, $version, $type = "version"){
		$name = trim($name);
		$version = trim($version);
		$type = trim($type);
		if(!in_array($type, [
			'version',
			'version_code',
			'build_version'
		])
		){
			throw new \Exception("版本类型初始化错误");
		}
		$this->db = db();
		if(!is_object($this->db)){
			throw new \Exception("必须先初始化SQL连接");
		}
		$this->top_info = $this->db->get("version", "*", ['name' => $name]);
		if(!isset($this->top_info['id'])){
			throw new \Exception("对应的版本控制不存在");
		}
		$this->now_version = $this->db->get("version_control", "*", [
			'AND' => [
				'name' => $name,
				$type => $version
			]
		]);
	}

	/**
	 * 获取最高版本信息
	 * @return array|false
	 */
	public function get_top_info(){
		return $this->top_info;
	}

	/**
	 * 获取当前版本信息
	 * @return array|false
	 */
	public function get_now_version(){
		return $this->now_version;
	}

	/**
	 * 获取最高版本的检测信息
	 * @return array|false
	 */
	public function get_top_update_info(){
		$info = $this->db->get("version_control", "*", [
			'AND' => [
				'name' => $this->top_info['name'],
				'version_code' => $this->top_info['top_version_code']
			]
		]);
		$info['update_info'] = $this->parse_info($info['update_info']);
		return $info;
	}

	/**
	 * 获取全部更新信息
	 * @return array
	 */
	public function get_all_updates(){
		$name = $this->top_info['name'];
		$now_code = isset($this->now_version['version_code']) ? $this->now_version['version_code'] : 0;
		$info = $this->db->select("version_control", [
			'version',
			'version_code',
			'build_version',
			'update_info'
		], [
			'AND' => [
				'name' => $name,
				'version_code[>]' => $now_code
			]
		]);
		$rt = [];
		foreach($info as $v){
			$v['update_info'] = $this->parse_info($v['update_info']);
			$rt[$v['version']] = $v;
		}
		ksort($rt);
		return $rt;
	}

	/**
	 * 获取全部BUG信息
	 * @return array
	 */
	public function get_all_bugs(){
		$name = $this->top_info['name'];
		$now_code = isset($this->now_version['version_code']) ? $this->now_version['version_code'] : 0;
		$info = $this->db->select("version_control", [
			'version',
			'version_code',
			'build_version',
			'bugs'
		], [
			'AND' => [
				'name' => $name,
				'version_code[>=]' => $now_code
			]
		]);
		$rt = [];
		foreach($info as $v){
			$v['bugs'] = $this->parse_info($v['bugs']);
			$rt[$v['version']] = $v;
		}
		ksort($rt);
		return $rt;
	}

	/**
	 * 解析更新信息的格式
	 * @param string $info
	 * @return array [['msg'=>'','url'=>''],['msg'=>'','url'=>'']]
	 */
	private function parse_info($info){
		$up = [];
		if(empty($info)){
			return [];
		}
		foreach(explode("\n", $info) as $vvv){
			$vvv = trim($vvv);
			if(empty($vvv)){
				continue;
			}
			$v3 = explode("[#]", $vvv);
			$up[] = [
				'msg' => $v3[0],
				'url' => (isset($v3[1]) && !empty($v3[1])) ? $v3[1] : "#"
			];
		}
		return $up;
	}
}