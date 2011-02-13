<div class="content-view-thumbnail">
{if $show_link}
    {attribute_view_gui attribute=$node.data_map.image image_class=small href=concat( '/content/browse/', $node.node_id )|ezurl}
{else}
    {attribute_view_gui attribute=$node.data_map.image image_class=small}
{/if}
</div>
