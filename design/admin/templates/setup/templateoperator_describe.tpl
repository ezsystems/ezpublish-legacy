<form method="post" action={'setup/templateoperator'|ezurl}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Template operator wizard (step 3 of 3)'|i18n( 'design/admin/setup/rad/templateoperator' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Class name'|i18n( 'design/admin/setup/rad/templateoperator' )}:</label>
<input class="box" type="text" name="ClassName" value="{$class_name|wash}" />
</div>

<div class="block">
<label>{'Author'|i18n( 'design/admin/setup/rad/templateoperator' )}:</label>
<input class="box" type="text" name="CreatorName" value="{fetch(user,current_user).contentobject.name|wash}" />
</div>

<div class="block">
<label>{'Description'|i18n( 'design/admin/setup/rad/templateoperator' )}:</label>
<textarea class="box" name="Description" cols="60" rows="5">
{'Handles template operator %operator_name. By using %operator_name you can...'|i18n( 'design/admin/setup/rad/templateoperator',, hash( '%operator_name', $operator_name))}
</textarea>
<p>{'Hint: The first line will be used for the brief description. The rest will become the documentation.'|i18n( 'design/admin/setup/rad/templateoperator' )}</p>
</div>

<div class="block">
<label>{'Example code'|i18n( 'design/admin/setup/rad/templateoperator' )}:</label>
<textarea class="box" name="ExampleCode" cols="60" rows="3">{$example_code|wash}</textarea>
<p>{'Hint: Feel free to add example code that demonstrates how the operator works.'|i18n( 'design/admin/setup/rad/templateoperator' )}</p>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input type="hidden" value="download" name="OperatorStep" />
<input class="button" type="submit" name="TemplateOperatorRestartButton" value="{'Restart'|i18n( 'design/admin/setup/rad/templateoperator' )}" />
<input class="button" type="submit" name="TemplateOperatorStepButton" value="{'Finish and generate'|i18n( 'design/admin/setup/rad/templateoperator' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

{section name=Persistence loop=$persistent_data}
<input type="hidden" name="PersistentData[{$:key|wash}]" value="{$:item|wash}" />
{/section}

</form>
