<?php
if(!is_admin_page()){
	hook()->add('project_content', 'picture_post_edit');
	hook()->add('pm_header', 'picture_header');
	hook()->add('pm_footer', 'picture_footer');
}
function picture_header(){
	if(project() && project()->type() == 'picture'){
		echo theme()->js(array('src' => get_file_url('ex/plugin/picture/jquery.colorbox-min.js'))), "\n";
		echo theme()->css(array('href' => get_file_url('ex/plugin/picture/colorbox.css'))), "\n";
	}
}

function picture_footer(){
	if(project() && project()->type() == 'picture'){
		echo '<script type="text/javascript">
jQuery(document).ready(function($){
  $("#PMP-picture img").parent("a").colorbox({rel:"group", speed:350,width:"85%",initialWidth:"300",initialHeight:"100",
  opacity:0.8,loop:false,scrolling:false,escKey:false,arrowKey:false,top:false,right:false,bottom:false,left:false});
});
</script>';
	}
}

function picture_post_edit($content, $type){
	return $content;
}

?>