{* Treemenu. *}
{section show=ezpreference( 'admin_treemenu' )}
<h4>{'Media library'|i18n( 'design/admin/pagelayout' )} <a class="showhide" href={'/user/preferences/set/admin_treemenu/0'|ezurl}>[-]</a></h4>
<div id="contentstructure">
    {include uri='design:contentstructuremenu/content_structure_menu.tpl' custom_root_node_id=ezini( 'NodeSettings', 'MediaRootNode', 'content.ini')}
</div>
{section-else}
<h4>{'Media library'|i18n( 'design/admin/pagelayout' )} <a class="showhide" href={'/user/preferences/set/admin_treemenu/1'|ezurl}>[+]</a></h4>
{/section}

{* Trashcan. *}
<ul>
    <li><a href={concat( '/content/trash/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )|ezurl}>{'Trash'|i18n( 'design/admin/pagelayout' )}</a></li>
</ul>

{* Left menu width control. *}
<p>
<a href={'/user/preferences/set/admin_left_menu_width/13'|ezurl}>Small</a>
<a href={'/user/preferences/set/admin_left_menu_width/25'|ezurl}>Medium</a>
<a href={'/user/preferences/set/admin_left_menu_width/50'|ezurl}>Large</a>
</p>