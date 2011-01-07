<div id="header-logo">
{if $ui_context_edit}
    <span title="eZ Publish {fetch( 'setup', 'version' )}">&nbsp;</span>
{else}
    <a href="{ezini('SiteSettings', 'DefaultPage', 'site.ini')|ezurl( 'no' )}" title="eZ Publish {fetch( 'setup', 'version' )}">&nbsp;</a>
{/if}
</div>

<div id="header-search">
{include uri='design:page_search.tpl'}
</div>

<div id="header-usermenu">
{if $ui_context_edit}
    <span title="{'Logout from the system.'|i18n( 'design/admin/pagelayout' )}" id="header-usermenu-logout" class="disabled">{'Logout'|i18n( 'design/admin/pagelayout' )}</span>
{else}
    <a href={'/user/logout'|ezurl} title="{'Logout from the system.'|i18n( 'design/admin/pagelayout' )}" id="header-usermenu-logout">{'Logout'|i18n( 'design/admin/pagelayout' )}</a>
{/if}
</div>

