{* Product - List item view *}
<div class="view-listitem">
    <div class="class-product">

        <a href={$node.url_alias|ezurl}><h3>{$node.name|wash()}</h3></a>

    {section show=$node.object.data_map.image.content}
        <div class="content-image">
            {attribute_view_gui alignment=right image_class=small attribute=$node.object.data_map.image.content.data_map.image}
        </div>
    {/section}

        <div class="content-short">
           {attribute_view_gui attribute=$node.object.data_map.intro}
        </div>

        <div class="content-price">
           <p>
           {attribute_view_gui attribute=$node.object.data_map.price}
           </p>
        </div>
   </div>
</div>
