{* Image - Gallery line view *}
<div class="content-view-galleryline">
    <div class="class-image">

    <div class="attribute-image">
        <p>{attribute_view_gui attribute=$node.object.data_map.image image_class=gallerythumbnail
                               href=$node.url_alias|ezurl}</p>
    </div>

    <div class="attribute-caption">
        <p>{$node.name}</p>
    </div>

    </div>
</div>