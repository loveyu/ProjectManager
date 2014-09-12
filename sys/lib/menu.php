<?php
namespace ULib;
/**
 * 菜单函数
 */
class Menu{
	/**
	 * @var int 项目ID
	 */
	private $p_id = 0;
	/**
	 * @var int 子项目ID
	 */
	private $item_id = 0;
	/**
	 * @var string 项目名字
	 */
	private $p_name = '';
	/**
	 * @var string 项目标题
	 */
	private $p_title = '';
	/**
	 * @var null 子项目列表
	 */
	private $list = null;
	/**
	 * @var bool 顶级列表
	 */
	private $top = true;

	/**
	 * 创建菜单，并且设置是否为顶级菜单，用于设置项目为单独页面的情况
	 * @param bool $top
	 */
	public function __construct($top = false){
		$project = project();
		if($project == false){
			$this->top();
		} else{
			$this->p_id = $project->project_id();
			$this->item_id = $project->item_id();
			$this->p_name = $project->project_name();
			$this->p_title = $project->project_title();
			if(!$top){
				$this->project();
				$this->top = false;
			} else{
				$this->top();
			}
		}
	}

	/**
	 * 针对项目获取子项列表
	 */
	private function project(){
		$this->list = db()->select("item", array(
			'id',
			'name',
			'title',
			'sort'
		), array(
			'project_id' => $this->p_id,
			'ORDER' => 'sort'
		));
	}

	/**
	 * 获取顶级菜单列表
	 */
	private function top(){
		foreach(db()->select("project", array(
			'id',
			'name',
			'title',
			'sort'
		), array(
			'type' => 'project',
			'ORDER' => 'sort',
			'LIMIT' => array(
				0,
				4
			)
		)) as $v){
			$this->list[] = $v;
		}

		foreach(db()->select("project", array(
			'id',
			'name',
			'title',
			'sort'
		), array(
			'type' => 'page',
			'ORDER' => 'sort',
			'LIMIT' => array(
				0,
				3
			)
		)) as $v){
			$this->list[] = $v;
		}

	}

	/**
	 * 用于输出菜单，添加相应的内容
	 * @param string $tag         标签
	 * @param string $before      之前
	 * @param string $end         之后
	 * @param string $class_style 活跃菜单的class名称
	 */
	public function out($tag = 'li', $before = '', $end = "\n", $class_style = 'active'){
		$class = "";
		if(empty($this->item_id)){
			$class = " class=\"$class_style\"";
		}
		if(!$this->top){
			echo $before, "<$tag$class><a$class id=\"menu_project_", $this->p_id, "\" href=\"" . get_url($this->p_name) . "\">", $this->p_name, "</a></$tag>", $end;
			foreach($this->list as $v){
				$class = "";
				if($v['id'] == $this->item_id){
					$class = " class=\"$class_style\"";
				}
				echo $before, "<$tag$class><a$class id=\"menu_item_$v[id]\" href=\"" . get_url($this->p_name, $v['name']) . "\">$v[title]</a></$tag>", $end;
			}
		} else{
			$class = '';
			if($this->p_id == 0){
				$class = " class=\"$class_style\"";
			}
			echo $before, "<$tag$class><a$class href=\"" . get_url() . "\">首页</a></$tag>", $end;
			foreach($this->list as $v){
				$class = "";
				if($v['id'] == $this->p_id){
					$class = " class=\"$class_style\"";
				}
				echo $before, "<$tag$class><a$class id=\"menu_project_$v[id]\" href=\"" . get_url($v['name']) . "\">$v[title]</a></$tag>", $end;
			}
		}
	}
}