<div class="content-view-thumbnail">
{section show=$show_link}
    {attribute_view_gui attribute=$node.data_map.image image_class=small href=concat( '/content/browse/', $node.node_id )|ezurl}
{section-else}
    {attribute_view_gui attribute=$node.data_map.image image_class=small}
{/section}
</div>
