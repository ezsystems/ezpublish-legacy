{* Gallery - Line view *}

<div class="view-line">
    <div class="class-gallery">

        <h2>{$node.name|wash()}</h2>

    {section show=$node.object.data_map.image.content}
        <div class="content-image">
            {attribute_view_gui alignment=right image_class=small attribute=$node.object.data_map.image.content.data_map.image href=$node.url_alias|ezurl}
        </div>
    {/section}

        <div class="content-short">
           {attribute_view_gui attribute=$node.object.data_map.short_description}
        </div>

        <div class="content-link">
            <p><a href={$node.url_alias|ezurl}>Read more</a></p>
        </div>
    </div>
</div>
