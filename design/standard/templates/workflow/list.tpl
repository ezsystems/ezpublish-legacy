<form action="{$module.functions.list.uri}" method="post" name="WorkflowEdit">

<h1>Defined workflows</h1>
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

{section name=WorkflowGroup loop=$group_list}
<tr>
    <td><h2>{$WorkflowGroup:item.name}</h2>
    <td colspan="6"></td>
    <td width="1%"><a href="{$module.functions.groupedit.uri}/{$WorkflowGroup:item.id}"><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
    <td width="1%"><input type="checkbox" name="WorkflowGroup_id_checked[]" value="{$WorkflowGroup:item.id}"></td>
</tr>

  {section name=Workflow loop=$WorkflowGroup:item.workflows}
    {let workflow=$workflow_list[$WorkflowGroup:Workflow:item]}
    <tr>
    <td class="{$WorkflowSequence:item}" width="1%">{$WorkflowGroup:Workflow:workflow.id}</td>
    <td class="{$WorkflowSequence:item}">{$WorkflowGroup:Workflow:workflow.name}</td>
    <td class="{$WorkflowSequence:item}">{$WorkflowGroup:Workflow:workflow.workflow_type_string}</td>
    <td class="{$WorkflowSequence:item}">{$WorkflowGroup:Workflow:workflow.creator_id}</td>
    <td class="{$WorkflowSequence:item}">{$WorkflowGroup:Workflow:workflow.modifier_id}</td>
    <td class="{$WorkflowSequence:item}">{$WorkflowGroup:Workflow:workflow.created|l10n(shortdatetime)}</td>
    <td class="{$WorkflowSequence:item}">{$WorkflowGroup:Workflow:workflow.modified|l10n(shortdatetime)}</td>
    <td class="{$WorkflowSequence:item}" width="1%"><a href="{$module.functions.edit.uri}/{$WorkflowGroup:Workflow:workflow.id}"><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
    <td class="{$WorkflowSequence:item}" width="1%"><input type="checkbox" name="Workflow_id_checked[]" value="{$WorkflowGroup:Workflow:workflow.id}"></td>
    </tr>
    {/let}
    {sequence name=WorkflowSequence}
  {/section}

{/section}
</table>

<table width="100%">
<tr>
<td width="99%"></td>
<td>{include uri="design:gui/button.tpl" name=new id_name=NewWorkflowButton value="New Workflow"}</td>
<td>{include uri="design:gui/button.tpl" name=new id_name=NewGroupButton value="New Group"}</td>
<td>{include uri="design:gui/button.tpl" name=delete id_name=DeleteButton value="Delete"}</td>
</tr>
</table>

</form>