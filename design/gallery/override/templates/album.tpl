{let image_limit=mul( $node.object.data_map.column.content, 4 )
     image_count=fetch( content, list_count, hash( parent_node_id, $node.node_id,
                                                   class_filter_type, include,
						   class_filter_array, array( 'image' ) ) )
     image_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                            limit, $image_limit,
                                            offset, $view_parameters.offset,
                                            class_filter_type, include,
                                            class_filter_array, array( 'image' ),
                                            sort_by, array( 'published', false() ) ) )
     previous_album=fetch( content, list, hash( parent_node_id, $node.parent.node_id,
                                                class_filter_type, include,
                                                class_filter_array, array( "album" ),
                                                limit, 1,
                                                attribute_filter, array( and, array( 'name', '<', $node.object.name ) ),
                                                sort_by, array( 'name', false() ) ) )
     next_album=fetch( content, list, hash( parent_node_id, $node.parent.node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( "album" ),
                                            limit, 1,
                                            attribute_filter, array( and, array( 'name', '>', $node.object.name ) ),
                                            sort_by, array( 'name', true() ) ) )
     previous_album_image=false()
     next_album_image=false()
     page_count=ceil( div( $image_count, $image_limit ) )}

{section show=$previous_album|gt( 0 )}
    {set previous_album_image=fetch( content, list, hash( parent_node_id, $previous_album[0].node_id,
                                                          class_filter_type, include,
                                                          class_filter_array, array( "image" ),
                                                          limit, 1,
                                                          sort_by, array( 'published', false() ) ) )}
{/section}
{section show=$next_album|gt( 0 )}
    {set next_album_image=fetch( content, list, hash( parent_node_id, $next_album[0].node_id,
                                                      class_filter_type, include,
                                                      class_filter_array, array( "image" ),
                                                      limit, 1,
                                                      sort_by, array( 'published', false() ) ) )}
{/section}

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

    <div class="info">
      {section show=$page_count_count|gt( 1 )}
          <h2>{$image_count} images in this album on {$page_count} pages</h2>
      {section-else}
          <h2>{$image_count} images in this album</h2>
      {/section}
      <div class="navigator">
          {include name=navigator
             uri='design:navigator/google.tpl'
             page_uri=concat('/content/view','/full/',$node.node_id)
             item_count=$image_count
             view_parameters=$view_parameters
             item_limit=$image_limit}
      </div>
    </div>
  
    <table>
      <tr> 
      {section var=image loop=$image_list}  
         <td>
            {node_view_gui view=line href=$image.item.url_alias|ezurl content_node=$image.item}
         </td>
         {delimiter modulo=$node.object.data_map.column.content}
           </tr>
           <tr>
         {/delimiter}
      {/section}
      </tr>
      </table>

    <div class="info">
      <div class="navigator">
          {include name=navigator
             uri='design:navigator/google.tpl'
             page_uri=concat('/content/view','/full/',$node.node_id)
             item_count=$image_count
             view_parameters=$view_parameters
             item_limit=$image_limit}
      </div>
    </div>

    <div class="navigation">

        {section show=or( $previous_album_image|gt( 0 ), $next_album_image|gt( 0 ) )}
        <div class="gallery">
            <h2>In gallery <em>{$node.parent.name|wash}</em></h2>
            <ul>
                {section show=$previous_album_image|gt( 0 )}
                <li>
                    <h3><a href={$previous_album[0].url_alias|ezurl}><strong class="arrow">&laquo;</strong> {$previous_album[0].name}</a></h3>
                    {node_view_gui view=navigator href=$previous_album_image[0].url_alias|ezurl content_node=$previous_album_image[0]}
                </li>
                {section-else}
                    {section show=$previous_album|gt( 0 )}
                    <li>
                        <h3><a href={$previous_album[0].url_alias|ezurl}><strong class="arrow">&laquo;</strong> {$previous_album[0].name}</a></h3>
                    </li>
                    {/section}
                {/section}
                {section show=$next_album_image|gt( 0 )}
                <li>
                    <h3><a href={$next_album[0].url_alias|ezurl}>{$next_album[0].name} <strong class="arrow">&raquo;</strong><a/></h3>
                    {node_view_gui view=navigator href=$next_album_image[0].url_alias|ezurl content_node=$next_album_image[0]}
                </li>
                {section-else}
                    {section show=$next_album|gt( 0 )}
                    <li>
                        <h3><a href={$next_album[0].url_alias|ezurl}>{$next_album[0].name} <strong class="arrow">&raquo;</strong><a/></h3>
                    </li>
                    {/section}
                {/section}
            </ul>
        </div>
        {/section}

    </div>

</div>

{/let}