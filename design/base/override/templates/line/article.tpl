{* Article - Line view *}

<div class="view-line">
    <div class="class-article">
    
    {section show=ne($node.object.data_map.body.content.output.output_text,'')}
        <h2><a href={concat( "/content/view/full/", $node.node_id, "/")|ezurl}>{$node.name}</a></h2>
    {section-else}
        <h2>{$node.name}</h2>
    {/section}
    
    {section show=ne($node.object.data_map.intro.content.output.output_text,'')}
    <div class="content-short">
        {attribute_view_gui attribute=$node.object.data_map.intro}
    </div>
    {/section}
    
    {section show=ne($node.object.data_map.body.content.output.output_text,'')}
        <div class="content-link">
            <p><a href={concat( "/content/view/full/", $node.node_id, "/")|ezurl}>Read more...</a></p>
        </div>
    {/section}
    
    </div>
</div>