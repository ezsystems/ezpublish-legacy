{* Comment - Line view *}

<div class="view-line">
    <div class="class-comment">
    
    <h3>{$node.name}</h3>
    
    <div class="content-byline">
        <p class="author">{$node.object.data_map.author.content|wash}</p>
        <p class="date">{$node.object.published|l10n(date)}</p>
        <div class="break"></div>
    </div>
    
    <div class="content-long">
        {$node.object.data_map.message.content|wash(xhtml)|break|wordtoimage|autolink}
    </div>
    
    </div>
</div>

