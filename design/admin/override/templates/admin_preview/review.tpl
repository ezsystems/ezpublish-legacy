{* Review - Admin preview *}
<div class="content-view-full">
    <div class="class-review">

    <h1>{$node.name|wash}</h1>

    <div class="attribute-byline">
        <p class="author">{$node.data_map.author.content|wash}</p>
        <p class="date">({$node.object.published|l10n(shortdatetime)})</p>
        <div class="break"></div>
    </div>

    <div class="attribute-long">
        <p>{$node.data_map.message.content|wash(xhtml)|break|wordtoimage|autolink}</p>
    </div>

    <div class="attribute-rating">
        <p>
        <label>Rating:</label>
        {attribute_view_gui attribute=$node.data_map.rating}
        </p>
    </div>

    </div>
</div>
