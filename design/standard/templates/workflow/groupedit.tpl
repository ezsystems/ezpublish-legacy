<form action="{$module.functions.groupedit.uri}/{$workflow_group.id}" method="post" name="WorkflowGroupEdit">

<h1>Editing workflow group - {$workflow_group.name}</h1>

{section show=$is_remove_tried|not}

<p>Created by {$workflow_group.creator_id} on {$workflow_group.created|l10n(shortdatetime)}</p>
<p>Modified by {$workflow_group.modifier_id} on {$workflow_group.modified|l10n(shortdatetime)}</p>
<table>
<tr><td>Name:</td></tr>
<tr><td>{include uri="design:gui/lineedit.tpl" name=Name id_name=WorkflowGroup_name value=$workflow_group.name}</td></tr>

<tr><td>

{section show=$can_store}
<p class="important">Workflow group stored</p>
{/section}
{section show=$require_fixup}
<p class="important">Data requires fixup</p>
{/section}

<table>
<tr>
  <th colspan="2">Workflow</th>
</tr>
{section name=Workflow loop=$assigned_workflow_list sequence=array(bglight,bgdark)}
<tr>
  <td class="{$Workflow:sequence}">{$Workflow:item.name}</td>
  <td class="{$Workflow:sequence}" width="1%"><a href="{$module.functions.edit.uri}/{$Workflow:item.id}"><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
</tr>
{/section}
</table>

</td>

</table>

<hr/>

<table width="100%">
<tr>
    <td>{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}</td>
    <td>{include uri="design:gui/button.tpl" name=Apply id_name=ApplyButton value=Apply}</td>
    <td>{include uri="design:gui/button.tpl" name=Discard id_name=RemoveButton value=Remove}</td>
    <td width="99%"></td>
</tr>
</table>

</td></tr>
</table>

{/section}
{section show=$is_remove_tried}

blah


{/section}


</form>