<form action={concat( $module.functions.groupedit.uri, '/', $workflow_group.id )|ezurl} method="post" name="WorkflowGroupEdit">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Edit <%group_name> [Workflow group]'|i18n( 'design/admin/workflow/groupedit',, hash( '%group_name', $workflow_group.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
    <label>{'Name'|i18n( 'design/admin/workflow/groupedit' )}:</label>
    <input class="box" id="workflowGroupName" type="text" name="WorkflowGroup_name" value="{$workflow_group.name|wash}" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>


{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/workflow/groupedit' )}" />
<input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/workflow/groupedit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

{literal}
<script type="text/javascript">
    window.onload=function()
    {
        document.getElementById('workflowGroupName').select();
        document.getElementById('workflowGroupName').focus();
    }
</script>
{/literal}
