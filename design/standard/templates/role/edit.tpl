<div class="maincontentheader">
<h1>{"Role edit"|i18n("design/standard/role")} {$role.name}</h1>
</div>

<form action={concat($module.functions.edit.uri,"/",$role.id,"/")|ezurl} method="post" >

<div class="block">
<label>{"Name:"|i18n("design/standard/role")}</label><div class="labelbreak"></div>
<input class="box" type="edit" name="NewName" value="{$role.name}" />
</div>

<h2>{"Current policies:"|i18n("design/standard/role")}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="10%">{"Module:"|i18n("design/standard/role")}</th>
    <th width="10%">{"Function:"|i18n("design/standard/role")}</th>
    <th width="79%">{"Limitation list:"|i18n("design/standard/role")}</th>
    <th width="1%">{"Remove:"|i18n("design/standard/role")}</th>
</tr>
{section name=Policy loop=$policies sequence=array(bglight,bgdark) }
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
    <td class="{$Policy:sequence}">
	<input type="checkbox" name="DeleteIDArray[]" value="{$Policy:item.id}" />
	{* <img src={"editdelete.png"|ezimage} alt="" /> *}

    </td>
   </tr>
{/section}  
</table>
<div class="buttonblock">
<input class="button" type="submit" name="CreatePolicy" value="{'New'|i18n('design/standard/role')}" />
<input class="button" type="submit" name="RemovePolicies"  value="{'Remove'|i18n('design/standard/role')}" />
</div>

<div class="buttonblock">
<input class="button" type="submit" name="Apply" value="{'Apply'|i18n('design/standard/role')}" />
<input class="button" type="submit" name="Discard" value="{'Discard'|i18n('design/standard/role')}" />
</div>


</form>
