{* Gallery - Line view *}

<div class="view-line">
    <div class="class-gallery">

TEST
        <h1>{$node.name|wash()}</h1>

        <div class="content-short">
           {attribute_view_gui attribute=$node.object.data_map.short_description}
        </div>

        <div class="content-image">
           {attribute_view_gui attribute=$node.object.data_map.image}
        </div>

        <div class="content-link">
            <p><a href={$node.url_alias|ezurl}>Read more</a></p>
        </div>
    </div>
</div>
