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
using version <b><a href={concat("/content/view/",$process.content_id,"/",$process.content_version)|ezurl}>{$process.content_version}</a></b>
in parent <b><a href={concat("/content/view/",$process.node_id)|ezurl}>{$process.node.name}</a></b>
</p>

<h2>Workflow event log</h2>

<table>
<tr>
  <th>Name</th><th>Description</th><th>Status</th><th>Information</th>
</tr>
{section name=EventLog loop=$event_log}
<tr>
  <td>{$EventLog:item.type_group}/{$EventLog:item.type_name}</td>
  <td>{$EventLog:item.description}</td>
  <td>{$EventLog:item.status_text}</td>
  <td>{$EventLog:item.information}</td>
</tr>
{/section}
</table>

</form>