<!--底部开始-->
</div>
<footer class="pm_footer" role="contentinfo">
	<div class="container">
		<p>&copy;<a href="http://www.loveyu.org/">恋羽日记</a> 2013 - <?php echo date("Y") ?>.
			<a href="<?php echo get_url(array(
				"Home",
				!is_login() ? "login" : "logout"
			));?>"><?php echo !is_login() ? "登录" : "登出"; ?></a>&nbsp;<?php if(is_login()):?>
			<a href="<?php echo get_url('Admin')?>">控制面板</a>&nbsp;
			<?php endif;?>
			<a href="<?php echo get_file_url('sitemap.xml') ?>" rel="external">网站地图</a>
		</p>
	</div>
	<?php pm_footer(); ?>
	<?php if(is_login()): ?>
		<script>console.log(<?php echo json_encode("页面加载 ".c()->getTimer()->get_second()." 秒， 查询 ".db()->get_query_count()." 次")?>)</script>
	<?php endif; ?>
	<script src="<?php echo get_file_url('js/loveyu.js') ?>" type="text/javascript"></script>
</footer>
</body>
</html>