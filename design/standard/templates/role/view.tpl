<div class="maincontentheader">
<h1>{"Role view"|i18n('role/view')}</h1>
</div>
<form action={concat($module.functions.view.uri,"/",$role.id,"/")|ezurl} method="post" >
<div class="block">
<label>Name:</label><div class="labelbreak"></div>
<p class="box">{$role.name} <a href={concat("/role/edit/",$role.id,"/")|ezurl}>[edit]</a></p>
</div>
<p class="comment">This "edit" button needs to be done in some other way; maybe by just using the regular edit image button used in lists. th[eZ]</p>

<h2>{"Role policies"|i18n('role/view')}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>ID:</th>
    <th>Module:</th>
    <th>Function:</th>
    <th>Limitation:</th>

</tr>

{section name=Policy loop=$policies sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Policy:sequence}">{$Policy:item.id}</td>
	<td class="{$Policy:sequence}">
	{$Policy:item.module_name}
	</td>
        <td class="{$Policy:sequence}">
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

</tr>
{/section}
</table>

<h2>{"Users and groups assigned to this role"|i18n('role/view')}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="99%">User:</th>
	<th width="1%">&nbsp;</th>    
</tr>
{section name=User loop=$user_array sequence=array(bglight,bgdark)}
<tr>
	<td  class="{$User:sequence}">
	{$User:item.name}
	</td>
	<td  class="{$User:sequence}">
	<input type="checkbox" value="{$User:item.id}" name="UserIDArray[]" />
	</td>
</tr>
{/section}
</table>

<div class="buttonblock">
<input class="button" type="submit" name="AssignRoleButton" value="Assign role" />
<input class="button" type="submit" name="RemoveRoleAssignmentButton" value="Remove role assignment" />
</div>

</form>
