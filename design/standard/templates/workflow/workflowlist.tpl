<form action={concat($module.functions.workflowlist.uri,"/",$group_id)|ezurl} method="post" name="WorkflowList">

<h1>{"Defined workflows for "}{$group_name}</h1>
{section show=$workflow_list}
<table width="100%" cellspacing="0">
<tr>
  <th align="left">ID</th>
  <th align="left">Name</th>
  <th align="left">Type</th>
  <th align="left">Creator</th>
  <th align="left">Modifier</th>
  <th align="left">Created</th>
  <th align="left">Modified</th>
</tr>

{sequence name=WorkflowSequence loop=array(bglight,bgdark)}

{section name=Workflow loop=$workflow_list}
<tr>
    <td class="{$WorkflowSequence:item}" width="1%">{$Workflow:item.id}</td>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.name}</td>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.workflow_type_string}</td>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.creator_id}</td>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.modifier_id}</td>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.created|l10n(shortdatetime)}</td>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.modified|l10n(shortdatetime)}</td>
    <td class="{$WorkflowSequence:item}" width="1%"><a href={concat($module.functions.edit.uri,"/",$Workflow:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
    <td class="{$WorkflowSequence:item}" width="1%"><input type="checkbox" name="Workflow_id_checked[]" value="{$Workflow:item.id}"></td>
</tr>
{sequence name=WorkflowSequence}
{/section}
</table>
{/section}

{section show=$temp_workflow_list}
<h3>{"Temporary workflows for "}{$group_name}</h3>
<table width="100%" cellspacing="0">
<tr>
  <th align="left">ID</th>
  <th align="left">Name</th>
  <th align="left">Type</th>
  <th align="left">Creator</th>
  <th align="left">Modifier</th>
  <th align="left">Created</th>
  <th align="left">Modified</th>
</tr>

{sequence name=TempWorkflowSequence loop=array(bglight,bgdark)}

{section name=TempWorkflow loop=$temp_workflow_list}
<tr>
    <td class="{$TempWorkflowSequence:item}" width="1%">{$TempWorkflow:item.id}</td>
    <td class="{$TempWorkflowSequence:item}">{$TempWorkflow:item.name}</td>
    <td class="{$TempWorkflowSequence:item}">{$TempWorkflow:item.workflow_type_string}</td>
    <td class="{$TempWorkflowSequence:item}">{$TempWorkflow:item.creator_id}</td>
    <td class="{$TempWorkflowSequence:item}">{$TempWorkflow:item.modifier_id}</td>
    <td class="{$TempWorkflowSequence:item}">{$TempWorkflow:item.created|l10n(shortdatetime)}</td>
    <td class="{$TempWorkflowSequence:item}">{$TempWorkflow:item.modified|l10n(shortdatetime)}</td>
    <td class="{$TempWorkflowSequence:item}" width="1%"><a href={concat($module.functions.edit.uri,"/",$TempWorkflow:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
    <td class="{$TempWorkflowSequence:item}" width="1%"><input type="checkbox" name="Temp_Workflow_id_checked[]" value="{$TempWorkflow:item.id}"></td>
</tr>
{sequence name=TempWorkflowSequence}
{/section}
</table>
{/section}


<table width="100%">
<tr>
<td width="99%"></td>
<td>{include uri="design:gui/button.tpl" name=new id_name=NewWorkflowButton value="New Workflow"}</td>
<td>{include uri="design:gui/button.tpl" name=delete id_name=DeleteButton value="Delete"}</td>
</tr>
</table>
<input type="hidden" name = "CurrentGroupID" value="{$group_id}">
<input type="hidden" name = "CurrentGroupName" value="{$group_name}">
</form>
