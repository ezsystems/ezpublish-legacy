<div class="maincontentheader">
<h1>{"Create policy for"|i18n("design/standard/role")} {$role.name}</h1>
</div>

<form action={concat($module.functions.edit.uri,"/",$role.id,"/")|ezurl} method="post" >

<div class="step">
<table cellspacing="0" cellpadding="4" border="0">
<tr>
  <td>

<h2>{"Step 1"|i18n("design/standard/role")}</h2>
<div class="block">
   	<div class="element">
	<label>{"Module"|i18n("design/standard/role")}</label><div class="labelbreak"></div>
    <p class="box">{$current_module}</p>
    </div>
   	<div class="element">
	<label>{"Access"|i18n("design/standard/role")}</label><div class="labelbreak"></div>
    <p class="box">{"Limited"|i18n("design/standard/role")}</p>
    </div>
    <div class="break"></div>
</div>
<div class="buttonblock">
  	<input class="button" type="submit" name="Step1" value="{'Go back to step 1'|i18n('design/standard/role')}" />
</div>

  </td>
  <td>

<h2>{"Step 2"|i18n("design/standard/role")}</h2>
<div class="block">
   	<div class="element">
	<label>{"Function"|i18n("design/standard/role")}</label><div class="labelbreak"></div>
    <p class="box">{$current_function}</p>
    </div>
   	<div class="element">
	<label>{"Access"|i18n("design/standard/role")}</label><div class="labelbreak"></div>
    <p class="box">{"Limited"|i18n("design/standard/role")}</p>
    </div>
    <div class="break"></div>
</div>
<div class="buttonblock">
  	<input class="button" type="submit" name="Step2" value="{'Go back to step 2'|i18n('design/standard/role')}" />
</div>

  </td>
</tr>
</table>
</div>

<h2>{"Step 3"|i18n("design/standard/role")}</h2>
<p>{"Specify limitations in function"|i18n("design/standard/role")} <b>{$current_function}</b> {"in module"|i18n("design/standard/role")} <b>{$current_module}</b>. {"'Any' means no limitation by this parameter."|i18n("design/standard/role")}</p>
<div class="block">

     {section name=Limitations loop=$function_limitations}
<div class="element">
    <label>{$Limitations:item.name}:</label><div class="labelbreak"></div>
     <select name="{$Limitations:item.name}[]" size="8" multiple >
     <option value="-1" selected >{"Any"|i18n("design/standard/role")}</option>
     {section name=LimitationValues loop=$Limitations:item.values}
     <option value="{$Limitations:LimitationValues:item.value}">{$Limitations:LimitationValues:item.Name}</option>
     {/section}   
     </select>
</div>
     {/section}  
<div class="break"></div>
</div>
<div class="buttonblock">
<input class="button" type="submit" name="AddLimitation" value="{'Ok'|i18n('design/standard/role')}" />
<input type="hidden" name="CurrentModule" value="{$current_module}" />
<input type="hidden" name="CurrentFunction" value="{$current_function}" />

{*<input type="submit" name="DiscardLimitation" value="Return to functions" />*}
<input class="button" type="submit" value="{'Cancel'|i18n('design/standard/role')}" />
</div>
</form>
