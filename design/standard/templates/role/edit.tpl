<h1>{"Role edit"|i18n('role/edit')} {$role.name}</h1>

<form action="{$module.functions.edit.uri}/{$role.id}/" method="post" >

<input type="edit" name="NewName" value="{$role.name}">

    <h2>Current policies:</h2><br/>

<table width="100%">
<tr>
	<th align="left">Module</th>
	<th align="left">Function</th>
	<th align="left">Limitation list</th>
	<th align="left">Remove</th>

</tr>
{section name=Policy loop=$policies sequence=array(bglight,bgdark) }
   <tr>
    <td width="10%" class="{$Policy:sequence}">
     {$Policy:item.module_name}
    </td>
    <td width="10%" class="{$Policy:sequence}">
     {$Policy:item.function_name}
    </td>
    <td class="{$Policy:sequence}">
      {$Policy:item.limitation}
        {section name=Limitation loop=$Policy:item.limitations}
           {$Policy:Limitation:item.identifier}(
	          {section name=LimitationValues loop=$Policy:Limitation:item.values_as_array_with_names}

                   {$Policy:Limitation:LimitationValues:item.Name}
                  {delimiter},{/delimiter}
                  {/section})

	   {delimiter},{/delimiter}
        {/section}  
    </td>
    <td>
		     <input type="checkbox" name="DeleteIDArray[]" value="{$Policy:item.id}" />
		          <img src={"editdelete.png"|ezimage} border="0">

    </td>
   </tr>
{/section}  
<tr>
<td>
   <input type="submit" name="RemovePolicies"  value="Remove selected policies" />

</td>
</tr>
</table>

{section show=$no_functions}

You are not able to give access to limited functions of module <b>{$current_module}</b> because function list for it is not defined <br/>
{/section}

{section show=$no_limitations}

You are not able to give limited access to function <b>{$current_function}</b>  of module <b>{$current_module}</b> because function list for it is not defined <br/>

{/section}

Give access to module:
    {section show=$show_modules}
    <select  name="Modules" size="1">
    <option value="*"> Every module </option>
    {section name=All loop=$modules }
      <option value="{$All:item}">{$All:item}</option>
    {/section}
    </select>

      <input type="submit" name="AddModule" value="Allow all" /> <input type="submit" name="CustomFunction" value="Allow limited" />
    {/section}

{section show=$show_functions}
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
    <input type="submit" name="DiscardFunction" value="Discard" />
{/section}

{section show=$show_limitations}
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
<input type="hidden" name="CurrentModule" value="{$current_module}" /><br />
<input type="hidden" name="CurrentFunction" value="{$current_function}" /><br />
<input type="submit" name="AddLimitation" value="Add Limitation" /><input type="submit" name="DiscardLimitation" value="Return to functions" />
{/section}
<br/>
<br/>
<br/>
Apply all changes to the role:
<input type="submit" name="Apply" value="Apply" /> <br/>
or discard all changes to role: 
<input type="submit" name="Discard" value="Discard" />



</form>
