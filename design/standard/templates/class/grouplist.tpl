<form action={"class/grouplist"|ezurl} method="post" name="GroupList">

<div class="maincontentheader">
<h1>{"Defined class groups"|i18n('content/class')}</h1>
</div>

<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{"Name"|i18n('content/class')}:</th>
    <th>{"Modifier"|i18n('content/class')}:</th>
    <th>{"Modified"|i18n('content/class')}:</th>
</tr>

{section name=Group loop=$groups sequence=array(bglight,bgdark)}
<tr>
    <td class="{$Group:sequence}"><a href={concat($module.functions.classlist.uri,"/",$Group:item.id)|ezurl}>{$Group:item.name}</a></td>
    <td class="{$Group:sequence}">
{content_view_gui view=text_linked content_object=$Group:item.modifier.contentobject}</td>
    <td class="{$Group:sequence}"><span class="small">{$Group:item.modified|l10n(shortdatetime)}</span></td>
    <td class="{$Group:sequence}" width="1%"><div class="listbutton"><a href={concat($module.functions.groupedit.uri,"/",$Group:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></div></td>
  <td class="{$Group:sequence}" width="1%"><input type="checkbox" name="DeleteIDArray[]" value="{$Group:item.id}"></td>
</tr>
{/section}

</table>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=newgroup id_name=NewGroupButton value="New"|i18n('content/class')}
{include uri="design:gui/button.tpl" name=removegroup id_name=RemoveGroupButton value="Remove"|i18n('content/class')}
</div>

</form>