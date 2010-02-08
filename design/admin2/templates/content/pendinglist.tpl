{let item_type=ezpreference( 'admin_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     list_count=fetch( content, pending_count )}

<div class="context-block content-pendinglist">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'My pending items [%pending_count]'|i18n( 'design/admin/content/pendinglist',, hash( '%pending_count', $list_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{if $list_count}
{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="button-left">
    <p class="table-preferences">
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/admin_list_limit/1/content/pendinglist'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_list_limit/3/content/pendinglist'|ezurl}>50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_list_limit/1/content/pendinglist'|ezurl}>10</a>
        <a href={'/user/preferences/set/admin_list_limit/2/content/pendinglist'|ezurl}>25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_list_limit/2/content/pendinglist'|ezurl}>25</a>
        <a href={'/user/preferences/set/admin_list_limit/3/content/pendinglist'|ezurl}>50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="float-break"></div>
</div>

<table class="list" cellspacing="0">
<tr>
    <th>{'Name'|i18n( 'design/admin/content/pendinglist' )}</th>
    <th>{'Type'|i18n( 'design/admin/content/pendinglist' )}</th>
    <th>{'Section'|i18n( 'design/admin/content/pendinglist' )}</th>
    <th>{'Modified'|i18n( 'design/admin/content/pendinglist' )}</th>
</tr>

{section var=PendingItems loop=fetch( content, pending_list, hash( limit, $number_of_items, offset, $view_parameters.offset ) ) sequence=array(bglight,bgdark)}
<tr class="{$PendingItems.sequence}">
    <td>{$PendingItems.item.contentobject.content_class.identifier|class_icon( small, $PendingItems.item.contentobject.content_class.name|wash )}&nbsp;<a href={concat( '/content/versionview/', $PendingItems.item.contentobject.id, '/', $PendingItems.item.version, '/' )|ezurl}>{$PendingItems.item.version_name|wash}</a></td>
    <td>{$PendingItems.item.contentobject.content_class.name|wash}</td>
    <td>{let section_object=fetch( section, object, hash( section_id, $PendingItems.item.contentobject.section_id ) )}{section show=$section_object}{$section_object.name|wash}{section-else}<i>{'Unknown'|i18n( 'design/admin/content/pendinglist' )}</i>{/section}{/let}</td>
    <td>{$PendingItems.item.modified|l10n( shortdatetime )}</td>
</tr>
{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/content/pendinglist'
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{else}
<div class="block">
<p>{'The pending list is empty.'|i18n( 'design/admin/content/pendinglist' )}</p>
</div>
{/if}

{* DESIGN: Content END *}</div></div></div>

</div>
{/let}
