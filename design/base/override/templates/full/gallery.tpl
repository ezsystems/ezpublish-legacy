{* Gallery - Full view *}

<div class="view-full">
    <div class="class-gallery">

        <h1>{$node.name|wash()}</h1>

        <div class="content-short">
           {attribute_view_gui attribute=$node.object.data_map.short_description}
        </div>

        <div class="content-long">
           {attribute_view_gui attribute=$node.object.data_map.description}
        </div>

        <a href={concat('/content/view/slideshow/',$node.node_id)|ezurl}>Slideshow</a>

        {let page_limit=10
             children=fetch_alias( children, hash( parent_node_id, $node.node_id,
                                                  offset, $view_parameters.offset,
			        		  limit, $page_limit ) )
             list_count=fetch_alias( children_count, hash( parent_node_id, $node.node_id ) )}

        {section show=$children}
           <div class="view-children">
               <table>
               <tr>
               {section var=child loop=$children sequence=array(bglight,bgdark)}
                   <td>
                      {node_view_gui view=line content_node=$child}
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