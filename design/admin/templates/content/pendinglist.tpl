{let item_type=ezpreference( 'items' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     list_count=fetch( content, pending_count )
     pending_list=fetch( content, pending_list, hash( limit, $number_of_items, offset, $view_parameters.offset ) )}

<form name="pendinglistaction" action={concat( 'content/pendinglist/' )|ezurl} method="post" >

<div class="context-block">
<h2 class="context-title">{'My pending list [%pending_count]'|i18n( 'design/admin/content/pendinglist',, hash( '%pending_count', $pending_list|count ) )}</h2>

{section show=$pending_list}
{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/items/1'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/items/3'|ezurl}>50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/items/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/items/2'|ezurl}>25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/items/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/items/3'|ezurl}>50</a>
        {/case}

        {/switch}
    </p>
</div>
</div>
</div>

<table class="list" cellspacing="0">
<tr>
    <th>{'Name'|i18n( 'design/admin/content/pendinglist' )}</th>
    <th>{'Class'|i18n( 'design/admin/content/pendinglist' )}</th>
    <th>{'Section'|i18n( 'design/admin/content/pendinglist' )}</th>
    <th>{'Version'|i18n( 'design/admin/content/pendinglist' )}</th>
    <th>{'Modified'|i18n( 'design/admin/content/pendinglist' )}</th>
</tr>

{section var=PendingItems loop=$pending_list sequence=array(bglight,bgdark)}
<tr class="{$PendingItems.sequence}">
    <td>{$PendingItems.item.contentobject.content_class.identifier|class_icon( small, $PendingItems.item.contentobject.content_class.name )}&nbsp;<a href={concat( '/content/versionview/', $PendingItems.item.contentobject.id, '/', $PendingItems.item.version, '/' )|ezurl}>{$PendingItems.item.contentobject.name|wash}</a></td>
    <td>{$PendingItems.item.contentobject.content_class.name|wash}</td>
    <td>{$PendingItems.item.contentobject.section_id}</td>
    <td>{$PendingItems.item.version}</td>
    <td>{$PendingItems.item.modified|l10n( datetime )}</td>
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

{section-else}

<p>{'The pending list is empty.'|i18n( 'design/admin/content/pendinglist' )}</p>

{/section}

</div>