{* Comment - Line view *}

<div class="content-view-line">
    <div class="class-comment">

    <h3>{$node.name|wash}</h3>

    <div class="attribute-byline">
        <p class="author">{$node.data_map.author.content|wash}</p>
        <p class="date">{$node.object.published|l10n(date)}</p>
        <div class="break"></div>
    </div>

    <div class="attribute-message">
        {$node.data_map.message.content|wash(xhtml)|break}
    </div>

    </div>
</div>