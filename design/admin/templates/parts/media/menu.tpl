{* Treemenu. *}
<div id="content-tree">
{section show=ezpreference( 'admin_treemenu' )}

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4><a class="showhide" href={concat( '/user/preferences/set/admin_treemenu/0/', $uri_string )|ezurl} title="Hide media library."><span class="bracket">[</span>-<span class="bracket">]</span></a> {'Media library'|i18n( 'design/admin/parts/media/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div id="contentstructure">
    {include uri='design:contentstructuremenu/content_structure_menu.tpl' custom_root_node_id=ezini( 'NodeSettings', 'MediaRootNode', 'content.ini')}
</div>

{section-else}

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4><a class="showhide" href={concat( '/user/preferences/set/admin_treemenu/1/', $uri_string )|ezurl} title="Show media library."><span class="bracket">[</span>+<span class="bracket">]</span></a> {'Media library'|i18n( 'design/admin/parts/media/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{/section}

{section show=ne( $ui_context, 'browse' )}

{* Trashcan. *}
<div id="trash">
<ul>
    <li><img src={'trash-icon-16x16.gif'|ezimage} width="16" height="16" alt="Trash" />&nbsp;<a href={concat( '/content/trash/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )|ezurl} title="{'View and manage the contents of the trash bin.'|i18n( 'design/admin/parts/media/menu' )}">{'Trash'|i18n( 'design/admin/parts/media/menu' )}</a></li>
</ul>
</div>

{* Left menu width control. *}
<div class="widthcontrol">
<p>
<a href={'/user/preferences/set/admin_left_menu_width/13'|ezurl} title="{'Change the left menu width to small size.'|i18n( 'design/admin/parts/media/menu' )}">{'Small'|i18n( 'design/admin/parts/media/menu' )}</a>
<a href={'/user/preferences/set/admin_left_menu_width/19'|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'design/admin/parts/media/menu' )}">{'Medium'|i18n( 'design/admin/parts/media/menu' )}</a>
<a href={'/user/preferences/set/admin_left_menu_width/25'|ezurl} title="{'Change the left menu width to large size.'|i18n( 'design/admin/parts/media/menu' )}">{'Large'|i18n( 'design/admin/parts/media/menu' )}</a>
</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>