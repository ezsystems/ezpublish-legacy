{* File - Line view *}

<div class="view-line">
    <div class="class-file">
    <h2>{$node.name}</h2>

    <div class="imageleft">
      {$node.object.data_map.file.content.mime_type|mimetype_icon()}
    </div>
    <div class="content-short">
        {attribute_view_gui attribute=$node.object.data_map.description}
    </div>
    <div class="break" />
    <div class="content-file">
        <p>{attribute_view_gui attribute=$node.object.data_map.file icon='no'}</p>
    </div>

    </div>
</div>