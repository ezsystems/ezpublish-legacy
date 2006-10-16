{* rush *}
<div class="context-block">

{* DESIGN: Header START *}
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Workflow processes [%trigger_count]'|i18n( 'design/admin/workflow/processlist',, hash( '%trigger_count', $total_process_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{if $trigger_list}
{foreach $trigger_list as $keyt => $tentry}
{if and( $tentry.trigger, $tentry.process_list )}
<div class="block">
<label>
{$tentry.trigger.module_name}/{$tentry.trigger.function_name}/{$tentry.trigger.name}&nbsp;:&nbsp;[{$tentry.process_list|count}]
</label>
</div>
{*'[%process_count]'|i18n( 'design/admin/workflow/processlist',, hash( '%process_count', $tentry.process_list|count ) )*}
<table class="list" cellspacing="0">
<tr>
    <th>{'Workflow'|i18n( 'design/admin/workflow/processlist' )}</th>
    <th>{'Created'|i18n( 'design/admin/workflow/processlist' )}</th>
    <th>{'Process status'|i18n( 'design/admin/workflow/processlist' )}</th>
    <th>{'Last event status'|i18n( 'design/admin/workflow/processlist' )}</th>
    <th>{'Current event status'|i18n( 'design/admin/workflow/processlist' )}</th>
</tr>
{def $w_statuses=fetch( 'workflow', 'workflow_statuses' )}
{def $w_type_statuses=fetch( 'workflow', 'workflow_type_statuses' )}
{section var=Processes loop=$tentry.process_list sequence=array( bglight, bgdark )}
<tr class="{$Processes.sequence}">
    <td><a href={concat( $module.functions.workflow.view.uri, '/', $Processes.item.workflow_id )|ezurl}>{$Processes.item.workflow.name|wash}</a></td>
    <td>{$Processes.item.created|l10n( shortdatetime )}</td>
    <td>({$Processes.item.status|wash}) {if is_set( $w_statuses[$Processes.item.status] )}{$w_statuses[$Processes.item.status]}{/if}</td>
    <td>
        {if $Processes.item.last_workflow_event}
        status : {$Processes.item.last_event_status|wash}<br/>
        status : ({$Processes.item.last_event_status}) {if is_set( $w_type_statuses[$Processes.item.last_event_status] )}{$w_type_statuses[$Processes.item.last_event_status]}{/if}<br/>
        event type : {$Processes.item.workflow_event.workflow_type.type|wash}<br/>
        {if $Processes.item.last_workflow_event.event_type.description}
        description : {$Processes.item.last_workflow_event.event_type.description|wash}<br/>{/if}
        {if $Processes.item.workflow_event.workflow_type.information}
        information : {$Processes.item.workflow_event.workflow_type.information|wash}<br/>{/if}
        {else}&nbsp;-{/if}
    </td>
    <td>
        {if $Processes.item.workflow_event}
        status : ({$Processes.item.event_status}) {if is_set( $w_type_statuses[$Processes.item.event_status] )}{$w_type_statuses[$Processes.item.event_status]}{/if}<br/>
        event type : {$Processes.item.workflow_event.workflow_type.type|wash}<br/>
        {if $Processes.item.workflow_event.workflow_type.description}
        description : {$Processes.item.workflow_event.workflow_type.description|wash}<br/>{/if}
        {if $Processes.item.workflow_event.workflow_type.information}
        information : {$Processes.item.workflow_event.workflow_type.information|wash}<br/>{/if}
        {else}&nbsp;-{/if}
    </td>
</tr>
{/section}
{undef}
</table>
{/if}
{/foreach}
{else}
<div class="block">
<p>{'There are no workflow process for current logined user.'|i18n( 'design/admin/workflow/proccesslist' )}</p>
</div>
{/if}

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

