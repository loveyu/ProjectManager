<?php
namespace ULib;

/**
 * 用户操作类
 */
class User{
	/**
	 * @var int 状态码
	 */
	private $code = 0;

	/**
	 * @var \CLib\Cookie COOKIE存放类
	 */
	private $ck;

	/**
	 * @var null 用户ID
	 */
	private $u_id = null;
	/**
	 * @var null 用户名
	 */
	private $u_name = null;
	/**
	 * @var null 用户加密盐
	 */
	private $u_salt = null;
	/**
	 * @var null 用户加密后的密码
	 */
	private $u_password = null;

	/**
	 * 初始化，并设置是否启用自动登录
	 * @param bool $auto_login
	 */
	function __construct($auto_login = false){
		$this->ck = new \CLib\Cookie(cfg()->get('cookie', 'encode'));
		if($auto_login){
			$this->auto_login();
		}
	}

	/**
	 * 自动登录，返回是否登录成功，并设置状态码
	 * @return bool
	 */
	private function auto_login(){
		$s = explode("\t", trim(req()->cookie('uf')));
		if(count($s) == 2 && (list($user, $token) = $s) && !empty($user) && !empty($token)){
			if(db()->has("user", array(
				'AND' => array(
					'user' => $user,
					'token' => $token
				)
			))
			){
				$this->code = 1;
				$this->set_user_info($user);
				return true;
			}
		}
		//cookie登陆失败
		$this->code = 21;
		return false;
	}


	/**
	 * 设置用户信息，根据当前登录情况设置用户
	 * @param $data 用户名
	 */
	public function set_user_info($data){
		if(!is_array($data)){
			$data = db()->get("user", array(
				'id',
				'user',
				'salt',
				'password'
			), array('user' => $data));
		}
		$this->u_id = $data['id'];
		$this->u_name = $data['user'];
		$this->u_salt = $data['salt'];
		$this->u_password = $data['password'];
	}

	/**
	 * 用户登录，并设置状态码
	 * @param string $user
	 * @param string $password
	 * @param bool   $remember
	 */
	public function login($user, $password, $remember = false){
		$this->code = 0;
		$s = db()->get("user", array(
			'id',
			'user',
			"salt",
			"password",
			'token'
		), array('user' => trim($user)));
		if(empty($s)){
			//用户不存在
			$this->code = 11;
		} else{
			if(salt_hash(_hash(trim($password)), $s['salt']) != $s['password']){
				//密码错误
				$this->code = 12;
			}
		}
		if($remember){
			ck()->set("uf", "$user\t" . $s['token'], time() + 60 * 60 * 24 * 7);
		} else{
			ck()->set("uf", "$user\t" . $s['token']);
		}
		$this->set_user_info($s);
	}

	/**
	 * 修改用户密码
	 * @param string $old
	 * @param string $new
	 * @param string $confirm
	 * @return array 修改状态信息
	 */
	public function change_password($old, $new, $confirm){
		$rt = array(
			'status' => false,
			'error' => ''
		);
		if($new !== $confirm){
			$rt['error'] = "两次旧密码不一致";
		} else if($new == $old){
			$rt['error'] = "新旧密码必须不一致";
		} else if(strlen($new) < 6 || strlen($old) < 6){
			$rt['error'] = "新旧密码长度至少为6";
		} else if(!$this->check_password($old)){
			$rt['error'] = "原始密码错误";
		} else if($this->update_password($new)){
			$rt['status'] = true;
		} else{
			$rt['error'] = "未知错误";
		}
		return $rt;
	}

	/**
	 * 更新用户COOKIE登录信息，操作导致重新登录
	 *
	 * @param bool $confirm 确认信息
	 * @return array
	 */
	public function update_token($confirm = false){
		$rt = array(
			'status' => false,
			'error' => '必须传递确认参数'
		);
		if($confirm){
			if(db()->update("user", array('token' => _hash($this->u_password . salt(50))), array('id' => $this->u_id))
			){
				$rt['status'] = true;
				$rt['error'] = "";
			} else{
				$rt["error"] = "更新失败，请尝试手动更新数据库";
			}
		}
		return $rt;
	}

	/**
	 * 生成新的用户信息
	 * @param bool $confirm
	 * @return array
	 */
	public function new_user_info($confirm = false){
		$rt = array(
			'status' => false,
			'error' => '必须传递确认参数',
			'data' => array()
		);
		if($confirm){
			$rt['data']['plain'] = salt(12);
			$rt['data']['salt'] = salt(40);
			$rt['data']['password'] = salt_hash(_hash($rt['data']['plain']), $rt['data']['salt']);
			$rt['data']['token'] = _hash($rt['data']['password'] . salt(50));
			$rt['status'] = true;
			$rt['error'] = "";
		}
		return $rt;
	}

	/**
	 * 更新用户密码
	 * @param $n_pwd
	 * @return bool
	 */
	private function update_password($n_pwd){
		$salt = salt(40);
		$password = salt_hash(_hash($n_pwd), $salt);
		return db()->update("user", array(
			'password' => $password,
			'salt' => $salt,
			'token' => _hash($password . salt(50))
		), array('id' => $this->u_id)) > 0;
	}


	/**
	 * 检测密码是否正确，前提为用户已登录，且用户信息被设置
	 * @param $pwd
	 * @return bool
	 */
	private function check_password($pwd){
		return salt_hash(_hash(trim($pwd)), $this->u_salt) == $this->u_password;
	}

	/**
	 * 用户登录是否成功
	 * @return bool
	 */
	public function login_status(){
		return $this->code < 10;
	}

	/**
	 * 登录代码
	 * @return int
	 */
	public function login_code(){
		return $this->code;
	}

	/**
	 * 登录后的跳转地址
	 * @return array|string
	 */
	public function login_go(){
		if($this->login_status()){
			return 'Admin';
		} else{
			return array(
				'Home',
				'login'
			);
		}
	}

	/**
	 *
	 */
	public function logout(){
		$this->ck->del('uf');
	}

	/**
	 * @param $user
	 * @param $password
	 */
	public function create_user($user, $password){
		$salt = salt(40);
		$password = salt_hash(_hash($password), $salt);
		var_dump(db()->insert("user", array(
			'user' => $user,
			'password' => $password,
			'salt' => $salt,
			'token' => _hash($password . salt(50))
		)));
		var_dump(db()->error());
	}


}
