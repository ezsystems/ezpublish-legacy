<div class="context-block">
<h2 class="context-title">{'Workflow triggers'|i18n( 'design/admin/trigger/list' )}</h2>

<form action={$module.functions.list.uri|ezurl} method="post" >

<table class="list" cellspacing="0">
<tr>
    <th>{'Module'|i18n( 'design/admin/trigger/list' )}</th>
    <th>{'Function'|i18n( 'design/admin/trigger/list' )}</th>
    <th>{'Connection type'|i18n( 'design/admin/trigger/list' )}</th>
    <th>{'Workflow'|i18n( 'design/admin/trigger/list' )}</th>
</tr>

{section var=Triggers loop=$possible_triggers sequence=array( bglight, bgdark )}
<tr class="{$Triggers.sequence}">
<td>{$Triggers.item.module}</td>
<td>{$Triggers.item.operation}</td>
<td>{$Triggers.item.connect_type}</td>
<td>
<select name="WorkflowID_{$Triggers.item.key}">
<option value="-1">{'No workflow'|i18n( 'design/admin/trigger/list' )}</option>
{section var=Workflows loop=$Triggers.item.allowed_workflows}
<option value="{$Workflows.item.id}" {section show=eq( $Workflows.item.id, $Triggers.item.workflow_id )} selected="selected" {/section}>{$Workflows.item.name} 
</option>
{/section}
</select>
</td>

</tr>
{/section}

</table>

{* Buttons. *}
<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'Store changes'|i18n( 'design/admin/trigger/list' )}" />
</div>

</form>

</div>


