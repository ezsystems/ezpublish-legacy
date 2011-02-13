{* Article - List item view *}
<div class="content-view-listitem">
    <div class="class-article">

     <h3><a href={$node.url_alias|ezurl}>{$node.data_map.title.content|wash}</a></h3>

    {if $node.data_map.image.content}
        <div class="attribute-image">
            {attribute_view_gui alignment=right image_class=small attribute=$node.data_map.image.content.data_map.image}
        </div>
    {/if}

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.intro}
    </div>

    {if $node.data_map.body.content.is_empty|not}
        <div class="attribute-link">
            <p><a href={$node.url_alias|ezurl}>{"Read more..."|i18n("design/base")}</a></p>
        </div>
    {/if}

    </div>
</div>