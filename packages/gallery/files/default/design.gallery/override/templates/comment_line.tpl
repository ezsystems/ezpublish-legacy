<div class="comment">
<h2>{$node.name|wash}</h2>
{$node.object.data_map.name.content|wash()}
<p>
{$node.object.data_map.comment.content|wash()|nl2br()|wordtoimage()|autolink()}
</p>
</div>