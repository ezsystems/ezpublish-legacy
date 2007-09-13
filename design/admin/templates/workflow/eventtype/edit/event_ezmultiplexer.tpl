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


{* Languages *}
<div class="element">
    <label>{'Affected languages'|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
    <select name="WorkflowEvent_event_ezmultiplexer_languages_{$event.id}[]" size="5" multiple="multiple">
    <option value="-1"
         {section show=eq( count( $event.language_list ), 0 )}
             selected="selected"
         {/section}>
    {'All languages'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    {section var=Language loop=$event.workflow_type.languages}
    <option value="{$Language.item.id}"{section show=is_set( $event.language_list[$Language.item.id] )} selected="selected"{/section}>{$Language.item.name|wash}</option>
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

{* Affected versions option *}
<div class="element">
    <label>{'Affected versions'|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
    <select name="WorkflowEvent_event_ezmultiplexer_version_option_{$event.id}[]" size="3" multiple="multiple">
    <option value="0"{section show=or( lt($event.version_option, 1), gt($event.version_option, 2) )} selected="selected"{/section}>{'All versions'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    <option value="1"{section show=eq( $event.version_option, 1)} selected="selected"{/section}>{'Publishing new object'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    <option value="2"{section show=eq( $event.version_option, 2)} selected="selected"{/section}>{'Updating existing object'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    </select>
</div>

</div>

<div class="block">

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
    {section show=$event.workflow_type.workflow_list}
    <select name="WorkflowEvent_event_ezmultiplexer_workflow_id_{$event.id}">
    {section var=Workflows loop=$event.workflow_type.workflow_list}
    <option value="{$Workflows.item.value}"{section show=eq( $Workflows.item.value, $event.selected_workflow )} selected="selected"{/section}>{$Workflows.item.Name|wash}</option>
    {/section}
    </select>
    {section-else}
    {'You have to create a workflow before using this event.'|i18n( 'design/admin/workflow/eventtype/edit' )}
    {/section}
</div>

</div>
