{* Type *}
<div class="block">
    <label>{'Type'|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
	{section show=ne($event.workflow_type.available_gateways|count(), 0)}
    <select name="WorkflowEvent_event_ezpaymentgateway_gateways_{$event.id}[]" size="5" multiple="multiple">
    <option value="-1"{if $event.selected_gateways_types|contains( -1 )} selected="selected"{/if}>{'All'|i18n( 'design/admin/workflow/eventtype/edit' )}</option>
    {section var=Gateways loop=$event.workflow_type.available_gateways}
        <option value="{$Gateways.item.value}"{if $event.selected_gateways_types|contains( $Gateways.item.value )} selected="selected"{/if}>{$Gateways.item.Name|wash}</option>
    {/section}
     </select>
	 {section-else}
	    <br/>
		<div class="error">
	 	{"There are no payment gateway extensions installed."|i18n('design/admin/workflow/eventtype/edit')}
	 	{"Please install a payment extension first."|i18n('design/admin/workflow/eventtype/edit')}
	 	</div>
	 {/section}
</div>
