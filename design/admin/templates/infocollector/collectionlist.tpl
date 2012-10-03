<form name="collections" method="post" action={concat( '/infocollector/collectionlist/', $object.id )|ezurl}>

{let number_of_items=min( ezpreference( 'admin_infocollector_list_limit' ), 3)|choose( 10, 10, 25, 50 )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Information collected by <%object_name> (%collection_count)'|i18n( 'design/admin/infocollector/collectionlist',, hash( '%object_name', $object.name, '%collection_count', $collection_count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$collection_array}
{* Items per page selector. *}
<div class="context-toolbar">
<div class="button-left">
<p class="table-preferences">
{switch match=$number_of_items}
{case match=25}
<a href={'/user/preferences/set/admin_infocollector_list_limit/1'|ezurl}>10</a>
<span class="current">25</span>
<a href={'/user/preferences/set/admin_infocollector_list_limit/3'|ezurl}>50</a>
{/case}

{case match=50}
<a href={'/user/preferences/set/admin_infocollector_list_limit/1'|ezurl}>10</a>
<a href={'/user/preferences/set/admin_infocollector_list_limit/2'|ezurl}>25</a>
<span class="current">50</span>
{/case}

{case}
<span class="current">10</span>
<a href={'/user/preferences/set/admin_infocollector_list_limit/2'|ezurl}>25</a>
<a href={'/user/preferences/set/admin_infocollector_list_limit/3'|ezurl}>50</a>
{/case}

{/switch}
</p>
</div>
<div class="float-break"></div>
</div>

{* Collection table. *}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="{'Invert selection.'|i18n( 'design/admin/infocollector/collectionlist' )}" title="{'Invert selection.'|i18n( 'design/admin/infocollector/collectionlist' )}" onclick="ezjs_toggleCheckboxes( document.collections, 'CollectionIDArray[]' ); return false;" /></th>
    <th class="tight">{'Collection ID'|i18n( 'design/admin/infocollector/collectionlist' )}</th>
    <th>{'Created'|i18n( 'design/admin/infocollector/collectionlist' )}</th>
    <th>{'Modified'|i18n( 'design/admin/infocollector/collectionlist' )}</th>
    <th>{'Creator'|i18n( 'design/admin/infocollector/collectionlist' )}</th>
</tr>
{section var=Collections loop=$collection_array sequence=array( bglight, bgdark )}
<tr class="{$Collections.sequence}">
    <td><input type="checkbox" name="CollectionIDArray[]" value="{$Collections.item.id}" title="{'Select collected information for removal.'|i18n( 'design/admin/infocollector/collectionlist' )}" /></td>
    <td class="number" align="right"><a href={concat( '/infocollector/view/', $Collections.item.id )|ezurl}>{$Collections.item.id}</a></td>
    <td>{$Collections.item.created|l10n( shortdatetime )}</td>
    <td>{$Collections.item.modified|l10n( shortdatetime )}</td>
    <td>{if $Collections.item.creator} {$Collections.item.creator.contentobject.name|wash} {else} {'Unknown user'|i18n( 'design/admin/infocollector/collectionlist' )} {/if}</td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'No information has been collected by this object.'|i18n( 'design/admin/infocollector/collectionlist' )}</p>
</div>

{/section}

{* Navigator. *}
<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/infocollector/collectionlist/', $object.id )
         item_count=$collection_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>
{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
{if $collection_array}
<input class="button" type="submit" name="RemoveCollectionsButton" value="{'Remove selected'|i18n( 'design/admin/infocollector/collectionlist' )}" title="{'Remove selected collection.'|i18n( 'design/admin/infocollector/collectionlist' )}" />
{else}
<input class="button-disabled" type="submit" name="RemoveCollectionsButton" value="{'Remove selected'|i18n( 'design/admin/infocollector/collectionlist' )}" disabled="disabled" />
{/if}
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

{/let}

</form>
