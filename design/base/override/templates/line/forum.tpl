<div class="view-full">
    <div class="class-folder">
    <h2><a href={$node.url_alias|ezurl}>{$node.name|wash}</a></h2>

    <div class="attribute-short">
    {attribute_view_gui attribute=$node.object.data_map.description}
    </div>

    <p>
    {"Number of Topics:"|i18n("design/forum/layout")}
    {fetch('content','list_count',hash(parent_node_id,$node.node_id))}
    </p>
    <p>
    {"Number of Posts:"|i18n("design/forum/layout")}
    {fetch('content','tree_count',hash(parent_node_id,$node.node_id))}
    </p>
    </div>
</div>