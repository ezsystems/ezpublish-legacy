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
            <li>
            {section show=and($menu.last.level|lt($menu.level),$menu.number|gt(1))}
            <ul>
               <li>
            {/section}

            {set depth=$menu.level}
            <div class="menu-level-{$menu.level}">
            <a {$menu.is_selected|choose('','class="selected"')}
                href={$menu.url_alias|ezurl}>{$menu.text|shorten(25)}</a>
            </div>

           </li>
        {/section}
        </ul>

        {section show=$depth|gt(1) loop=$depth|sub(1)}
         </li>
        </ul>
        {/section}

{/let}
