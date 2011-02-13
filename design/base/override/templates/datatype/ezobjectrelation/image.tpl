{if $attribute.content}
<div class="imageright">

    <div class="imageobject">
    {attribute_view_gui attribute=$attribute.content.data_map.image image_class=articleimage}
    </div>

    <div class="imagecaption">
    {attribute_view_gui attribute=$attribute.content.data_map.caption}
    </div>

</div>

{/if}