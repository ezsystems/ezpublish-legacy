{let node_list=fetch( content, tree, hash( parent_node_id, $parent_node,
                      limit, 5,
					  sort_by, array( published, false() ) ) )}

<div class="toollist">
    <div class="toollist-design">
    <h2>{$title}</h2>
    {section var=node loop=$node_list sequence=array(bglight,bgdark)}
    <div class="view-children">
        {node_view_gui view=listitem content_node=$node}
    </div>
    {/section}
    </div>
</div>

{/let}