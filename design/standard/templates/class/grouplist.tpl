<form action={"class/grouplist"|ezurl} method="post" name="GroupList">

<div class="maincontentheader">
<h1>{"Class groups"|i18n("design/standard/class/list")}</h1>
</div>
<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{"Name"|i18n("design/standard/class/list")}</th>
    <th>{"Modifier"|i18n("design/standard/class/list")}</th>
    <th>{"Modified"|i18n("design/standard/class/list")}</th>
    <th>{"Edit"|i18n("design/standard/class/list")}</th>
    <th>{"Remove"|i18n("design/standard/class/list")}</th>
</tr>

{section name=Group loop=$groups sequence=array(bglight,bgdark)}
<tr>
    <td class="{$Group:sequence}"><a href={concat($module.functions.classlist.uri,"/",$Group:item.id)|ezurl}>{$Group:item.name|wash}</a></td>
    <td class="{$Group:sequence}">{content_view_gui view=text_linked content_object=$Group:item.modifier.contentobject}</td>
    <td class="{$Group:sequence}"><span class="small">{$Group:item.modified|l10n(shortdatetime)}</span></td>
    <td class="{$Group:sequence}" width="1"><div class="listbutton"><a href={concat($module.functions.groupedit.uri,"/",$Group:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></div></td>
    <td class="{$Group:sequence}" width="1" align="right"><input type="checkbox" name="DeleteIDArray[]" value="{$Group:item.id}"></td>
</tr>
{/section}
<tr>
  <td colspan="4">
    {include uri="design:gui/button.tpl" name=newgroup id_name=NewGroupButton value="New group"|i18n("design/standard/class/list")}
  </td>
  <td align="right">
    <input type="image" name="RemoveGroupButton" value="{'Remove'|i18n('design/standard/class/view')}" src={"trash.png"|ezimage} />
  </td>
</tr>
</table>

</form>
