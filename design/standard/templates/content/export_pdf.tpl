<form action={"content/exportpdf"|ezurl} method="post" name="ExportPDF">

{let node=fetch(content, node, hash(node_id,$export_node_id))} 

<table class="layout" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Main part start -->
    <div class="maincontentheader">
    <h1>{"PDF Export"|i18n("design/standard/content")}</h1>
    </div>

    <div class="block">

    <label>{"Source node"|i18n("design/standard/content")}</label><div class="labelbreak"></div>
    <input type="text" readonly="readonly" size="45" value="{section show=$node}{$node.path_identification_string|wash}{/section}" maxlength="60" />
    {include uri="design:gui/button.tpl" id_name="ExportPDFBrowse" value="Browse"|i18n("design/standard/content")}
    <input type="hidden" name="export_pdf_node_id" value="{$node.node_id|wash}" />
    <br/>

    <label>{"Export structure"|i18n("design/standard/content")}</label><div class="labelbreak"></div>
    <input type="radio" name="export_pdf_type" checked="checked" value="tree">{"Tree"|i18n("design/standard/content")}</input>
    <br/>
    <input type="radio" name="export_pdf_type" value="node">{"Node"|i18n("design/standard/content")}</input>
    <br/>
    
    <label>{"Export classes"|i18n("design/standard/content")}</label><div class="labelbreak"></div>
    <select name="export_pdf_class_list[]" multiple="multiple" size="8">
    {section var=class loop=$export_class_array}
      <option value="{$class.item.id}" selected="selected">{$class.item.name|wash}</option>
    {/section}
    </select>
    <br/>

    <label>{"Site access"|i18n("design/standard/content")}</label><div class="labelbreak"></div>
    <select name="export_pdf_site_access" size="1">
    {section var=site_access loop=$export_site_access}
      <option value="{$site_access.index}">{$site_access.item|wash}</option>
    {/section}
    </select>
    <br/>

    <label>{"Export destination"|i18n("design/standard/content")}</label><div class="labelbreak"></div>
    <input type="radio" name="export_pdf_destination" value="url" checked="checked">{"Export to URL"|i18n("design/standard/content")}</input>
    {include uri="design:gui/lineedit.tpl" id_name="export_pdf_destination_url" value="/pdf/pdfexport.pdf"}
    <br/>
    <input type="radio" name="export_pdf_destination" value="download">{"Export for direct download"|i18n("design/standard/content")}</input>
    </br>

    {include uri="design:gui/button.tpl" id_name="ExportPDFExport" value="Export"|i18n("design/standard/content") }

    </td>
</tr>

</table>

{/let}

</form>