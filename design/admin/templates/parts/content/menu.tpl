<div id="content-tree">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{section show=ezpreference( 'admin_treemenu' )}
<h4><a class="showhide" href={concat( '/user/preferences/set/admin_treemenu/0/', $uri_string )|ezurl} title="Hide content structure."><span class="bracket">[</span>-<span class="bracket">]</span></a> {'Content structure'|i18n( 'design/admin/parts/content/menu' )}</h4>
{section-else}
<h4><a class="showhide" href={concat( '/user/preferences/set/admin_treemenu/1/', $uri_string )|ezurl} title="Show content structure."><span class="bracket">[</span>+<span class="bracket">]</span></a> {'Content structure'|i18n( 'design/admin/parts/content/menu' )}</h4>
{/section}

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Treemenu. *}
<div id="contentstructure">
{section show=ezpreference( 'admin_treemenu' )}
    {include uri='design:contentstructuremenu/content_structure_menu.tpl'}
{/section}
</div>

{* Trashcan. *}
{section show=ne( $ui_context, 'browse' )}
<div id="trash">
<ul>
    <li><img src={'trash-icon-16x16.gif'|ezimage} width="16" height="16" alt="Trash" />&nbsp;<a href={concat( '/content/trash/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )|ezurl} title="{'View and manage the contents of the trash bin.'|i18n( 'design/admin/parts/content/menu' )}">{'Trash'|i18n( 'design/admin/parts/content/menu' )}</a></li>
</ul>
</div>
{/section}

{* Left menu width control. *}
<div class="widthcontrol">
<p>
{switch match=ezpreference( 'admin_left_menu_width' )}
{case match='medium'}
<a href={concat( '/user/preferences/set/admin_left_menu_width/small/', $uri_string )|ezurl} title="{'Change the left menu width to small size.'|i18n( 'design/admin/parts/content/menu' )}">{'Small'|i18n( 'design/admin/parts/content/menu' )}</a>
<span class="current">{'Medium'|i18n( 'design/admin/parts/content/menu' )}</span>
<a href={concat( '/user/preferences/set/admin_left_menu_width/large/', $uri_string )|ezurl} title="{'Change the left menu width to large size.'|i18n( 'design/admin/parts/content/menu' )}">{'Large'|i18n( 'design/admin/parts/content/menu' )}</a>
{/case}

{case match='large'}
<a href={concat( '/user/preferences/set/admin_left_menu_width/small/', $uri_string )|ezurl} title="{'Change the left menu width to small size.'|i18n( 'design/admin/parts/content/menu' )}">{'Small'|i18n( 'design/admin/parts/content/menu' )}</a>
<a href={concat( '/user/preferences/set/admin_left_menu_width/medium/', $uri_string )|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'design/admin/parts/content/menu' )}">{'Medium'|i18n( 'design/admin/parts/content/menu' )}</a>
<span class="current">{'Large'|i18n( 'design/admin/parts/content/menu' )}</span>
{/case}

{case}
<span class="current">{'Small'|i18n( 'design/admin/parts/content/menu' )}</span>
<a href={concat( '/user/preferences/set/admin_left_menu_width/medium/', $uri_string )|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'design/admin/parts/content/menu' )}">{'Medium'|i18n( 'design/admin/parts/content/menu' )}</a>
<a href={concat( '/user/preferences/set/admin_left_menu_width/large/', $uri_string )|ezurl} title="{'Change the left menu width to large size.'|i18n( 'design/admin/parts/content/menu' )}">{'Large'|i18n( 'design/admin/parts/content/menu' )}</a>
{/case}
{/switch}
</p>
</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
