<div class="block">

{* Sections *}
<div class="element">
    <label>{'Affected sections'|i18n( 'design/admin/workflow/eventtype/edit' )}</label>
    <select name="WorkflowEvent_event_ezapprove_section_{$event.id}[]" size="5" multiple >
    <option value="-1"{section show=$event.selected_sections|contains( -1 )} selected="selected" {/section}>{'All sections'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    {section var=Sections loop=$event.workflow_type.sections}
    <option value="{$Sections.item.value}"{section show=$event.selected_sections|contains( $Sections.item.value )} selected="selected"&nbsp; {/section}>{$Sections.item.name|wash}</option>
    {/section}
    </select>
</div>


{* User who functions as approver *}
<div class="element">
    <label>{'User who approves content'|i18n( 'design/admin/workflow/eventtype/edit' )}</label>
    <select name="WorkflowEvent_event_ezapprove_editor_{$event.id}[]" size="5">
    {section var=Users loop=$event.workflow_type.users}
    <option value="{$Users.item.value}" {section show=$event.selected_users|contains( $Users.item.value )}selected="selected"{/section}>{$Users.item.Name|wash}&nbsp;({$Users.item.value})</option>
    {/section}
    </select>
</div>


{* Excluded users & groups *}
<div class="element">
    <label>{'Excluded users and groups'|i18n( 'design/admin/workflow/eventtype/edit' )}</label>
    <select name="WorkflowEvent_event_ezapprove_groups_{$event.id}[]" size="5" multiple>
    <option value="-1"{section show=$event.selected_usergroups|contains( -1 )} selected="selected" {/section}>{'All users and groups'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    {section var=Groups loop=$event.workflow_type.usergroups}
    <option value="{$Groups.item.value}"{section show=$event.selected_usergroups|contains( $Groups.item.value )} selected="selected" {/section}>{$Groups.item.name|wash} ({$Groups.item.value})</option>
    {/section}
    </select>
</div>

</div>