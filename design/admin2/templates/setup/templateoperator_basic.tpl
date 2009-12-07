<form method="post" action={'setup/templateoperator'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Template operator wizard (step 2 of 3)'|i18n( 'design/admin/setup/rad/templateoperator' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name'|i18n( 'design/admin/setup/rad/templateoperator' )}:</label>
<input class="box" type="text" name="Name" value="" />
</div>

<div class="block">
<label>{'Handles input'|i18n( 'design/admin/setup/rad/templateoperator' )}:</label>
<input type="checkbox" name="InputCheck" value="1" checked="checked" />
</div>

<div class="block">
<label for="ezsetup_radtemplateoperator_output">{'Generates output'|i18n( 'design/admin/setup/rad/templateoperator' )}:</label>
<input id="ezsetup_radtemplateoperator_output" type="checkbox" name="OutputCheck" value="1" checked="checked" />
</div>

<div class="block">
<label>{'Parameters'|i18n( 'design/admin/setup/rad/templateoperator' )}:</label>
<select name="Parameter">
    <option value="1">{'No parameters'|i18n( 'design/admin/setup/rad/templateoperator' )}</option>
    <option value="2">{'Named parameters'|i18n( 'design/admin/setup/rad/templateoperator' )}</option>
    <option value="3">{'Sequential parameters'|i18n( 'design/admin/setup/rad/templateoperator' )}</option>
    <option value="4">{'Custom'|i18n( 'design/admin/setup/rad/templateoperator' )}</option>
</select>
</div>


</div>
{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input type="hidden" value="describe" name="OperatorStep" />
<input class="button" type="submit" name="TemplateOperatorRestartButton" value="{'Restart'|i18n( 'design/admin/setup/rad/templateoperator' )}" />
<input class="button" type="submit" name="TemplateOperatorStepButton" value="{'Next'|i18n( 'design/admin/setup/rad/templateoperator' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

{section name=Persistence loop=$persistent_data}
<input type="hidden" name="PersistentData[{$:key|wash}]" value="{$:item|wash}" />
{/section}

</div>
</form>

