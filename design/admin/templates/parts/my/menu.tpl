<ul>
    <li><a href={'/content/draft/'|ezurl}>{'My drafts'|i18n("design/admin/layout")}</a></li>
    <li><a href={'/content/pendinglist/'|ezurl}>{'My pending list'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={'/notification/settings/'|ezurl}>{'My notification settings'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={'/content/bookmark/'|ezurl}>{'My bookmarks'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={'/collaboration/view/summary'|ezurl}>{'Collaboration'|i18n( 'design/admin/parts/my/menu' )}</a></li>
    <li><a href={concat( '/user/password/', $current_user.contentobject_id, '/')|ezurl}>{"Change password"|i18n( 'design/admin/parts/my/menu' )}</a></li>
</ul>
