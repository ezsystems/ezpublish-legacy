<form action={concat($module.functions.grouplist.uri)|ezurl} method="post" name="GroupList">

<div class="maincontentheader">
<h1>{"Defined workflow groups"|i18n('content/class')}</h1>
</div>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th><a href={concat($module.functions.list.uri,"/name")|ezurl}>{"Name"|i18n('content/class')}</a>:</th>
    <th width="1%" colspan="2">&nbsp;</th>
</tr>

{section name=Groups loop=$groups sequence=array(bglight,bgdark)}
<tr>
    <td class="{$Groups:sequence}"><a href={concat($module.functions.workflowlist.uri,"/",$Groups:item.id)|ezurl}>{$Groups:item.name}</a></td>
    <td class="{$Groups:sequence}"><div class="listbutton"><a href={concat($module.functions.groupedit.uri,"/",$Groups:item.id)|ezurl}><img name="edit" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></div></td>
    <td class="{$Groups:sequence}"><input type="checkbox" name="ContentClass_id_checked[]" value="{$Groups:item.id}"></td>
</tr>
{/section}
</table>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=newgroup id_name=NewGroupButton value="New"|i18n('content/class')}
{include uri="design:gui/button.tpl" name=deletegroup id_name=DeleteGroupButton value="Remove"|i18n('content/class')}
</div>

</form>