{* Type *}
<div class="block">
    <label>{'Type'|i18n( 'design/admin/workflow/eventtype/edit' )}</label>
    <select name="WorkflowEvent_event_ezpaymentgateway_gateways_{$event.id}[]" size="5" multiple="multiple">
    <option value="-1"{section show=$event.selected_gateways_types|contains( -1 )} selected="selected"{/section}>{'All'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    {section var=Gateways loop=$event.workflow_type.available_gateways}
        <option value="{$Gateways.item.value}"{section show=$event.selected_gateways_types|contains( $Gateways.item.value )} selected="selected"{/section}>{$Gateways.item.Name|wash}</option>
    {/section}
     </select>
</div>
