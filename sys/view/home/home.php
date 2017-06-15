<div id="home">
	<h1><?php site_title() ?></h1>

	<div class="row content">
		<div class="col-md-3">
			<div class="avatar">
				<a href="http://www.loveyu.org/">
					<img title="恋羽" class="img-circle" src="<?php echo get_file_url("images/yongqi.jpg?v1") ?>" alt="勇气"/>
				</a>
			</div>
			<!--<img src="--><?php //echo get_file_url("images/alipay.png?v1") ?><!--" class="img-responsive"/>-->
		</div>
		<div class="col-md-9 project_list">
			<?php
			foreach(m_project()->get_list() as $v){
				echo '<dl><dt><a href="', get_url($v['name']), '">', $v['title'], '</a></dt><dd>', $v['desc'], "</dd></dl>\n";
			}
			?>
		</div>
	</div>
</div>