{* Gallery - Admin preview *}

<div class="content-view-full">
    <div class="class-gallery">

        <h1>{$node.name|wash()}</h1>

        {if $node.data_map.image.content}
            <div class="attribute-image">
                {attribute_view_gui alignment=right image_class=medium attribute=$node.data_map.image.content.data_map.image}
            </div>
        {/if}

        <div class="attribute-short">
           {attribute_view_gui attribute=$node.data_map.short_description}
        </div>

        <div class="attribute-long">
           {attribute_view_gui attribute=$node.data_map.description}
        </div>

    </div>
</div>