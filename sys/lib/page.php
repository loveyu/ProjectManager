<?php
namespace ULib;
/**
 * 页面编辑操作类
 */
class Page{
	/**
	 * @var bool|null 原始数据
	 */
	private $old = null;
	/**
	 * @var bool|null 新的数据，在对数据进行修改时临时存储
	 */
	private $new = null;
	/**
	 * @var null页面ID
	 */
	private $id = null;

	/**
	 * 构造函数
	 * @param int $page_id 页面ID
	 */
	public function __construct($page_id){
		$this->id = $page_id;
		$this->old = db()->get("page", array(
			'title',
			'content',
			'status',
			'time',
			'uptime',
			'view',
			'keywords',
			'type'
		), array('id' => $page_id));
		$this->new = $this->old;
	}

	/**
	 * 判断文章是否存在
	 * @return bool
	 */
	public function exists(){
		return $this->old && isset($this->old['title']);
	}

	/**
	 * 获取或设置标题
	 * @param string|null $data
	 * @return mixed
	 */
	public function title($data = null){
		if($data === null){
			return $this->new['title'];
		}
		$this->old['title'] = $data;
	}

	/**
	 * 获取或设置关键字
	 * @param null|string $data
	 * @return mixed
	 */
	public function keywords($data = null){
		if($data === null){
			return $this->new['keywords'];
		}
		$this->old['keywords'] = $data;
	}

	/**
	 * 获取或设置内容
	 * @param null|string $data
	 * @return mixed
	 */
	public function content($data = null){
		if($data === null){
			return $this->new['content'];
		}
		$this->old['content'] = $data;
	}

	/**
	 * 获取或设置文章状态
	 * @param null|string $data
	 * @return mixed
	 */
	public function status($data = null){
		if($data === null){
			return $this->new['status'];
		}
		$this->old['status'] = $data;
	}

	/**
	 * 获取或设置文章时间
	 * @param null|string $data
	 * @return mixed
	 */
	public function time($data = null){
		if($data === null){
			return $this->new['time'];
		}
		$this->old['time'] = $data;
	}

	/**
	 * 获取或设置文件更新时间
	 * @param null|string $data
	 * @return mixed
	 */
	public function up_time($data = null){
		if($data === null){
			return $this->new['uptime'];
		}
		$this->old['uptime'] = $data;
	}

	/**
	 * 获取或设置文章访问次数
	 * @param null|int $data
	 * @return mixed
	 */
	public function view_count($data = null){
		if($data === null){
			return $this->new['view'];
		}
		$this->old['view'] = $data;
	}

	/**
	 * 获取或设置文章类型
	 * @param null|string $data
	 * @return mixed
	 */
	public function type($data = null){
		if($data === null){
			return $this->new['type'];
		}
		$this->old['type'] = $data;
	}

	/**
	 * 返回数据
	 * @return array 文章全部信息
	 */
	public function out_data(){
		$rt = array(
			"status" => true,
			'data' => array(),
			'status_list' => $this->status_list()
		);
		if($this->new == false){
			$rt['status'] = false;
		} else{
			$rt['data'] = $this->new;
		}
		return $rt;
	}

	/**
	 * 重POST表单中获取数据并设置到信息数据中
	 */
	public function set_data(){
		$plain = req()->_plain();
		$this->title($plain->post('title'));
		$this->status($plain->post('status'));
		$this->time($plain->post('time'));
		$this->type($plain->post('type'));
		$this->view_count($plain->post('view'));
		$this->keywords($plain->post('keywords'));
		$this->content(req()->post('content'));
	}

	/**
	 * 检测并更新数据
	 * @return array
	 */
	public function up_data(){
		$rt = array();
		foreach(array_keys($this->new) as $name){
			if($this->old[$name] != $this->new[$name]){
				$rt[$name] = $this->old[$name];
			}
		}
		$msg = array(
			'status' => 'success',
			'message' => ''
		);
		if(count($rt) > 0){
			$rt['uptime'] = date('Y-m-d H:i:s');
			if(!db()->update("page", $rt, array('id' => $this->id))){
				$msg['status'] = 'error';
				$msg['message'] = db()->error();
			}
		} else{
			$msg['status'] = 'no change';
		}
		return $msg;
	}

	/**
	 * 获取文章状态列表
	 * @return array
	 */
	public function status_list(){
		return array(
			'publish',
			'delete',
			'draft'
		);
	}
}

?>