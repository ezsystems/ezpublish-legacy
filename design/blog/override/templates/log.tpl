{let page_limit=20
     comments=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,array( array( 'published', false() ) ),
                                          limit, $page_limit,
                                          offset, $view_parameters.offset,
                                          class_filter_type, 'include',
                                          class_filter_array, array( 'comment' ) ))
    comment_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

<form method="post" action={"content/action/"|ezurl}>

<div class="log">

<h2>{$node.object.published|datetime('custom', '%l | %j %F %Y')}</h2>
 <div class="logentry">
    <h3>{$node.name}</h3>
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