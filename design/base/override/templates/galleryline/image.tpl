{* Image - Gallery line view *}

<div class="view-galleryline">
    <div class="class-image">

    <div class="content-image">
        <p>{attribute_view_gui attribute=$node.object.data_map.image image_class=gallerythumbnail href=$node.url_alias|ezurl()}</p>
    </div>

    <div class="content-caption">
        <p>{$node.name}</p>
    </div>

    </div>
</div>