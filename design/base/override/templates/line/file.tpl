{* File - Line view *}

<div class="view-line">
    <div class="class-file">
    
     <h2>{$node.name}</h2>
    
    {section show=ne($node.object.data_map.description.content.output.output_text,'')}
    <div class="content-short">
        {attribute_view_gui attribute=$content_version.data_map.description}
    </div>
    {/section}
    
    {section show=ne($node.object.data_map.body.content.output.output_text,'')}
        <div class="content-link">
            <p>{attribute_view_gui attribute=$node.object.data_map.file}</p>
        </div>
    {/section}
    
    </div>
</div>