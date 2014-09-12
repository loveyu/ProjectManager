<?php
namespace UView;
class Page extends \Core\Page{

	/**
	 * 构造函数，生成页面网址参数
	 */
	public function __construct(){
		theme()->header_add("<script>var SITE_URL = '" . URL_WEB . "';</script>", 0);
	}

	/**
	 * 项目页面
	 * @param $project
	 * @param $item
	 */
	public function project($project, $item){
		if(\ULib\Router::$begin_status){
			lib()->load('project', 'menu')->add("project", new \ULib\Project($project, $item));
			lib()->add('menu', new \ULib\Menu());
			$pj = project();
			if($pj->is_top()){
				set_title($pj->title());
			} else{
				set_title($pj->title() . " - " . $pj->project_title());
			}
			theme()->set_desc($pj->desc());
			theme()->header_add("<script>var PM_PAGE_ID = " . $pj->id() . ";</script>", 40);
			theme()->set_keywords($pj->keywords());
			header("Content-Type:text/html; charset=utf-8");
			$this->__view('comm/header.php');
			switch($pj->type()){
				case 'page':
					$this->__view('project/page.php');
					break;
				case 'home':
					$this->__view('project/home.php');
					break;
				case 'picture':
					$this->__view('project/picture.php');
					break;
				case 'about':
					$this->__view('project/about.php');
					break;
				case 'document':
					$this->__view('project/document.php');
					break;
				case 'download':
					$this->__view('project/download.php');
					break;
				case 'change':
					$this->__view('project/change.php');
					break;
				case 'help':
					$this->__view('project/help.php');
					break;
				default:
					$this->__view('project/page.php');
			}
			$this->__view('comm/footer.php');
		} else{
			$this->__load_404();
		}
	}

	/**
	 * 单独页面
	 * @param $page
	 */
	public function page($page){
		if(\ULib\Router::$begin_status){
			lib()->load('project', 'menu')->add("project", new \ULib\Project($page, 0));
			lib()->add('menu', new \ULib\Menu(true));
			set_title(project()->title(), site_title(false));
			theme()->header_add("<script>var PM_PAGE_ID = " . project()->id() . ";</script>", 40);
			theme()->set_desc(project()->desc());
			theme()->set_keywords(project()->keywords());
			header("Content-Type:text/html; charset=utf-8");
			$this->__view('comm/header.php');
			$this->__view('project/page.php');
			$this->__view('comm/footer.php');
		} else{
			$this->__load_404();
		}
	}
}

?>