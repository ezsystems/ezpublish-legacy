<div id="leftmenu">
<div id="leftmenu-design">

<h3 class="hide">Left menu</h3>

{let docs=treemenu( $module_result.path,
                    $module_result.node_id,
                    array( 'folder' ), 0, 5 )
                    depth=1}
        <ul>
        {section var=menu loop=$:docs last-value}
            {section show=and($menu.last.level|gt($menu.level),$menu.number|gt(1))}
            </ul>
            </li>
            {/section}

            {set depth=$menu.level}
            <li class="menu-level-{$menu.level}">
            {section show=and($menu.last.level|lt($menu.level),$menu.number|gt(1))}
            <ul>
               <li class="menu-level-{$menu.level}">
            {/section}

            <a {$menu.is_selected|choose('','class="selected"')}
                href={$menu.url_alias|ezurl}>{$menu.text|shorten(25)}</a>

           </li>

        {/section}
        {section show=$docs|count}
            <li class="menu-level-0"></li>
        {/section}
        </ul>

        {section show=$depth|gt(1) loop=$depth|sub(1)}
         </li>
         <li class="menu-level-0"></li>
        </ul>
        {/section}

{/let}

</div>
</div>

