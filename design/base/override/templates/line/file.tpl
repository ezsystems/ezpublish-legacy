{* File - Line view *}

<div class="content-view-line">
    <div class="class-file">
    <h2>{$node.name}</h2>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.object.data_map.description}
    </div>
    <div class="break"></div>
    <div class="attribute-file">
        <p>{attribute_view_gui attribute=$node.object.data_map.file icon_size='small' icon_title=$node.name}</p>
    </div>

    </div>
</div>
