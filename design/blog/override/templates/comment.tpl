<div id="comment">
<h2>{$node.name|wash()}</h2>
<p>
  {$node.object.data_map.comment.content|wash()|nl2br()|wordtoimage()|autolink()}
</p>
</div>