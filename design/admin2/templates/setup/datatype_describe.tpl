<form method="post" action={'setup/datatype'|ezurl}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Datatype wizard (step 3 of 3)'|i18n( 'design/admin/setup/rad/datatype' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name of class'|i18n( 'design/admin/setup/rad/datatype' )}:</label>
<input class="box" type="text" name="ClassName" value="{$class_name|wash}" />
</div>

<div class="block">
<label>{'Class constant name'|i18n( 'design/admin/setup/rad/datatype' )}:</label>
<input class="box" type="text" name="ConstantName" value="{$constant_name|wash}" />
</div>


<div class="block">
<label>{'The creator of the datatype'|i18n( 'design/admin/setup/rad/datatype' )}:</label>
<input class="box" type="text" name="CreatorName" value="{fetch(user,current_user).contentobject.name|wash}" />
</div>

<div class="block">
<label>{'Description'|i18n( 'design/admin/setup/rad/datatype' )}:</label>
<textarea class="box" name="Description" cols="60" rows="5">
{'Handles the datatype %datatype_name. By using %datatype_name you can ...'|i18n( 'design/admin/setup/rad/datatype',, hash( '%datatype_name', $datatype_name ) )}
</textarea>
<p>{'Hint: The first line will be used as the brief description. The rest will become operator documentation.'|i18n( 'design/admin/setup/rad/datatype' )}</p>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input type="hidden" value="download" name="OperatorStep" />
<input class="button" type="submit" name="DatatypeRestartButton" value="{'Restart'|i18n( 'design/admin/setup/rad/datatype' )}" />
<input class="button" type="submit" name="DatatypeStepButton" value="{'Finish and generate'|i18n( 'design/admin/setup/rad/datatype' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

{section name=Persistence loop=$persistent_data}
<input type="hidden" name="PersistentData[{$:key|wash}]" value="{$:item|wash}" />
{/section}

</form>
