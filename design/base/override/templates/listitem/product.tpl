{* Product - List item view *}
<div class="content-view-listitem">
    <div class="class-product">

        <a href={$node.url_alias|ezurl}><h3>{$node.name|wash()}</h3></a>

    {if $node.data_map.image.content}
        <div class="attribute-image">
            {attribute_view_gui alignment=right image_class=small attribute=$node.data_map.image.content.data_map.image}
        </div>
    {/if}

        <div class="attribute-short">
           {attribute_view_gui attribute=$node.data_map.short_description}
        </div>

        <div class="attribute-price">
           <p>
           {attribute_view_gui attribute=$node.data_map.price}
           </p>
        </div>
   </div>
</div>
