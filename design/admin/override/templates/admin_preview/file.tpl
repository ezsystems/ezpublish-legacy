{* File - Admin preview *}

<div class="content-view-full">
    <div class="class-file">

    <h1>{$node.object.data_map.name.content|wash()}</h1>

    {section show=$node.object.data_map.description.content.is_empty|not}
        <div class="attribute-long">
            {attribute_view_gui attribute=$node.object.data_map.description}
        </div>
    {/section}

    <div class="attribute-file">
        <p>{attribute_view_gui attribute=$node.object.data_map.file icon_title=$node.name}</p>
    </div>
    
    </div>
</div>
