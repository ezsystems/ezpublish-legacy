<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'RSS exports [%exports_count]'|i18n( 'design/admin/rss/list',, hash( '%exports_count', $rssexport_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<form name="rssexportslist" action={'rss/list'|ezurl} method="post" name="RSSExport">

{section show=$rssexport_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.rssexportslist, 'DeleteIDArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/trash' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Version'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Status'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Modified'|i18n( 'design/admin/rss/list' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=RSSExports loop=$rssexport_list sequence=array( bglight, bgdark )}
<tr class="{$RSSExports.sequence}">
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$RSSExports.item.id}"></td>
    <td><a href={concat("rss/feed/",$RSSExports.item.access_url)|ezurl}>{$RSSExports.item.title|wash}</a></td>
    <td>{$RSSExports.item.rss_version|wash}</td>
    <td>{section show=$RSSExports.item.active|eq(1)}{"Active"|i18n( 'design/admin/rss/list' )}{section-else}{"Inactive"|i18n( 'design/admin/rss/list' )}{/section}</td>
    <td>{content_view_gui view=text_linked content_object=$RSSExports.item.modifier.contentobject}</td>
    <td>{$RSSExports.item.modified|l10n( shortdatetime )}</td>
    <td><a href={concat("rss/edit_export/",$RSSExports.item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'The RSS export list is empty.'|i18n( 'design/admin/rss/list' )}</p>
</div>
{/section}


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
{section show=$rssexport_list|count}
<input class="button" type="submit" name="RemoveExportButton" value="{'Remove selected'|i18n( 'design/admin/rss/list' )}" />
{section-else}
<input class="button" type="submit" name="RemoveExportButton" value="{'Remove selected'|i18n( 'design/admin/rss/list' )}" disabled="disabled" />
{/section}

<input class="button" type="submit" name="NewExportButton" value="{'New export'|i18n( 'design/admin/rss/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>


<form name="rssimportslist" action={'rss/list'|ezurl} method="post" name="RSSImport">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'RSS imports [%imports_count]'|i18n( 'design/admin/rss/list',, hash( '%imports_count', $rssimport_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$rssimport_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.rssimportslist, 'DeleteIDArrayImport[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/trash' )}" /></th>
    <th>{"Name"|i18n( 'design/admin/rss/list' )}</th>
    <th>{"Status"|i18n( 'design/admin/rss/list' )}</th>
    <th>{"Modifier"|i18n( 'design/admin/rss/list' )}</th>
    <th>{"Modified"|i18n( 'design/admin/rss/list' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=RSSImports loop=$rssimport_list sequence=array( bglight, bgdark )}
<tr class="{$RSSImports.sequence}">
    <td><input type="checkbox" name="DeleteIDArrayImport[]" value="{$RSSImports.item.id}"></td>
    <td>{$RSSImports.item.name|wash}</td>
    <td>{section show=$RSSImports.item.active|eq(1)}{"Active"|i18n( 'design/admin/rss/list' )}{section-else}{"Inactive"|i18n( 'design/admin/rss/list' )}{/section}</td>
    <td>{content_view_gui view=text_linked content_object=$RSSImports.item.modifier.contentobject}</td>
    <td>{$RSSImports.item.modified|l10n( shortdatetime )}</td>
    <td><a href={concat( 'rss/edit_import/', $RSSImports.item.id )|ezurl}><img class="button" src={'edit.png'|ezimage} width="16" height="16" alt="Edit" /></a></td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'The RSS import list is empty.'|i18n( 'design/admin/rss/list' )}</p>
</div>
{/section}


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
{section show=$rssimport_list|count}
<input class="button" type="submit" name="RemoveImportButton" value="{'Remove selected'|i18n( 'design/admin/rss/list' )}" />
{section-else}
<input class="button" type="submit" name="RemoveImportButton" value="{'Remove selected'|i18n( 'design/admin/rss/list' )}" disabled="disabled" />
{/section}

<input class="button" type="submit" name="NewImportButton" value="{'New import'|i18n( 'design/admin/rss/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
