<?php
namespace UView;
class Error extends \Core\Page{

	/**
	 * 数据库错误页面
	 */
	public function sql(){
		if(db()->status()){
			$this->page_404();
		} else{
			echo "SQL Error:", db()->ex_message();
		}
	}

	/**
	 * 404错误页面
	 */
	public function page_404(){
		lib()->load('menu')->add('menu', new \ULib\Menu());
		header("HTTP/1.1 404 Not Found");
		header("Content-Type:text/html; charset=utf-8");
		$this->__view('comm/header.php');
		$this->__view('home/404.html');
		$this->__view('comm/footer.php');
	}

}