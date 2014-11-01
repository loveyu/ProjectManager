<?php
hook()->add('admin_plugin_menu', 'out_hook_menu');
hook()->add('admin_plugin_content', 'out_hook_content');
hook()->add('pm_footer', 'out_hook_footer');
hook()->add('pm_header', 'out_hook_header');

if(!is_array(($cfg = cfg()->get('s_g', 'out_hook')))){
	if($cfg){
		cfg()->set(array(
			's_g',
			'out_hook'
		), json_decode($cfg, true));
	}
}

function out_hook_menu($menu){
	array_push($menu, array(
		'name' => '输出钩子设置',
		'router' => array(
			'Admin',
			'plugin',
			'out_hook'
		),
		'hidden' => false
	));
	return $menu;
}

function out_hook_content($name){
	if($name == 'out_hook'){
		if(!req()->is_post() || out_hook_post()){
			include(__DIR__ . "/page.php");
		}
	}
	return $name;
}

function out_hook_footer(){
	$cfg = cfg();
	echo $cfg->get('s_g','out_hook','common_foot');
	if(is_login()){
		echo $cfg->get('s_g','out_hook','login_foot');
	}else{
		echo $cfg->get('s_g','out_hook','gust_foot');
	}
}

function out_hook_header(){
	$cfg = cfg();
	echo $cfg->get('s_g','out_hook','common_head');
	if(is_login()){
		echo $cfg->get('s_g','out_hook','login_head');
	}else{
		echo $cfg->get('s_g','out_hook','gust_head');
	}
}

function out_hook_post(){
	$req = req();
	$data = array(
		'common_head' => $req->post('common_head'),
		'common_foot' => $req->post('common_foot'),
		'login_head' => $req->post('login_head'),
		'login_foot' => $req->post('login_foot'),
		'gust_head' => $req->post('gust_head'),
		'gust_foot' => $req->post('gust_foot'),
	);
	/**
	 * @var $setting \ULib\Setting
	 */
	$setting = lib()->using('setting');
	$rt = $setting->edit_sql('out_hook', json_encode($data));
	if($rt['status'] == false){
		if(!$setting->add('out_hook', json_encode($data), true)){
			echo "<h4 class='text-danger'>更新数据失败，无法创建设置选项</h4>";
			return false;
		}
	}
	echo "<p class='well well-sm text-success'>更新成功！</p>";
	cfg()->set(array(
		's_g',
		'out_hook'
	), $data);
	return true;
}