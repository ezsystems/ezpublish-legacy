<div class="maincontentheader">
<h1>{"create policy"|i18n('role/edit')} {$role.name}</h1>
</div>

<form action={concat($module.functions.edit.uri,"/",$role.id,"/")|ezurl} method="post" >

<div class="step">
<h2>Step 1</h2>
<div class="block">
   	<div class="element">
	<label>Module:</label><div class="labelbreak"></div>
    <p class="box">{$current_module}</p>
    </div>
   	<div class="element">
	<label>Access:</label><div class="labelbreak"></div>
    <p class="box">Limited</p>
    </div>
    <div class="break"></div>
</div>
<div class="buttonblock">
  	<input class="button" type="submit" name="Step1" value="Go back to step 1" />
</div>
</div>

{section show=$no_functions}
<div class="warning">
<p>You are not able to give access to limited functions of module <b>{$current_module}</b> because function list for it is not defined.</p>
</div>
{section-else}

<h2>Step 2</h2>
<p>Specify function in module <b>{$current_module}</b>.</p>
<div class="block">
	<label>Function:</label><div class="labelbreak"></div>
    <select  name="ModuleFunction" size="1">
    {section name=Functions loop=$functions}
       <option value="{$Functions:item}">{$Functions:item}</option>
    {/section}
    </select>
    <input type="hidden" name="CurrentModule" value="{$current_module}" />
</div>

<div class="buttonblock">
    <input class="button" type="submit" name="AddFunction" value="Allow all" />
    <input class="button" type="submit" name="Limitation" value="Allow limited" />
</div>
   {* <input type="submit" name="DiscardFunction" value="Discard" />*}
{/section}

<div class="buttonblock">
<input class="button" type="submit" value="Cancel" />
</div>

</form>