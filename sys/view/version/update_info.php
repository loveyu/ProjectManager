<?php
/**
 * @var array $__list
 */
if(empty($__list)): ?>
	<p>无任何更新记录</p>
<?php
else:?>
	<dl class="dl-horizontal">
	<?php foreach($__list as $v): ?>
		<dt>版本 <?php echo $v['version'] ?></dt>
		<dd>
			<?php if(count($v['update_info'])): ?>
				<ol>
					<?php foreach($v['update_info'] as $v2): ?>
						<li><?php echo $v2['msg'] ?><?php if($v2['url'] != "#" && !empty($v2['url'])): ?>
								<a href="<?php echo $v2['url'] ?>" rel="external"><small><i>详情</i></small></a>
							<?php endif; ?></li>
					<?php endforeach; ?>
				</ol>
			<?php else: ?>
				<i>无信息</i>
			<?php endif; ?>
		</dd>
	<?php endforeach; ?>
	</dl><?php endif;