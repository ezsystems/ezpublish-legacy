{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'My account'|i18n( 'design/admin/parts/my/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<ul>
    <li><a href={'/content/draft/'|ezurl}>{'My drafts'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={'/content/pendinglist/'|ezurl}>{'My pending list'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={'/notification/settings/'|ezurl}>{'My notification settings'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={'/content/bookmark/'|ezurl}>{'My bookmarks'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={'/collaboration/view/summary'|ezurl}>{'Collaboration'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={concat( '/user/password/', $current_user.contentobject_id, '/')|ezurl}>{'Change password'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={'/shop/basket/'|ezurl}>{'My shopping basket'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={'/shop/wishlist/'|ezurl}>{'My wish list'|i18n( 'design/admin/parts/my/menu' )}</a></li>

    <li>
    <li>{'Edit mode settings'}</li>
    <ul>
    <li>{'Relations'}:
    {section show=ezpreference( 'admin_edit_show_relations' )}
        <b>{'on'|i18n( 'design/admin/parts/my/menu' )}</b>&nbsp;<a href={'/user/preferences/set/admin_edit_show_relations/0'|ezurl} title="{'Hide related objects in edit mode.'|i18n( 'design/admin/node/view/full' )}">{'off'|i18n( 'design/admin/parts/my/menu' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_edit_show_relations/1'|ezurl} title="{'Show related objects in edit mode.'|i18n( 'design/admin/node/view/full' )}">{'on'|i18n( 'design/admin/parts/my/menu' )}</a>&nbsp;<b>{'off'|i18n( 'design/admin/parts/my/menu' )}</b>
    {/section}
    </li>

    <li>{'Locations'}:
    {section show=ezpreference( 'admin_edit_show_locations' )}
        <b>{'on'|i18n( 'design/admin/parts/my/menu' )}</b>&nbsp;<a href={'/user/preferences/set/admin_edit_show_locations/0'|ezurl} title="{'Hide locations in edit mode.'|i18n( 'design/admin/node/view/full' )}">{'off'|i18n( 'design/admin/parts/my/menu' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_edit_show_locations/1'|ezurl} title="{'Show locations in edit mode.'|i18n( 'design/admin/node/view/full' )}">{'on'|i18n( 'design/admin/parts/my/menu' )}</a>&nbsp;<b>{'off'|i18n( 'design/admin/parts/my/menu' )}</b>
    {/section}
    </li>

    </ul>

</ul>

{* DESIGN: Content END *}</div></div></div></div></div></div>
