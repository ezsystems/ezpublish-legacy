{let gallery_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                              class_filter_type, include,
                                              class_filter_array, array( 'gallery' ),
                                              sort_by, array( 'name', true() ) ) )}
<div id="frontpage">

    <h1>{$node.name|wash}</h1>

    {attribute_view_gui attribute=$node.object.data_map.description}

    <div id="gallery">
    <div class="list">
    <h2>{"Galleries"|i18n("design/gallery/layout")}</h2>

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

    <table class="list">
    <tr>
    {section var=gallery loop=$gallery_list}
        <td class="image">
        {let gallery_item_count=fetch( content, tree_count,
                                       hash( parent_node_id, $gallery.item.node_id,
                                             class_filter_type, include,
                                             class_filter_array, array( 'image' ) ) )
             first_album=fetch( content, list,
                                hash( parent_node_id, $gallery.item.node_id,
                                      class_filter_type, include,
                                      class_filter_array, array( 'album' ),
                                      limit, 1 ) )
             first_image_list=false()
             first_image=false()}
        {section show=$first_album|gt( 0 )}
            {section show=$first_album[0].data_map.image.content.original.is_valid}
                {set first_image=$first_album[0].data_map.image}
            {section-else}
                {set first_image_list=fetch( content, list,
                                             hash( parent_node_id, $first_album[0].node_id,
                                                   class_filter_type, include,
                                                   class_filter_array, array( 'image' ),
                                                   limit, 1 ) )}
                {set first_image=$first_image_list[0].data_map.image}
            {/section}
            {section show=$first_image}
                 {attribute_view_gui href=$gallery.item.url_alias|ezurl image_class=small_h attribute=$first_image}
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
    </div>
    </div>

    <div id="image">
    <div class="list">
    {let image_list=fetch( content, tree,
                           hash( parent_node_id, 2,
                                 limit, 3,
                                 sort_by, array( published, false() ),
                                 class_filter_type, include, 
                                 class_filter_array, array( 'image' ) ) )}

    <h2>{"Latest images"|i18n("design/gallery/layout")}</h2>
    <table class="imagelist">
    <tr>
        {section var=image loop=$image_list}
        <td>
            {attribute_view_gui href=$image.item.url_alias|ezurl image_class=small_h attribute=$image.item.data_map.image}
            <p class="caption">{attribute_view_gui attribute=$image.item.data_map.caption}</p>
        </td>
        {delimiter modulo=3}
        </tr>
        <tr>
        {/delimiter}
        {/section}
    </tr>
    </table>
    {/let}
    </div>
    </div>

    <div id="news">
    <div class="list">
    {let news_list=fetch( content, tree,
                          hash( parent_node_id, 2,
                                limit, 5,
                                sort_by, array( published, false() ),
                                class_filter_type, include, 
                                class_filter_array, array( 'article' ) ) )}

    <h2>{"Latest news"|i18n("design/gallery/layout")}</h2>
    <ul>
        {section var=news loop=$news_list}
        <li>
            <a href={$news.item.url_alias|ezurl}>{$news.item.name|wash}</a>
            <div class="date">
                ({$news.item.object.published|l10n( shortdate )})
            </div>
        </li>
        {/section}
    </ul>
    {/let}
    </div>
    </div>

    <div id="comment">
    <div class="list">
    {let comments_list=fetch( content, tree,
                              hash( parent_node_id, 2,
                                    limit, 5,
                                    sort_by, array( published, false() ),
                                    class_filter_type, include, 
                                    class_filter_array, array( 'comment' ) ) )}

    <h2>{"Latest comments"|i18n("design/gallery/layout")}</h2>
    <ul>
        {section var=comment loop=$comments_list}
        <li>
            <a href={concat( $comment.item.parent.url_alias, "/#commentlist" )|ezurl}>{$comment.item.name|wash}</a>
            <div class="date">
                ({$comment.item.object.published|l10n( shortdate )})
            </div>  
        </li>
        {/section}
    </ul>
    {/let}
    </div>
    </div>

</div>
{/let}
