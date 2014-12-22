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
	public function out_menu($menu){
		array_push($menu, array(
			'name' => '统计记录',
			'router' => array(
				'Admin',
				'plugin',
				'record_counter'
			),
			'hidden' => false
		));
		return $menu;
	}

	public function out_content($name){
		if($name == 'record_counter'){
			if($this->sql_check()){
				include(__DIR__ . "/page.php");
			} else{
				include(__DIR__ . "/select_db.php");
			}
		}
		return $name;
	}

	private function sql_check(){
		$name = req()->get('db');

		return true;
	}
} 