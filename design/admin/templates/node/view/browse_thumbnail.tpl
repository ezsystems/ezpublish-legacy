<div class="content-view-thumbnail">
{section show=$show_link}
    <a href={concat('/content/browse/',$node.node_id)|ezurl}>{$node.class_identifier|class_icon( normal, $node.object.class_name )}</a>
{section-else}
    {$node.class_identifier|class_icon( normal, $node.object.class_name )}
{/section}
</div>
