{* Review - Line view *}

<div class="content-view-line">
    <div class="class-review">

    <h3>{$node.name|wash}</h3>

    <div class="attribute-byline">
        <p class="author">
            {if $node.data_map.author.content|count_chars()|gt(0)}
                {$node.data_map.author.content|wash}
            {else}
                {$node.object.owner.name|wash}
            {/if}
        </p>
        <p class="date">{$node.object.published|l10n(date)}</p>
        <div class="break"></div>
    </div>

    <div class="attribute-message">
        <p>
        {$node.data_map.message.content|wash(xhtml)|break|wordtoimage|autolink}
        </p>
    </div>

    {section show=$node.data_map.rating.content.0|ge(0)}
    <div class="content-rating">
        {section loop=5|sub($node.data_map.rating.content.0)}
           <img src={"rating-icon.gif"|ezimage} width="24" height="24" alt="Star" />
        {/section}
    </div>
    {/section}

    </div>
</div>