{* Image - Line view *}

<div class="view-line">
    <div class="class-image">

    <h2>{$node.name}</h2>

    <div class="content-image">
        <p>{attribute_view_gui attribute=$node.object.data_map.image image_class=small href=$node.url_alias|ezurl()}</p>
    </div>

    <div class="content-caption">
        {attribute_view_gui attribute=$node.object.data_map.caption}
    </div>

    </div>
</div>