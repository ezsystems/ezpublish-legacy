{* Treemenu. *}
<div id="content-tree">
{section show=ezpreference( 'admin_treemenu' )}

<div class="box-header">
<div class="box-tc"><div class="box-ml"><div class="box-mr">
<div class="box-tl"><div class="box-tr">
<h4>{'Content structure'|i18n( 'design/admin/parts/content/menu' )} <a class="showhide" href={'/user/preferences/set/admin_treemenu/0'|ezurl}>[-]</a></h4>
</div></div>
</div></div></div>
</div>

<div class="box-bc"><div class="box-ml"><div class="box-mr">
<div class="box-bl"><div class="box-br">
<div class="box-content">

<div id="contentstructure">
    {include uri='design:contentstructuremenu/content_structure_menu.tpl'}
</div>

{* Trashcan. *}
<ul>
    <li><a href={concat( '/content/trash/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )|ezurl}>{'Trash'|i18n( 'design/admin/parts/content/menu' )}</a></li>
</ul>

{* Left menu width control. *}
<p>
<a href={'/user/preferences/set/admin_left_menu_width/13'|ezurl}>{'Small'|i18n( 'design/admin/parts/content/menu' )}</a>
<a href={'/user/preferences/set/admin_left_menu_width/25'|ezurl}>{'Medium'|i18n( 'design/admin/parts/content/menu' )}</a>
<a href={'/user/preferences/set/admin_left_menu_width/50'|ezurl}>{'Large'|i18n( 'design/admin/parts/content/menu' )}</a>
</p>

</div>
</div></div>
</div></div></div>

{section-else}
<div class="box-header">
<div class="box-tc"><div class="box-ml"><div class="box-mr">
<div class="box-tl"><div class="box-tr">
<h4>{'Content structure'|i18n( 'design/admin/parts/content/menu' )} <a class="showhide" href={'/user/preferences/set/admin_treemenu/1'|ezurl}>[+]</a></h4>
</div></div>
</div></div></div>
</div>

<div class="box-bc"><div class="box-ml"><div class="box-mr">
<div class="box-bl"><div class="box-br">
<div class="box-content">

{* Trashcan. *}
<br />

<ul>
    <li><a href={concat( '/content/trash/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )|ezurl}>{'Trash'|i18n( 'design/admin/parts/content/menu' )}</a></li>
</ul>

{* Left menu width control. *}
<p>
<a href={'/user/preferences/set/admin_left_menu_width/13'|ezurl}>{'Small'|i18n( 'design/admin/parts/content/menu' )}</a>
<a href={'/user/preferences/set/admin_left_menu_width/25'|ezurl}>{'Medium'|i18n( 'design/admin/parts/content/menu' )}</a>
<a href={'/user/preferences/set/admin_left_menu_width/50'|ezurl}>{'Large'|i18n( 'design/admin/parts/content/menu' )}</a>
</p>

</div>
</div></div>
</div></div></div>

{/section}
</div>
