<?php
namespace ULib;
/**
 * 项目管理类
 */
class ManageProject{
	/**
	 * @var array|null 所以项目列表
	 */
	private $all_list = null;

	/**
	 * 获取对应的项目列表
	 * @return array
	 */
	public function get_list(){
		if($this->all_list === null){
			$this->all_list = db()->select('project', array(
				'id',
				'name',
				'title',
				'desc'
			), array(
				'type' => 'project',
				'ORDER' => 'sort'
			));
		}
		return $this->all_list;
	}

	/**
	 * 获取项目列表，更加页面数
	 * @param int $page   第几页
	 * @param int $number 每页数量
	 * @return array
	 */
	public function get_list_of_page($page = 1, $number = 20){
		if(empty($page))
			$page = 1;
		if(empty($number))
			$number = 20;
		$rt = array(
			'status' => false,
			'count' => db()->count("project"),
			'data' => array(),
			'number' => $number,
			'page' => $page
		);
		$rt['data'] = db()->select("project", array(
			'id',
			'name',
			'title',
			'page_id',
			'type'
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

	/**
	 * 获取项目的信息
	 * @param int $id
	 * @return bool | array
	 */
	public function info($id = 0){
		$rt = false;
		if($id > 0){
			$rt = db()->get('project', array(
				'name',
				'title',
				'desc',
				'page_id',
				'id',
				'type',
				'sort'
			), array('id' => $id));
		}
		return $rt;
	}

	/**
	 * 创建一个项目
	 * @param string $name
	 * @param string $title
	 * @param string $desc
	 * @param string $sort
	 * @param string $type
	 * @return array
	 */
	public function create($name, $title, $desc, $sort, $type = 'project'){
		$rt = array(
			'page_id' => 0,
			'project_id' => 0,
			'message' => '',
			'sql_error' => '',
			'status' => false
		);
		if(empty($name) || empty($title)){
			$rt['message'] = '名称和标题不允许空';
			return $rt;
		}
		$rt['page_id'] = $this->create_page($title);
		if($rt['page_id']){
			$rt['project_id'] = db()->insert('project', array(
				'name' => $name,
				'title' => $title,
				'desc' => $desc,
				'sort' => $sort,
				'page_id' => $rt['page_id'],
				'type' => $type
			));
			if(!$rt['project_id']){
				$rt['message'] = '插入项目内容失败';
				$rt['sql_error'] = db()->error();
				db()->delete('page', array('id' => $rt['page_id']));
			} else{
				$rt['status'] = true;
			}
		} else{
			$rt['message'] = '创建页面失败';
			$rt['sql_error'] = db()->error();
		}
		return $rt;
	}

	/**
	 * 删除一个项目
	 * @param int $id
	 * @return array 状态信息
	 */
	public function delete($id){
		$rt = array(
			'status' => false,
			'error' => ''
		);
		$info = db()->get("project", array('page_id'), array('id' => $id));
		if(isset($info['page_id'])){
			$pages = array();
			foreach(db()->select("item", array('page_id'), array('project_id' => $id)) as $v){
				$pages[] = $v['page_id'];
			}
			if(count($pages)){
				if(!db()->delete("item", array('project_id' => $id))){
					$rt['error'] = '子项目无法删除';
					return $rt;
				} else{
					if(!db()->delete("page", array('id' => $pages))){
						$rt['error'] = '子项目已删除，但页面未删除';
						return $rt;
					}
				}
			}
			if(db()->delete("project", array('id' => $id))){
				if(db()->delete("page", array('id' => $info['page_id']))){
					$rt['status'] = true;
				} else{
					$rt['error'] = "项目页面删除失败，其中项目已删除" . ($pages > 0 ? "，但子项目已删除" : "");
				}
			} else{
				$rt['error'] = "项目删除失败" . ($pages > 0 ? "，但子项目已删除" : "");
			}
		} else{
			$rt['error'] = "项目不存在";
		}
		return $rt;
	}

	/**
	 * 编辑子项
	 * @param int    $id
	 * @param string $name
	 * @param string $title
	 * @param string $desc
	 * @param string $sort
	 * @param string $type
	 * @return array
	 */
	public function edit($id, $name, $title, $desc, $sort, $type){
		$rt = array(
			'message' => '',
			'sql_error' => '',
			'status' => false
		);
		if($id < 1 || empty($name) || empty($title) || !in_array($type, array(
			'project',
			'page'
		))
		){
			$rt['message'] = 'ID、名称和标题不允许空且类型必须合法';
			return $rt;
		}
		if(db()->update("project", array(
			'name' => $name,
			'desc' => $desc,
			'sort' => $sort,
			'type' => $type,
			'title' => $title
		), array('id' => $id))
		){
			$rt['status'] = true;
		} else{
			$rt['message'] = '修改项目失败';
			$rt['sql_error'] = db()->error();
			if(isset($rt['sql_error'][0]) && $rt['sql_error'][0] == "00000"){
				$rt['message'] = '数据无改变';
				$rt['sql_error'] = '';
			}
		}
		return $rt;
	}

	/**
	 * 创建一个简单的页面
	 * @param string $title
	 * @return string
	 */
	public function create_page($title){
		return db()->insert('page', array(
			'title' => $title,
			'time' => date('Y-m-d H:i:s')
		));
	}
}