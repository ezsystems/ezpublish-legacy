<form action={concat($module.functions.list.uri)|ezurl} method="post" name="WorkflowList">

<p class="comment">Seems like this is an obsolete copy of another template; this is confirmed by JB. To be deleted. th[eZ]</p>

<div class="maincontentheader">
<h1>Defined workflows</h1>
</div>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>ID:</th>
    <th>Name:</th>
    <th>Type:</th>
    <th>Creator:</th>
    <th>Modifier:</th>
    <th>Created:</th>
    <th>Modified:</th>
</tr>

{sequence name=WorkflowSequence loop=array(bglight,bgdark)}

{section name=WorkflowGroup loop=$group_list}
<tr>
    <td><h2>{$WorkflowGroup:item.name}</h2>
    <td colspan="6"></td>
    <td width="1%"><a href={concat($module.functions.groupedit.uri,"/",$WorkflowGroup:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
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
    <td class="{$WorkflowSequence:item}" width="1%"><a href={concat($module.functions.edit.uri,"/",$WorkflowGroup:Workflow:workflow.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
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
