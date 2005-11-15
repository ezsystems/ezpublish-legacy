{* Comment - Full view *}

<div class="content-view-full">
    <div class="class-comment">

    <h1>{$node.name}</h1>

    <div class="attribute-byline">
        <p class="author">{$node.data_map.author.content|wash}</p>
        <p class="date">({$node.object.published|l10n(shortdatetime)})</p>
        <div class="break"></div>
    </div>

    <div class="attribute-long">
        {$node.data_map.message.content|wash(xhtml)|break|wordtoimage|autolink}
    </div>

    </div>
</div>

