{let children=fetch_alias( children, hash( parent_node_id, $object.main_node_id, limit, 5 ) ) }

<div class="view-children">
    {section var=child loop=$children sequence=array(bglight,bgdark)}
         {node_view_gui view=line content_node=$child}
    {/section}
</div>
{/let}