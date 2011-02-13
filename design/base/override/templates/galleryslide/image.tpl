{* Image - Gallery slide view *}

<div class="content-view-galleryslide">
    <div class="class-image">

    <h1>{$parent_name|wash()}: {$node.name|wash()}</h1>

    <div class="attribute-image">
        <p>{attribute_view_gui attribute=$node.data_map.image image_class=large}</p>
    </div>

    <div class="attribute-caption">
        {attribute_view_gui attribute=$node.data_map.caption}
    </div>

    </div>
</div>
