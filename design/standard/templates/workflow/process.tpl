<form action={concat($module.functions.process.uri,"/",$process.id)|ezurl} method="post" name="WorkflowProcess">

<h1>Workflow process {$process.id}</h1>

<p>
Workflow process was created at <b>{$process.created|l10n(shortdatetime)}</b>
and modified at <b>{$process.modified|l10n(shortdatetime)}</b>.
</p>

<h2>Workflow</h2>
<p>
Using workflow <b><a href={concat($module.functions.edit.uri,"/",$process.workflow_id)|ezurl}>{$current_workflow.name} ({$process.workflow_id})</a></b> for processing.
</p>

<h2>User</h2>
<p>
This workflow is running for user <b>{$process.user.login}</b>.
</p>

<h2>Content object</h2>
<p>
Workflow was created for content <b><a href={concat("/content/view/",$process.content_id)|ezurl}>{$process.content.name}</a></b>
using version <b><a href={concat("/content/view/{$process.content_id}/",$process.content_version)|ezurl}>{$process.content_version}</a></b>
in parent <b><a href={concat("/content/view/",$process.node_id)|ezurl}>{$process.node.name}</a></b>
</p>

<h2>Workflow event</h2>
{section show=$current_event|not}
<p>
Workflow has not started yet, number of main events in workflow is <b>{$current_workflow.event_count}</b>.
</p>
{/section}
{section show=$current_event}
<p>
Current event position is <b>{$process.event_position}</b>.
Event to be run is <i>{$current_event.workflow_type.name}</i> event <b>{$current_event.description} ({$process.event_id})</b>.
</p>
{/section}

{section show=$event_status}
<p>
Last event returned status <b>{$event_status}.
</p>
{/section}

<h3>Workflow event list</h3>
{section name=Workflow loop=$current_workflow.ordered_event_list}
  {switch name=EventNumber match=$Workflow:number}
    {case match=$process.event_position}
      <b>{$Workflow:number}: {$Workflow:item.workflow_type.name} - {$Workflow:item.description}</b><br/>
    {/case}
    {case match=$process.last_event_position}
      <i>{$Workflow:number}: {$Workflow:item.workflow_type.name} - {$Workflow:item.description}</i><br/>
    {/case}
    {case}
      {$Workflow:number}: {$Workflow:item.workflow_type.name} - {$Workflow:item.description}<br/>
    {/case}
  {/switch}
{/section}

<br/>

<table width="100%">
<tr>
<td>{include uri="design:gui/button.tpl" name=new id_name=Reset value="Reset"}</td>
<td>{include uri="design:gui/button.tpl" name=new id_name=RunProcess value="Next step"}</td>
<td width="99%" />
</tr>
</table>

</form>