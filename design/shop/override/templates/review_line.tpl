<div class="review">

<h3>{$node.name}</h3>
{section show=$node.object.published}
<div class="date">
    {$node.object.published|l10n(shortdatetime)}
</div>
{/section}
<byline>
By {$node.object.owner.name}
</byline>
<p>{$node.object.data_map.description.content|wash|autolink|wordtoimage|break}</p>

<div class="rating">
{section show=$node.object.data_map.rating.content|gt(0)}
    <label>Rating</label> [{$node.object.data_map.rating.content} of 5 Stars!]
{section-else}
    Not rated
{/section}
</div>

</div>