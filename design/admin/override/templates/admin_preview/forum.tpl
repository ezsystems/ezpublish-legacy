{* Forum - Admin preview *}

{let topic_list=fetch('content','list',hash( parent_node_id, $node.node_id,
                                             limit, 20,
                                             offset, $view_parameters.offset,
                                             sort_by, array( array( attribute, false(), 'forum_topic/sticky' ), array( 'modified_subnode', false() ), array( 'node_id', false() ) ) ) )
     topic_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

<div class="content-view-full">
    <div class="class-forum">

    <h1>{$node.name|wash}</h1>

    <div class="attribute-short">
    {attribute_view_gui attribute=$node.data_map.description}
    </div>

    </div>
</div>

{/let}
