{* Article - List embed view *}
<div class="content-view-embed">
    <div class="class-article">

     <h2>{$object.data_map.title.content|wash}</h2>

     <div class="content-body">
    {if $object.data_map.image.content}
        <div class="attribute-image">
            {attribute_view_gui alignment=right image_class=articlethumbnail attribute=$object.data_map.image.content.data_map.image href=$object.main_node.url_alias|ezurl}
        </div>
    {/if}

    <div class="attribute-short">
        {attribute_view_gui attribute=$object.data_map.intro}
    </div>

    {if $object.data_map.body.content.is_empty|not}
        <div class="attribute-link">
            <p><a href={$object.main_node.url_alias|ezurl}>{"Read more..."|i18n("design/base")}</a></p>
        </div>
    {/if}
    </div>
    </div>
</div>
