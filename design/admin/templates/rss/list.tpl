{* Export window. *}
<form name="rssexportslist" method="post" action={'rss/list'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'RSS exports [%exports_count]'|i18n( 'design/admin/rss/list',, hash( '%exports_count', $rssexport_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$rssexport_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection'|i18n( 'design/admin/rss/list' )}" onclick="ezjs_toggleCheckboxes( document.rssexportslist, 'DeleteIDArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/rss/list' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Version'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Status'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Modified'|i18n( 'design/admin/rss/list' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=RSSExports loop=$rssexport_list sequence=array( bglight, bgdark )}
<tr class="{$RSSExports.sequence}">

    {* Remove. *}
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$RSSExports.item.id}" title="{'Select RSS export for removal.'|i18n( 'design/admin/rss/list' )}" /></td>

    {* Name. *}
    <td>{if $RSSExports.item.active|eq( 1 )} <a href={concat( 'rss/feed/', $RSSExports.item.access_url )|ezurl}>{$RSSExports.item.title|wash}</a>{else}{$RSSExports.item.title|wash}{/if}</td>

    {* Version. *}
    <td>{$RSSExports.item.rss_version|wash}</td>

    {* Status. *}
    <td>{if $RSSExports.item.active|eq( 1 )}{'Active'|i18n( 'design/admin/rss/list' )}{else}{'Inactive'|i18n( 'design/admin/rss/list' )}{/if}</td>

    {* Modifier. *}
    <td><a href={$RSSExports.item.modifier.contentobject.main_node.url_alias|ezurl}>{$RSSExports.item.modifier.contentobject.name|wash}</a></td>

    {* Modified. *}
    <td>{$RSSExports.item.modified|l10n( shortdatetime )}</td>

    {* Edit. *}
    <td><a href={concat( 'rss/edit_export/', $RSSExports.item.id )|ezurl}><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/rss/list' )}" title="{'Edit the <%name> RSS export.'|i18n('design/admin/rss/list',, hash( '%name', $RSSExports.item.title) )|wash}" /></a></td>

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
    <input type="submit" name="RemoveExportButton" value="{'Remove selected'|i18n( 'design/admin/rss/list' )}" title="{'Remove selected RSS exports.'|i18n( 'design/admin/rss/list' ) }" {if $rssexport_list|not}class="button-disabled" disabled="disabled"{else}class="button"{/if}
/>
    <input class="button" type="submit" name="NewExportButton" value="{'New export'|i18n( 'design/admin/rss/list' )}" title="{'Create a new RSS export.'|i18n( 'design/admin/rss/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>



{* Import window. *}
<form name="rssimportslist" method="post" action={'rss/list'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'RSS imports [%imports_count]'|i18n( 'design/admin/rss/list',, hash( '%imports_count', $rssimport_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$rssimport_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection'|i18n( 'design/admin/rss/list' )}" onclick="ezjs_toggleCheckboxes( document.rssimportslist, 'DeleteIDArrayImport[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/rss/list' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Status'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/rss/list' )}</th>
    <th>{'Modified'|i18n( 'design/admin/rss/list' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=RSSImports loop=$rssimport_list sequence=array( bglight, bgdark )}
<tr class="{$RSSImports.sequence}">

    {* Remove. *}
    <td><input type="checkbox" name="DeleteIDArrayImport[]" value="{$RSSImports.item.id}" title="{'Select RSS import for removal.'|i18n( 'design/admin/rss/list' )}" /></td>

    {* Name. *}
    <td>{$RSSImports.item.name|wash}</td>

    {* Status. *}
    <td>{if $RSSImports.item.active|eq(1)}{'Active'|i18n( 'design/admin/rss/list' )}{else}{'Inactive'|i18n( 'design/admin/rss/list' )}{/if}</td>

    {* Modifier. *}
    <td><a href={$RSSImports.item.modifier.contentobject.main_node.url_alias|ezurl}>{$RSSImports.item.modifier.contentobject.name|wash}</a></td>

    {* Modified. *}
    <td>{$RSSImports.item.modified|l10n( shortdatetime )}</td>

    {* Edit. *}
    <td><a href={concat( 'rss/edit_import/', $RSSImports.item.id )|ezurl}><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/rss/list' )}" title="{'Edit the <%name> RSS import.'|i18n('design/admin/rss/list',, hash( '%name', $RSSImports.item.name) )|wash }" /></a></td>

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
    <input {if $rssimport_list|count}class="button"{else}class="button-disabled" disabled="disabled"{/if} type="submit" name="RemoveImportButton" value="{'Remove selected'|i18n( 'design/admin/rss/list' )}" title="{'Remove selected RSS imports.'|i18n( 'design/admin/rss/list' ) }" />
    <input class="button" type="submit" name="NewImportButton" value="{'New import'|i18n( 'design/admin/rss/list' )}" title="{'Create a new RSS import.'|i18n( 'design/admin/rss/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
