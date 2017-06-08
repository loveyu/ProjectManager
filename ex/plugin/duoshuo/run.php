<?php
hook()->add('project_content', 'duoshuo_hook');
function duoshuo_hook($data, $type, $item_id, $title){
	$url = json_encode(URL_NOW);
	$item_id = json_encode(md5($item_id));
	$html = <<<HTML
<div id="disqus_thread"></div>
<script>
var disqus_config = function () {this.page.url = {$url};this.page.identifier = {$item_id};};
(function() {var d = document, s = d.createElement('script');s.src = 'https://loveyu-net.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());(d.head || d.body).appendChild(s);})();
</script>
HTML;

	return str_replace('<[COMMENT_TAG]>', $html, $data);
}