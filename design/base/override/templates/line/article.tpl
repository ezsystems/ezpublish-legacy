{* Article - Line view *}
<div class="view-line">
    <div class="class-article">
    {section show=$node.object.data_map.image.content}
        <div class="imageright">
            {attribute_view_gui attribute=$node.object.data_map.image.content.data_map.image}
        </div>
    {/section}
    {section show=$node.object.data_map.body.content.is_empty|not}
        <h2><a href={concat( "/content/view/full/", $node.node_id, "/")|ezurl}>{$node.name}</a></h2>
    {section-else}
        <h2>{$node.name}</h2>
    {/section}

    {section show=$node.object.data_map.intro.content.is_empty|not}
    <div class="content-short">
        {attribute_view_gui attribute=$node.object.data_map.intro}
    </div>
    {/section}

    {section show=$node.object.data_map.body.content.is_empty|not}
        <div class="content-link">
            <p><a href={concat( "/content/view/full/", $node.node_id, "/")|ezurl}>Read more...</a></p>
        </div>
    {/section}

    </div>
</div>