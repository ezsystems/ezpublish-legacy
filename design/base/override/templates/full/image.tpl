{* Image - Full view *}

<div class="view-full">
    <div class="class-image">

    <h2>{$node.name}</h2>

    <div class="content-image">
        <p>{attribute_view_gui attribute=$node.object.data_map.image image_class=large}</p>
    </div>

    <div class="content-short">
        {attribute_view_gui attribute=$node.object.data_map.caption}
    </div>

    </div>
</div>