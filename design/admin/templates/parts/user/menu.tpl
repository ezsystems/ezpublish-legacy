<ul>
    <li><a href={"/role/list/"|ezurl}>{"Roles"|i18n("design/admin/layout")}</a></li>
</ul>

{* Treemenu. *}
{section show=ezpreference( 'admin_treemenu' )}
<h4>{'User accounts'|i18n( 'design/admin/pagelayout' )} <a class="showhide" href={'/user/preferences/set/admin_treemenu/0'|ezurl}>[-]</a></h4>
<div id="contentstructure">
    {include uri='design:contentstructuremenu/content_structure_menu.tpl' custom_root_node_id=ezini( 'NodeSettings', 'UserRootNode', 'content.ini')}
</div>
{section-else}
<h4>{'User accounts'|i18n( 'design/admin/pagelayout' )} <a class="showhide" href={'/user/preferences/set/admin_treemenu/1'|ezurl}>[+]</a></h4>
{/section}

{* Trashcan. *}
<ul>
    <li><a href={concat( '/content/trash/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )|ezurl}>{'Trash'|i18n( 'design/admin/pagelayout' )}</a></li>
</ul>
