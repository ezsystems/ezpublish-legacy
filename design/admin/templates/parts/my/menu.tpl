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
</ul>

{* DESIGN: Content END *}</div></div></div></div></div></div>
