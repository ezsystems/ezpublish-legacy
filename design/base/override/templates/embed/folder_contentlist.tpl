{let children=fetch_alias( children, hash( parent_node_id, $object.main_node_id ) ) }

<div class="view-children">
    {section var=Child loop=$children sequence=array(bglight,bgdark)}
         <h4><a href={concat( "/content/view/full/", $Child.node_id, "/")|ezurl}>{$Child.name}</a></h4>
    {/section}
</div>
{/let}