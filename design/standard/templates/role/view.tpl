<div class="maincontentheader">
<h1>{"Role view"|i18n("design/standard/role")}</h1>
</div>
<form action={concat($module.functions.view.uri,"/",$role.id,"/")|ezurl} method="post" >
<div class="block">
<label>{"Name:"|i18n("design/standard/role")}</label><div class="labelbreak"></div>
<p class="box">{$role.name} <a href={concat("/role/edit/",$role.id,"/")|ezurl}><img alt="{'Edit'|i18n('design/standard/role')}" src={"edit.png"|ezimage} /></a></p>
</div>

<h2>{"Role policies"|i18n("design/standard/role")}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{"Module:"|i18n("design/standard/role")}</th>
    <th>{"Function:"|i18n("design/standard/role")}</th>
    <th>{"Limitation:"|i18n("design/standard/role")}</th>
</tr>

{section name=Policy loop=$policies sequence=array(bglight,bgdark)}
<tr>
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

<h2>{"Users and groups assigned to this role"|i18n("design/standard/role")}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="99%">{"User:"|i18n("design/standard/role")}</th>
	<th width="1%">&nbsp;</th>    
</tr>
{section name=User loop=$user_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$User:sequence}">
	{$User:item.name}
	</td>
	<td class="{$User:sequence}">
	<input type="checkbox" value="{$User:item.id}" name="UserIDArray[]" />
	</td>
</tr>
{/section}
<tr>
  <td>
    <input class="button" type="submit" name="AssignRoleButton" value="{'Assign'|i18n('design/standard/role')}" />
  </td>
  <td align="right" width="1">
    <input type="image" name="RemoveRoleAssignmentButton" value="{'Remove'|i18n('design/standard/role')}" src={"trash.png"|ezimage} />
  </td>
</tr>
</table>

</form>
