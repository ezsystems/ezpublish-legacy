<form action={concat($module.functions.workflowlist.uri,"/",$group_id)|ezurl} method="post" name="WorkflowList">

<div class="maincontentheader">
<h1>{"Defined workflows for "}{$group_name}</h1>
</div>

{section show=$workflow_list}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>Name:</th>
    <th>Modifier:</th>
    <th>Modified:</th>
    <th width="1%" colspan="2">&nbsp;</th>
</tr>

{sequence name=WorkflowSequence loop=array(bglight,bgdark)}

{section name=Workflow loop=$workflow_list}
<tr>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.name}</td>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.modifier_id}</td>
    <td class="{$WorkflowSequence:item}"><span class="small">{$Workflow:item.modified|l10n(shortdatetime)}</span></td>
    <td class="{$WorkflowSequence:item}"><div class="listbutton"><a href={concat($module.functions.edit.uri,"/",$Workflow:item.id)|ezurl}><img name="edit"  src={"edit.png"|ezimage} width="16" height="16" align="Edit" /></a></div></td>
    <td class="{$WorkflowSequence:item}"><input type="checkbox" name="Workflow_id_checked[]" value="{$Workflow:item.id}"></td>
</tr>
{sequence name=WorkflowSequence}
{/section}
</table>
{/section}

{section show=$temp_workflow_list}
<h2>{"Temporary workflows for "}{$group_name}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="1%">ID:</th>
    <th>Name:</th>
    <th>Type:</th>
    <th>Creator:</th>
    <th>Modifier:</th>
    <th>Created:</th>
    <th>Modified:</th>
    <th width="1%" colspan="2">&nbsp;</th>
</tr>

{sequence name=TempWorkflowSequence loop=array(bglight,bgdark)}

{section name=TempWorkflow loop=$temp_workflow_list}
<tr>
    <td class="{$TempWorkflowSequence:item}">{$TempWorkflow:item.name}</td>
    <td class="{$TempWorkflowSequence:item}">{$TempWorkflow:item.modifier_id}</td>
    <td class="{$TempWorkflowSequence:item}">{$TempWorkflow:item.modified|l10n(shortdatetime)}</td>
    <td class="{$TempWorkflowSequence:item}"><div class="listbutton"><a href={concat($module.functions.edit.uri,"/",$TempWorkflow:item.id)|ezurl}><img name="edit" src={"edit.png"|ezimage} width="16" height="16" align="Edit" /></a></div></td>
    <td class="{$TempWorkflowSequence:item}"><input type="checkbox" name="Temp_Workflow_id_checked[]" value="{$TempWorkflow:item.id}"></td>
</tr>
{sequence name=TempWorkflowSequence}
{/section}
</table>
{/section}


<div class="buttonblock">
{include uri="design:gui/button.tpl" name=new id_name=NewWorkflowButton value="New"}
{include uri="design:gui/button.tpl" name=delete id_name=DeleteButton value="Remove"}
</div>

<input type="hidden" name = "CurrentGroupID" value="{$group_id}">
<input type="hidden" name = "CurrentGroupName" value="{$group_name}">
</form>
