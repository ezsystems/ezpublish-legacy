{let user_class_group_id=ezini('UserSettings','UserClassGroupID')
     user_class_list_allowed=fetch('content','can_instantiate_classes')
     user_class_list=fetch('content','can_instantiate_class_list',hash(group_id,$user_class_group_id))}
<table width="120" cellpadding="1" cellspacing="0" border="0">
{section show=$user_class_list_allowed}
<tr>
    <td colspan="2">
    <form method="post" action={"content/action"|ezurl}>
         <select name="ClassID" class="classcreate">
	      {section name=Classes loop=$user_class_list}
	      <option value="{$:item.id}">{$:item.name}</option>
	      {/section}
         </select>
	 <br />
         <input class="classbutton" type="submit" name="NewButton" value="{'New'|i18n('design/standard/node/view')}" />
    </form>
    </td>
</tr>
{/section}
<tr>
    <td class="bullet" width="1">
    <img src={"arrow.gif"|ezimage} width="8" height="11" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/content/view/full/5/"|ezurl}>{"Users"|i18n("design/admin/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"arrow.gif"|ezimage} width="8" height="11" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/role/list/"|ezurl}>{"Roles"|i18n("design/admin/layout")}</a></p>
    </td>
</tr>
</table>
{/let}
