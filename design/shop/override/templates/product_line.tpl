<div class="productline">

<h2><a href={$node.url_alias|ezurl}>{$node.name|wash}</a></h2>

{attribute_view_gui attribute=$node.object.data_map.image image_class=small href=$node.url_alias|ezurl css_class="listimage"}

{attribute_view_gui attribute=$node.object.data_map.description}

<div class="price">
    <p>{attribute_view_gui attribute=$node.object.data_map.price}</p>
</div>

<div class="readmore">
    <p><a href={$node.url_alias|ezurl}>{"Read more"|i18n("design/shop/layout")}</a></p>
</div>

</div>

<div class="break" />
