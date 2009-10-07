<div id="leftmenu">
<div id="leftmenu-design">

<h3 class="hide">{"Left sub menu"|i18n("design/base")}</h3>

{section show=and( is_set( $module_result.path[1] ), is_set( $module_result.node_id ) )}
{let root_node=fetch( content, node, hash( node_id, 2 ) )
     submenu=fetch( content, list, hash( parent_node_id, $module_result.path[1].node_id,
                                         class_filter_type, include,
                                         class_filter_array, ezini( 'MenuContentSettings', 'LeftIdentifierList', 'menu.ini' ),
                                         sort_by, $root_node.sort_array ) )}
    <ul>
    {section var=menu loop=$submenu}
        <li class="menu-level-0"><a href={$menu.url_alias|ezurl}>{$menu.name|shorten( 25 )}</a></li>
    {/section}

    {if $submenu|count}
    <li class="menu-level-0"></li>
    {/if}
    </ul>

    <div class="breakall"></div>

{/let}
{/section}

</div>
</div>
