<?php
/**
 * 主题函数
 */

/**
 * 获取网站名称
 * @param bool $out
 * @return string
 */
function site_name($out = true){
	if($out){
		echo cfg()->get('s_g', 'site_name');
	} else{
		return cfg()->get('s_g', 'site_name');
	}
}

/**
 * 获取网站标题
 * @param bool $out
 * @return string
 */
function site_title($out = true){
	if($out){
		echo cfg()->get('s_g', 'site_title');
	} else{
		return cfg()->get('s_g', 'site_title');
	}
}

/**
 * 网站描述
 * @param bool $out
 * @return array|bool|null|object|string
 */
function site_desc($out = true){
	if($out){
		echo cfg()->get('s_g', 'site_desc');
	} else{
		return cfg()->get('s_g', 'site_desc');
	}
}

/**
 * 网站关键字
 * @return string
 */
function site_keywords(){
	return cfg()->get('s_g', 'keywords') . "";
}

/**
 * 设置标题
 * @param null $title
 * @param null $desc
 */
function set_title($title = null, $desc = null){
	if($title !== null)
		cfg()->set(array(
			'theme',
			'title'
		), $title);
	if($desc !== null)
		cfg()->set(array(
			'theme',
			'desc'
		), $desc);
}


/**
 * 输出标题
 * @param bool $out
 * @return mixed
 */
function pm_title($out = true){
	$title = hook()->apply('pm_title_title', cfg()->get('theme', 'title'));
	$desc = hook()->apply('pm_title_title', cfg()->get('theme', 'desc'));

	if($title === null){
		$title = site_title(false);
	}
	if($desc === null){
		$desc = site_desc(false);
	}
	if(empty($desc)){
		$out = hook()->apply('pm_title', $title);
	} else{
		$out = hook()->apply('pm_title', $title . " | " . $desc);
	}
	if($out)
		echo $out;
	return $out;
}

/**
 * 主题头回调函数
 */
function pm_header(){
	hook()->apply('pm_header', null);
}

/**
 * 主题尾回调函数
 */
function pm_footer(){
	hook()->apply('pm_footer', null);
}

/**
 * 返回主题类
 * @return \ULib\Theme
 */
function theme(){
	return lib()->using('theme');
}

/**
 * 判断是否已登录
 * @return bool
 */
function is_login(){
	return user()->login_status();
}

/**
 * 判断是否为管理员页面
 * @return bool
 */
function is_admin_page(){
	$u = u()->getUriInfo()->getUrlList();
	return isset($u[0]) && $u[0] == 'Admin';
}


/**
 * 返回是否为首页
 * @return bool
 */
function is_home(){
	return count(u()->getUriInfo()->getUrlList()) == 0;
}

/**
 * 返回是否为404页面
 * @return bool
 */
function is_404(){
	return cfg()->get('system', 'is_404') == true;
}

//自己初始化主题类并存入库管理
lib()->load('theme')->add('theme', new \ULib\Theme());