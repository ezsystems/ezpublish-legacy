{* rush *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Workflow processes [%trigger_count]'|i18n( 'design/admin/workflow/processlist',, hash( '%trigger_count', $total_process_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-toolbar">
<div class="button-left">
    <p class="table-preferences">
    {switch match=$page_limit}
    {case match=25}
        <a href={'/user/preferences/set/admin_workflow_processlist_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_workflow_processlist_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_workflow_processlist_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <a href={'/user/preferences/set/admin_workflow_processlist_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_workflow_processlist_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <a href={'/user/preferences/set/admin_workflow_processlist_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="float-block"></div>
</div>

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
    <th>{'User'|i18n( 'design/admin/workflow/processlist' )}</th>
    <th>{'Created'|i18n( 'design/admin/workflow/processlist' )}</th>
    <th>{'Process status'|i18n( 'design/admin/workflow/processlist' )}</th>
    <th>{'Last event'|i18n( 'design/admin/workflow/processlist' )}</th>
    <th>{'Current event'|i18n( 'design/admin/workflow/processlist' )}</th>
</tr>
{def $w_statuses=fetch( 'workflow', 'workflow_statuses' )}
{def $w_type_statuses=fetch( 'workflow', 'workflow_type_statuses' )}
{section var=Processes loop=$tentry.process_list sequence=array( bglight, bgdark )}
<tr class="{$Processes.sequence}">
    <td><a href={concat( $module.functions.view.uri, '/', $Processes.item.workflow_id )|ezurl}>{$Processes.item.workflow.name|wash}</a></td>
    <td>{if $Processes.item.user}<a href={$Processes.item.user.contentobject.main_node.url|ezurl}>{$Processes.item.user.login|wash}</a>{else}&nbsp;-{/if}</td>
    <td>{$Processes.item.created|l10n( shortdatetime )}</td>
    <td>({$Processes.item.status|wash}) {if is_set( $w_statuses[$Processes.item.status] )}{$w_statuses[$Processes.item.status]}{/if}</td>
    <td>
        {if $Processes.item.last_workflow_event}
        status : ({$Processes.item.last_event_status}) {if is_set( $w_type_statuses[$Processes.item.last_event_status] )}{$w_type_statuses[$Processes.item.last_event_status]}{/if}<br/>
        event type : {$Processes.item.last_workflow_event.workflow_type.type|wash}<br/>
        {if $Processes.item.last_workflow_event.workflow_type.description}
        description : {$Processes.item.last_workflow_event.workflow_type.description|wash}<br/>{/if}
        {if $Processes.item.workflow_event.workflow_type.information}
        information : {$Processes.item.last_workflow_event.workflow_type.information|wash}<br/>{/if}
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
<p>{'There are no workflow processes in progress.'|i18n( 'design/admin/workflow/proccesslist' )}</p>
</div>
{/if}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/workflow/processlist' )
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}
</div>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

