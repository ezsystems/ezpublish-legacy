{let log_node_id=50
     all_category_list=fetch( content, list, hash( parent_node_id, $log_node_id,
                                                   class_filter_type, include,
                                                   class_filter_array, array( 1 ),
                                                   sort_by, array( 'name', true() ) ) )}

<h2>Categories</h2>

<ul>
  {section var=category loop=$all_category_list}
    {let log_count=fetch( content, tree_count, hash( parent_node_id, $category.item.node_id,
                                                     class_filter_type, include,
                                                     class_filter_array, array( 23 ) ) )}
        {section show=eq( $module_result.content_info.node_id, $category.item.node_id )}
            <li class="selected">{$category.item.name|wash}
        {section-else}
            <li><a href={concat( "content/view/full/", $category.item.node_id )|ezurl}>{$category.item.name|wash}</a>
        {/section}
        <em>{section show=$log_count|eq(1)}1 entry{section-else}{$log_count} entries{/section}</em></li>
    {/let}
  {/section}
</ul>
{/let}
