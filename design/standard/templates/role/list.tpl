<a href={"/manual/user/permissions#Roles"|ezurl} target="_ezpublishmanual"><img src={"help.gif"|ezimage} align="right"> </a>

<div class="maincontentheader">
<h1>{"Role list"|i18n("design/standard/role")}</h1>
</div>

<form action={concat($module.functions.list.uri,"/")|ezurl} method="post" >

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="97%">{"Name:"|i18n("design/standard/role")}</th>
    <th width="1%">{"Assign:"|i18n("design/standard/role")}</th>
    <th width="1%">{"Edit:"|i18n("design/standard/role")}</th>
    <th width="1%">{"Remove:"|i18n("design/standard/role")}</th>
</tr>

{section name=All loop=$roles sequence=array(bglight,bgdark)}
<tr>
    <td class="{$All:sequence}">
    <a href={concat("/role/view/",$All:item.id)|ezurl}>{$All:item.name}</a>
    </td>
    <td class="{$All:sequence}">
	<a href={concat("/role/assign/",$All:item.id)|ezurl}><img src={"attach.png"|ezimage} alt="" /></a>
    </td>
    <td class="{$All:sequence}">
	<a href={concat("/role/edit/",$All:item.id)|ezurl}><img src={"edit.png"|ezimage} alt="" /></a>
    </td>
    <td class="{$All:sequence}">
	<input type="checkbox" name="DeleteIDArray[]" value="{$All:item.id}" /><img src={"editdelete.png"|ezimage} alt="" />
    </td>
</tr>
{/section}
</table>

<div class="buttonblock">
<input class="button" type="submit" name="NewButton" value="{'New'|i18n('design/standard/role')}" />
<input class="button" type="submit" name="RemoveButton" value="{'Remove'|i18n('design/standard/role')}" />
</div>

</form>
