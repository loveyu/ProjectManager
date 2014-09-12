<?php
namespace ULib;
/**
 * 项目显示输出类
 */
class Project{
	/**
	 * @var array 获取到项目全部数据
	 */
	private $data;
	/**
	 * @var int 项目ID
	 */
	private $project_id;
	/**
	 * @var int 子项目ID
	 */
	private $item_id;

	/**
	 * 构造函数,依据项目读取全部数据
	 * @param int $project_id
	 * @param int $item_id
	 */
	public function __construct($project_id, $item_id){
		$this->project_id = $project_id;
		$this->item_id = $item_id;
		$data = null;
		if(empty($item_id)){
			//非子项目，仅仅为顶级项目
			$data = db()->select("project", array(
				'[><]page' => array(
					'page_id' => 'id'
				)
			), array(
				'project.id' => 'p_id',
				'project.name' => 'p_name',
				'project.title' => 'p_title',
				'project.desc' => 'desc',
				'page.content' => 'content',
				'page.time' => 'time',
				'page.id' => 'id',
				'page.type' => 'type',
				'page.title' => 'title',
				'page.keywords' => 'keywords'
			), array(
				'project.id' => $project_id
			));
		} else{
			//存在子项目
			$data = db()->select("item", array(
				'[><]page' => array(
					'page_id' => 'id'
				),
				'[><]project' => array(
					'project_id' => 'id'
				)
			), array(
				'project.id' => 'p_id',
				'project.name' => 'p_name',
				'project.title' => 'p_title',
				'item.name' => 'item_name',
				'item.title' => 'item_title',
				'page.content' => 'content',
				'item.desc' => 'desc',
				'page.time' => 'time',
				'page.id' => 'id',
				'page.type' => 'type',
				'page.title' => 'title',
				'page.keywords' => 'keywords'
			), array(
				'AND' => array(
					'project.id' => $project_id,
					'item.id' => $item_id
				)
			));
		}
		//对于数据，获取的只有一条
		if(isset($data[0]))
			$this->data = $data[0];
	}

	/**
	 * 判断是否为顶级
	 * @return bool
	 */
	public function is_top(){
		return empty($this->item_id);
	}

	/**
	 * 获取标题
	 * @return string
	 */
	public function title(){
		return $this->data['title'];
	}

	/**
	 * 获取内容
	 * @return string
	 */
	public function content(){
		return hook()->apply('project_content', $this->data['content'], $this->type());
	}

	/**
	 * 获取关键字
	 * @return string
	 */
	public function keywords(){
		return $this->data['keywords'];
	}

	/**
	 * 获取文章ID
	 *
	 * @return int
	 */
	public function id(){
		return $this->data['id'] + 0;
	}

	/**
	 * 获取文章类型
	 * @return string
	 */
	public function type(){
		return $this->data['type'];
	}

	/**
	 * 获取项目名称
	 * @return string
	 */
	public function project_name(){
		return $this->data['p_name'];
	}

	/**
	 * 获取项目标题
	 * @return string
	 */
	public function project_title(){
		return $this->data['p_title'];
	}

	/**
	 * 获取页面描述
	 * @return string
	 */
	public function desc(){
		return $this->data['desc'];
	}

	/**
	 * 项目ID
	 *
	 * @return int
	 */
	public function project_id(){
		return 0 + $this->project_id;
	}

	/**
	 * 子项目ID
	 *
	 * @return int
	 */
	public function item_id(){
		return 0 + $this->item_id;
	}
}

?>