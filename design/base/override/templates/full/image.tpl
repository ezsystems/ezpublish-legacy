{* Image - Full view *}

<div class="content-view-full">
    <div class="class-image">

    <h1>{$node.name}</h1>

    <div class="attribute-image">
        <p>{attribute_view_gui attribute=$node.object.data_map.image image_class=imagelarge}</p>
    </div>

    <div class="attribute-caption">
        {attribute_view_gui attribute=$node.object.data_map.caption}
    </div>

    </div>
</div>