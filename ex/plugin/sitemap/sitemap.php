<?php
namespace Plugin;
class SiteMap{
	private $out = false;

	private $content = "";

	private $count_number = 0;

	public function __construct($out_message){
		$this->out = $out_message;
	}

	public function msg($text, $type = 'primary'){
		if($this->out){
			echo "<p class='text-$type'>$text</p>";
		}
	}

	private function add_header(){
		$this->count_number = 0;
		$this->content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<?xml-stylesheet type="text/xsl" href="' . get_file_url('ex/plugin/sitemap/sitemap.xsl') . '"?>' . "\n" . '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . ' http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\r\n";
	}

	private function add_footer(){
		$this->content .= '</urlset>';
	}

	private function add($loc, $lastmod, $changefreq, $priority, $title){
		if(!empty($lastmod)){
			$lastmod = "<lastmod>$lastmod</lastmod>";
		}
		if(!empty($changefreq)){
			$changefreq = "<changefreq>$changefreq</changefreq>";
		}
		if(!empty($priority)){
			$priority = "<priority>$priority</priority>";
		}
		$this->content .= "\t<url><loc>$loc</loc>$lastmod$changefreq$priority</url>\n";
		++$this->count_number;
	}

	private function date_convert($datetime){
		return gmdate(DATE_ATOM, strtotime($datetime));
		;
	}

	public function create(){
		$this->add_header();
		$this->add(get_url(), gmdate(DATE_ATOM, time()), 'daily', '1.0', site_title(false) . " | " . site_desc(false));
		$page = db()->select("project", array(
			'[><]page' => array(
				'page_id' => 'id'
			)
		), array(
			'project.id' => 'id',
			'project.name' => 'name',
			'project.title' => 'title',
			'project.desc' => 'desc',
			'page.uptime' => 'time'
		), array(
			'project.type' => 'page',
			'ORDER' => 'sort'
		));

		foreach($page as $v){
			$this->add(get_url($v['name']), $this->date_convert($v['time']), 'weekly', '0.8', $v['title'] . " - " . site_title(false));
		}
		unset($page);

		$project = db()->select("project", array(
			'[><]page' => array(
				'page_id' => 'id'
			)
		), array(
			'project.id' => 'id',
			'project.name' => 'name',
			'project.title' => 'title',
			'project.desc' => 'desc',
			'page.uptime' => 'time'
		), array(
			'project.type' => 'project',
			'ORDER' => 'sort'
		));
		foreach($project as $v){
			$this->add(get_url($v['name']), $this->date_convert($v['time']), 'weekly', '0.7', $v['title'] . " - " . site_title(false));
			foreach(db()->select("item", array(
				'[><]page' => array(
					'page_id' => 'id'
				)
			), array(
				'item.name' => 'name',
				'item.title' => 'title',
				'item.desc' => 'desc',
				'page.uptime' => 'time'
			), array('item.project_id' => $v['id'])) as $v2){
				$this->add(get_url($v['name'], $v2['name']), $this->date_convert($v2['time']), 'monthly', '0.5', $v2['title'] . " - " . $v['title']);
			}
		}
		$this->add_footer();

	}

	public function count(){
		return $this->count_number;
	}

	public function  write_file($path){
		@file_put_contents($path, $this->content);
	}

}