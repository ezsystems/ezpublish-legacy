<div class="context-block">
<h2 class="context-title">{'%group_name [Workflow group]'|i18n( 'design/admin/workflow/workflowlist',, hash( '%group_name', $group.name ) )} </h2>

<div class="context-attributes">

<div class="block">
    <label>{'ID'|i18n( 'design/admin/workflow/workflowlist' )}</label>
    {$group.id}
</div>

<div class="block">
    <label>{'Name'|i18n( 'design/admin/workflow/workflowlist' )}</label>
    {$group.name}
</div>

</div>

<div class="controlbar">
<div class="block">
<form action={'workflow/grouplist'|ezurl} method="post" name="GroupList">
    <input type="hidden" name="ContentClass_id_checked[]" value="{$group.id}" />
    <input type="hidden" name="EditGroupID" value="{$group.id}" />
    <input class="button" type="submit" name="EditGroupButton" value="{'Edit'|i18n( 'design/admin/workflow/workflowlist' )}" />
    <input class="button" type="submit" name="DeleteGroupButton" value="{'Remove'|i18n( 'design/admin/workflow/workflowlist' )}" />
</form>
</div>
</div>

</div>

<form name="workflowlistform" action={concat( $module.functions.workflowlist.uri, '/', $group_id )|ezurl} method="post" name="WorkflowList">

<div class="context-block">
<h2 class="context-title"><a href={'/workflow/grouplist'|ezurl} title="{'Back to workflow groups'|i18n( 'design/admin/workflow/workflowlist' )}" /><img src={'back-button-16x16.gif'|ezimage} alt="{'Back to workflow groups'|i18n( 'design/admin/workflow/workflowlist' )}" title="{'Back to workflow groups'|i18n( 'design/admin/workflow/workflowlist' )}" /></a>&nbsp;{'Workflows [%workflow_count]'|i18n( 'design/admin/workflow/workflowlist',, hash( '%workflow_count', $workflow_list|count ) )}</h2>

{section show=$workflow_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.workflowlistform, 'Workflow_id_checked[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/trash' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/workflow/workflowlist' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/workflow/workflowlist' )}</th>
    <th>{'Modified'|i18n( 'design/admin/workflow/workflowlist' )}</th>
    <th class="tight">&nbsp;</th>
  </tr>
   {section var=Workflows loop=$workflow_list sequence=array( bglight, bgdark )}
       <tr class="{$Workflows.sequence}">
    <td><input type="checkbox" name="Workflow_id_checked[]" value="{$Workflows.item.id}"></td>
    <td><a href={concat( $module.functions.view.uri, '/', $Workflows.item.id )|ezurl}>{$Workflows.item.name}</a></td>
    <td>
    {let modifier=fetch( content, object, hash( object_id, $Workflows.item.modifier_id ) )}
    <a href={$modifier.main_node.url_alias|ezurl}>{$modifier.name}</a>
    {/let}
    </td>
    <td>{$Workflows.item.modified|l10n( shortdatetime )}</td>
    <td><a href={concat( $module.functions.edit.uri, '/', $Workflows.item.id )|ezurl}><img name="edit" src={'edit.png'|ezimage} width="16" height="16" alt="{'Edit'|i18n('design/admin/workflow/workflowlist')}" /></a></td>
    </tr>
   {/section}
</table>
{section-else}
<p>{'There are no workflows in this group.'|i18n( 'design/admin/workflow/workflowlist' )}</p>
{/section}

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
