{* File - Full view *}

<div class="view-full">
    <div class="class-file">

    <h1>{$node.object.data_map.name.content|wash()}</h1>

    {section show=$node.object.data_map.description.content.is_empty|not}
        <div class="content-long">
            {attribute_view_gui attribute=$node.object.data_map.description}
        </div>
    {/section}

    <div class="content-file">
        <p>{attribute_view_gui attribute=$node.object.data_map.file}</p>
    </div>
</div>
