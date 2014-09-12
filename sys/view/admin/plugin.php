<h3>插件设置</h3>
<ul class="nav nav-pills panel">
	<?php admin_plugin_menu();?>
</ul>
<?php
hook()->apply('admin_plugin_content', $__param);
?>
