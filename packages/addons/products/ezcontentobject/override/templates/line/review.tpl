{* Review - Line view *}

<div class="content-view-line">
    <div class="class-review">

    <h3>{$node.name}</h3>

    <div class="attribute-byline">
        <p class="author">
            {section show=$node.object.data_map.author.content|count_chars()|gt(0)}
                {$node.object.data_map.author.content|wash}
            {section-else}
                {$node.object.owner.name|wash}
            {/section}
        </p>
        <p class="date">{$node.object.published|l10n(date)}</p>
        <div class="break"></div>
    </div>

    <div class="attribute-message">
        <p>
        {$node.object.data_map.message.content|wash(xhtml)|break|wordtoimage|autolink}
        </p>
    </div>

    {section show=$node.object.data_map.rating.content.0|gt(0)}
    <div class="content-rating">
        {section loop=6|sub($node.object.data_map.rating.content.0)}
           <img src={"rating-icon.gif"|ezimage} width="24" height="24" alt="Star" />
        {/section}
    </div>
    {/section}

    </div>
</div>