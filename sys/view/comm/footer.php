<!--底部开始-->
</div>
<footer class="pm_footer" role="contentinfo">
	<div class="container">
		<p>&copy;&nbsp;CopyRight&nbsp;By <a href="http://www.loveyu.org/">恋羽日记</a> 2013.
			页面加载 <?php echo c()->getTimer()->get_second()?> 秒， 数据库查询 <?php echo db()->get_query_count();?> 次。
			<a href="<?php echo get_url(array(
				"Home",
				!is_login() ? "login" : "logout"
			));?>"><?php echo !is_login() ? "登录" : "登出";?></a>&nbsp;
			<a href="<?php echo get_file_url('sitemap.xml')?>" rel="external">网站地图</a>
		</p>
	</div>
	<?php pm_footer();?>
	<script src="<?php echo get_file_url('js/loveyu.js')?>" type="text/javascript"></script>
</footer>
</body>
</html>