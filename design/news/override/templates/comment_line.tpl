<h4>{$node.name|wash()} | {$node.object.published|l10n(datetime)} | by {$node.object.data_map.name.content|wash()}</h4>
<p>
  {$node.object.data_map.comment.content|wash()|nl2br()|wordtoimage()|autolink()}
</p>

