{let children=fetch_alias( subtree, hash( parent_node_id, $object.main_node_id ) ) }

<div class="content-view-children">
    {section var=Child loop=$children}
         <h4><a href={concat( "/content/view/full/", $Child.node_id, "/")|ezurl}>{$Child.name}</a></h4>
    {/section}
</div>
{/let}