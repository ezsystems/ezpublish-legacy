{let gallery_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                              class_filter_type, include,
                                              class_filter_array, array( 'gallery' ),
                                              sort_by, array( 'name', true() ) ) )}
<div id="frontpage">

    <div class="object_title">
        <h1>{$node.name}</h1>
    </div>

    {attribute_view_gui attribute=$node.object.data_map.description}

    <h2>Galleries</h2>

    <table class="list">
    <tr>
    {section var=gallery loop=$gallery_list}
         <td class="image">
    {let gallery_item_count=fetch( content, tree_count, hash( parent_node_id, $gallery.item.node_id,
                                                              class_filter_type, include,
                                                              class_filter_array, array( 'image' ) ) )
         first_album=fetch( content, list, hash( parent_node_id, $gallery.item.node_id,
                                                 class_filter_type, include,
                                                 class_filter_array, array( 'album' ),
                                                 limit, 1 ) )
         first_image_list=false()
         first_image=false()}
    {section show=$first_album|gt( 0 )}
        {section show=$first_album[0].data_map.image.content.original.is_valid}
            {set first_image=$first_album[0].data_map.image}
        {section-else}
            {set first_image_list=fetch( content, list, hash( parent_node_id, $first_album[0].node_id,
                                                              class_filter_type, include,
                                                              class_filter_array, array( 'image' ),
                                                              limit, 1 ) )}
            {set first_image=$first_image_list[0].data_map.image}
        {/section}
        {section show=$first_image}
             {attribute_view_gui href=$gallery.item.url_alias|ezurl image_class=small attribute=$first_image}
        {/section}
     {/section}
         </td>
         <td class="info">
             <h2><a href={$gallery.item.url_alias|ezurl} title="{attribute_view_gui attribute=$gallery.item.data_map.description}">{$gallery.item.name}</a></h2>
             {attribute_view_gui attribute=$gallery.item.data_map.description}
             <p class="byline">Last changed on {$gallery.item.object.published|l10n( shortdate )}.</p>
             <p class="counter">This gallery contains {$gallery_item_count} images.</p>
     {/let}
    {delimiter modulo=2}
         </tr>
         <tr>
    {/delimiter}
    {/section}
    </tr>
    </table>


    <form method="post" action={"content/action"|ezurl}>

    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
    <input type="hidden" name="ViewMode" value="full" />

    {let class_list=$node.object.can_create_class_list
         id_list=array()}
    {section var=class loop=$class_list}
        {set id_list=$id_list|array_append( $class.item.id )}
    {/section}
    {section show=$id_list|contains( 27 )}
        <div class="editbutton">
            <input type="hidden" name="NodeID" value="{$node.node_id}" />
            <input type="hidden" name="ClassID" value="27" />
           <input class="button" type="submit" name="NewButton" value="{'New gallery'|i18n('design/standard/node/view')}" />
        </div>
    {/section}
    {/let}
    </form>

</div>
{/let}
