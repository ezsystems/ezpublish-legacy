<form action={concat( $module.functions.groupedit.uri, '/', $workflow_group.id )|ezurl} method="post" name="WorkflowGroupEdit">

<div class="context-block">
<h2 class="context-title">{'Edit'|i18n( 'design/admin/workflow/groupedit',, array( $workflow_group.name ) )}&nbsp;<i>{$workflow_group.name|wash}</i>&nbsp;[{'Workflow group'|i18n( 'design/admin/workflow/groupedit' )}]</h2>

<div class="context-attributes">
<div class="block">
<label>{'Name'|i18n( 'design/admin/workflow/groupedit' )}</label>
{include uri="design:gui/lineedit.tpl" name=Name id_name=WorkflowGroup_name value=$workflow_group.name}
</div>
</div>

{* Buttons. *}
<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/workflow/groupedit' )}" />
<input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/workflow/groupedit' )}" />
</div>
</div>

</div>

</form>
