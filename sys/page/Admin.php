<?php
namespace UView;

/**
 * 管理员界面
 */
class Admin extends \Core\Page{
	/**
	 * 构造函数，加载管理员函数，初始化用户
	 */
	function __construct(){
		l_h("admin.php");
		$this->user = new \ULib\User(true);
		if(!user()->login_status()){
			redirect(user()->login_go());
		}
		lib()->load('menu')->add('menu', new \ULib\Menu());
		theme()->header_add("<script>var SITE_URL = '" . URL_WEB . "';</script>", 0);
		set_title(null, '控制中心');
	}

	/**
	 * 主页
	 */
	public function main(){
		set_title('控制中心', '');
		header("Content-Type:text/html; charset=utf-8");
		$this->__view('admin/header.php');
		$this->__view('admin/index.php');
		$this->__view('admin/footer.php');
	}

	/**
	 * 创建项目
	 */
	public function project_create(){
		set_title('创建新的项目');
		theme()->header_add(theme()->js(array('src' => get_file_url("js/jquery.form.js"))));
		header("Content-Type:text/html; charset=utf-8");
		$this->__view('admin/header.php');
		$this->__view('admin/project_create.php');
		$this->__view('admin/footer.php');
	}

	/**
	 * 编辑子项目
	 */
	public function item_edit(){
		header("Content-Type:text/html; charset=utf-8");
		lib()->load('manage_item');
		$mi = new \ULib\ManageItem();
		$rt = $mi->info_of_project(req()->_plain()->get('id') + 0);
		if(!isset($rt[0]['p_id'])){
			set_title("项目页面选择");
			theme()->header_add(theme()->js(array("src" => get_file_url('js/admin/page_list.js'))));
			$this->__view('admin/header.php');
			$this->__view('admin/page_list.php');
		} else{
			theme()->header_add(theme()->js(array('src' => get_file_url("js/jquery.form.js"))));
			set_title('修改子项目页面信息 “' . $rt[0]['i_title'] . '”');
			$this->__view('admin/header.php');
			$this->__view('admin/item_edit.php', $rt[0]);
		}
		$this->__view('admin/footer.php');
	}

	/**
	 * 项目编辑
	 */
	public function project_edit(){
		header("Content-Type:text/html; charset=utf-8");
		lib()->load('manage_project');
		$mp = new \ULib\ManageProject();
		$rt = $mp->info(req()->_plain()->get('id') + 0);
		if($rt == false){
			set_title("项目页面选择");
			theme()->header_add(theme()->js(array("src" => get_file_url('js/admin/page_list.js'))));
			$this->__view('admin/header.php');
			$this->__view('admin/page_list.php');
		} else{
			theme()->header_add(theme()->js(array('src' => get_file_url("js/jquery.form.js"))));
			set_title('修改子项目页面信息 “' . $rt['title'] . '”');
			$this->__view('admin/header.php');
			$this->__view('admin/project_edit.php', $rt);
		}
		$this->__view('admin/footer.php');
	}

	/**
	 * 子项目创建
	 * @param int $id 项目ID
	 */
	public function item_create($id = 0){
		set_title('创建子项目');
		theme()->header_add(theme()->js(array('src' => get_file_url("js/jquery.form.js"))));
		header("Content-Type:text/html; charset=utf-8");
		$this->__view('admin/header.php');
		lib()->load('manage_project');
		$mp = new \ULib\ManageProject();
		if(($info = $mp->info($id)) == false){
			$this->__view('admin/project_select.php', array(
				'list' => $mp->get_list(),
				'mode' => 'item_create'
			));
		} else{
			$this->__view('admin/item_create.php', $info);
		}
		$this->__view('admin/footer.php');
	}

	/**
	 * 编辑页面
	 */
	public function page_edit(){
		header("Content-Type:text/html; charset=utf-8");
		lib()->load('page');
		$page = new \ULib\Page(req()->_plain()->get('id') + 0);
		if($page->exists()){
			page_edit_header_out();
			set_title("文章编辑 `" . $page->title() . "`");
			theme()->header_add(theme()->js(array("src" => get_file_url('js/admin/page_edit.js'))));
			$this->__view('admin/header.php');
			$this->__view('admin/page_edit.php');
		} else{
			set_title("文章编辑页面选择");
			theme()->header_add(theme()->js(array("src" => get_file_url('js/admin/page_list.js'))));
			$this->__view('admin/header.php');
			$this->__view('admin/page_list.php');
		}
		$this->__view('admin/footer.php');
	}

	/**
	 * 创建页面
	 */
	public function page_create(){
		set_title("创建页面");
		header("Content-Type:text/html; charset=utf-8");
		$this->__view('admin/header.php');
		$this->__view('admin/page_create.php');
		$this->__view('admin/footer.php');
	}

	/**
	 * 网站设置
	 */
	public function setting(){
		set_title("网站设置");
		header("Content-Type:text/html; charset=utf-8");
		theme()->header_add(theme()->js(array("src" => get_file_url('js/admin/setting.js'))));
		$this->__view('admin/header.php');
		$this->__view('admin/setting.php');
		$this->__view('admin/footer.php');
	}

	/**
	 * 用户设置
	 */
	public function user(){
		set_title("用户设置");
		header("Content-Type:text/html; charset=utf-8");
		theme()->header_add(theme()->js(array('src' => get_file_url("js/jquery.form.js"))));
		$this->__view('admin/header.php');
		$this->__view('admin/user.php');
		$this->__view('admin/footer.php');
	}

	/**
	 * 插件页面
	 */
	public function plugin($plugin_name){
		set_title("插件设置");
		header("Content-Type:text/html; charset=utf-8");
		$this->__view('admin/header.php');
		$this->__view('admin/plugin.php', array('param' => $plugin_name));
		$this->__view('admin/footer.php');
	}

	/**
	 * ajax请求操作
	 */
	public function ajax(){
		header("Content-Type:application/json; charset=utf-8");
		$plain = req()->_plain();
		switch($plain->get('type')){
			case "project_create":
				lib()->load('manage_project');
				$mp = new \ULib\ManageProject();
				echo json_encode($mp->create($plain->post('name'), $plain->post('title'), $plain->post('desc'), $plain->post('sort') + 0, $plain->post('type')));
				break;
			case "project_edit":
				lib()->load('manage_project');
				$mp = new \ULib\ManageProject();
				echo json_encode($mp->edit($plain->post('id') + 0, $plain->post('name'), $plain->post('title'), $plain->post('desc'), $plain->post('sort') + 0, $plain->post('type')));
				break;
			case 'item_create':
				lib()->load('manage_item');
				$mi = new \ULib\ManageItem();
				echo json_encode($mi->create($plain->post('name'), $plain->post('title'), $plain->post('desc'), $plain->post('sort'), $plain->post('project_id') + 0));
				break;
			case 'item_edit':
				lib()->load('manage_item');
				$mi = new \ULib\ManageItem();
				echo json_encode($mi->edit($plain->post('id') + 0, $plain->post('name'), $plain->post('title'), $plain->post('desc'), $plain->post('sort')));
				break;
			case 'page_info':
				lib()->load('page');
				$page = new \ULib\Page($plain->get('id') + 0);
				echo json_encode($page->out_data());
				break;
			case 'project_list':
				lib()->load('manage_project');
				$mp = new \ULib\ManageProject();
				echo json_encode($mp->get_list_of_page($plain->get('page') + 0, $plain->get('number') + 0));
				break;
			case 'item_list':
				lib()->load('manage_item');
				$mi = new \ULib\ManageItem();
				$data = $mi->get_list($plain->get('id') + 0);
				echo json_encode(array(
					'status' => (is_array($data) && count($data) > 0),
					'data' => $data
				));
				break;
			case 'item_delete':
				lib()->load('manage_item');
				$mi = new \ULib\ManageItem();
				echo json_encode($mi->delete($plain->post('id') + 0));
				break;
			case 'project_delete':
				lib()->load('manage_project');
				$mp = new \ULib\ManageProject();
				echo json_encode($mp->delete($plain->post('id') + 0));
				break;
			case 'page_edit':
				lib()->load('page');
				$page = new \ULib\Page($plain->get('id') + 0);
				$page->set_data();
				echo json_encode($page->up_data());
				break;
			case 'setting_get':
				$s = setting_lib();
				echo json_encode($s->get_list($plain->post('page'), $plain->post('number')));
				break;
			case 'setting_edit':
				$s = setting_lib();
				echo json_encode($s->edit_sql($plain->post('name'), req()->post('value')));
				break;
			case 'user_password':
				echo json_encode(user()->change_password($plain->post('old_password'), $plain->post('new_password'), $plain->post('confirm_password')));
				break;
			case 'user_token_update':
				echo json_encode(user()->update_token($plain->post('confirm') == 'OK'));
				break;
			case 'user_new_user_info':
				echo json_encode(user()->new_user_info($plain->post('confirm') == 'OK'));
				break;
			default:
				echo json_encode(array(
					'status' => false,
					'error' => '未知请求',
					'err' => '未知请求',
					'message' => '未知请求',
					'msg' => '未知请求',
				));
		}
	}
}