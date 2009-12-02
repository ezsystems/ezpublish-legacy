{* Warnings *}
{section show=and( $validation.processed, $validation.groups )}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Input did not validate'|i18n( 'design/admin/workflow/view' )}</h2>
<ul>
{section var=item loop=$validation.groups}
    <li>{$item.text}</li>
{/section}
</ul>
</div>
{/section}




{* Workflow *}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'%workflow_name [Workflow]'|i18n( 'design/admin/workflow/view' ,, hash( '%workflow_name', $workflow.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

<div class="context-information">
<p>{'Last modified'|i18n( 'design/admin/workflow/view' )}:&nbsp;{$workflow.modified|l10n( shortdatetime )}&nbsp;<a href={$workflow.creator.contentobject.main_node.url_alias|ezurl}>{$workflow.creator.contentobject.name|wash}</a></p>
</div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
    <label>{'ID'|i18n( 'design/admin/workflow/view' )}:</label>
    {$workflow.id}
</div>

<div class="block">
    <label>{'Name'|i18n( 'design/admin/workflow/view' )}:</label>
    {$workflow.name|wash}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<form action={concat( '/workflow/edit/', $workflow.id )|ezurl} method="post">
<input class="button" type="submit" name="" value="{'Edit'|i18n( 'design/admin/workflow/view' )}" />
</form>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>




{* Groups *}
<form name="workflowgroups" method="post" action={concat( '/workflow/view/', $workflow.id )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Member of groups [%group_count]'|i18n( 'design/admin/workflow/view',, hash( '%group_count', $workflow.ingroup_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/workflow/view' )}" title="{'Invert selection.'|i18n( 'design/admin/workflow/view' )}" onclick="ezjs_toggleCheckboxes( document.workflowgroups, 'group_id_checked[]' ); return false;" /></th>
    <th class="wide">{'Group'|i18n( 'design/admin/workflow/view' )}</th>
</tr>
{section var=Groups loop=$workflow.ingroup_list sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">
    <td class="tight"><input type="checkbox" name="group_id_checked[]" value="{$Groups.item.group_id}" /></td>
    <td class="wide"><a href={concat( '/workflow/workflowlist/', $Groups.item.group_id )|ezurl}>{$Groups.item.group_name|wash}</a></td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    <input class="button" type="submit" name="DeleteGroupButton" value="{'Remove selected'|i18n( 'design/admin/workflow/view' )}" />
    </div>
    <div class="block">
    {section show=sub( count( $workflow.group_list ),count( $workflow.ingroup_list ) )}
        <select name="Workflow_group">
        {section var=Groups loop=$workflow.group_list}
            {if $workflow.ingroup_id_list|contains($Groups.item.id)|not}
                <option value="{$Groups.item.id}/{$Groups.item.name|wash}">{$Groups.item.name|wash}</option>
            {/if}
        {/section}
        </select>
        <input class="button" type="submit" name="AddGroupButton" value="{'Add to group'|i18n( 'design/admin/workflow/view' )}" />
    {section-else}
        <select name="ContentClass_group" disabled="disabled">
        <option value="">{'No group'|i18n( 'design/admin/workflow/view' )}</option>
        </select>
        <input class="button" type="submit" name="AddGroupButton" value="{'Add to group'|i18n( 'design/admin/workflow/view' )}" disabled="disabled" />
    {/section}
    </div>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>



{* Events *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Events [%event_count]'|i18n( 'design/admin/workflow/view',, hash( '%event_count', $event_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th>{'Position'|i18n( 'design/admin/workflow/view' )}</th>
    <th>{'Description'|i18n( 'design/admin/workflow/view' )}</th>
    <th>{'Type'|i18n( 'design/admin/workflow/view' )}</th>
    <th>{'Additional information'|i18n( 'design/admin/workflow/view' )}</th>
</tr>
{section var=Events loop=$event_list sequence=array( bglight, bgdark )}
<tr class="{$Events.sequence}">
    <td>{$Events.item.placement}</td>
    <td>{$Events.item.description}</td>
    <td>{$Events.item.workflow_type.group_name|wash}/{$Events.item.workflow_type.name|wash}</td>
    <td>{event_view_gui event=$Events.item}</td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
