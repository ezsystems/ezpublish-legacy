{let link_limit=10
     link_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                          class_filter_type, include,
                                          class_filter_array, array( 'link' ),
                                          offset, $view_parameters.offset,
                                          limit, $link_limit,
                                          sort_by, array( 'published', false() ) ) )
     category_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                               class_filter_type, include,
                                               class_filter_array, array( 'folder' ),
                                               sort_by, array( 'name', true() ) ) )}
<div id="archive">

  <div class="header">
    <h1><span>Link Archive</span></h1>
    <em>Worthwhile websites, weblogs, journals, news pubs, and the like</em>
  </div>

  <div class="linklist">
  <h2>Recent Links</h2>
    {section var=link loop=$link_list}
    <div class="link">
      <h2><a href={concat( $link.item.data_map.url.content )|ezurl} >{$link.item.name|wash}</a></h2>
      <div class="linkentry">
        {attribute_view_gui attribute=$link.item.data_map.link}
        {attribute_view_gui attribute=$link.item.data_map.description}
      </div>
    </div>
    {/section}
  
  
  <h2>All Categories</h2>
  <dl>
    {section var=category loop=$category_list}
    {let link_count=fetch( content, tree_count, hash( parent_node_id, $category.item.node_id,
                                                      class_filter_type, include,
                                                      class_filter_array, array( 'link' ) ) )}
      <dt><a href={concat( "content/view/full/", $category.item.node_id )|ezurl}>{$category.item.name|wash}</a> <em>{section show=$link_count|eq(1)}1 entry{section-else}{$link_count} entries{/section}</em></dt>
      <dd>{attribute_view_gui attribute=$category.item.data_map.description}</dd>
    {/let}
    {/section}
  </dl>

  </div>
</div>

{/let}
