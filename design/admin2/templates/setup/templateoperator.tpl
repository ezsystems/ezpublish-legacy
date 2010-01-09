<form method="post" action={'setup/templateoperator'|ezurl}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Template operator wizard (step 1 of 3)'|i18n( 'design/admin/setup/rad' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<p>
{'Welcome to the template operator wizard. Template operators are usually used for manipulating template variables. However, they can also be used to generate or fetch data. This wizard will take you through a couple of steps with some basic choices. When finished, eZ Publish will generate a PHP framework for a new operator (which will be available for download).'|i18n( 'design/admin/setup/rad' )}
</p>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input type="hidden" value="basic" name="OperatorStep" />
<input class="button" type="submit" name="" value="{'Restart'|i18n( 'design/admin/setup/rad' )}" disabled="disabled" />
<input class="button" type="submit" value="{'Next'|i18n( 'design/admin/setup/rad' )}" name="TemplateOperatorStepButton" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</form>
