<?php

/**
 * User: loveyu
 * Date: 2014/12/22
 * Time: 22:26
 */
class RecordAction{
	/**
	 * @var string 操作类型
	 */
	private $action;
	/**
	 * @var \CLib\Sql 数据库对象
	 */
	private $db;

	private $table_name;

	/**
	 * 初始化
	 * @param string $action
	 * @param string $table_name
	 */
	function __construct($action, $table_name){
		$this->action = $action;
		$this->table_name = $table_name;
		$this->db = db();
	}

	/**
	 * 执行调用
	 * @return array
	 */
	public function run(){
		$rt = [
			'status' => false,
			'data' => NULL
		];
		switch($this->action){
			case "tmp_create":
				$rt['status'] = $this->tmp_create(req()->get('db'));
				break;
			case "tmp_delete":
				$rt['status'] = $this->tmp_delete();
				break;
			case "tmp_day_output":
				$rt = $this->tmp_day_output();
				break;
			case "tmp_phone_output":
				$rt = $this->tmp_phone_output();
				break;
			case "tmp_phone_model_output":
				$rt = $this->tmp_phone_model_output();
				break;
			case "day_output":
				$rt = $this->day_output(req()->get('db'));
				break;
			case "most_frequently_used":
				$rt = $this->most_frequently_used(req()->get('db'));
				break;
			case "count_used":
				$rt = $this->count_used(req()->get('db'));
				break;
			case "tmp_android_version":
				$rt = $this->tmp_android_version();
				break;
			case "tmp_width_height":
				$rt = $this->tmp_width_height();
				break;
			default:
				break;
		}
		return $rt;
	}

	private function tmp_create($db){
		$this->db->exec("DROP TABLE IF EXISTS `{$this->table_name}`;
CREATE TABLE `{$this->table_name}` (
  `id` bigint(20) unsigned NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  `max_version` char(20) DEFAULT NULL,
  `version` char(20) DEFAULT NULL,
  `ip` char(15) DEFAULT NULL,
  `uid` char(48) DEFAULT NULL,
  `version_code` int(11) unsigned DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `phone_model` varchar(100) DEFAULT NULL,
  `width` int(11) unsigned DEFAULT NULL,
  `height` int(11) unsigned DEFAULT NULL,
  `android` varchar(20) DEFAULT NULL,
  `android_sdk` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		$this->db->exec("insert into `{$this->table_name}` (SELECT * FROM `{$db}` where 1 GROUP BY `uid` ORDER by `id`);");
		return true;
	}

	private function tmp_delete(){
		$this->db->exec("DROP TABLE IF EXISTS `{$this->table_name}`;");
		return true;
	}

	private function tmp_day_output(){
		$min = $this->db->min($this->table_name, 'time');
		$max = $this->db->max($this->table_name, 'time');
		header_download("Download " . date("Y-m-d.H.i.s", $min) . " - " . date("Y-m-d.H.i.s", $max) . ".txt");
		$min = strtotime(date("Y-m-d", $min));
		$max = strtotime(date("Y-m-d", $max)) + 86400;
		for($i = $min; $i < $max; $i += 86400){
			$c = $this->db->count($this->table_name, [
				'AND' => [
					'time[>=]' => $i,
					'time[<]' => $i + 86400
				]
			]);
			echo date("Y-m-d", $i), "\t", $c, "\r\n";
		}
		return "";
	}

	private function tmp_phone_output(){
		header_download("PhoneReport " . date("Y-m-d") . ".txt");
		$stmt = $this->db->query("select `phone`, COUNT(`phone`) as `count` from `{$this->table_name}` where 1 GROUP BY `phone` ORDER BY count desc;");
		if(!$stmt){
			return "";
		}
		while(($data = $stmt->fetch(PDO::FETCH_ASSOC)) != NULL){
			echo implode("\t", $data), "\r\n";
		}
		$stmt->closeCursor();
		return "";
	}

	private function tmp_width_height(){
		header_download("Phone Resolution Report " . date("Y-m-d") . ".txt");
		$stmt = $this->db->query("select `width`,`height`,COUNT(*) as `count` from `{$this->table_name}` where 1 GROUP BY `width`,`height` ORDER BY `count` desc;");
		if(!$stmt){
			return "";
		}
		while(($data = $stmt->fetch(PDO::FETCH_ASSOC)) != NULL){
			echo implode("\t", $data), "\r\n";
		}
		$stmt->closeCursor();
		return "";
	}

	private function tmp_android_version(){
		header_download("AndroidVersion " . date("Y-m-d") . ".txt");
		$stmt = $this->db->query("select `android`,`android_sdk`, COUNT(`android`) as `count` from {$this->table_name} where 1 GROUP BY `android` ORDER BY `count` desc;");
		if(!$stmt){
			return "";
		}
		while(($data = $stmt->fetch(PDO::FETCH_ASSOC)) != NULL){
			echo implode("\t", $data), "\r\n";
		}
		$stmt->closeCursor();
		return "";
	}


	private function tmp_phone_model_output(){
		header_download("PhoneModelReport " . date("Y-m-d") . ".txt");
		$stmt = $this->db->query("select phone_model, COUNT(`phone_model`) as `count` from {$this->table_name} where 1 GROUP BY `phone_model` ORDER BY `count` desc;");
		if(!$stmt){
			return "";
		}
		while(($data = $stmt->fetch(PDO::FETCH_ASSOC)) != NULL){
			echo $data['phone_model'], "\t", $data['count'], "\r\n";
		}
		$stmt->closeCursor();
		return "";
	}

	private function day_output($db){
		$min = $this->db->min($db, 'time');
		$max = $this->db->max($db, 'time');
		header_download("DayReport " . date("Y-m-d.H.i.s", $min) . " - " . date("Y-m-d.H.i.s", $max) . ".txt");
		$min = strtotime(date("Y-m-d", $min));
		$max = strtotime(date("Y-m-d", $max)) + 86400;
		for($i = $min; $i < $max; $i += 86400){
			$stmt = $this->db->query("SELECT count(`count`) as `total`,SUM(`count`) as `all` from (SELECT count(*) as `count` FROM `{$db}` WHERE `time` >= {$i} AND `time` < " . ($i + 86400) . " GROUP BY `uid`) as temp;");
			$c = $stmt->fetch(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			echo date("Y-m-d", $i), "\t", $c['total'], "\t", $c['all'], "\r\n";
		}
		return "";
	}

	private function most_frequently_used($db){
		header_download("Most Frequently Used " . date("Y-m-d") . ".txt");
		$stmt = $this->db->query("SELECT `phone`,`phone_model`,`android`,COUNT(`uid`) as `count` FROM `{$db}` where 1 GROUP BY `uid` ORDER BY `count` desc;");
		$i = 1;
		while(($data = $stmt->fetch(PDO::FETCH_ASSOC)) != NULL){
			echo $i++, "\t", implode("\t", $data), "\r\n";
		}
		$stmt->closeCursor();
		return "";
	}

	private function count_used($db){
		header_download("Count Used " . date("Y-m-d") . ".txt");
		$stmt = $this->db->query("select `count`,count(`count`) as `c` FROM (SELECT COUNT(`uid`) as `count` FROM `{$db}` where 1 GROUP BY `uid` ORDER BY `count` desc )as temp GROUP BY `count` ORDER BY `count` desc;");
		if(!$stmt){
			return "";
		}
		while(($data = $stmt->fetch(PDO::FETCH_ASSOC)) != NULL){
			echo implode("\t", $data), "\r\n";
		}
		$stmt->closeCursor();
		return "";
	}
} 