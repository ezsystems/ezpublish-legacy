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

<h2>Step 3</h2>
<p>Specify limitations in function <b>{$current_function}</b> in module <b>{$current_module}</b>. Any means no limitation by this parameter.</p>
<div class="block">

     {section name=Limitations loop=$function_limitations}
<div class="element">
    <label>{$Limitations:item.name}:</label><div class="labelbreak"></div>
     <select name="{$Limitations:item.name}[]" size="5" multiple >
     <option value="-1" selected >Any</option>
     {section name=LimitationValues loop=$Limitations:item.values}
     <option value="{$Limitations:LimitationValues:item.value}">{$Limitations:LimitationValues:item.value}-{$Limitations:LimitationValues:item.Name}</option>
     {/section}   
     </select>
</div>
     {/section}  
<div class="break"></div>
</div>
<div class="buttonblock">
<input class="button" type="submit" name="AddLimitation" value="Ok" />
<input type="hidden" name="CurrentModule" value="{$current_module}" />
<input type="hidden" name="CurrentFunction" value="{$current_function}" />

{*<input type="submit" name="DiscardLimitation" value="Return to functions" />*}
<input class="button" type="submit" value="Cancel" />
</div>
</form>