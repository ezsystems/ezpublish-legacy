{literal}
<script language="JavaScript1.2" type="text/javascript">
<!--
function toggleCheckboxes( formname, checkboxname )
{
    with( formname )
        {
        for( var i=0; i<elements.length; i++ )
        {
            if( elements[i].type == 'checkbox' && elements[i].name == checkboxname && elements[i].disabled == "" )
            {
                if( elements[i].checked == true )
                {
                    elements[i].checked = false;
                }
                else
                {
                    elements[i].checked = true;
                }
            }
            }
    }
}
//-->
</script>
{/literal}

<form name="pdfexportlist" action={"pdf/list"|ezurl} method="post" name="PDFList">

<div class="context-block">
<h2 class="context-title">{'PDF Exports [%export_count]'|i18n( 'design/admin/pdf/list',, hash( '%export_count', $pdfexport_list|count ) )}</h2>

<table class="list" cellspacing="0">
{section show=$pdfexport_list}
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="toggleCheckboxes( document.pdfexportlist, 'DeleteIDArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/trash' )}" /></th>
    <th>{'Name'|i18n( 'design/standard/pdf/list' )}</th>
    <th>{'Modifier'|i18n( 'design/standard/pdf/list' )}</th>
    <th>{'Modified'|i18n( 'design/standard/pdf/list' )}</th>
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

{* Buttons. *}
<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="RemoveExportButton" value="{'Remove selected'|i18n( 'design/standard/pdf/list' )}" {section show=$pdfexport_list|not}disabled="disabled"{/section} />
    <input class="button" type="submit" name="NewPDFExport" value="{'New PDF export'|i18n( 'design/standard/pdf/list' )}" />
</div>
</div>

</div>

</form>

