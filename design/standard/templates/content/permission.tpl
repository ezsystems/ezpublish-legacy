<h1>Object permission</h1>

<form action="/content/permission/{$object_id}/" method="post">
Select permission set
<br/>
<select name="PermissionSet">
  {section name=Permissions loop=$permission_sets }
  <option value="{$Permissions:item.id}"
  	{switch name=sw match=$Permissions:item.id}
	{case match=$permission_id}
	selected
	{/case}
	{/switch}



  >{$Permissions:item.name}</option>
  {/section}
</select>
<input type="submit" name="SelectPermissionSet" value="Select"  />
<br/>
<input type="text" name="NewPermissionName" value="" />
<input type="submit" name="CreateNewPermissionSet" value="Create new"  />

<table width="100%" cellspacing="0" border="0">
<tr>
	<th>
	{"Read"|i18n}:
	</th>
	<th>
	{"Create"|i18n}:
	</th>
	<th>
	{"Edit"|i18n}:
	</th>
	<th>
	{"Remove"|i18n}:
	</th>
</tr>
<tr>
	<td>
 	<select name="ReadGroupArray[]" multiple size="5">
	<option value="-1">Everybody</option>
	{section name=UserGroups loop=$user_groups }


	<option value="{$UserGroups:item.id}"
	{switch name=sw match=$UserGroups:item.id}
	{case in=$read_groups key=id}
	selected
	{/case}
	{/switch}
        >{$UserGroups:item.name}</option>
	{/section}
	</select>
        </td>
	<td>
 	<select name="CreateGroupArray[]" multiple size="5">
	<option value="-1">Everybody</option>
	{section name=UserGroups loop=$user_groups }
	<option value="{$UserGroups:item.id}"
	{switch name=sw match=$UserGroups:item.id}
	{case in=$create_groups key=id}
	selected
	{/case}
	{/switch}
	>{$UserGroups:item.name}</option>
	{/section}
	</select>
        </td>
	<td>
 	<select name="EditGroupArray[]" multiple size="5">
	<option value="-1">Everybody</option>
	{section name=UserGroups loop=$user_groups }
	<option value="{$UserGroups:item.id}"
	{switch name=sw match=$UserGroups:item.id}
	{case in=$edit_groups key=id}
	selected
	{/case}
	{/switch}
	>{$UserGroups:item.name}</option>
	{/section}
	</select>
        </td>
	<td>
 	<select name="RemoveGroupArray[]" multiple size="5">
	<option value="-1">Everybody</option>
	{section name=UserGroups loop=$user_groups }
	<option value="{$UserGroups:item.id}"
	{switch name=sw match=$UserGroups:item.id}
	{case in=$remove_groups key=id}
	selected
	{/case}
	{/switch}
	>{$UserGroups:item.name}</option>
	{/section}
	</select>
        </td>
</tr>
<tr>
	<th cellpadding="4">
	<h2>Workflows</h2>
	</th>
</tr>
<tr>
	<td>
 	<select name="ReadWorkflow">
	{section name=Workflow loop=$workflows }
	<option value="{$Workflow:item.id}">{$Workflow:item.name}</option>
	{/section}
	</select>
        </td>
	<td>
 	<select name="ReadWorkflow">
	{section name=Workflow loop=$workflows }
	<option value="{$Workflow:item.id}">{$Workflow:item.name}</option>
	{/section}
	</select>
        </td>
	<td>
 	<select name="ReadWorkflow">
	{section name=Workflow loop=$workflows }
	<option value="{$Workflow:item.id}">{$Workflow:item.name}</option>
	{/section}
	</select>
        </td>
	<td>
 	<select name="ReadWorkflow">
	{section name=Workflow loop=$workflows }
	<option value="{$Workflow:item.id}">{$Workflow:item.name}</option>
	{/section}
	</select>
        </td>
</tr>
</table>

<br />
<input type="submit" name="StoreButton" value="Store"  />
<input type="submit" name="CancelButton" value="Cancel"  />
</form>