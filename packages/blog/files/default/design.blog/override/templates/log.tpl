{let log_limit=20
     log_node_id=50
     comments=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,array( array( 'published', false() ) ),
                                          limit, $log_limit,
                                          offset, $view_parameters.offset,
                                          class_filter_type, 'include',
                                          class_filter_array, array( 'comment' ) ))
    comment_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}


{let previous_log=fetch( content, tree, hash( parent_node_id, $log_node_id,
                                              class_filter_type, include,
                                              class_filter_array, array( 'log' ),
                                              limit, 1,
                                              attribute_filter, array( and, array( 'published', '<', $node.object.published ) ),
                                              sort_by, array( 'published', false() ) ) )
    next_log=fetch( content, tree, hash( parent_node_id, $log_node_id,
                                              class_filter_type, include,
                                              class_filter_array, array( 'log' ),
                                              limit, 1,
                                              attribute_filter, array( and, array( 'published', '>', $node.object.published ) ),
                                              sort_by, array( 'published', true() ) ) )}
  <div class="header">
    <h1><span>{"Log Archive by Entry"|i18n("design/blog/layout")}</span></h1>
    <p>
{section show=$previous_log|gt(0)}
    <strong class="arrow">&laquo;</strong> <a href={concat( "content/view/full/", $previous_log[0].node_id )|ezurl} title="{$previous_log[0].name|wash}">Previous entry</a>
{/section}
{section show=and( $previous_log|gt( 0 ), $next_log|gt( 0 ) )}
<span class="sub">|</span>
{/section}
{section show=$next_log}
    <a href={concat( "content/view/full/", $next_log[0].node_id )|ezurl} title="{$next_log[0].name|wash}">Next entry</a> <strong class="arrow">&raquo;</strong>
{/section}
</p>
  </div>
{/let}



<form method="post" action={"content/action/"|ezurl}>

<div class="log">

<h2>{$node.object.published|datetime('custom', '%l | %j %F %Y')}</h2>
 <div class="logentry">
    <h3>{$node.name|wash}</h3>
    {attribute_view_gui attribute=$node.object.data_map.log}
    <div class="byline">
       <p>
          {$node.object.published|datetime('custom', '%g:%i%a')} in {$node.parent.name}
       </p>
    </div>

 {section show=$node.object.data_map.enable_comments.content}

 <div class="commentlist">
   <h3>{$comment_count} comments</h3>
   {section loop=$comments}
       <div class="comment">
            {node_view_gui view=line content_node=$:item}
       </div>
   {/section}

   <div class="commentbutton">
      <input type="hidden" name="ClassID" value="26" />
      <input class="button" type="submit" name="NewButton" value="New comment" />
   </div>
 </div>
  {/section}

  </div>
</div>

<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />

</form>

{/let}
