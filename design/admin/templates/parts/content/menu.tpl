<table width="120" cellpadding="1" cellspacing="0" border="0">
{section show=fetch('content', 'can_instantiate_classes')}
<tr>
    <td colspan="2">
<form method="post" action={"content/action"|ezurl}>
         <select name="ClassID" class="classcreate">
	      {section name=Classes loop=fetch('content', 'can_instantiate_class_list')}
	      <option value="{$Classes:item.id}">{$Classes:item.name|wash}</option>
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
    <p class="menuitem"><a class="menuitem" href={"/content/view/full/2/"|ezurl}>{"Frontpage"|i18n("design/admin/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"arrow.gif"|ezimage} width="8" height="11" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/content/view/sitemap/2/"|ezurl}>{"Sitemap"|i18n("design/admin/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"arrow.gif"|ezimage} width="8" height="11" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/content/trash/"|ezurl}>{"Trash"|i18n("design/admin/layout")}</a></p>
    </td>
</tr>
</table>
