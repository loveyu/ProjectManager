<?php if(!defined('_BasePath_')){
	exit;
}
?>
<div>
	<form action="" method="post">
		<fieldset class="well">
			<legend>无登录区分</legend>
			<label for="oh_c_h" class="control-label">顶部</label>
			<textarea id="oh_c_h" name="common_head"
					  class="form-control"><?php echo htmlentities(cfg()->get('s_g', 'out_hook', 'common_head')) ?></textarea>
			<label for="oh_c_f">底部</label>
			<textarea id="oh_c_f" name="common_foot"
					  class="form-control"><?php echo htmlentities(cfg()->get('s_g', 'out_hook', 'common_foot')) ?></textarea>
			<button type="submit" class="btn btn-warning">更新</button>
		</fieldset>
		<fieldset class="well">
			<legend>登录状态下</legend>
			<label for="oh_l_h" class="control-label">顶部</label>
			<textarea id="oh_l_h" name="login_head"
					  class="form-control"><?php echo htmlentities(cfg()->get('s_g', 'out_hook', 'login_head')) ?></textarea>
			<label for="oh_l_f">底部</label>
			<textarea id="oh_l_f" name="login_foot"
					  class="form-control"><?php echo htmlentities(cfg()->get('s_g', 'out_hook', 'login_foot')) ?></textarea>
			<button type="submit" class="btn btn-warning">更新</button>
		</fieldset>
		<fieldset class="well">
			<legend>未登录状态</legend>
			<label for="oh_g_h" class="control-label">顶部</label>
			<textarea id="oh_g_h" name="gust_head"
					  class="form-control"><?php echo htmlentities(cfg()->get('s_g', 'out_hook', 'gust_head')) ?></textarea>
			<label for="oh_g_f">底部</label>
			<textarea id="oh_g_f" name="gust_foot"
					  class="form-control"><?php echo htmlentities(cfg()->get('s_g', 'out_hook', 'gust_foot')) ?></textarea>
			<button type="submit" class="btn btn-warning">更新</button>
		</fieldset>
	</form>
</div>