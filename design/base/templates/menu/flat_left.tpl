<div id="leftmenu">
<div id="leftmenu-design">

<h3 class="hide">{"Left menu"|i18n("design/base")}</h3>

{let docs=treemenu( $module_result.path,
                    is_set( $module_result.node_id )|choose( 2, $module_result.node_id ),
                    ezini( 'MenuContentSettings', 'LeftIdentifierList', 'menu.ini' ),
                    0, 5 )
                    depth=1
                    last_level=0}
        {if $docs|count|gt(0)}
        <ul>
        {section var=menu loop=$:docs last-value}
            {set last_level=$menu.last|is_array|choose( $menu.level, $menu.last.level )}
            {if and( $last_level|eq( $menu.level ), $menu.number|gt( 1 ) )}
                </li>
            {else}
            {if and( $last_level|gt( $menu.level ), $menu.number|gt( 1 ) )}
                </li>
                    {"</ul>
                </li>"|repeat(sub( $last_level, $menu.level ))}
            {/if}
            {/if}

            {if and( $last_level|lt( $menu.level ), $menu.number|gt( 1 ) )}
                {'<ul><li>'|repeat(sub($menu.level,$last_level,1))}
                <ul>
                    <li class="menu-level-{$menu.level}">
            {else}
                <li class="menu-level-{$menu.level}">
            {/if}

            <a {$menu.is_selected|choose( '', 'class="selected"' )} href={$menu.url_alias|ezurl}>{$menu.text|shorten( 25 )}</a>

            {set depth=$menu.level}
        {/section}
           </li>

        {if sub( $depth, 0 )|gt( 0 ) loop=sub( $depth, 0 )}
            </ul>
        </li>
        {/if}
        </ul>
        {/if}

{/let}

</div>
</div>

