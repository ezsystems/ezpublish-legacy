{* Folder - Line view *}

<div class="view-line">
    <div class="class-folder">

        <h2><a href={$node.url_alias|ezurl}>{$node.name|wash()}</a></h2>

       {section show=$node.object.data_map.summary.content.is_empty|not}
        <div class="content-short">
        {attribute_view_gui attribute=$node.object.data_map.summary}
        </div>
       {/section}

        <div class="content-link">
            <p><a href={$node.url_alias|ezurl}>Read more...</a></p>
        </div>

    {/let}

    </div>
</div>
