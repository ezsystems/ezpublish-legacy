{* Link - Full view *}

<div class="view-full">
    <div class="class-link">
    
    <h1>{attribute_view_gui attribute=$node.data_map.title}</h1>
    
    {section show=ne($node.object.data_map.description.content.output.output_text,'')}
    <div class="content-full">
        {attribute_view_gui attribute=$node.data_map.description}
    </div>
    {/section}
    
    {section show=ne($node.object.data_map.link.content,'')}
        <div class="content-link">
            <p><a href="{$node.object.data_map.link.content}">{$node.object.data_map.link.data_text}</a></p>
        </div>
    {/section}
    
    </div>
</div>