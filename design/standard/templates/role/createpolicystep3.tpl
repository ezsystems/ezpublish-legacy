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

<table width="100%" >
<tr>
<td>
<div class="step">
<h2>Step 2</h2>
<div class="block">
   	<div class="element">
	<label>Function:</label><div class="labelbreak"></div>
    <p class="box">{$current_function}</p>
    </div>
   	<div class="element">
	<label>Access:</label><div class="labelbreak"></div>
    <p class="box">Limited</p>
    </div>
    <div class="break"></div>
</div>
<div class="buttonblock">
  	<input class="button" type="submit" name="Step2" value="Go back to step 2" />
</div>
</div>
</td>
</tr>
</table>







<b>{$current_module}</b>
 to function <b>{$current_function}</b>:<br />
Chose limitations from lists (Any means no limitation by this parameter)
<table>
<tr>
     {section name=Limitations loop=$function_limitations}
<td>     <b>{$Limitations:item.name}:</b><br/>
     <select name="{$Limitations:item.name}[]" size="5" multiple >
     <option value="-1" selected >Any</option>
     {section name=LimitationValues loop=$Limitations:item.values}
     <option value="{$Limitations:LimitationValues:item.value}">{$Limitations:LimitationValues:item.value}-{$Limitations:LimitationValues:item.Name}</option>
     {/section}   
     </select>
     <br/>
</td>
     {/section}  
</tr>
</table>
<br/>	
<input type="submit" name="AddLimitation" value="Ok" />
<input type="hidden" name="CurrentModule" value="{$current_module}" />
<input type="hidden" name="CurrentFunction" value="{$current_function}" />

{*<input type="submit" name="DiscardLimitation" value="Return to functions" />*}

<br/>
<br/>
<br/>
<input type="submit" value="Cancel" />

</form>