{* File - List item view *}

<div class="content-view-listitem">
    <div class="class-file">

    <h3>{$node.name|wash}</h3>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.description}
    </div>

    <div class="attribute-file">
        <p>{attribute_view_gui attribute=$node.data_map.file icon_size='small' icon_title=$node.name}</p>
    </div>

    </div>
</div>