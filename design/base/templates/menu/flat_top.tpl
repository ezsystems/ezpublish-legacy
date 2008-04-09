<div id="topmenu">
    <div id="topmenu-design">

    <h3 class="hide">{"Top menu"|i18n("design/base")}</h3>

    {let root_node=fetch( content, node, hash( node_id, 2 ) )
         menuitems=fetch( content, list, hash( parent_node_id, 2,
                                               class_filter_type, include,
                                               class_filter_array, ezini( 'MenuContentSettings', 'TopIdentifierList', 'menu.ini' ),
                                               sort_by, $root_node.sort_array ) )}

    {if $menuitems|count|gt(0)}
    <ul>
    {section var=menu loop=$menuitems}
            {let selected=and( count( $module_result.path )|gt(1), eq( $module_result.path[1].node_id, $menu.node_id ) )}
            {section show=eq( $menu.object.content_class.identifier, "link" )}
                <li {$selected|choose( '', 'class="selected"' )}><div class="spacing"><a href={$menu.data_map.location.content|ezurl}>{$menu.object.name|wash}</a></div></li>
            {section-else}
                {section show=eq( sum( $menu.index, 1 ), $menuitems|count )}
                <li class="last {$selected|choose( '', 'selected' )}"><div class="spacing"><a href={$menu.url_alias|ezurl}>{$menu.name|wash}</a></div></li>
                {section-else}
                <li {$selected|choose( '', 'class="selected"' )}><div class="spacing"><a href={$menu.url_alias|ezurl}>{$menu.name|wash}</a></div></li>
                {/section}
            {/section}
            {/let}
    {/section}
    </ul>
    {/if}
    {/let}
	<div class="break"></div>
    </div>
</div>

<hr class="hide" />


