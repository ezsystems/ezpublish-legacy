<div id="topmenu">
<div id="topmenu-design">

<h3 class="hide">Top menu</h3>

{let menuitems=fetch('content','list',hash('parent_node_id',2,'sort_by',array(array(priority,true()))))}
    <ul>
    {section name=menuloop loop=$menuitems}

            {section show=eq( $:item.object.content_class.identifier, "menu_link" )}
                <li><div><a href="{$menuloop:item.object.data_map.link.content}">{$:item.object.data_map.link.data_text|wash}</a></div></li>
            {section-else}
                <li><div><a href={$:item.url_alias|ezurl}>{$:item.name|wash}</a></div></li>
            {/section}
    {/section}
    </ul>
{/let}

</div>
</div>
