<div id="leftmenu">
<div id="leftmenu-design">

<h3 class="hide">Left sub menu</h3>

{section show=and(is_set($module_result.path[1]),is_set($module_result.node_id))}

    <ul>
    {section var=menu loop=fetch('content','list',hash(parent_node_id,$module_result.path[1].node_id,sort_by,array(array(priority,true()))))}
        <li class="menu-level-0"><a href={$menu.url_alias|ezurl}>{$menu.name|shorten(25)}</a></li>
    {/section}
    <li class="menu-level-0"></li>
    </ul>

    <div class="breakall"></div>
{/section}

</div>
</div>