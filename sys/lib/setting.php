<?php
namespace ULib;
/**
 * 数据库设置类
 */
class Setting{
	/**
	 * 生成数据库设置信息
	 */
	public function __construct(){
		$list = db()->select("setting", array(
			'name',
			'value'
		), array('auto' => 1));
		$rt = array();
		foreach($list as $v){
			$rt[$v['name']] = $v['value'];
		}
		cfg()->set(array('s_g'), $rt);
	}

	/**
	 * 编辑数据库设置
	 * @param string $name
	 * @param string $value
	 * @return array 状态信息
	 */
	public function edit_sql($name, $value){
		$rt = array(
			'status' => false,
			'error' => ''
		);
		$data = db()->get("setting", array(
			'name',
			'value'
		), array('name' => $name));
		if(isset($data['name'])){
			if($data['value'] == $value){
				$rt['status'] = true;
			} else{
				if(db()->update("setting", array('value' => $value), array('name' => $name))){
					$rt['status'] = true;
				} else{
					$rt['status'] = db()->error();
				}
			}
		} else{
			$rt['error'] = "该设置不存在";
		}
		return $rt;
	}

	/**
	 * 获取项目全部列表
	 * @param int $page
	 * @param int $number
	 * @return array
	 */
	public function get_list($page, $number = 20){
		if(empty($page))
			$page = 1;
		if(empty($number))
			$number = 20;
		$rt = array(
			'status' => false,
			'count' => db()->count("setting"),
			'data' => array(),
			'number' => $number,
			'page' => $page
		);
		$rt['data'] = db()->select("setting", array(
			'id',
			'name',
			'value',
			'auto'
		), array(
			'LIMIT' => array(
				$number * ($page - 1),
				$number
			)
		));
		if(count($rt['data']) > 0)
			$rt['status'] = true;
		return $rt;
	}

}