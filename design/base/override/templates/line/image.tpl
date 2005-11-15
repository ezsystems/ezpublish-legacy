{* Image - Line view *}

<div class="content-view-line">
    <div class="class-image">

    <h2>{$node.name|wash}</h2>

    <div class="content-image">
        <p>{attribute_view_gui attribute=$node.data_map.image image_class=small href=$node.url_alias|ezurl()}</p>
    </div>

    <div class="attribute-caption">
        {attribute_view_gui attribute=$node.data_map.caption}
    </div>

    </div>
</div>