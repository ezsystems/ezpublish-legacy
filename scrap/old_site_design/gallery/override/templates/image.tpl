{* Image template

   Displays the image in full view with download link and navigation.
*}
{default is_preview=false()}

{let comment_limit=5
     comments=fetch( 'content', 'list', hash( parent_node_id, $node.node_id,
                                              sort_by ,array( array( 'published', false() ) ),
                                              limit, $comment_limit,
                                              offset, $view_parameters.offset,
                                              class_filter_type, 'include',
                                              class_filter_array, array( "comment" ) ) )
     comment_count=fetch( 'content', 'list_count', hash( parent_node_id, $node.node_id ) )
     image_attribute=$node.data_map.image
     image_content=$image_attribute.content
     previous_image=fetch( content, list, hash( parent_node_id, $node.parent.node_id,
                                                class_filter_type, include,
                                                class_filter_array, array( "image" ),
                                                limit, 1,
                                                attribute_filter, array( and, array( 'published', '>', $node.object.published ) ),
                                                sort_by, array( 'published', true() ) ) )
     next_image=fetch( content, list, hash( parent_node_id, $node.parent.node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( "image" ),
                                            limit, 1,
                                            attribute_filter, array( and, array( 'published', '<', $node.object.published ) ),
                                            sort_by, array( 'published', false() ) ) )
     previous_album=fetch( content, list, hash( parent_node_id, $node.parent.parent.node_id,
                                                class_filter_type, include,
                                                class_filter_array, array( "album" ),
                                                limit, 1,
                                                attribute_filter, array( and, array( 'name', '<', $node.parent.object.name ) ),
                                                sort_by, array( 'name', false() ) ) )
     next_album=fetch( content, list, hash( parent_node_id, $node.parent.parent.node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( "album" ),
                                            limit, 1,
                                            attribute_filter, array( and, array( 'name', '>', $node.parent.object.name ) ),
                                            sort_by, array( 'name', true() ) ) )
     previous_album_image=0
     next_album_image=0}

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

<div id="image">

<form method="post" action={"content/action/"|ezurl}>

    <h1>{$node.name|wash}</h1>

    {section show=$is_preview|not}
        <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
        <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
        <input type="hidden" name="ViewMode" value="full" />

        {section show=$node.object.can_edit}
        <div class="editbutton">
           <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
        </div>
        {/section}
    {/section}

    {attribute_view_gui attribute=$image_attribute image_class=large link_to_image}
    <p class="caption">{attribute_view_gui attribute=$node.object.data_map.caption}</p>

{section show=$is_preview|not}
<div class="download">
    <a href={concat( "content/download/", $node.contentobject_id, "/", $image_attribute.id, "/image/", $image_content.original_filename )|ezurl}>download</a>
</div>
{/section}

{section show=$is_preview|not}

<div class="navigation">

    {section show=or( $previous_image|gt( 0 ), $next_image|gt( 0 ) )}
    <div class="album">
        <h2>More image in album <em>{$node.parent.name|wash}</em></h2>
        <table>
        <tr>
            {section show=$previous_image|gt( 0 )}
            <td>
                <h3><a href={$previous_image[0].url_alias|ezurl}><strong class="arrow">&laquo; (previous)</strong> {$previous_image[0].name|wash}</a></h3>
                {node_view_gui view=navigator href=$previous_image[0].url_alias|ezurl content_node=$previous_image[0]}
            </td>
            {/section}
            {section show=$next_image|gt( 0 )}
            <td>
                <h3><a href={$next_image[0].url_alias|ezurl}>{$next_image[0].name|wash} <strong class="arrow">(next) &raquo;</strong></a></h3>
                {node_view_gui view=navigator href=$next_image[0].url_alias|ezurl content_node=$next_image[0]}
            </td>
            {/section}
        </tr>
        </table>
    </div>
    {/section}

    {cache-block keys=array( $node.parent.node_id )}
    {section show=or( $previous_album_image|gt( 0 ), $next_album_image|gt( 0 ) )}
    <div class="gallery">
        <h2>In gallery <em>{$node.parent.parent.name|wash}</em></h2>
        <table>
        <tr>
            {section show=$previous_album_image|gt( 0 )}
            <td>
                <h3><a href={$previous_album[0].url_alias|ezurl}><strong class="arrow">&laquo;</strong> {$previous_album[0].name}</a></h3>
                {node_view_gui view=navigator href=$previous_album_image[0].url_alias|ezurl content_node=$previous_album_image[0]}
            </td>
            {/section}
            {section show=$next_album_image|gt( 0 )}
            <td>
                <h3><a href={$next_album[0].url_alias|ezurl}>{$next_album[0].name} <strong class="arrow">&raquo;</strong><a/></h3>
                {node_view_gui view=navigator href=$next_album_image[0].url_alias|ezurl content_node=$next_album_image[0]}
            </td>
            {/section}
        </tr>
        </table>
    </div>
    {/section}
    {/cache-block}

</div>

<div class="commentbutton">
   <input type="hidden" name="ClassID" value="26" />
   <input class="button" type="submit" name="NewButton" value="New comment" />
</div>

{section show=$comments}
<div id="commentlist">
   {section loop=$comments}
       {node_view_gui view=line content_node=$:item}
   {/section}
</div>
{/section}

<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />

</form>

{/section}

</div>

{/let}
{/default}
