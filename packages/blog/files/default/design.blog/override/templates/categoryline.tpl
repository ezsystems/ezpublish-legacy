<div class="category">
{let item_count=fetch( content, tree_count, hash( parent_node_id, $node.node_id,
                                                 class_filter_type, exclude,
                                                 class_filter_array, array( 'folder', 'comment' ) ) )}
  <div><a href={concat( "content/view/full/", $node.node_id )|ezurl}>{$node.name|wash}</a> <em>{section show=$item_count|eq(1)}1 entry{section-else}{$item_count} entries{/section}</em></div>
  <div class="description">{attribute_view_gui attribute=$node.data_map.description}</div>
{/let}
</div>
