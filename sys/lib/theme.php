<?php
namespace ULib;
/**
 * 主题操作类
 */
class Theme{
	/**
	 * @var array 头部信息列表
	 */
	private $header_list = array();
	/**
	 * @var array 底部信息列表
	 */
	private $footer_list = array();

	/**
	 * 初始化钩子,并在文件头中加入是否登录的js变量
	 */
	function __construct(){
		hook()->add('pm_header', array(
			$this,
			'header_hook'
		));
		hook()->add('pm_footer', array(
			$this,
			'footer_hook'
		));
		$this->header_add("<script>var PM_USER_LOGIN_STATUS = " . (is_login() ? "true" : "false") . ";</script>", 40);
	}

	/**
	 * 生成一个js引入连接
	 *
	 * @param array $list 传入名称列表
	 * @return string
	 */
	function js($list){
		if(!isset($list['type'])){
			$list['type'] = 'text/javascript';
		}
		$d = "";
		foreach($list as $n => $v){
			$d .= " " . $n . '="' . $v . '"';
		}
		return "<script$d></script>";
	}

	/**
	 * 生成css引入连接
	 *
	 * @param array $list 传入名称列表
	 * @return string
	 */
	function css($list){
		if(!isset($list['rel'])){
			$list['rel'] = 'stylesheet';
		}
		return $this->link($list);
	}

	/**
	 * 生成引入连接
	 * @param array $list
	 * @return string
	 */
	function link($list){
		$d = "";
		foreach($list as $n => $v){
			$d .= " " . $n . '="' . $v . '"';
		}
		return "<link$d />";
	}

	/**
	 * 生成标签
	 * @param array $list
	 * @return string
	 */
	function meta($list){
		$d = "";
		foreach($list as $n => $v){
			$d .= " " . $n . '="' . $v . '"';
		}
		return "<meta$d />";
	}

	/**
	 * 添加自定义内容到头部
	 * @param string $content
	 * @param int    $pr 排名，先后顺序
	 */
	public function header_add($content, $pr = 50){
		if(!isset($this->header_list[$pr])){
			$this->header_list[$pr] = array();
		}
		$this->header_list[$pr][] = $content;
	}

	/**
	 * 添加自定义内容到底部
	 * @param string $content
	 * @param int    $pr 排名，先后顺序
	 */
	public function footer_add($content, $pr = 50){
		if(!isset($this->footer_list[$pr])){
			$this->footer_list[$pr] = array();
		}
		$this->footer_list[$pr][] = $content;
	}

	/**
	 * 输出头部内容
	 * @param bool  $echo 是否直接输出
	 * @param array $con  连接字符，[0]之前，[1]之后
	 * @return string 返回数据
	 */
	public function header_out($echo = true, $con = array(
		"",
		"\n"
	)){
		$data = '';
		$keys = array_keys($this->header_list);
		sort($keys);
		foreach($keys as $key){
			foreach($this->header_list[$key] as $v){
				$data .= $con[0] . $v . $con[1];
			}
		}
		if($echo)
			echo $data;
		return $data;
	}

	/**
	 * 输出底部内容
	 * @param bool  $echo 是否直接输出
	 * @param array $con  连接字符，[0]之前，[1]之后
	 * @return string 返回数据
	 */
	public function footer_out($echo = true, $con = array(
		"",
		"\n"
	)){
		$data = '';
		$keys = array_keys($this->footer_list);
		sort($keys);
		foreach($keys as $key){
			foreach($this->footer_list[$key] as $v){
				$data .= $con[0] . $v . $con[1];
			}
		}
		if($echo)
			echo $data;
		return $data;
	}

	/**
	 * 设置页面描述
	 * @param $desc
	 */
	public function set_desc($desc){
		$desc = trim($desc);
		if(empty($desc))
			return;
		$this->header_add($this->meta(array(
			'name' => 'Description',
			'content' => $desc
		)));
	}

	/**
	 * 设置页面关键字
	 * @param string|array $keywords
	 */
	public function set_keywords($keywords){
		$keywords = trim($keywords);
		if(is_array($keywords)){
			$keywords = implode(", ", $keywords);
		}
		if(empty($keywords))
			return;
		$this->header_add($this->meta(array(
			'name' => 'Keywords',
			'content' => $keywords
		)));
	}

	/**
	 * 头部运行钩子
	 */
	public function header_hook(){
		$this->header_out();
	}

	/**
	 * 底部运行钩子
	 */
	public function footer_hook(){
		$this->footer_out();

	}
}