<form name="pdfexportlist" action={"pdf/list"|ezurl} method="post" name="PDFList">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'PDF Exports [%export_count]'|i18n( 'design/admin/pdf/list',, hash( '%export_count', $pdfexport_list|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
{section show=$pdfexport_list}
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/pdf/list' )}" onclick="ezjs_toggleCheckboxes( document.pdfexportlist, 'DeleteIDArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/pdf/list' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/pdf/list' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/pdf/list' )}</th>
    <th>{'Modified'|i18n( 'design/admin/pdf/list' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=PDFExports loop=$pdfexport_list sequence=array( bglight, bgdark )}
<tr class="{$PDFExports.sequence}">
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$PDFExports.item.id}"></td>
    <td>{'pdfexport'|icon( 'small', 'PDF Export'|i18n( 'design/admin/pdf/list' ) )}&nbsp;
    {section show=$PDFExports.item.status|eq(1)}
      <a href={$PDFExports.item.filepath|ezroot}>{$PDFExports.item.title|wash}</a>
    {section-else show=$PDFExports.item.status|eq(2)}
      <a href={concat("pdf/edit/", $PDFExports.item.id, "/generate")|ezurl}>{$PDFExports.item.title|wash}</a>
    {/section}
    </td>
    <td>{content_view_gui view=text_linked content_object=$PDFExports.item.modifier.contentobject}</td>
    <td>{$PDFExports.item.modified|l10n( shortdatetime )}</td>
    <td><a href={concat( 'pdf/edit/', $PDFExports.item.id )|ezurl}><img class="button" src={'edit.png'|ezimage} width="16" height="16" alt="Edit" /></a></td>

</tr>
{/section}
{section-else}
    {'There are no PDF exports in the list.'|i18n( 'design/admin/pdf/list' )}
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="RemoveExportButton" value="{'Remove selected'|i18n( 'design/admin/pdf/list' )}" {section show=$pdfexport_list|not}disabled="disabled"{/section} />
    <input class="button" type="submit" name="NewPDFExport" value="{'New PDF export'|i18n( 'design/admin/pdf/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

