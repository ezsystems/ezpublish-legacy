{* File - List item view *}

<div class="view-listitem">
    <div class="class-file">

    <h3>{$node.name}</h3>

    <div class="content-short">
        {attribute_view_gui attribute=$node.object.data_map.description}
    </div>

    <div class="content-file">
        <p>{attribute_view_gui attribute=$node.object.data_map.file icon=no}</p>
    </div>

    </div>
</div>