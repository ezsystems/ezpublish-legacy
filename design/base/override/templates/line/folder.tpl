{* Folder - Line view *}

<div class="view-line">
    <div class="class-folder">

    {let list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

    {section show=ne($list_count,0)}
        <h2><a href={$node.url_alias|ezurl}>{$node.name|wash()}</a></h2>
    {section-else}
        <h2>{$node.name|wash()}</h2>
    {/section}

    {section show=$node.object.data_map.summary.content.is_empty|not}
        <div class="content-short">
        {attribute_view_gui attribute=$node.object.data_map.summary}
        </div>
    {/section}

    {section show=ne($list_count,0)}
        <div class="content-link">
            <p><a href={$node.url_alias|ezurl}>Read more...</a></p>
        </div>
    {/section}

    {/let}

    </div>
</div>
