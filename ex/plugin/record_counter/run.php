<?php
/**
 * User: loveyu
 * Date: 2014/12/5
 * Time: 16:27
 */
if(is_admin_page()){
	include_once __DIR__ . "/RecordCounter.php";
	$counter = new RecordCounter();
	hook()->add('admin_plugin_menu', [
			$counter,
			'out_menu'
		]);
	hook()->add('admin_plugin_content', [
			$counter,
			'out_content'
		]);
}