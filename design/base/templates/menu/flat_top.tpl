<div id="topmenu">
    <div id="topmenu-design">

    <h3 class="hide">{"Top menu"|i18n("design/base")}</h3>

    {let root_node=fetch( content, node, hash( node_id, 2 ) )
         menuitems=fetch( content, list, hash( parent_node_id, 2,
                                               class_filter_type, include,
                                               class_filter_array, ezini( 'MenuContentSettings', 'LeftIdentifierList', 'menu.ini' ),
                                               sort_by, $root_node.sort_array ) )}
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

<hr class="hide" />


