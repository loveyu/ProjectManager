<?php
namespace ULib;
if(!defined('_CorePath_'))
	exit;
/**
 * 自定义Hook类
 */
class Hook{
	/**
	 * @var \CLib\Router 系统路由
	 */
	private $_router;

	/**
	 * @var Router 自定义路由
	 */
	private $project_router;

	/**
	 * 构造函数
	 */
	function __construct(){
		l_h('func.php');
		c_lib()->load('router', 'sql')->add('sql', new \CLib\Sql(cfg()->get('sql')));
		$this->_router = new \CLib\Router(); //系统路由类
		lib()->load('router');
		$this->project_router = new Router(); //自定义路由类
	}

	/**
	 * 添加钩子,并初始化部分信息
	 */
	public function add(){
		if(!db()->status()){
			hook()->add('UriInfo_process', array(
				$this,
				'sql_error'
			));
		} else{
			c_lib()->load('cookie')->add('cookie', new \CLib\Cookie(cfg()->get('cookie', 'encode')));
			lib()->load('setting', 'user', 'plugin')->add('setting', new Setting());
			lib()->add('user', new User(true));
			l_h('theme.php');
			hook()->add('UriInfo_process', array(
				$this,
				'router'
			));
			lib()->add('plugin', new Plugin(_BasePath_ . "/ex/plugin"));
		}
	}

	/**
	 * 指定数据库连接出错的页面
	 * @return array
	 */
	public function sql_error(){
		return array(
			'Error',
			'sql'
		);
	}

	/**
	 * 路由回调
	 * @param $list
	 * @return mixed
	 */
	public function router($list){
		return $this->project_router->end($this->_router->result($this->project_router->begin($list)));
	}
}