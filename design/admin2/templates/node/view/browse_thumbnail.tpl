<div class="content-view-thumbnail">
{if $show_link}
    <a href={concat('/content/browse/',$node.node_id)|ezurl}>{$node.class_identifier|class_icon( normal, $node.object.class_name )}</a>
{else}
    {$node.class_identifier|class_icon( normal, $node.object.class_name )}
{/if}
</div>
