{* File - Line view *}

<div class="view-line">
    <div class="class-file">
    <h2>{$node.name}</h2>

    <div class="content-short">
        {attribute_view_gui attribute=$content_version.data_map.description}
    </div>

    <div class="content-file">
        <p>{attribute_view_gui attribute=$node.object.data_map.file}</p>
    </div>

    </div>
</div>