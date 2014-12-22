<?php
/**
 * User: loveyu
 * Date: 2014/12/5
 * Time: 16:28
 */

/**
 * Class RecordCounter
 */
class RecordCounter{
	/**
	 * @var \CLib\Sql
	 */
	private $db;
	private $tmp_table = "_report_tmp";
	const name = "record_counter";

	/**
	 * 初始化
	 */
	function __construct(){
		$this->db = db();
	}

	/**
	 * 输出菜单
	 * @param $menu
	 * @return mixed
	 */
	public function out_menu($menu){
		array_push($menu, array(
			'name' => 'Android统计记录',
			'router' => array(
				'Admin',
				'plugin',
				self::name
			),
			'hidden' => false
		));
		return $menu;
	}

	public function ajax(){
		$msg = [
			'status' => false,
			'data' => NULL
		];
		if(!$this->sql_table_check(req()->get('db'))){
			$msg['data'] = 'Sql check error!';
		} else{
			try{
				require_once(__DIR__ . "/RecordAction.php");
				if(req()->is_post()){
					$action = new RecordAction(req()->post('action'), $this->tmp_table);
					echo json_encode($action->run());
					return;
				} else{
					if($this->sql_table_check($this->tmp_table)){
						$action = new RecordAction(req()->get('action'), $this->tmp_table);
						echo $action->run();
						return;
					} else{
						$msg['data'] = "Template table is not exist";
					}
				}
			} catch(Exception $e){
				$msg['data'] = "Exception:" . $e->getMessage();
			}
		}
		echo json_encode($msg);
	}

	/**
	 * 输出内容
	 * @param $name
	 * @return mixed
	 */
	public function out_content($name){
		if($name == self::name){
			if($this->sql_table_check(req()->get('db'))){
				$db = req()->get('db');
				$has_tmp_table = $this->sql_table_check($this->tmp_table);
				$db_count = $this->db->count($db);
				$tmp_count = $has_tmp_table ? $this->db->count($this->tmp_table) : -1;
				include(__DIR__ . "/page.php");
			} else{
				$list = $this->get_table_list();
				include(__DIR__ . "/select_db.php");
			}
		}
		return $name;
	}


	/**
	 * 检测当前是否有数据库
	 * @param string $name
	 * @return bool
	 */
	private function sql_table_check($name){
		if(empty($name)){
			return false;
		}
		$s = $this->db->query("show tables like " . $this->db->quote($name));
		return $s->rowCount() == 1;
	}

	/**
	 * 返回数据库列表
	 * @return array
	 */
	private function get_table_list(){
		$stmt = $this->db->query("show tables");
		if(!$stmt){
			return [];
		}
		$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$rt = [];
		foreach($list as $v){
			$rt[] = $v['Tables_in_pm'];
		}
		return $rt;
	}
} 