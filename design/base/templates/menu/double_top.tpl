<div id="topmenu">
<div id="topmenu-design">

<h3 class="hide">Top menu</h3>


{let menuitems=fetch('content','list',hash('parent_node_id',2,'sort_by',array(array(priority,true()))))}
    <ul>
    {section name=menuloop loop=$menuitems}

            {section show=eq( $:item.object.content_class.identifier, "menu_link" )}
                <li><div><a href="{$menuloop:item.object.data_map.link.content}">{$:item.object.data_map.link.data_text|wash}</a></div></li>
            {section-else}
                <li><div><a href={concat( "/content/view/full/", $menuloop:item.node_id, "/")|ezurl}>{$:item.name|wash}</a></div></li>
            {/section}
    {/section}
    </ul>
{/let}

</div>
</div>

{section show=and(is_set($module_result.path[1]),is_set($module_result.node_id))}

<div id="submenu">

<h2 class="hide">Sub menu</h2>

<ul>
{section var=menu loop=fetch('content','list',hash(parent_node_id,$module_result.path[1].node_id,sort_by,array(array(priority,true()))))}
    <li {$menu.index|eq(0)|choose('','class="first"')}><a href={$menu.url_alias|ezurl}>{$menu.name}</a></li>
{/section}
</ul>

<div class="breakall"></div>

</div>
{/section}

