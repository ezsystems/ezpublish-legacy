<h4>{$node.object.published|l10n(datetime)} | by {$node.object.data_map.author.content|wash()}</h4>
<p>
  {$node.object.data_map.message.content|wash()|nl2br()|wordtoimage()|autolink()}
</p>

