<div id="topmenu">
    <div id="topmenu-design">

    <h3 class="hide">Top menu</h3>

{let menuitems=fetch('content','list',hash('parent_node_id',2,'sort_by',array(array(priority,true()))))}
    <ul>
    {section name=menuloop loop=$menuitems}

            {section show=eq( $:item.object.content_class.identifier, "menu_link" )}
                <li {eq($module_result.path[1].node_id,$menuloop:item.node_id)|choose('','class="selected"')}><div class="spacing"><a href="{$menuloop:item.object.data_map.link.content}">{$:item.object.data_map.link.data_text|wash}</a></div></li>
            {section-else}
                <li {eq($module_result.path[1].node_id,$menuloop:item.node_id)|choose('','class="selected"')}><div class="spacing"><a href={concat( "/content/view/full/", $menuloop:item.node_id, "/")|ezurl}>{$:item.name|wash}</a></div></li>
            {/section}
    {/section}
    </ul>
{/let}

    <div class="breakall"></div>

    </div>
</div>

{section show=and(is_set($module_result.path[1]),is_set($module_result.node_id))}

<div id="submenu">
    <div id="submenu-design">

    <h3 class="hide">Sub menu</h3>

    <ul>
    {section var=menu loop=fetch('content','list',hash(parent_node_id,$module_result.path[1].node_id,sort_by,array(array(priority,true()))))}
        <li {$menu.index|eq(0)|choose('','class="first"')}><a href={$menu.url_alias|ezurl}>{$menu.name}</a></li>
    {/section}
    </ul>

    <div class="breakall"></div>
    </div>

</div>
{/section}


{*
<div id="topmenu" class="{eq($module_result.path[1].node_id,80)|choose('regular-section','last-section')}">

<h2 class="invisible">Top menu</h2>

<ul>
    <li class="first {eq($module_result.path[1].node_id,16)|choose('','selected')}"><div class="spacing"><a href={"/company"|ezurl}>Company</a></div></li>
    <li {eq($module_result.path[1].node_id,65)|choose('','class="selected"')}><div class="spacing"><a href={"/ez_publish"|ezurl}>eZ publish</a></div></li>
    <li {eq($module_result.path[1].node_id,17)|choose('','class="selected"')}><div class="spacing"><a href={"/products"|ezurl}>Products</a></div></li>
    <li {eq($module_result.path[1].node_id,18)|choose('','class="selected"')}><div class="spacing"><a href={"/services"|ezurl}>Services</a></div></li>
    <li {eq($module_result.path[1].node_id,19)|choose('','class="selected"')}><div class="spacing"><a href={"/community"|ezurl}>Community</a></div></li>
    <li class="last {eq($module_result.path[1].node_id,80)|choose('','selected')}"><div class="spacing"><a href={"/partner"|ezurl}>Partner</a></div></li>
</ul>

<div class="breakall"></div>

</div>

{section show=and(is_set($module_result.path[1]),is_set($module_result.node_id))}
{section show=ne($module_result.section_id,10)}
<div id="submenu">
<h2 class="invisible">Sub menu</h2>
<ul>
{section var=menu loop=fetch('content','list',hash(parent_node_id,$module_result.path[1].node_id,sort_by,array(array(priority,true())),  ))}
    <li {$menu.index|eq(0)|choose('','class="first"')}><a href={$menu.url_alias|ezurl}>{$menu.name}</a></li>
{/section}
</ul>
<div class="breakall"></div>

</div>
{/section}
{/section}
*}
