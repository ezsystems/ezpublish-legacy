{* Treemenu. *}
{section show=ezpreference( 'admin_treemenu' )}

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4><a class="showhide" href={'/user/preferences/set/admin_treemenu/0'|ezurl} title="Hide users and user groups."><span class="bracket">[</span>-<span class="bracket">]</span></a> {'User accounts'|i18( 'design/admin/parts/user/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div id="contentstructure">
    {include uri='design:contentstructuremenu/content_structure_menu.tpl' custom_root_node_id=ezini( 'NodeSettings', 'UserRootNode', 'content.ini')}
</div>

{section show=ne( $ui_context, 'browse')}
<ul>
    <li><a href={'role/list/'|ezurl}>{'Roles and policies'|i18n( 'design/admin/parts/user/menu' )}</a></li>
</ul>
{/section}

{section-else}

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4><a class="showhide" href={'/user/preferences/set/admin_treemenu/1'|ezurl} title="Show users and user groups."><span class="bracket">[</span>+<span class="bracket">]</span></a> {'User accounts'|i18n( 'design/admin/parts/user/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{section show=ne( $ui_context, 'browse')}
<ul>
    <li><a href={'role/list/'|ezurl}>{'Roles'|i18n( 'design/admin/parts/user/menu' )}</a></li>
</ul>
{/section}

{/section}

{* Trashcan. *}
<div id="trash">
<ul>
    <li><img src={'trash-icon-16x16.gif'|ezimage} width="16" height="16" alt="Trash" />&nbsp;<a href={concat( '/content/trash/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )|ezurl} title="{'View and manage the contents of the trash bin.'|i18n( 'design/admin/parts/user/menu' )}">{'Trash'|i18n( 'design/admin/parts/user/menu' )}</a></li>
</ul>
</div>

{* Left menu width control. *}
<p>
<a href={'/user/preferences/set/admin_left_menu_width/13'|ezurl}>{'Small'|i18n( 'design/admin/parts/user/menu' )}</a>
<a href={'/user/preferences/set/admin_left_menu_width/25'|ezurl}>{'Medium'|i18n( 'design/admin/parts/user/menu' )}</a>
<a href={'/user/preferences/set/admin_left_menu_width/50'|ezurl}>{'Large'|i18n( 'design/admin/parts/user/menu' )}</a>
</p>

{* DESIGN: Content END *}</div></div></div></div></div></div>
