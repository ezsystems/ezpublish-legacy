
<h1>{"create policy"|i18n('role/edit')} {$role.name}</h1>

<form action={concat($module.functions.edit.uri,"/",$role.id,"/")|ezurl} method="post" >


<table width="100%" >
<tr>
<td>
<div class="step" bgcolor="grey" >
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
</td>
</tr>
</table>





{section show=$no_functions}

You are not able to give access to limited functions of module <b>{$current_module}</b> because function list for it is not defined <br/>


{section-else}



    {$current_module}<br/>
    to function:
    <select  name="ModuleFunction" size="1">
    {section name=Functions loop=$functions}
       <option value="{$Functions:item}">{$Functions:item}</option>
    {/section}
    </select>
    <input type="hidden" name="CurrentModule" value="{$current_module}" /><br />
    <input type="submit" name="AddFunction" value="Allow all" />
    <input type="submit" name="Limitation" value="Allow limited" />
   {* <input type="submit" name="DiscardFunction" value="Discard" />*}
{/section}
<br/>
<br/>
<br/>
<input type="submit" value="Cancel" />

</form>