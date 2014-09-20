<?php
namespace ULib;


/**
 * Class Version
 * @package ULib
 */
class Version
{
	/**
	 * @var \CLib\Sql 数据库操作实例
	 */
	private $db;

	/**
	 * 构造器
	 */
	function __construct(){
		$this->db = db();
	}

	/**
	 * 获取所有的版本控制
	 * @return array|bool
	 */
	public function get_name_list(){
		return $this->db->select("version", ['name']);
	}

	/**
	 * 依据ID获取版本的详细信息
	 * @param int $id 数据库中的ID值
	 * @return bool|array
	 */
	public function get_detail($id){
		return $this->db->get("version_control", "*", ['id' => $id]);
	}

	/**
	 * 编辑某一个版本信息
	 * @param int $id 版本ID值
	 * @param string $force_update
	 * @param string $download_url
	 * @param string $update_url
	 * @param string $update_info
	 * @param string $message
	 * @param string $bugs
	 * @return array
	 */
	public function version_edit($id, $force_update, $download_url, $update_url, $update_info, $message, $bugs){
		$rt = [
			'status' => false,
			'msg' => ''
		];
		if(!$this->db->has("version_control", ['id' => $id])){
			$rt['msg'] = "不存在该版本控";
			return $rt;
		}
		$force_update = $force_update ? 1 : 0;
		if(!filter_var($download_url, FILTER_VALIDATE_URL)){
			$rt['msg'] = "下载地址有误";
			return $rt;
		}
		if(!filter_var($update_url, FILTER_VALIDATE_URL)){
			$rt['msg'] = "更新地址有误";
			return $rt;
		}
		$update_info = implode("\n", array_map("trim", explode("\n", $update_info)));
		$bugs = implode("\n", array_map("trim", explode("\n", $bugs)));
		$rid = $this->db->update("version_control", [
			'update_info' => $update_info,
			'message' => $message,
			'bugs' => $bugs,
			'force_update' => $force_update,
			'time_update' => time(),
			'download_url' => $download_url,
			'update_url' => $update_url
		], ['id' => $id]);
		if($rid > 0){
			$rt['msg'] = $rid;
			$rt['status'] = true;
		} else{
			$rt['msg'] = "更新失败";
		}
		return $rt;
	}

	/**
	 * 编辑该版本控制的信息
	 * @param string $name
	 * @param string $url
	 * @param string $info
	 * @return array
	 */
	public function edit_info($name, $url, $info){
		$rt = [
			'status' => false,
			'msg' => ''
		];
		if(!filter_var($url, FILTER_VALIDATE_URL)){
			$rt['msg'] = "地址错误";
			return $rt;
		}
		$r = $this->db->update("version", [
			'download' => $url,
			'info' => $info
		], ['name' => $name]);
		if($r > 0){
			$rt['status'] = true;
		} else{
			$rt['msg'] = "未更新任何数据";
		}
		return $rt;
	}

	/**
	 * 依据最新的版本信息查询当前版本控制的最高版本，依据version_code
	 * @param string $name
	 */
	private function set_top_version($name){
		$info = $this->db->select("version_control", [
			'version',
			'version_code'
		], [
			'name' => $name,
			'ORDER' => 'version_code DESC',
			'LIMIT' => [
				0,
				1
			]
		]);
		$info = isset($info[0]) ? $info[0] : null;
		if(isset($info['version'])){
			$this->db->update("version", [
				'top_version' => $info['version'],
				'top_version_code' => $info['version_code']
			], ['name' => $name]);
		} else{
			$this->db->update("version", [
				'top_version' => '0',
				'top_version_code' => 0
			], ['name' => $name]);
		}
	}

	/**
	 * 删除一个版本
	 * @param $id
	 * @return array
	 */
	public function delete_version($id){
		$info = $this->db->get("version_control", "*", ['id' => $id]);
		if(isset($info['name'])){
			$rt = $this->db->delete("version_control", ['id' => $id]);
			if($rt){
				$this->set_top_version($info['name']);
				return [
					'status' => true,
					'msg' => 'ok'
				];
			} else{
				return [
					'status' => false,
					'msg' => '删除出错'
				];
			}
		} else{
			return [
				'status' => false,
				'msg' => '数据未找到'
			];
		}
	}

	/**
	 * 获取一个版本的信息
	 * @param $name
	 * @return array|bool
	 */
	public function get_info_of_version($name){
		return $this->db->get("version", "*", ['name' => $name]);
	}

	/**
	 * 获取某一版本控制的列表
	 * @param $name
	 * @return array|bool
	 */
	public function get_item_list($name){
		return $this->db->select("version_control", [
			'id',
			'version',
			'version_code',
			'build_version',
			'time',
			'time_update',
			'download_url',
			'update_url'
		], ['name' => $name]);
	}

	/**
	 * 判断某一版本控制是否存在
	 * @param $name
	 * @return bool
	 */
	public function check_version_name_exists($name){
		return $this->db->has("version", ['name' => $name]);
	}

	/**
	 * 添加一个新版本
	 * @param string $name
	 * @param string $version
	 * @param string $version_code
	 * @param string $build_version
	 * @param string $force_update
	 * @param string $download_url
	 * @param string $update_url
	 * @param string $update_info
	 * @param string $message
	 * @return array
	 */
	public function version_add($name, $version, $version_code, $build_version, $force_update, $download_url, $update_url, $update_info, $message){
		$rt = [
			'status' => false,
			'msg' => ''
		];
		if(!$this->check_version_name_exists($name)){
			$rt['msg'] = "不存在该版本控制名称";
			return $rt;
		}
		$version = implode(".", array_map("intval", explode(".", $version)));
		$version_code = intval($version_code);
		$build_version = trim($build_version);
		if($this->db->has("version_control", [
			'AND' => [
				'name' => $name,
				[
					'OR' => [
						'version' => $version,
						'version_code' => $version_code,
						'build_version' => $build_version
					]
				]
			]
		])
		){
			$rt['msg'] = "当前版本号存在冲突";
			return $rt;
		}
		$force_update = $force_update ? 1 : 0;
		if(!filter_var($download_url, FILTER_VALIDATE_URL)){
			$rt['msg'] = "下载地址有误";
			return $rt;
		}
		if(!filter_var($update_url, FILTER_VALIDATE_URL)){
			$rt['msg'] = "更新地址有误";
			return $rt;
		}
		$update_info = implode("\n", array_map("trim", explode("\n", $update_info)));
		$id = $this->db->insert("version_control", [
			'name' => $name,
			'version' => $version,
			'version_code' => $version_code,
			'build_version' => $build_version,
			'update_info' => $update_info,
			'message' => $message,
			'force_update' => $force_update,
			'time' => time(),
			'download_url' => $download_url,
			'update_url' => $update_url
		]);
		if($id > 0){
			$rt['msg'] = $id;
			$this->set_top_version($name);
			$rt['status'] = true;
		} else{
			$rt['msg'] = "创建失败";
		}
		return $rt;
	}

	/**
	 * 创建一个新版本控制
	 * @param string $name
	 * @param string $url
	 * @param string $info
	 * @return array
	 */
	public function version_create($name, $url, $info){
		$rt = [
			'status' => false,
			'msg' => ''
		];
		if(!preg_match("/^[a-zA-Z_]{4,}$/", $name)){
			$rt['msg'] = "名称不符合规则";
			return $rt;
		}
		if(!filter_var($url, FILTER_VALIDATE_URL)){
			$rt['msg'] = "网址不符合规则";
			return $rt;
		}
		if($this->db->has("version", ['name' => $name])){
			$rt['msg'] = "名称重复";
			return $rt;
		}
		$id = $this->db->insert("version", [
			'name' => $name,
			'download' => $url,
			'info' => $info
		]);
		if($id < 1){
			$rt['msg'] = "创建失败" . implode(",", $this->db->error());
		} else{
			$rt['msg'] = $id;
			$rt['status'] = true;
		}
		return $rt;
	}
} 