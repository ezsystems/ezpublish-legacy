{if and( is_set( $group_id ), $group_id )}
       <form name="WorkflowEdit" method="post" action={concat( $module.functions.edit.uri, '/', $workflow.id, '/', $group_id )|ezurl}>
{else}
      <form name="WorkflowEdit" method="post" action={concat( $module.functions.edit.uri, '/', $workflow.id, '/' )|ezurl}>
{/if}
{* Feedback *}
{*section show=and( $validation.processed, $validation.groups )*}
{section show=$validation.processed}
{section show=$validation.groups}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Input did not validate'|i18n( 'design/admin/workflow/edit' )}</h2>
<ul>
{section var=item loop=$validation.groups}
    <li>{$item.text}</li>
{/section}
</ul>
</div>
{/section}
{section show=$validation.events}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The workflow could not be stored.'|i18n( 'design/admin/workflow/edit' )}</h2>
<p>{'The following information is either missing or invalid'|i18n( 'design/admin/workflow/edit' )}:</p>
<ul>
{section var=unvalEvent loop=$validation.events}
    <li>({$unvalEvent.placement})&nbsp;{$unvalEvent.workflow_type.group_name}&nbsp;/&nbsp;{$unvalEvent.item.workflow_type.name|wash}:
    {section show=is_set( $unvalEvent.item.reason )}
        <br />{$unvalEvent.reason.text|wash}
        {section show=is_set( $unvalEvent.item.reason.list )}
        <ul>
        {section var=subitem loop=$unvalEvent.reason.list show=is_set( $unvalEvent.reason.list )}
            <li>{$subitem|wash}</li>
        {/section}
        </ul>
        {/section}
    </li>
    {/section}
{/section}
</ul>
</div>
{/section}
{/section}

{if $require_fixup}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Data requires fixup'|i18n( 'design/admin/workflow/edit' )}</h2>
</div>
{/if}



<div class="block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Edit <%workflow_name> [Workflow]'|i18n( 'design/admin/workflow/edit',, hash( '%workflow_name', $workflow.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name'|i18n( 'design/admin/workflow/edit' )}:</label>
<input class="halfbox" id="workflowName" type="text" name="Workflow_name" value="{$workflow.name|wash}" />
</div>

{section show=$event_list}

<hr />

{section var=Events loop=$event_list}
<table class="list" cellspacing="0">
<tr>
<th>
<input type="checkbox" name="WorkflowEvent_id_checked[]" value="{$Events.item.id}" />
&nbsp;
{if $Events.item.workflow_type|is_null}
    <span class="error">{$Events.number}({$Events.item.placement})&nbsp;{'Error : Could not load workflow event "%eventtype" (event type not available)'|i18n( 'design/admin/workflow/edit',, hash( '%eventtype', $Events.item.workflow_type_string ) )}</span>
</th>
</tr>
<tr>
    <td><em>{'Hint : This can happen when a workflow extension has been disabled'|i18n( 'design/admin/workflow/edit' )}</em></td>
</tr>
{else}
	{$Events.number}({$Events.item.placement})&nbsp;{$Events.item.workflow_type.group_name}&nbsp;/&nbsp;{$Events.item.workflow_type.name|wash}
	<div class="button-right">
	<a href={concat( $module.functions.down.uri, '/', $workflow.id, '/', $Events.item.id )|ezurl}><img src={'button-move_down.gif'|ezimage} height="16" width="16" alt="{'Move down'|i18n( 'design/admin/workflow/edit' )}" title="{'Move down'|i18n( 'design/admin/workflow/edit' )}" /></a>
	&nbsp;
	<a href={concat( $module.functions.up.uri, '/', $workflow.id, '/', $Events.item.id )|ezurl}><img src={'button-move_up.gif'|ezimage} height="16" width="16" alt="{'Move up'|i18n( 'design/admin/workflow/edit' )}" title="{'Move up'|i18n( 'design/admin/workflow/edit' )}" /></a>
	</div>
	</th>
	</tr>

	<tr><td>
	<div class="block">
	<label>{'Description / comments'|i18n( 'design/admin/workflow/edit' )}:</label>
	<input class="halfbox" type="text" name="WorkflowEvent_description[]" value="{$Events.item.description}" />
	</div>

	{if and( is_set( $selectedClass ), $selectedClass )}
        {event_edit_gui event=$Events.item selectedClass=$selectedClass}
    {else}
        {event_edit_gui event=$Events.item}
    {/if}
{/if}

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
<input class="button" type="submit" name="DeleteButton" value="{'Remove selected events'|i18n( 'design/admin/workflow/edit' )}" {if $event_list|not}disabled="disabled"{/if} />
</div>


<div class="block">
<select name="WorkflowTypeString">
{section var=WorkflowTypes loop=$workflow_type_list}
<option value="{$WorkflowTypes.item.type_string}">{$WorkflowTypes.item.group_name}&nbsp;/&nbsp;{$WorkflowTypes.item.name|wash}</option>
{/section}
</select>
<input class="button" type="submit" name="NewButton" value="{'Add event'|i18n( 'design/admin/workflow/edit' )}" />
</div>




</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/workflow/edit' )}" />
<input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/workflow/edit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>


</div>

</form>

{literal}
<script type="text/javascript">
jQuery(function( $ )//called on document.ready
{
    document.getElementById('workflowName').select();
    document.getElementById('workflowName').focus();
});
</script>
{/literal}
