<div class="person_line">

<div class="object_title">
<a href={$node.url_alias|ezurl}><h1>{$node.name} ( {attribute_view_gui attribute=$node.object.data_map.position} )</h1></a>
</div>

<div class="imageright">
    {attribute_view_gui attribute=$node.object.data_map.picture image_class=medium}
</div>

<div class="contact">
{attribute_view_gui attribute=$node.object.data_map.person_numbers}
</div>

</div>