{* Image - Full view *}

<div class="view-full">
    <div class="class-image">

    <h1>{$node.name}</h1>

    <div class="content-image">
        <p>{attribute_view_gui attribute=$node.object.data_map.image image_class=large}</p>
    </div>

    <div class="content-caption">
        {attribute_view_gui attribute=$node.object.data_map.caption}
    </div>

    </div>
</div>