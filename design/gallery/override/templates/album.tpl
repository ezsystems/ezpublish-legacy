<div id="album">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

{section show=$node.object.can_edit}
<div class="editbutton">
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
</div>
{/section}

<div class="object_title">
    <h1>{$node.name}</h1>
</div>

{attribute_view_gui attribute=$node.object.data_map.description}

</form>

{let image_limit=2
     image_count=fetch( content, list_count, hash( parent_node_id, $node.node_id,
                                                   class_filter_type, include,
						   class_filter_array, array( 5 ) ) )
     image_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                            limit, $image_limit,
                                            offset, $view_parameters.offset,
                                            class_filter_type, include,
                                            class_filter_array, array( 5 ),
                                            sort_by, array( 'published', false() ) ) )}

<table border="1" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td align="center">{$image_count} images in this album on {ceil(div($image_count,$image_limit))} pages</td>
  </tr>
  <tr>
    <td>{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$image_count
         view_parameters=$view_parameters
         item_limit=$image_limit}</td>
  </tr>
</table>
  
<table>
  <tr> 
  {section var=image loop=$image_list}  
     <td>
        {node_view_gui view=line content_node=$image.item}
     </td>
     {delimiter modulo=$node.object.data_map.column.content}
       </tr>
       <tr>
     {/delimiter}
  {/section}
  </tr>
  </table>
</div>

<table border="1" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td>{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$image_count
         view_parameters=$view_parameters
         item_limit=$image_limit}</td>
  </tr>
</table>    
{/let}