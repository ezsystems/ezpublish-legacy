{* File - List item view *}

<div class="content-view-listitem">
    <div class="class-file">

    <h3>{$node.name}</h3>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.object.data_map.description}
    </div>

    <div class="attribute-file">
        <p>{attribute_view_gui attribute=$node.object.data_map.file icon=no}</p>
    </div>

    </div>
</div>