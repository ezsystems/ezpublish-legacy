{* Comment - Full view *}

<div class="view-full">
    <div class="class-comment">
    
    <h1>{$node.name}</h1>
    
    <div class="content-byline">
        <p class="author">{$node.object.data_map.author.content|wash}</p>
        <p class="date">({$node.object.published|l10n(shortdatetime)})</p>
        <div class="break"></div>
    </div>
    
    <div class="content-long">
        {$node.object.data_map.message.content|wash(xhtml)|break|wordtoimage|autolink}
    </div>
    
    </div>
</div>

