<div id="leftmenu">
<div id="leftmenu-design">

<h3 class="hide">{"Left menu"|i18n("design/base")}</h3>

{let docs=treemenu( $module_result.path,
                    $module_result.node_id,
                    array( 'folder' ), 0, 5 )
                    depth=1}
        <ul>
        {section var=menu loop=$:docs last-value}
            {section show=and( $menu.last.level|eq( $menu.level ), $menu.number|gt( 1 ) )}
                </li>
            {section-else}
            {section show=and( $menu.last.level|gt( $menu.level ), $menu.number|gt( 1 ) )}
                </li>
                    {"</ul>
                </li>"|repeat(sub( $menu.last.level, $menu.level ))}
            {/section}
            {/section}

            {section show=and( $menu.last.level|lt( $menu.level ), $menu.number|gt( 1 ) )}
                <ul>
                    <li class="menu-level-{$menu.level}">
            {section-else}
                <li class="menu-level-{$menu.level}">
            {/section}

            <a {$menu.is_selected|choose( '', 'class="selected"' )} href={$menu.url_alias|ezurl}>{$menu.text|shorten( 25 )}</a>

            {set depth=$menu.level}
        {/section}
           </li>

        {section show=sub( $depth, 0 )|gt( 0 ) loop=sub( $depth, 0 )}
            </ul>
        </li>
        {/section}
        </ul>

{/let}

</div>
</div>

