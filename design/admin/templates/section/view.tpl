<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'section'|icon( 'normal', 'Section'|i18n( 'design/admin/section/view' ) )}&nbsp;{'%section_name [Section]'|i18n( '/design/admin/section/view',, hash( '%section_name', $section.name ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name'|i18n( 'design/admin/section/view' )}</label>
{$section.name}
</div>

<div class="block">
<label>{'ID'|i18n( 'design/admin/section/view' )}</label>
{$section.id}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<form action={concat( '/section/edit/', $section.id )|ezurl} method="post">
<input class="button" type="submit" name="" value="{'Edit'|i18n( 'design/admin/section/view' )}" />
</form>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

