<h1>{"Role edit"|i18n('role/edit')} {$role.name}</h1>

<form action="{$module.functions.edit.uri}/{$role.id}/" method="post" >

<input type="edit" name="NewName" value="{$role.name}">


<table width="100%">
<tr>
    <td valign="top">
    <b>Current policies:</b><br/>
    <select name="RolePolicy"  size="5">
     {section name=Policy loop=$policies }
     	<option value="{$Policy:item.id}">{$Policy:item.module_name}:{$Policy:item.function_name}:{$Policy:item.limitation} 
        {section name=Limitation loop=$Policy:item.limitations}
           {$Policy:Limitation:item.identifier}({$Policy:Limitation:item.values_as_string})
	   {delimiter},{/delimiter}
        {/section}  
        </option>
     {/section}  
   </select>
   <br/>
   <input type="submit" name="RemovePolicy"  value="Remove selected policy" />
   </td>
   <td>
   {section show=$show_modules}
   <b>Available modules</b><br />
   <table>
   <tr>
    <td valign="top">
    <select  name="Modules" size="5">
    <option value="*">*</option>
    {section name=All loop=$modules }
      <option value="{$All:item}">{$All:item}</option>
    {/section}
    </select>
    </td>
    <td>
      <input type="submit" name="AddModule" value="Allow all" /><br />
      <input type="submit" name="CustomFunction" value="Allow limited" />
    </td>
    </tr>
    </table>
    {/section}

{section show=$show_functions}
<table width="100%">
<tr>
    <td>
    <b>Custom function - {$current_module}:</b><br />
    <select  name="ModuleFunction" size="5">
    {section name=Functions loop=$functions}
       <option value="{$Functions:item}">{$Functions:item}</option>
    {/section}
    </select>
    </td>
    <td>
    <input type="hidden" name="CurrentModule" value="{$current_module}" /><br />
    <input type="submit" name="AddFunction" value="Allow all" />
    <br />
    <input type="submit" name="Limitation" value="Allow limited" />
    <br />
    <input type="submit" name="DiscardFunction" value="Discard" />
    </td>
</tr>
</table>
{/section}

{section show=$show_limitations}
<table width="100%">
<tr>
     <td>
     <b>Function limitations - {$current_module}: {$current_function}:</b><br />
     {section name=Limitations loop=$function_limitations}  
     <b>{$Limitations:item.name}:</b><br/>
     <select name="{$Limitations:item.name}[]" size="5" multiple >
     <option value="-1" selected >Any</option>
     {section name=LimitationValues loop=$Limitations:item.values}
     <option value="{$Limitations:LimitationValues:item.value}">{$Limitations:LimitationValues:item.value}-{$Limitations:LimitationValues:item.Name}</option>
     {/section}   
     </select>
     <br/>
     {/section}  
     </td>
</tr>
</table>

<br/>	
<input type="hidden" name="CurrentModule" value="{$current_module}" /><br />
<input type="hidden" name="CurrentFunction" value="{$current_function}" /><br />
<input type="submit" name="AddLimitation" value="Add Limitation" /><br />
<input type="submit" name="DiscardLimitation" value="Return to functions" />
{/section}

</td>

</table>

<br />
<input type="submit" name="Apply" value="Apply" />
<input type="submit" name="Discard" value="Discard" />



</form>
