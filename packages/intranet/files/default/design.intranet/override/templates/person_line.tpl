<div class="person_line">

<a href={$node.url_alias|ezurl}><h2>{$node.name|wash} ( {attribute_view_gui attribute=$node.object.data_map.position} )</h2></a>

{attribute_view_gui attribute=$node.object.data_map.picture image_class=medium}

<div class="contact">
<h3>{"Contact information"|i18n("design/intranet/layout")}</h3>
{attribute_view_gui attribute=$node.object.data_map.person_numbers}
</div>

</div>
