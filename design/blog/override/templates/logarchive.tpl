{section show=or( $view_parameters.day, $view_parameters.month, $view_parameters.year )}

{let log_limit=5
     log_count=fetch( content, tree_count, hash( parent_node_id, $node.node_id,
                                                 class_filter_type, include,
                                                 class_filter_array, array( 'log' ),
                                                 attribute_filter, array( and, array( 'published', '>=', maketime( 0, 0, 0, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ),
                                                                               array( 'published', '<=', maketime( 23, 59, 59, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ) ) ) )
     log_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                          class_filter_type, include,
                                          class_filter_array, array( 'log' ),
                                          attribute_filter, array( and, array( 'published', '>=', maketime( 0, 0, 0, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ),
                                                                        array( 'published', '<=', maketime( 23, 59, 59, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ) ),
                                          offset, $view_parameters.offset,
                                          limit, $log_limit,
                                          sort_by, array( 'published', false() ) ) )}

<div id="dayarchive">

  <div class="header">
    <h1><span>Log Archive by Day</span></h1>

    <p>
{*<strong class="arrow">&laquo;</strong> <a href="http://www.stopdesign.com/log/2003/10/22/index.html" title="October 22, 2003">Previous day</a>*}
<span class="sub">|</span>
<strong>{maketime( 0, 0, 0, $view_parameters.month, $view_parameters.day, $view_parameters.year )|datetime( custom, '%d %M %Y' )}</strong>
<span class="sub">|</span>
{*<a href="http://www.stopdesign.com/log/2003/10/30/index.html" title="October 30, 2003">Next day</a> <strong class="arrow">&raquo;</strong>*}
</p>
  </div>

</div>

  {section var=log loop=$log_list}
    {node_view_gui view=line content_node=$log.item}
  {/section}

  {include name=navigator
           uri='design:navigator/google.tpl'
           page_uri=concat( '/content/view/full/', $node.node_id )
           item_count=$log_count
           view_parameters=$view_parameters
           item_limit=$log_limit}

{/let}

{section-else}

{let log_limit=10
     log_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                          class_filter_type, include,
                                          class_filter_array, array( 'log' ),
                                          offset, $view_parameters.offset,
                                          limit, $log_limit,
                                          sort_by, array( 'published', false() ) ) )
     category_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                               class_filter_type, include,
                                               class_filter_array, array( 'folder' ),
                                               sort_by, array( 'name', true() ) ) )}
<div id="archive">

  <div class="header">
    <h1><span>Log Archive</span></h1>
    <em>Parenthetical thoughts, concepts, and brainstorms</em>
  </div>

  <div class="loglist">
  <h2>Excerpts from Recent Entries</h2>
    {section var=log loop=$log_list}
    <div class="log">
      <h2><a href={concat( "content/view/full/", $log.item.node_id )|ezurl} >{$log.item.name|wash}</a>
          <em class="byline">{$log.item.object.published|datetime( custom, "%d %M %Y" )}
          {section show=$node.object.data_map.enable_comments.content}
              <a class="comments" href={$node.url_alias|ezurl}>{fetch('content','list_count',hash(parent_node_id,$node.node_id))} comments</a>
          {section-else}
              Comments disabled
          {/section}</em>
      </h2>
      <div class="logentry">
        {attribute_view_gui attribute=$log.item.data_map.log}
      </div>
    </div>
    {/section}
  
  
  <h2>Entry Categories</h2>
  <dl>
    {section var=category loop=$category_list}
    {let log_count=fetch( content, tree_count, hash( parent_node_id, $category.item.node_id,
                                                     class_filter_type, include,
                                                     class_filter_array, array( 'log' ) ) )}
      <dt><a href={concat( "content/view/full/", $category.item.node_id )|ezurl}>{$category.item.name|wash}</a> <em>{section show=$log_count|eq(1)}1 entry{section-else}{$log_count} entries{/section}</em></dt>
      <dd>{attribute_view_gui attribute=$category.item.data_map.description}</dd>
    {/let}
    {/section}
  </dl>

  </div>
</div>

{/let}

{/section}
