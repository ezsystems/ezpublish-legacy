{* Gallery - Embed view *}
<div class="content-view-embed">
    <div class="class-gallery">
        <a href={$object.main_node.url_alias|ezurl}><h2>{$object.name|wash}</h2></a>

    {let children=fetch_alias( children, hash( parent_node_id, $object.main_node_id, limit, 5 ) ) }
    <div class="content-view-children">
    {section var=child loop=$children}
         {node_view_gui view=listitem content_node=$child}
    {/section}
    </div>
    {/let}
    </div>
</div>

