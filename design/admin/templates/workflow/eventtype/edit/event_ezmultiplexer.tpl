<div class="block">

{* Sections *}
<div class="element">
    <label>{'Affected sections'|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
    <select name="WorkflowEvent_event_ezmultiplexer_section_ids_{$event.id}[]" size="5" multiple="multiple">
    <option value="-1"{section show=$event.selected_sections|contains( -1 )} selected="selected" {/section}>{'All sections'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    {section var=Sections loop=$event.workflow_type.sections}
    <option value="{$Sections.item.value}"{section show=$event.selected_sections|contains( $Sections.item.value )} selected="selected"{/section}>{$Sections.item.name|wash}</option>
    {/section}
    </select>
</div>


{* Classes *}
<div class="element">
    <label>{'Classes to run workflow'|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
    <select name="WorkflowEvent_event_ezmultiplexer_class_ids_{$event.id}[]" size="5" multiple="multiple">
    <option value="-1"{section show=$event.selected_classes|contains( -1 )} selected="selected" {/section}>{'All classes'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    {section var=Classes loop=$event.workflow_type.contentclass_list}
    <option value="{$Classes.item.value}"{section show=$event.selected_classes|contains( $Classes.item.value )} selected="selected"{/section}>{$Classes.item.Name|wash}</option>
    {/section}
    </select>
</div>


{* Users without workflow IDs *}
<div class="element">
    <label>{'Users without workflow IDs'|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
    <select name="WorkflowEvent_event_ezmultiplexer_not_run_ids_{$event.id}[]" size="5" multiple="multiple">
    {section var=Groups loop=$event.workflow_type.usergroups}
    <option value="{$Groups.item.value}"{section show=$event.selected_usergroups|contains( $Groups.item.value )} selected="selected"{/section}>{$Groups.item.name|wash}</option>
    {/section}
    </select>
</div>


{* Workflow to run *}
<div class="element">
    <label>{'Workflow to run'|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
    <select name="WorkflowEvent_event_ezmultiplexer_workflow_id_{$event.id}">
    {section var=Workflows loop=$event.workflow_type.workflow_list}
    <option value="{$Workflows.item.value}"{section show=eq( $Workflows.item.value, $event.selected_workflow )} selected="selected"{/section}>{$Workflows.item.Name|wash}</option>
    {/section}
    </select>
</div>

</div>
