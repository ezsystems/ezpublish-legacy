{let item_node_id=$module_result.content_info.parent_node_id
     all_category_list=fetch( content, list, hash( parent_node_id, cond( $item_node_id|eq(1), 50 , $item_node_id ),
                                                   class_filter_type, include,
                                                   class_filter_array, array( 'folder' ),
                                                   sort_by, array( 'name', true() ) ) )}

<h2>{"Categories"|i18n("design/blog/layout")}</h2>

<ul>
  {section var=category loop=$all_category_list}
    {let item_count=fetch( content, tree_count, hash( parent_node_id, $category.item.node_id,
                                                     class_filter_type, exclude,
                                                     class_filter_array, array( 'folder', 'comment' ) ) )}
        {section show=eq( $module_result.content_info.node_id, $category.item.node_id )}
            <li class="selected">{$category.item.name|wash}
        {section-else}
            <li><a href={concat( "content/view/full/", $category.item.node_id )|ezurl}>{$category.item.name|wash}</a>
        {/section}
        <em>{section show=$item_count|eq(1)}1 entry{section-else}{$item_count} entries{/section}</em></li>
    {/let}
  {/section}
</ul>
{/let}
