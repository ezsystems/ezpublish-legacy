<form name="grouplistform" action={concat($module.functions.grouplist.uri)|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Workflow groups [%groups_count]'|i18n( 'design/admin/workflow/grouplist',, hash( '%groups_count', $groups|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$groups}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.grouplistform, 'ContentClass_id_checked[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/workflow/grouplist' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/workflow/grouplist' )}</th>
    <th class="tight">&nbsp;</th>

</tr>
{section var=Groups loop=$groups sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">
    <td><input type="checkbox" name="ContentClass_id_checked[]" value="{$Groups.item.id}" title="{'Select workflow group for removal.'|i18n( 'design/admin/workflow/grouplist' )}" /></td>
    <td><a href={concat( $module.functions.workflowlist.uri, '/', $Groups.item.id )|ezurl}>{$Groups.item.name|wash}</a></td>
    <td><a href={concat( $module.functions.groupedit.uri, '/', $Groups.item.id )|ezurl}><img src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/workflow/grouplist' )}" title="{'Edit the <%workflow_group_name> workflow group.'|i18n( 'design/admin/workflow/grouplist',, hash( '%workflow_group_name', $Groups.item.name ) )|wash}" /></a></td>
</tr>
{/section}
{section-else}
<div class="block">
<p>{'There are no workflow groups.'|i18n( 'design/admin/workflow/grouplist' )}</p>
</div>
{/section}

</table>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    {if $groups}
    <input class="button" type="submit" name="DeleteGroupButton" value="{'Remove selected'|i18n( 'design/admin/workflow/grouplist' )}"  title="{'Remove selected workflow groups.'|i18n( 'design/admin/workflow/grouplist' )}"  />
    {else}
    <input class="button-disabled" type="submit" name="DeleteGroupButton" value="{'Remove selected'|i18n( 'design/admin/workflow/grouplist' )}" disabled="disabled" />
    {/if}
    <input class="button" type="submit" name="NewGroupButton" value="{'New workflow group'|i18n( 'design/admin/workflow/grouplist' )}" title="{'Create a new workflow group.'|i18n( 'design/admin/workflow/grouplist' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
