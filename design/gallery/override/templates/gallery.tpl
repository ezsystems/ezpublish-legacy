<div id="gallery">

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

{let album_item_count=false()
     last_changed=false()
     album_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( 28 ),
                                            sort_by, array( 'published', false() ) ) )}
{section var=Album loop=$album_list}
{set album_item_count=fetch( content, list_count, hash( parent_node_id, $Album.item.node_id,
                                                  class_filter_type, include,
						  class_filter_array, array( 5 ) ) )}
<table width="100%"> 
  <tr> 
     <td>
     <a href={concat('content/view/full/',$Album.item.node_id)|ezurl}><img src={$Album.item.data_map.image.content["medium"].full_path|ezroot} alt="{$Album.item.data_map.image.content.alternative_text|wash(xhtml)}" /></a>
     </td>
     <td>
         <div class="object_title">
	 <a href={concat('/content/view','/full/',$Album.item.node_id)|ezurl}><h2>{$Album.item.name}</h2></a>
	 </div>
         {attribute_view_gui attribute=$Album.item.data_map.description}
	 <p>
	 Last changed on {$Album.item.object.published|l10n( shortdate )}. This album contains {$album_item_count} items.
	 </p>
     </td>
  </tr>
  <tr> 
     <td colspan="2">
     <hr />
     </td>
  </tr>
  </table>
{/section}
</div>
{/let}
