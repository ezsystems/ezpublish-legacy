{* Image template *}
{let comments=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,array( array( 'published', false() ) ),
                                          limit, $log_limit,
                                          offset, $view_parameters.offset,
                                          class_filter_type, 'include',
                                          class_filter_array, array( 'comment' ) ))
     comment_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

<div id="image">
<h1>{$node.name}</h1>

{attribute_view_gui attribute=$node.object.data_map.image}

{attribute_view_gui attribute=$node.object.data_map.caption}

    <div class="commentlist">
       {section loop=$comments}
           {node_view_gui view=line content_node=$:item}
       {/section}
    </div>
</div>

{/let}