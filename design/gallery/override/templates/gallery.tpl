{let album_item_count=false()
     last_changed=false()
     album_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( 28 ),
                                            sort_by, array( 'published', false() ) ) )}
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
    
    <table class="list">
    <tr>
    {section var=album loop=$album_list}
    {set album_item_count=fetch( content, list_count, hash( parent_node_id, $album.item.node_id,
                                                      class_filter_type, include,
    						  class_filter_array, array( 5 ) ) )}
    {let album_image_content=$album.item.data_map.image.content}
         <td class="image">
         {section show=$album_image_content.original.is_valid}
             {attribute_view_gui href=$album.item.url_alias|ezurl image_class=small attribute=$album.item..data_map.image}
         {section-else}
             {let album_first_image=fetch( content, list, hash( parent_node_id, $album.item.node_id,
                                                                class_filter_type, include,
                                                                class_filter_array, array( "image" ),
                                                                limit, 1,
                                                                sort_by, array( 'published', false() ) ) )}
             {section show=$album_first_image|gt( 0 )}
                 {attribute_view_gui href=$album.item.url_alias|ezurl image_class=small attribute=$album_first_image[0].data_map.image}
             {/section}
             {/let}
         {/section}
         </td>
         <td class="info">
             <h2><a href={$album.item.url_alias|ezurl}>{$album.item.name}</a></h2>
             {attribute_view_gui attribute=$album.item.data_map.description}
    	     <p class="byline">Last changed on {$album.item.object.published|l10n( shortdate )}.</p>
    	     <p class="counter">This album contains {$album_item_count} items.</p>
    {/let}
    {delimiter modulo=$node.object.data_map.column.content}
         </tr>
         <tr>
    {/delimiter}
    {/section}
    </tr>
    </table>

</div>
{/let}
