<?php
hook()->add('admin_plugin_menu', 'sitemap_menu');
hook()->add('admin_plugin_content', 'sitemap_content');

/**
 * 插件内容设置
 * @param $name 对应标记参数
 */
function sitemap_content($name){
	if($name == 'sitemap'){
		if(req()->is_post()){
			sitemap_create(true);
		}
		include(__DIR__ . "/page.php");
	}
	return $name;
}

/**
 * 添加插件菜单
 * @param $menu
 * @return mixed
 */
function sitemap_menu($menu){
	array_push($menu, array(
		'name' => '网站地图设置',
		'router' => array(
			'Admin',
			'plugin',
			'sitemap'
		),
		'hidden' => false
	));
	return $menu;
}

/**
 * 返回可读的大小
 * @param int $size 整型的文件大小
 * @param int $save 保留的整数位
 * @return string
 */
function sitemap_size($size, $save = 2){
	$a = array(
		"B",
		"KB",
		"MB",
		"GB",
		"TB",
		"PB",
		"EB",
		"ZB",
		"YB"
	);
	$pos = 0;
	if($size < 0)
		return "0";
	while($size > 1024){
		$size /= 1024;
		$pos++;
	}
	return round($size, $save) . $a[$pos];
}

/**
 * 生成网站地图信息
 */
function sitemap_create($out_message = false){
	require_once(__DIR__ . "/sitemap.php");
	$sitemap = new \Plugin\SiteMap($out_message);
	$sitemap->msg("准备生成网站地图，最大10M，最多5000条<br />当前内存消耗：<span class='text-danger'>" . sitemap_size(memory_get_usage()) . "</span>");
	$sitemap->create();
	$file = _BasePath_ . "/sitemap.xml";
	$sitemap->write_file($file);
	$sitemap->msg("生成文件大小: <span class='text-danger'>" . sitemap_size(filesize($file))) . "</span>";
	$sitemap->msg("生成记录: <span class='text-danger'>" . $sitemap->count() . "</span> 条");
	$sitemap->msg("地图地址: <a rel='external' class='text-warning' href='" . get_file_url('sitemap.xml') . "'>" . get_file_url('sitemap.xml') . "</a>");
	$sitemap->msg("生成地图成功<br />当前内存消耗：<span class='text-danger'>" . sitemap_size(memory_get_usage()) . "</span>");
}