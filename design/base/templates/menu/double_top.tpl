{let root_node=fetch( content, node, hash( node_id, 2 ) )}
{section show=is_set( $module_result.node_id )}
<div id="topmenu">
    <div id="topmenu-design">

    <h3 class="hide">Top menu</h3>

    {let menuitems=fetch( 'content', 'list', hash( 'parent_node_id', 2,
                                                   'sort_by', $root_node.sort_array ) )}
    <ul>
    {section var=menu loop=$menuitems}
        {section show=eq( $menu.object.content_class.identifier, "link" )}
            <li {eq( $module_result.path[1].node_id, $menu.node_id )|choose( '', 'class="selected"' )}><div class="spacing"><a href="{$menu.object.data_map.link.content}">{$menu.object.data_map.link.data_text|wash}</a></div></li>
        {section-else}
            {section show=eq( sum( $menu.index, 1 ), $menuitems|count )}
            <li class="last {eq( $module_result.path[1].node_id, $menu.node_id )|choose( '', 'selected' )}"><div class="spacing"><a href={$menu.url_alias|ezurl}>{$menu.name|wash}</a></div></li>
            {section-else}
            <li {eq( $module_result.path[1].node_id, $menu.node_id )|choose( '', 'class="selected"' )}><div class="spacing"><a href={$menu.url_alias|ezurl}>{$menu.name|wash}</a></div></li>
            {/section}
        {/section}
    {/section}
    </ul>
    {/let}

    <div class="break"></div>

    </div>
</div>

{section show=and( is_set( $module_result.path[1]), is_set( $module_result.node_id ) )}

<div id="submenu">
    <div id="submenu-design">

    <h3 class="hide">Sub menu</h3>

    <ul>
        {section var=menu loop=fetch( 'content', 'list', hash( parent_node_id, $module_result.path[1].node_id,
                                                               sort_by, $root_node.sort_array ) )}

            {section show=eq( $menu.object.content_class.identifier, "link" )}
                <li {$menu.index|eq( 0 )|choose( '', 'class="first"' )}><a href={$menu.object.data_map.link.content}>{$menu.name}</a></li>
            {section-else}
                <li {$menu.index|eq( 0 )|choose( '', 'class="first"' )}><a href={$menu.url_alias|ezurl}>{$menu.name}</a></li>
            {/section}
        {/section}
    </ul>

    <div class="break"></div>
    </div>

</div>
{/section}

{/let}
