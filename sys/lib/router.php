<?php
namespace ULib;
/**
 * 用户数据库路由过滤类
 */
class Router{
	/**
	 * 标记该路由是否生效
	 * @var bool
	 */
	public static $begin_status = false;

	/**
	 * 前路由
	 * @param array $list
	 * @return array
	 */
	public function begin($list){
		return $list;
	}

	/**
	 * 后路由
	 * @param array $list
	 * @return array
	 */
	public function end($list){
		$rt = null;
		switch(count($list)){
			case 2:
				$rt = db()->select("item", array(
					'[><]project' => array('project_id' => 'id')
				), array(
					'item.id' => 'item',
					'project.id' => 'project'
				), array(
					'AND' => array(
						'project.name' => $list[0],
						'item.name' => $list[1]
					),
					'LIMIT' => 1
				));
				if(isset($rt[0])){
					$rt = $rt[0];
				} else{
					$rt = null;
				}
				break;
			case 1:
				$rt = db()->get("project", array(
					"id" => "project",
					"type"
				), array('name' => $list[0]));
				if(!is_array($rt)){
					$rt = null;
				} else{
					$rt['item'] = null;
					if($rt['type'] == 'page'){
						//当项目类型为Page时的处理
						self::$begin_status = true;
						return array(
							'Page',
							'page',
							$rt['project']
						);
					}
				}
				break;
		}
		if($rt !== null){
			self::$begin_status = true;
			$list = array(
				'Page',
				'project',
				$rt['project'],
				$rt['item']
			);
		}
		return $list;
	}
}