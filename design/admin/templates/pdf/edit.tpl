<form action={concat( 'pdf/edit/', $pdf_export.id )|ezurl} method="post" name="ExportPDF">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'pdfexport'|icon( 'normal', 'PDF Export'|i18n( 'design/admin/pdf/edit' ) )}&nbsp;{'%pdf_export_title [PDF export]'|i18n( 'design/admin/pdf/edit',, hash( '%pdf_export_title', $pdf_export.title ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">
    {* Title. *}
    <div class="block">
        <label>{'Title'|i18n( 'design/admin/pdf/edit' )}</label>
        {include uri="design:gui/lineedit.tpl" id_name="Title" value=$pdf_export.title|wash }
    </div>

    {* Display frontpage. *}
    <div class="block">
        <label>{'Display frontpage'|i18n( 'design/admin/pdf/edit' )}</label>
        <input type="checkbox" name="DisplayFrontpage" {section show=$pdf_export.show_frontpage|eq(1)}checked="checked"{/section} />
    </div>

    {* Intro text. *}
    <div class="block">
        <label>{'Intro text'|i18n( 'design/admin/pdf/edit' )}</label>
        <textarea name="IntroText" cols="64" rows="3">{$pdf_export.intro_text|wash}</textarea>
    </div>

    {* Sub text. *}
    <div class="block">
        <label>{'Sub text'|i18n( 'design/admin/pdf/edit' )}</label>
        <textarea name="SubText" cols="64" rows="3">{$pdf_export.sub_text|wash}</textarea>
    </div>

    {* Source node. *}
    <div class="block">
        <label>{'Source node'|i18n( 'design/admin/pdf/edit' )}</label>
        <input type="text" readonly="readonly" size="45" value="{section show=$pdf_export.source_node}{$pdf_export.source_node..path_identification_string|wash}{/section}" maxlength="60" />
        {include uri='design:gui/button.tpl' id_name='ExportPDFBrowse' value='Browse'|i18n( 'design/admin/pdf/edit' )}
        <input type="hidden" name="SourceNode" value="{$pdf_export.source_node_id|wash}" />
    </div>

    {* Export structure. *}
    <div class="block">
        <label>{"Export structure"|i18n( 'design/admin/pdf/edit' )}</label>
        <input type="radio" name="ExportType" {section show=$pdf_export.export_structure|eq("tree")} checked="checked" {/section} value="tree">{"Tree"|i18n( 'design/admin/pdf/edit' )}</input><br />
        <input type="radio" name="ExportType" {section show=$pdf_export.export_structure|eq("tree")|not()} checked="checked" {/section} value="node">{"Node"|i18n( 'design/admin/pdf/edit' )}</input>
    </div>

    {* Export classes. *}
    <div class="block">
        <label>{"Export classes"|i18n( 'design/admin/pdf/edit' )}</label>
        <select name="ClassList[]" multiple="multiple" size="8">
            {section var=class loop=$export_class_array}
                <option value="{$class.item.id}"
                {section show=$pdf_export.export_classes|contains($class.item.id)}
                    selected="selected"
                {/section}
                >{$class.item.name|wash}</option>
            {/section}
         </select>
    </div>

    {* Export destination. *}
    <div class="block">
        <label>{'Export destination'|i18n( 'design/admin/pdf/edit' )}</label>
        <input type="radio" name="DestinationType" value="url" {section show=$export_type|eq(2)|not}checked="checked"{/section}>{"Export to URL"|i18n( 'design/admin/pdf/edit' )}</input>
        {include uri="design:gui/lineedit.tpl" id_name="DestinationFile" value=$pdf_export.pdf_filename|wash }
        <input type="radio" name="DestinationType" value="download" {section show=$export_type|eq(2)}checked="checked"{/section}>{"Export for direct download"|i18n( 'design/admin/pdf/edit' )}</input>
    </div>

</div>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="ExportPDFButton" value="{'OK'|i18n( 'design/admin/pdf/edit' )}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/pdf/edit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
