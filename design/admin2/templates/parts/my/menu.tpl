{* See parts/ini_menu.tpl and menu.ini for more info, or parts/setup/menu.tpl for full example *}
{include uri='design:parts/ini_menu.tpl' ini_section='Leftmenu_my' i18n_hash=hash(
    '_my_account',         'My account'|i18n( 'design/admin/parts/my/menu' ),
    '_my_drafts',          'My drafts'|i18n( 'design/admin/parts/my/menu' ),
    '_my_pending',         'My pending items'|i18n( 'design/admin/parts/my/menu' ),
    '_my_notifications',   'My notification settings'|i18n( 'design/admin/parts/my/menu' ),
    '_my_bookmarks',       'My bookmarks'|i18n( 'design/admin/parts/my/menu' ),
    '_collaboration',      'Collaboration'|i18n( 'design/admin/parts/my/menu' ),
    '_change_password',    'Change password'|i18n( 'design/admin/parts/my/menu' ),
    '_my_shopping_basket', 'My shopping basket'|i18n( 'design/admin/parts/my/menu' ),
    '_my_wish_list',       'My wish list'|i18n( 'design/admin/parts/my/menu' ),
    '_edit_profile',       'Edit profile'|i18n( 'design/admin/parts/my/menu' ),
    '_dashboard',          'Dashboard'|i18n( 'design/admin/parts/my/menu' ),
)}


{def $custom_root_node = fetch( 'content', 'node', hash( 'node_id', 1 ) )}
{if $custom_root_node.can_read}
<div id="content-tree">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h4>{'Site structure'|i18n( 'design/admin/parts/content/menu' )}</h4>
{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{* Treemenu. *}
<div id="contentstructure">
{if ezini('TreeMenu','Dynamic','contentstructuremenu.ini')|eq('enabled')}
    {include uri='design:contentstructuremenu/content_structure_menu_dynamic.tpl' custom_root_node=$custom_root_node menu_persistence=false() hide_node_list=array(ezini( 'NodeSettings', 'DesignRootNode', 'content.ini'), ezini( 'NodeSettings', 'SetupRootNode', 'content.ini'))}
{else}
    {include uri='design:contentstructuremenu/content_structure_menu.tpl' custom_root_node_id=1}
{/if}
</div>

{* DESIGN: Content END *}</div></div></div>
</div>
{/if}

{* Left menu width control. *}
<div id="widthcontrol-links" class="widthcontrol">
<p>
{switch match=ezpreference( 'admin_left_menu_size' )}
    {case match='medium'}
    <a href={'/user/preferences/set/admin_left_menu_size/small'|ezurl} title="{'Change the left menu width to small size.'|i18n( 'design/admin/parts/user/menu' )}">{'Small'|i18n( 'design/admin/parts/user/menu' )}</a>
    <span class="current">{'Medium'|i18n( 'design/admin/parts/user/menu' )}</span>
    <a href={'/user/preferences/set/admin_left_menu_size/large'|ezurl} title="{'Change the left menu width to large size.'|i18n( 'design/admin/parts/user/menu' )}">{'Large'|i18n( 'design/admin/parts/user/menu' )}</a>
    {/case}

    {case match='large'}
    <a href={'/user/preferences/set/admin_left_menu_size/small'|ezurl} title="{'Change the left menu width to small size.'|i18n( 'design/admin/parts/user/menu' )}">{'Small'|i18n( 'design/admin/parts/user/menu' )}</a>
    <a href={'/user/preferences/set/admin_left_menu_size/medium'|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'design/admin/parts/user/menu' )}">{'Medium'|i18n( 'design/admin/parts/user/menu' )}</a>
    <span class="current">{'Large'|i18n( 'design/admin/parts/user/menu' )}</span>
    {/case}

    {case in=array( 'small', '' )}
    <span class="current">{'Small'|i18n( 'design/admin/parts/user/menu' )}</span>
    <a href={'/user/preferences/set/admin_left_menu_size/medium'|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'design/admin/parts/user/menu' )}">{'Medium'|i18n( 'design/admin/parts/user/menu' )}</a>
    <a href={'/user/preferences/set/admin_left_menu_size/large'|ezurl} title="{'Change the left menu width to large size.'|i18n( 'design/admin/parts/user/menu' )}">{'Large'|i18n( 'design/admin/parts/user/menu' )}</a>
    {/case}

    {case}
    <a href={'/user/preferences/set/admin_left_menu_size/small'|ezurl} title="{'Change the left menu width to small size.'|i18n( 'design/admin/parts/user/menu' )}">{'Small'|i18n( 'design/admin/parts/user/menu' )}</a>
    <a href={'/user/preferences/set/admin_left_menu_size/medium'|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'design/admin/parts/user/menu' )}">{'Medium'|i18n( 'design/admin/parts/user/menu' )}</a>
    <a href={'/user/preferences/set/admin_left_menu_size/large'|ezurl} title="{'Change the left menu width to large size.'|i18n( 'design/admin/parts/user/menu' )}">{'Large'|i18n( 'design/admin/parts/user/menu' )}</a>
    {/case}
{/switch}
</p>
</div>

{* This is the border placed to the left for draging width, js will handle disabling the one above and enabling this *}
<div id="widthcontrol-handler" class="hide">
<div class="widthcontrol-grippy"></div>
</div>

<!-- script language="javascript" type="text/javascript" src={"javascript/leftmenu_widthcontrol.js"|ezdesign} charset="utf-8"></script -->
