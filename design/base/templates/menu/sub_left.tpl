<div id="leftmenu">
<div id="leftmenu-design">

<h3 class="hide">Left sub menu</h3>

{section show=and(is_set($module_result.path[1]),is_set($module_result.node_id))}

{let submenu=fetch('content','list',hash(parent_node_id,$module_result.path[1].node_id,sort_by,array(array(priority,true()))) )}
    <ul>
    {section var=menu loop=$submenu}
        <li class="menu-level-0"><a href={$menu.url_alias|ezurl}>{$menu.name|shorten(25)}</a></li>
    {/section}

    {section show=$submenu|count}
    <li class="menu-level-0"></li>
    {/section}
    </ul>

    <div class="breakall"></div>

{/let}
{/section}

</div>
</div>