<form method="post" action={concat('/design/templateedit/',$template)|ezurl}>
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{"Template edit"|i18n("design/standard/design/templateadmin")} {$template}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">
<div class="block">

<textarea class="box" name="TemplateContent" cols="40" rows="30">{$template_content|wash(xhtml)}</textarea>

</div>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<input class="button" type="submit" value="{"Save"|i18n("design/standard/design/templateadmin")}" name="SaveButton" />
<input class="button" type="submit" value="{"Discard"|i18n("design/standard/design/templateadmin")}" name="DiscardButton" />

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>
