<?php
namespace CLib;

/**
 * Cookie操作类
 */
class Cookie{
	/**
	 * 设置是否加密COOKIE
	 *
	 * @var bool
	 */
	private $encode;

	/**
	 * 构造
	 * @param bool $encode
	 */
	public function __construct($encode = false){
		$this->encode = $encode;
		if($encode){
			if(!class_exists('Safe')){
				c_lib()->load('safe');
			}
			hook()->add('Request_cookie', array(
				$this,
				'de'
			));
			hook()->add('Cookie_set', array(
				$this,
				'en'
			));
		}
	}

	/**
	 * 通过Cookie_Key加密数据或数组
	 *
	 * @param $data
	 * @return array|string
	 */
	public function en($data){
		if($data === null)
			return $data;
		return is_array($data) ? array_map(array(
			$this,
			'de'
		), $data) : Safe::encrypt($data, COOKIE_KEY);
	}

	/**
	 * 通过Cookie_Key解密数据或数组
	 *
	 * @param $data
	 * @return array|string
	 */
	public function de($data){
		if($data === null)
			return $data;
		return is_array($data) ? array_map(array(
			$this,
			'de'
		), $data) : Safe::decrypt($data, COOKIE_KEY);
	}

	/**
	 * 设置COOKIE的值
	 *
	 * @param        $name
	 * @param        $value
	 * @param int    $expire
	 * @param string $path
	 * @param string $domain
	 * @param bool   $secure
	 * @param bool   $httponly
	 */
	public function set($name, $value, $expire = 0, $path = '', $domain = '', $secure = false, $httponly = true){
		setcookie(COOKIE_PREFIX . $name, hook()->apply('Cookie_set', $value), $expire, $this->path($path), $this->domain($domain), $secure, $httponly);
	}

	/**
	 * 删除COOKIE的值
	 *
	 * @param        $name
	 * @param string $path
	 * @param string $domain
	 */
	public function del($name, $path = '', $domain = ''){
		setcookie(COOKIE_PREFIX . $name, "", 0, $this->path($path), $this->domain($domain), '');
	}

	/**
	 * 修正COOKIE设置的路径
	 *
	 * @param $path
	 * @return mixed
	 */
	private function path($path){
		if('' == $path){
			$path = URL_PATH;
		}
		return hook()->apply('Cookie_path', $path);
	}

	/**
	 * 修改域名路径
	 * @param $domain
	 * @return string
	 */
	private function domain($domain){
		if('' == $domain){
			$domain = u()->getUriInfo()->getHttpHost();
		}
		return hook()->apply('Cookie_domain', $domain);
	}

}