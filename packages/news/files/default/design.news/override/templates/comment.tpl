<h4>{$node.name|wash()}</h4>
<p>
  {$node.object.data_map.comment.content|wash()|nl2br()|wordtoimage()|autolink()}
</p>