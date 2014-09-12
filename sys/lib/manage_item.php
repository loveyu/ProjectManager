<?php
namespace ULib;
/**
 * 管理项目的项
 */
class ManageItem{
	/**
	 * 创建一个子项
	 * @param string $name
	 * @param string $title
	 * @param string $desc
	 * @param string $sort
	 * @param int    $project_id
	 * @return array 状态信息
	 */
	public function create($name, $title, $desc, $sort, $project_id){
		$rt = array(
			'page_id' => 0,
			'item_id' => 0,
			'project_id' => $project_id,
			'message' => '',
			'sql_error' => '',
			'status' => false
		);
		if(empty($name) || empty($title)){
			$rt['message'] = '名称和标题不允许为空';
			return $rt;
		}
		$page_id = $this->create_page($title);
		$rt['page_id'] = $page_id;
		if($page_id){
			$item_id = db()->insert('item', array(
				'name' => $name,
				'title' => $title,
				'desc' => $desc,
				'sort' => $sort,
				'project_id' => $project_id,
				'page_id' => $page_id
			));
			$rt['item_id'] = $item_id;
			$err = db()->error();
			if($err[0] != '00000' || $err[1] != ''){
				$rt['message'] = '创建子页面失败';
				$rt['sql_error'] = db()->error();
				db()->delete('page', array('id' => $page_id));
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
	 * 编辑一个子项
	 * @param int    $id
	 * @param string $name
	 * @param string $title
	 * @param string $desc
	 * @param string $sort
	 * @return array 状态信息
	 */
	public function edit($id, $name, $title, $desc, $sort){
		$rt = array(
			'message' => '',
			'sql_error' => '',
			'status' => false
		);
		if($id < 1 || empty($name) || empty($title)){
			$rt['message'] = '名称和标题不允许空且类型必须合法';
			return $rt;
		}
		if(db()->update("item", array(
			'name' => $name,
			'desc' => $desc,
			'sort' => $sort,
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
	 * 删除一个子项
	 * @param int $id
	 * @return array 状态信息
	 */
	public function delete($id){
		$rt = array(
			'status' => false,
			'error' => ''
		);
		$info = db()->get("item", array('page_id'), array('id' => $id));
		if(isset($info['page_id'])){
			if(db()->delete("item", array('id' => $id))){
				if(db()->delete("page", array('id' => $info['page_id']))){
					$rt['status'] = true;
				} else{
					$rt['error'] = "对应的页面删除失败，子项目已删除，请手动刷新";
				}
			} else{
				$rt['error'] = "子项目删除失败";
			}
		} else{
			$rt['error'] = "子项目不存在";
		}
		return $rt;
	}

	/**
	 * 获取项目及子项的信息，从子项的ID
	 *
	 * @param int $id 子ID
	 * @return array|bool 存在返回数组，失败返回false
	 */
	public function info_of_project($id){
		$rt = db()->select("item", array(
			"[><]project" => array(
				"project_id" => "id"
			)
		), array(
			"project.id" => 'p_id',
			"project.name" => 'p_name',
			"project.title" => 'p_title',
			"item.id" => 'i_id',
			"item.name" => 'i_name',
			"item.title" => 'i_title',
			"item.sort" => 'i_sort',
			"item.desc" => 'i_desc'
		), array('item.id' => $id));
		return $rt;
	}

	/**
	 * 获取子项列表
	 * @param int $project_id
	 * @return array|bool
	 */
	public function get_list($project_id){
		return db()->select('item', array(
			'id',
			'name',
			'title',
			'page_id'
		), array('project_id' => $project_id));
	}

	/**
	 * 创建一个简单的页面，以便创建子项
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