<div class="view-full">
    <div class="class-forum">

    <h2><a href={$node.url_alias|ezurl}>{$node.name|wash}</a></h2>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.description}
    </div>

    <div class="infoline">
        <p class="topics">
        {"Number of Topics"|i18n("design/base")}:
        {fetch('content','list_count',hash(parent_node_id,$node.node_id))}
        </p>
        <p class="posts">
        {"Number of Posts"|i18n("design/base")}:
        {fetch('content','tree_count',hash(parent_node_id,$node.node_id))}
        </p>
    <div class="break"></div>
    </div>

    <div class="attribute-link">
        <p><a href={$node.url_alias|ezurl}>{"Enter forum"|i18n("design/base")}</a></p>
    </div>

    </div>
</div>