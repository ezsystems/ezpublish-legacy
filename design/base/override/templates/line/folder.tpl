{* Folder - Line view *}

<div class="view-line">
    <div class="class-folder">

    {let list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

    {section show=ne($list_count,0)}
        <h2><a href={concat( "/content/view/full/", $node.node_id, "/")|ezurl}>{$node.name}</a></h2>
    {section-else}
        <h2>{$node.name}</h2>
    {/section}

    {section show=$node.object.data_map.short_description.content.is_empty|not}
        <div class="content-short">
        {attribute_view_gui attribute=$node.object.data_map.short_description}
        </div>
    {/section}

    {section show=ne($list_count,0)}
        <div class="content-link">
            <p><a href={concat( "/content/view/full/", $node.node_id, "/")|ezurl}>Read more...</a></p>
        </div>
    {/section}

    {/let}

    </div>
</div>
