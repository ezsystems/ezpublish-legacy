<form name="grouplistform" action={concat($module.functions.grouplist.uri)|ezurl} method="post" name="GroupList">

<div class="context-block">
<h2 class="context-title">{'Workflow groups [%groups_count]'|i18n( 'design/admin/workflow/grouplist',, hash( '%groups_count', $groups|count ) )}</h2>

<table class="list" cellspacing="0">
{section show=$groups|count}
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.grouplistform, 'ContentClass_id_checked[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/trash' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/workflow/grouplist' )}</th>
    <th class="tight">&nbsp;</th>

</tr>
{section var=Groups loop=$groups sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">
    <td><input type="checkbox" name="ContentClass_id_checked[]" value="{$Groups.item.id}"></td>
    <td><a href={concat( $module.functions.workflowlist.uri, '/', $Groups.item.id )|ezurl}>{$Groups.item.name}</a></td>
    <td><a href={concat( $module.functions.groupedit.uri, '/', $Groups.item.id )|ezurl}><img name="edit" src={"edit.png"|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/workflow/grouplist' )}" title="{'Edit workflow'|i18n( 'design/admin/workflow/grouplist' )}" /></a></td>
</tr>
{/section}
{section-else}

<tr><td>{'There are no workflow groups.'|i18n( 'design/admin/workflow/grouplist' )}</td></tr>

{/section}

</table>

{* Buttons. *}
<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="DeleteGroupButton" value="{'Remove selected'|i18n( 'design/admin/workflow/grouplist' )}"  title="{'Remove selected workflow groups.'|i18n( 'design/admin/workflow/grouplist' )}" {section show=$groups|count|not}disabled="disabled"{/section} />
    <input class="button" type="submit" name="NewGroupButton" value="{'New workflow group'|i18n( 'design/admin/workflow/grouplist' )}" />
</div>
</div>

</div>

</form>
