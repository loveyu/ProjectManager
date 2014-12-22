<div class="well well-sm" style="font-size: 1.5em">
<?php
/**
 * @var array $list
 */
foreach($list as $v){
	if(strpos($v,"report")!==0)continue;
	echo "<a class=\"label label-info\" href=\"?db={$v}\">", $v, "</a>\n";
}
?>
</div>