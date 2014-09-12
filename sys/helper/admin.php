<?php
/**
 * 增加后台编辑器所需的引用文件
 */
function page_edit_header_out(){
	theme()->header_add(theme()->css(array(
		'href' => get_file_url("ex/kindeditor/themes/default/default.css")
	)));
	theme()->header_add(theme()->js(array(
		'src' => get_file_url("ex/kindeditor/kindeditor-min.js")
	)));
	theme()->header_add(theme()->js(array(
		'src' => get_file_url("ex/kindeditor/lang/zh_CN.js")
	)));
	theme()->header_add(theme()->js(array(
		'src' => get_file_url("js/jquery.form.js")
	)));
}

/**
 * 输出后台菜单
 */
function admin_menu(){
	$list = array(
		array(
			'name' => '创建项目',
			'router' => array(
				'Admin',
				'project_create'
			),
			'hidden' => false
		),
		array(
			'name' => '创建子页面',
			'router' => array(
				'Admin',
				'item_create'
			),
			'hidden' => false
		),
		array(
			'name' => '编辑页面',
			'router' => array(
				'Admin',
				'page_edit'
			),
			'hidden' => false
		),
		array(
			'name' => '编辑主项目',
			'router' => array(
				'Admin',
				'project_edit'
			),
			'hidden' => true
		),
		array(
			'name' => '编辑子项目',
			'router' => array(
				'Admin',
				'item_edit'
			),
			'hidden' => true
		),
		array(
			'name' => '网站设置',
			'router' => array(
				'Admin',
				'setting'
			),
			'hidden' => true
		),
		array(
			'name' => '用户设置',
			'router' => array(
				'Admin',
				'user'
			),
			'hidden' => false
		),
		array(
			'name' => '插件设置',
			'router' => array(
				'Admin',
				'plugin'
			),
			'hidden' => false
		),
	);
	echo simple_menu($list);
}

/**
 * 插件菜单
 */
function admin_plugin_menu(){
	echo simple_menu(hook()->apply('admin_plugin_menu', array()));
}

/**
 * 插件内容
 */
function admin_plugin_content($name){
	return hook()->apply('admin_plugin_content',$name);
}

/**
 * 输出后台顶部菜单
 */
function admin_top_menu(){
	echo simple_menu(array(
		array(
			'name' => '创建项目',
			'router' => array(
				'Admin',
				'project_create'
			),
			'hidden' => false
		),
		array(
			'name' => '网站设置',
			'router' => array(
				'Admin',
				'setting'
			),
			'hidden' => false
		),
		array(
			'name' => '返回首页',
			'router' => array(),
			'hidden' => false
		),
	));
}

/**
 * 对菜单列表进行处理，并返回
 * @param  array $list 菜单列表
 * @return string
 */
function simple_menu($list){
	$uri = u()->getUriInfo()->getUrlList();
	$rt = "";
	foreach($list as $v){
		$flag = " class=\"active\"";
		if(empty($v['router'])){
			$flag = "";
		} else{
			foreach(array_keys($v['router']) as $i){
				if(!isset($uri[$i]) || $uri[$i] != $v['router'][$i]){
					$flag = "";
					break;
				}
			}
		}
		if(!isset($v['hidden']) || $v['hidden'] == false || !empty($flag)){
			$rt .= "<li$flag><a href='" . get_url($v['router']) . "'>" . $v['name'] . "</a></li>\n";
		}
	}
	return $rt;
}