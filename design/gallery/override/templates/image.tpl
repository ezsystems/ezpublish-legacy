{* Image template *}
{let comments=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,array( array( 'published', false() ) ),
                                          limit, $log_limit,
                                          offset, $view_parameters.offset,
                                          class_filter_type, 'include',
                                          class_filter_array, array( 'comment' ) ))
     comment_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))
     image_attribute=$node.data_map.image
     image_content=$image_attribute.content}

<div id="image">

<form method="post" action={"content/action/"|ezurl}>

<h1>{$node.name}</h1>

{attribute_view_gui attribute=$image_attribute image_class=large}
<p>
  {attribute_view_gui attribute=$node.object.data_map.caption}
</p>

<div class="download">
    <a href={concat( "content/download/", $node.contentobject_id, "/", $image_attribute.id, "/image/", $image_content.original_filename )|ezurl}>[download]</a>
</div>

<div class="commentbutton">
   <input type="hidden" name="ClassID" value="26" />
   <input class="button" type="submit" name="NewButton" value="New comment" />
</div>

<div id="commentlist">
   {section loop=$comments}
       {node_view_gui view=line content_node=$:item}
   {/section}
</div>

<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />

</form>

</div>

{/let}