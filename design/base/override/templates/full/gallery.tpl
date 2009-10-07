{* Gallery - Full view *}

<div class="content-view-full">
    <div class="class-gallery">

        <h1>{$node.name|wash()}</h1>

    {if $node.data_map.image.content}
        <div class="attribute-image">
            {attribute_view_gui alignment=right image_class=medium attribute=$node.data_map.image.content.data_map.image}
        </div>
    {/if}

        <div class="attribute-short">
           {attribute_view_gui attribute=$node.data_map.short_description}
        </div>

        <div class="attribute-long">
           {attribute_view_gui attribute=$node.data_map.description}
        </div>

        {let page_limit=10
             children=fetch_alias( children, hash( parent_node_id, $node.node_id,
                                                   offset, $view_parameters.offset,
                                                   limit, $page_limit,
                                                   sort_by, $node.sort_array ) )
             list_count=fetch_alias( children_count, hash( parent_node_id, $node.node_id ) )}

        {section show=$children}
            <div class="attribute-link">
                <p>
                <a href={$children[0].url_alias|ezurl}>{'View as slideshow'|i18n( 'design/base' )}</a>
                </p>
            </div>

           <div class="content-view-children">
               <table>
               <tr>
               {section var=child loop=$children sequence=array( bglight, bgdark )}
                   <td>
                      {node_view_gui view=galleryline content_node=$child}
                   </td>
                   {delimiter modulo=4}
                   </tr>
                   <tr>
                   {/delimiter}
               {/section}
               </tr>
               </table>
           </div>
        {/section}

        {include name=navigator
                 uri='design:navigator/google.tpl'
                 page_uri=concat( '/content/view', '/full/', $node.node_id )
                 item_count=$list_count
                 view_parameters=$view_parameters
                 item_limit=$page_limit}
        {/let}

    </div>
</div>