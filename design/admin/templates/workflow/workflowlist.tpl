<form action={concat( $module.functions.workflowlist.uri, '/', $group_id )|ezurl} method="post" name="WorkflowList">

<div class="context-block">
<h2 class="context-title">{'Workflows inside %1'|i18n( 'design/admin/workflow/workflowlist', '%1 is workflow group', array( $group_name ) )}&nbsp;[{$workflow_list|count}]</h2>

{section show=$workflow_list}
<table class="list" cellspacing="0">
  <tr>
    <th>&nbsp;</th>
    <th>{'Name'|i18n( 'design/admin/workflow/workflowlist' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/workflow/workflowlist' )}</th>
    <th>{'Modified'|i18n( 'design/admin/workflow/workflowlist' )}</th>
    <th class="tight">&nbsp;</th>
  </tr>
   {section var=Workflows loop=$workflow_list sequence=array( bglight, bgdark )}
    <tr class="{$Workflows.sequence}">
    <td><input type="checkbox" name="Workflow_id_checked[]" value="{$Workflows.item.id}"></td>
    <td>{$Workflows.item.name}</td>
    <td>{$Workflows.item.modifier_id}</td>
    <td>{$Workflows.item.modified|l10n( shortdatetime )}</td>
    <td><a href={concat( $module.functions.edit.uri, '/', $Workflows.item.id )|ezurl}><img name="edit" src={'edit.png'|ezimage} width="16" height="16" alt="{'Edit'|i18n('design/admin/workflow/workflowlist')}" /></a></td>
    </tr>
   {/section}
{section-else}
<tr><td>{'There are no workflows in this group.'|i18n( 'design/admin/workflow/workflowlist' )}</td></tr>
{/section}
</table>

{* Buttons. *}
<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="DeleteButton" value="{'Remove selected'|i18n( 'design/admin/workflow/workflowlist' )}" {section show=$workflow_list|not}disabled="disabled"{/section} />
    <input class="button" type="submit" name="NewWorkflowButton" value="{'New workflow'|i18n( 'design/admin/workflow/workflowlist' )}" />
    <input type="hidden" name="CurrentGroupID" value="{$group_id}" />
    <input type="hidden" name="CurrentGroupName" value="{$group_name}" />
</div>
</div>

</div>
</form>
