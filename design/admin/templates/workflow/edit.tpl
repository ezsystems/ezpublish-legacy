<form action={concat( $module.functions.edit.uri, '/', $workflow.id )|ezurl} method="post" name="WorkflowEdit">

{* Feedback *}
{section show=and( $validation.processed, $validation.groups )}
<div class="message-warning">
<h2>{'Input did not validate'|i18n( 'design/admin/workflow/edit' )}</h2>
<ul>
{section var=item loop=$validation.groups}
    <li>{$item.text}</li>
{/section}
</ul>
</div>
{/section}

{section show=$can_store}
<div class="message-feedback">
<h2>{'The workflow was successfully stored.'|i18n( 'design/admin/workflow/edit' )}</h2>
</div>
{/section}

{section show=$require_fixup}
<div class="message-warning">
<h2>{'Data requires fixup'|i18n( 'design/admin/workflow/edit' )}</h2>
</div>
{/section}



<div class="block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Edit <%workflow_name> [Workflow]'|i18n( 'design/admin/workflow/edit',, hash( '%workflow_name', $workflow.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name'|i18n( 'design/admin/workflow/edit' )}</label>
<input class="halfbox" type="text" name="Workflow_name" value="{$workflow.name}" />
</div>

{section show=$event_list}

<hr />

{section var=Events loop=$event_list}
<table class="list" cellspacing="0">
<tr>
<th>
<input type="checkbox" name="WorkflowEvent_id_checked[]" value="{$Events.item.id}" />
&nbsp;
{$Events.number}({$Events.item.placement})&nbsp;{$Events.item.workflow_type.group_name}&nbsp;/&nbsp;{$Events.item.workflow_type.name}
<div class="right">
<a href={concat( $module.functions.down.uri, '/', $workflow.id, '/', $Events.item.id )|ezurl}><img src={'button-move_down.gif'|ezimage} height="16" width="16" alt="{'Move down'|i18n( 'design/admin/workflow/edit' )}" title="{'Move down'|i18n( 'design/admin/workflow/edit' )}" /></a>
&nbsp;
<a href={concat( $module.functions.up.uri, '/', $workflow.id, '/', $Events.item.id )|ezurl}><img src={'button-move_up.gif'|ezimage} height="16" width="16" alt="{'Move up'|i18n( 'design/admin/workflow/edit' )}" title="{'Move up'|i18n( 'design/admin/workflow/edit' )}" /></a>
</div>
</th>
</tr>

<tr><td>
<div class="block">
<label>{'Description / comments'|i18n( 'design/admin/workflow/edit' )}</label>
<input class="halfbox" type="text" name="WorkflowEvent_description[]" value="{$Events.item.description}" />
</div>

{event_edit_gui event=$Events.item}

<input type="hidden" name="WorkflowEvent_id[]" value="{$Events.item.id}" />
<input type="hidden" name="WorkflowEvent_placement[]" value="{$Events.item.placement}" />
</td>
</tr>
</table>
{/section}

<hr />
{section-else}
<div class="block">
<p>{'There are no events within this workflow.'|i18n( 'design/admin/workflow/edit' )}</p>
</div>
{/section}

<div class="block">
<input class="button" type="submit" name="DeleteButton" value="{'Remove selected events'|i18n( 'design/admin/workflow/edit' )}" {section show=$event_list|not}disabled="disabled"{/section} />
</div>


<div class="block">
<select name="WorkflowTypeString">
{section var=WorkflowTypes loop=$workflow_type_list}
<option value="{$WorkflowTypes.item.type_string}">{$WorkflowTypes.item.group_name}&nbsp;/&nbsp;{$WorkflowTypes.item.name}</option>
{/section}
</select>
<input class="button" type="submit" name="NewButton" value="{'Add event'|i18n( 'design/admin/workflow/edit' )}" />
</div>




</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/workflow/edit' )}" />
<input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/workflow/edit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>


</div>

</form>
