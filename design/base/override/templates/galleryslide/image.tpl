{* Image - Gallery slide view *}

<div class="view-galleryslide">
    <div class="class-image">

    <h1>{$parent_name|wash()}: {$node.name|wash()}</h1>

    <div class="content-image">
        <p>{attribute_view_gui attribute=$node.object.data_map.image image_class=imagelarge}</p>
    </div>

    <div class="content-caption">
        {attribute_view_gui attribute=$node.object.data_map.caption}
    </div>

    </div>
</div>
