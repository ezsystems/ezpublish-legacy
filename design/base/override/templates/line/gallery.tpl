{* Gallery - Line view *}

<div class="content-view-line">
    <div class="class-gallery">

        <h2>{$node.name|wash()}</h2>

    {if $node.data_map.image.content}
        <div class="content-image">
            {attribute_view_gui alignment=right image_class=small attribute=$node.data_map.image.content.data_map.image href=$node.url_alias|ezurl}
        </div>
    {/if}

        <div class="attribute-short">
           {attribute_view_gui attribute=$node.data_map.short_description}
        </div>

        <div class="attribute-link">
            <p><a href={$node.url_alias|ezurl}>{"Enter gallery"|i18n("design/base")}</a></p>
        </div>
    </div>
</div>
