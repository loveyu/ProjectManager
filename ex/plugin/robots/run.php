<?php
hook()->add('admin_plugin_menu', 'robots_menu');
hook()->add('admin_plugin_content', 'robots_content');

function robots_menu($menu){
	array_push($menu, array(
		'name' => 'Robots设置',
		'router' => array(
			'Admin',
			'plugin',
			'robots'
		),
		'hidden' => false
	));
	return $menu;
}

function robots_content($name){
	if($name == 'robots'){
		if(req()->is_post()){
			file_put_contents(_BasePath_ . "/robots.txt", req()->_plain()->post('robots'));
		}
		include(__DIR__ . "/page.php");
	}
	return $name;
}