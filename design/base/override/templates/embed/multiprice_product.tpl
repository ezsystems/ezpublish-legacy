{* Multiprice Product - List embed view *}
<h2>Multiprice Product - List embed view</h2>

<div class="content-view-embed">
    <div class="class-product">
        <a href={$object.main_node.url_alias|ezurl}><h2>{$object.name|wash}</h2></a>

        <div class="content-body">

    {if $object.data_map.image.content}
        <div class="attribute-image">
            {attribute_view_gui alignment=right image_class=small attribute=$object.data_map.image.content.data_map.image href=$object.main_node.url_alias|ezurl}
        </div>
    {/if}

        {attribute_view_gui attribute=$object.data_map.short_description}

        </div>

    </div>
</div>
