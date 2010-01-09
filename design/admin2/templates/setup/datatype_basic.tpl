<form method="post" action={'setup/datatype'|ezurl}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Datatype wizard (step 2 of 3)'|i18n( 'design/admin/setup/rad/datatype' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name'|i18n( 'design/admin/setup/rad/datatype' )}:</label>
<input class="box" type="text" name="Name" value="" />
</div>

<div class="block">
<label>{'Descriptive name'|i18n( 'design/admin/setup/rad/datatype' )}:</label>
<input class="box" type="text" name="DescName" value="" />
</div>

<div class="block">
<label for="ezsetup_raddatatype_classinput">{'Handle input on class level'|i18n( 'design/admin/setup/rad/datatype' )}:</label>
<input id="ezsetup_raddatatype_classinput" type="checkbox" name="ClassInput" value="1" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input type="hidden" value="describe" name="OperatorStep" />
<input class="button" type="submit" name="DatatypeRestartButton" value="{'Restart'|i18n( 'design/admin/setup/rad/datatype' )}" />
<input class="button" type="submit" name="DatatypeStepButton" value="{'Next'|i18n( 'design/admin/setup/rad/datatype' )}" />
{* DESIGN: Control bar END *}</div></div>
</div>
</div>

{section name=Persistence loop=$persistent_data}
<input type="hidden" name="PersistentData[{$:key|wash}]" value="{$:item|wash}" />
{/section}

</form>
