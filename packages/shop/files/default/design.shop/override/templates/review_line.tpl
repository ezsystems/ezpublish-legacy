<div class="review">

<h3>{$node.name|wash}</h3>
<div class="byline">
{section show=$node.object.published}
<p class="date">{$node.object.published|l10n(shortdatetime)}</p>
{/section}
<p class="name">By {$node.object.owner.name}</p>
</div>

<p>{$node.object.data_map.description.content|wash|autolink|wordtoimage|break}</p>

<div class="rating">
{section show=$node.object.data_map.rating.content|gt(0)}
{section loop=$node.object.data_map.rating.content}
    <img src={"rating-icon.gif"|ezimage} alt="" />
{/section}
    <p>Rating: {$node.object.data_map.rating.content} of 5</p>
{section-else}
    <p>{"No rating"|i18n("design/shop/layout")}</p>
{/section}
</div>

</div>
