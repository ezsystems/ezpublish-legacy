<div class="company_line">

<a href={$node.url_alias|ezurl}><h2>{$node.name|wash}</h2></a>

<div class="imageright">
    {attribute_view_gui attribute=$node.object.data_map.logo image_class=medium}
</div>

<h3>Contact information</h3>
{attribute_view_gui attribute=$node.object.data_map.company_number}
<h3>Address</h3>
{attribute_view_gui attribute=$node.object.data_map.company_address}

</div>