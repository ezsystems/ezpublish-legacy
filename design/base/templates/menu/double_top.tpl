{let root_node=fetch( content, node, hash( node_id, 2 ) )}
<div id="topmenu">
    <div id="topmenu-design">

    <h3 class="hide">{"Top menu"|i18n("design/base")}</h3>

    {let menuitems=fetch( content, list, hash( parent_node_id, 2,
                                               class_filter_type, include,
                                               class_filter_array, ezini( 'MenuContentSettings', 'TopIdentifierList', 'menu.ini' ),
                                               sort_by, $root_node.sort_array ) )}
    <ul>
    {section var=menu loop=$menuitems}
        {if eq( $menu.object.content_class.identifier, "link" )}
            <li {eq( $module_result.path[1].node_id, $menu.node_id )|choose( '', 'class="selected"' )}><div class="spacing"><a href="{$menu.data_map.location.content}">{$menu.object.name|wash}</a></div></li>
        {else}
            {if eq( sum( $menu.index, 1 ), $menuitems|count )}
            <li class="last {eq( $module_result.path[1].node_id, $menu.node_id )|choose( '', 'selected' )}"><div class="spacing"><a href={$menu.url_alias|ezurl}>{$menu.name|wash}</a></div></li>
            {else}
            <li {eq( $module_result.path[1].node_id, $menu.node_id )|choose( '', 'class="selected"' )}><div class="spacing"><a href={$menu.url_alias|ezurl}>{$menu.name|wash}</a></div></li>
            {/if}
        {/if}
    {/section}
    </ul>
    {/let}

    <div class="break"></div>

    </div>
</div>

{section show=and( is_set( $module_result.path[1]), is_set( $module_result.node_id ) )}
{let submenu_items=fetch( content, list, hash( parent_node_id, $module_result.path[1].node_id,
                                                       class_filter_type, include,
                                                       class_filter_array, ezini( 'MenuContentSettings', 'TopIdentifierList', 'menu.ini' ),
                                                       sort_by, $root_node.sort_array,
                                                       limit, 10 ) ) }
{section show=count($submenu_items)|gt(0)}
<div id="submenu">
    <div id="submenu-design">

    <h3 class="hide">{"Sub menu"|i18n("design/base")}</h3>

    <ul>
    {section var=menu loop=$submenu_items}

            {if eq( $menu.object.content_class.identifier, "link" )}
                <li {$menu.index|eq( 0 )|choose( '', 'class="first"' )}><div class="spacing"><a href={$menu.data_map.location.content}>{$menu.object.name|wash}</a></div></li>
            {else}
                <li {$menu.index|eq( 0 )|choose( '', 'class="first"' )}><div class="spacing"><a href={$menu.url_alias|ezurl}>{$menu.name|wash}</a></div></li>
            {/if}
        {/section}
    </ul>

    <div class="break"></div>
    </div>

</div>
{/section}
{/let}
{/section}

{/let}

<hr class="hide" />


