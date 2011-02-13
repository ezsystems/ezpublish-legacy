<form action={$module.functions.list.uri|ezurl} method="post" >

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Workflow triggers [%trigger_count]'|i18n( 'design/admin/trigger/list',, hash( '%trigger_count', $possible_triggers|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th>{'Module'|i18n( 'design/admin/trigger/list' )}</th>
    <th>{'Function'|i18n( 'design/admin/trigger/list' )}</th>
    <th>{'Connection type'|i18n( 'design/admin/trigger/list' )}</th>
    <th>{'Workflow'|i18n( 'design/admin/trigger/list' )}</th>
</tr>

{section var=Triggers loop=$possible_triggers sequence=array( bglight, bgdark )}
<tr class="{$Triggers.sequence}">
<td>{$Triggers.item.module}</td>
<td>{$Triggers.item.operation}</td>
<td>{$Triggers.item.connect_type}</td>
<td>
<select name="WorkflowID_{$Triggers.item.key}" title="{'Select the workflow that should be triggered %type the %function function is executed within the %module module.'|i18n( 'design/admin/trigger/list',, hash( '%type', $Triggers.item.connect_type, '%function', $Triggers.item.operation, '%module', $Triggers.item.module ) )|wash}">
<option value="-1">{'No workflow'|i18n( 'design/admin/trigger/list' )}</option>
{section var=Workflows loop=$Triggers.item.allowed_workflows}
<option value="{$Workflows.item.id}" {if eq( $Workflows.item.id, $Triggers.item.workflow_id )} selected="selected" {/if}>{$Workflows.item.name} 
</option>
{/section}
</select>
</td>

</tr>
{/section}

</table>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'Apply changes'|i18n( 'design/admin/trigger/list' )}" title="{'Click this button to store changes if you have modified any of the fields above.'|i18n( 'design/admin/trigger/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

