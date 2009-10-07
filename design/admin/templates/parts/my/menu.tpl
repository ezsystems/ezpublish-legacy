{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'My account'|i18n( 'design/admin/parts/my/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<ul>
    <li><div><a href={'/content/draft/'|ezurl}>{'My drafts'|i18n( 'design/admin/parts/my/menu' )}</a></div></li>
    <li><div><a href={'/content/pendinglist/'|ezurl}>{'My pending items'|i18n( 'design/admin/parts/my/menu' )}</a></div></li>
    <li><div><a href={'/notification/settings/'|ezurl}>{'My notification settings'|i18n( 'design/admin/parts/my/menu' )}</a></div></li>
    <li><div><a href={'/content/bookmark/'|ezurl}>{'My bookmarks'|i18n( 'design/admin/parts/my/menu' )}</a></div></li>
    <li><div><a href={'/collaboration/view/summary'|ezurl}>{'Collaboration'|i18n( 'design/admin/parts/my/menu' )}</a></div></li>
    <li><div><a href={concat( '/user/password/', $current_user.contentobject_id, '/')|ezurl}>{'Change password'|i18n( 'design/admin/parts/my/menu' )}</a></div></li>
    <li><div><a href={'/shop/basket/'|ezurl}>{'My shopping basket'|i18n( 'design/admin/parts/my/menu' )}</a></div></li>
    <li><div><a href={'/shop/wishlist/'|ezurl}>{'My wish list'|i18n( 'design/admin/parts/my/menu' )}</a></div></li>
</ul>

{* DESIGN: Content END *}</div></div></div></div></div></div>

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Edit mode settings'|i18n( 'design/admin/parts/my/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="settings">
<ul>
    <li class="nobullet">{'Locations'|i18n( 'design/admin/parts/my/menu')}:
    {if ezpreference( 'admin_edit_show_locations' )}
        <span class="current">{'on'|i18n( 'design/admin/parts/my/menu' )}</span>&nbsp;<a href={'/user/preferences/set/admin_edit_show_locations/0'|ezurl} title="{'Disable location window when editing content.'|i18n( 'design/admin/parts/my/menu' )}">{'off'|i18n( 'design/admin/parts/my/menu' )}</a>
    {else}
        <a href={'/user/preferences/set/admin_edit_show_locations/1'|ezurl} title="{'Enable location window when editing content.'|i18n( 'design/admin/parts/my/menu' )}">{'on'|i18n( 'design/admin/parts/my/menu' )}</a>&nbsp;<span class="current">{'off'|i18n( 'design/admin/parts/my/menu' )}</span>
    {/if}
    </li>
    <li class="nobullet">{'Re-edit'|i18n( 'design/admin/parts/my/menu')}:
    {if ezpreference( 'admin_edit_show_re_edit' )}
        <span class="current">{'on'|i18n( 'design/admin/parts/my/menu' )}</span>&nbsp;<a href={'/user/preferences/set/admin_edit_show_re_edit/0'|ezurl} title="{'Disable &quot;Back to edit&quot; checkbox when editing content.'|i18n( 'design/admin/parts/my/menu' )}">{'off'|i18n( 'design/admin/parts/my/menu' )}</a>
    {else}
        <a href={'/user/preferences/set/admin_edit_show_re_edit/1'|ezurl} title="{'Enable &quot;Back to edit&quot; checkbox when editing content.'|i18n( 'design/admin/parts/my/menu' )}">{'on'|i18n( 'design/admin/parts/my/menu' )}</a>&nbsp;<span class="current">{'off'|i18n( 'design/admin/parts/my/menu' )}</span>
    {/if}
    </li>
</ul>
</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>
