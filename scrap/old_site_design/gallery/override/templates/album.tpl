{default is_preview=false()}
{let image_limit=mul( $node.object.data_map.column.content, 2 )
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
     example_list=false()
     previous_album_image=0
     next_album_image=0
     page_count=ceil( div( $image_count, $image_limit ) )}

{section show=and( $is_preview, $image_list|eq( 0 ) )}
    {set example_list=true()}
    {set image_list=fetch( content, tree, hash( parent_node_id, 2,
                                                limit, $image_limit,
                                                class_filter_type, include,
                                                class_filter_array, array( 'image' ),
                                                sort_by, array( 'published', false() ) ) )}
    {set image_count=$image_list|count}
{/section}

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

    <h1>{$node.name|wash}</h1>

    {section show=$is_preview|not}
    <form method="post" action={"content/action"|ezurl}>

        <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
        <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
        <input type="hidden" name="ViewMode" value="full" />

        {section show=and( $is_preview|not, $node.object.can_edit )}
        <div class="editbutton">
           <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
        </div>
        {/section}

        {let class_list=$node.object.can_create_class_list
             id_list=array()}
        {section var=class loop=$class_list}
            {set id_list=$id_list|array_append( $class.item.id )}
        {/section}
        {section show=$id_list|contains( 5 )}
            <div class="editbutton">
                <input type="hidden" name="NodeID" value="{$node.node_id}" />
                <input type="hidden" name="ClassID" value="5" />
                <input class="button" type="submit" name="NewButton" value="{'New image'|i18n('design/standard/node/view')}" />
            </div>
        {/section}
        {/let}
    </form>
    {/section}

    {attribute_view_gui attribute=$node.object.data_map.description}

    <div class="info">
      {section show=$page_count|gt( 1 )}
          <h2>{$image_count} images in this album on {$page_count} pages{section show=$example_list} (Examples only){/section}</h2>
      {section-else}
          <h2>{$image_count} images in this album{section show=$example_list} (Examples only){/section}</h2>
      {/section}
      {section show=$is_preview|not}
      <div class="navigator">
          {include name=navigator
             uri='design:navigator/google.tpl'
             page_uri=concat('/content/view','/full/',$node.node_id)
             item_count=$image_count
             view_parameters=$view_parameters
             item_limit=$image_limit}
      </div>
      {/section}
    </div>
  
    <table class="imagelist">
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

    {section show=$is_preview|not}
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
    {/section}

    <div class="navigation">

        {section show=or( $previous_album_image|gt( 0 ), $next_album_image|gt( 0 ) )}
        <div class="gallery">
            <h2>In gallery <em>{$node.parent.name|wash}</em></h2>
            <table>
            <tr>
                {section show=$previous_album_image|gt( 0 )}
                <td>
                    <h3><a href={$previous_album[0].url_alias|ezurl}><strong class="arrow">&laquo;</strong> {$previous_album[0].name}</a></h3>
                    {node_view_gui view=navigator href=$previous_album_image[0].url_alias|ezurl content_node=$previous_album_image[0]}
                </td>
                {section-else}
                    {section show=$previous_album|gt( 0 )}
                    <td>
                        <h3><a href={$previous_album[0].url_alias|ezurl}><strong class="arrow">&laquo;</strong> {$previous_album[0].name}</a></h3>
                    </td>
                    {/section}
                {/section}
                {section show=$next_album_image|gt( 0 )}
                <td>
                    <h3><a href={$next_album[0].url_alias|ezurl}>{$next_album[0].name} <strong class="arrow">&raquo;</strong><a/></h3>
                    {node_view_gui view=navigator href=$next_album_image[0].url_alias|ezurl content_node=$next_album_image[0]}
                </td>
                {section-else}
                    {section show=$next_album|gt( 0 )}
                    <td
                        <h3><a href={$next_album[0].url_alias|ezurl}>{$next_album[0].name} <strong class="arrow">&raquo;</strong><a/></h3>
                    </td>
                    {/section}
                {/section}
            </tr>
            </table>
        </div>
        {/section}

    </div>

</div>

{/let}
{/default}
