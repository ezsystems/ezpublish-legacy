<form name="pdfexportlist" method="post" action={'pdf/list'|ezurl}>

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'PDF exports [%export_count]'|i18n( 'design/admin/pdf/list',, hash( '%export_count', $pdfexport_list|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$pdfexport_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/pdf/list' )}" onclick="ezjs_toggleCheckboxes( document.pdfexportlist, 'DeleteIDArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/pdf/list' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/pdf/list' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/pdf/list' )}</th>
    <th>{'Modified'|i18n( 'design/admin/pdf/list' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=PDFExports loop=$pdfexport_list sequence=array( bglight, bgdark )}
<tr class="{$PDFExports.sequence}">

    {*Remove. *}
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$PDFExports.item.id}" title="{'Select PDF export for removal.'|i18n( 'design/admin/pdf/list' )}" /></td>

    {* Name. *}
    <td>{'pdfexport'|icon( 'small', 'PDF Export'|i18n( 'design/admin/pdf/list' ) )}&nbsp;
    {section show=$PDFExports.item.status|eq(1)}
      {if and( is_set( $PDFExports.item.source_node ), $PDFExports.item.source_node )}
           <a href={$PDFExports.item.filepath|ezroot}>
      {/if}
      {$PDFExports.item.title|wash}
      {if and( is_set( $PDFExports.item.source_node ), $PDFExports.item.source_node )}
          </a>
      {/if}
    {section-else show=$PDFExports.item.status|eq(2)}
      <a href={concat('pdf/edit/', $PDFExports.item.id, '/generate')|ezurl}>{$PDFExports.item.title|wash}</a>
    {/section}
    </td>

    {* Modifier. *}
    <td><a href={$PDFExports.item.modifier.contentobject.main_node.url_alias|ezurl}>{$PDFExports.item.modifier.contentobject.name|wash}</a></td>

    {* Modified. *}
    <td>{$PDFExports.item.modified|l10n( shortdatetime )}</td>

    {* Edit. *}
    <td><a href={concat( 'pdf/edit/', $PDFExports.item.id )|ezurl}><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/pdf/list' )}" title="{'Edit the <%pdf_export_name> PDF export.'|i18n( 'design/admin/pdf/list',, hash( '%pdf_export_name', $PDFExports.item.title ) )|wash}" /></a></td>

</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'There are no PDF exports in the list.'|i18n( 'design/admin/pdf/list' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
{if $pdfexport_list}
    <input class="button" type="submit" name="RemoveExportButton" value="{'Remove selected'|i18n( 'design/admin/pdf/list' )}" title="{'Remove selected PDF exports.'|i18n( 'design/admin/pdf/list' )}" />
{else}
    <input class="button-disabled" type="submit" name="RemoveExportButton" value="{'Remove selected'|i18n( 'design/admin/pdf/list' )}" disabled="disabled" />
{/if}

<input class="button" type="submit" name="NewPDFExport" value="{'New PDF export'|i18n( 'design/admin/pdf/list' )}" title="{'Create a new PDF export.'|i18n( 'design/admin/pdf/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
