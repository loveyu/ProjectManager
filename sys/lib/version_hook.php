<?php
namespace ULib;

use Core\Page;

class Version_Hook extends Page
{
	function __construct(){
		hook()->add('project_content', [
			$this,
			'content_parse'
		]);
	}

	public function content_parse($data, $type){
		preg_match_all("/<!--\\[VERSION:([a-zA-Z_]+):([a-z_]+)\\]-->/", $data, $matches, PREG_SET_ORDER);
		foreach($matches as $v){
			switch($v[2]){
				case "history":
					$data = str_replace($v[0], $this->get_history($v[1]), $data);
					break;
				case "top_version":
					$data = str_replace($v[0], $this->get_top_version($v[1]), $data);
					break;
				case "top_version_code":
					$data = str_replace($v[0], $this->get_top_version_code($v[1]), $data);
					break;
				case "update_info":
					$data = str_replace($v[0], $this->get_update_info($v[1]), $data);
					break;
			}
		}
		return $data;
	}

	private function get_update_info($name){
		$this->__lib('version_api');
		$list = db()->select("version_control", ["update_info","version"], [
			'AND' => [
				'name' => $name,
				'version_code[>]' => 0
			],
			'ORDER' => 'version_code DESC'
		]);
		foreach($list as &$v){
			$v['update_info'] = Version_Api::parse_info($v['update_info']);
		}
		ob_start();
		$this->__view("version/update_info.php", ['list' => $list]);
		$c = ob_get_contents();
		ob_clean();
		return $c;
	}

	private function get_top_version($name){
		$r = db()->get("version", "top_version", ['name' => $name]);
		return isset($r['top_version']) ? $r['top_version'] : (is_string($r) ? $r : "0.0.0");
	}

	private function get_top_version_code($name){
		$r = db()->get("version", "top_version_code", ['name' => $name]);
		return isset($r['top_version_code']) ? $r['top_version_code'] : (is_string($r) ? $r : "0");
	}

	private function get_history($name){
		$list = db()->select("version_control", [
			'version',
			'time',
			'download_url'
		], [
			'AND' => [
				'name' => $name,
				'version_code[>]' => 0
			],
			'ORDER' => 'version_code DESC'
		]);
		ob_start();
		$this->__view("version/history.php", ['list' => $list]);
		$c = ob_get_contents();
		ob_clean();
		return $c;
	}
}