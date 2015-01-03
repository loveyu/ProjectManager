<?php
/**
 * HTTPS支持类
 */

/**
 * Class HttpsSupport
 */
class HttpsSupport{
	private $config;
	private $cdn = NULL;//选择后的CDN

	/**
	 * 初始化
	 */
	function __construct(){
		hook()->add('admin_plugin_menu', [
			$this,
			'out_menu'
		]);
		hook()->add('admin_plugin_content', [
			$this,
			'out_content'
		]);
	}

	/**
	 * 构造数据信息
	 */
	function  init(){
		$this->config = cfg()->get('s_g', 'https');
		if(!is_array($this->config)){
			if($this->config){
				$this->config = json_decode($this->config, true);
				cfg()->set(array(
					's_g',
					'https'
				), $this->config);
			}
		}
		if(!isset($this->config['mode']) || empty($this->config['mode'])){
			$this->config['mode'] = "share";
		}
		foreach([
			'https',
			'http',
			'path'
		] as $v){
			if(!isset($this->config[$v])){
				$this->config[$v] = "";
			}
		}
		$this->set();
	}

	/**
	 * 对相关设置进行强制操作
	 */
	private function set(){
		$is_ssl = is_ssl();
		switch($this->config['mode']){
			case "focus":
				//强制HTTPS
				if(!$is_ssl){
					redirect("https" . substr(URL_NOW, 4), "Location", 301);
				}
				break;
			case "none":
				//强制HTTP
				if($is_ssl){
					redirect("http" . substr(URL_NOW, 5), "Location", 301);
				}
				break;
		}
		$this->cdn = "";
		if($is_ssl){
			if(!empty($this->config['https'])){
				hook()->add('get_file_url', function (){
					return cfg()->get('s_g', 'https', 'https');
				});
				$this->cdn = $this->config['https'];
			}
		} else{
			if(!empty($this->config['http'])){
				hook()->add('get_file_url', function (){
					return cfg()->get('s_g', 'https', 'http');
				});
				$this->cdn = $this->config['http'];
			}
		}

		if(!empty($this->config['path']) && !empty($this->cdn)){
			hook()->add('Uri_load_begin', [
				$this,
				'uri_begin'
			]);
			hook()->add('Uri_load_end', [
				$this,
				'uri_end'
			]);
		}

	}

	/**
	 * 缓存开始
	 * @return null
	 */
	public function uri_begin(){
		ob_start();
		return NULL;
	}

	public function uri_end(){
		//缓存结束
		$content = ob_get_contents();
		ob_clean();
		$list = explode("|", $this->config['path']);
		$list = array_unique(array_map('trim', $list));
		if(!is_admin_page()){
			$host = u()->getUriInfo()->getHttpHost();
			if(is_ssl()){
				$need = "http://{$host}";
				$replace = "https://{$host}";
			} else{
				$need = "https://{$host}";
				$replace = "http://{$host}";
			}
			$content = str_replace($need, $replace, $content);
		}
		$need = [];
		$replace = [];
		foreach($list as $v){
			if(!empty($v)){
				$url = $this->cdn.($v[0]=="/"?substr($v,1):$v);
				$need[] = "src=\"{$v}";
				$need[] = "src='{$v}";
				$replace[] = "src=\"{$url}";
				$replace[] = "src='{$url}";
			}
		}
		echo str_replace($need, $replace, $content);
		return NULL;
	}


	/**
	 * 菜单输出
	 * @param $menu
	 * @return mixed
	 */
	function  out_menu($menu){
		array_push($menu, array(
			'name' => 'HTTPS 设置',
			'router' => array(
				'Admin',
				'plugin',
				'https'
			),
			'hidden' => false
		));
		return $menu;
	}

	/**
	 * 内容输出
	 * @param $name
	 * @return mixed
	 */
	function  out_content($name){
		if($name == "https"){
			if(!req()->is_post() || $this->post_data()){
				$this->page_content();
			}
		}
		return $name;
	}

	/**
	 * 页面信息输出
	 */
	private function page_content(){
		include(__DIR__ . "/page.php");

	}

	/**
	 * 数据提交
	 * @return bool
	 */
	private function  post_data(){
		$req = req()->_plain();
		$data = [
			'mode' => $req->post('mode'),
			'https' => $req->post('https'),
			'http' => $req->post('http'),
			'path' => $req->post('path')
		];
		if(!in_array($data['mode'], [
			'share',
			'focus',
			'none'
		])
		){
			echo "<h4 class='text-danger'>模式不正确</h4>";
			return false;
		}
		foreach([
			'http',
			'https'
		] as $v){
			if(!empty($data[$v])){
				if(!filter_var($data[$v], FILTER_VALIDATE_URL)){
					echo "<h4 class='text-danger'>错误的{$v}地址</h4>";
					return false;
				}
				if(substr($data[$v], -1) !== "/"){
					echo "<h4 class='text-danger'>{$v}必须以斜杠结尾</h4>";
					return false;
				}
			}
		}
		/**
		 * @var $setting \ULib\Setting
		 */
		$setting = lib()->using('setting');
		$rt = $setting->edit_sql('https', json_encode($data));
		if($rt['status'] == false){
			if(!$setting->add('https', json_encode($data), true)){
				echo "<h4 class='text-danger'>更新数据失败，无法创建设置选项</h4>";
				return false;
			}
		}
		echo "<p class='well well-sm text-success'>更新成功！</p>";
		cfg()->set(array(
			's_g',
			'https'
		), $data);
		return true;
	}

}

$https_server = new HttpsSupport();
$https_server->init();