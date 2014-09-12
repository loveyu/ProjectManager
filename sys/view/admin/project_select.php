<div id="item_create_select">
	<h3>选择项目</h3>

	<div class="list">
		<?php
		foreach($__list as $v){
			echo "<a class=\"label label-info\" href=\"", get_url(array(
				'Admin',
				$__mode,
				$v['id']
			)), "\">", $v['title'], "(", $v['name'], ")</a> \n";
		}
		?>
	</div>
</div>