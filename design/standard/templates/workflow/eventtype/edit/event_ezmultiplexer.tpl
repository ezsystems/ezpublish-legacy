<table with="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
        <div class="block">
            <label>{"Section IDs"|i18n("design/standard/workflow/eventtype/edit")}</label><div class="labelbreak"></div>
            <input class="box" type="text" name="WorkflowEvent_event_ezmultiplexer_section_ids_{$event.id}" size="50" value="{$event.data_text1}" maxlength="50" />
	</div>
    </td>
    <td>
        <div class="block">
            <label>{"Users without workflow IDs"|i18n("design/standard/workflow/eventtype/edit")}</label><div class="labelbreak"></div>
            <input class="box" type="text" name="WorkflowEvent_event_ezmultiplexer_not_run_ids_{$event.id}" size="50" value="{$event.data_text2}" maxlength="50" />
        </div>
    </td>
</tr>
<tr>
    <td>
        <div class="block">
            <label>{"IDs of classes to run workflow"|i18n("design/standard/workflow/eventtype/edit")}</label><div class="labelbreak"></div>
            <input class="box" type="text" name="WorkflowEvent_event_ezmultiplexer_class_ids_{$event.id}" size="50" value="{$event.data_text3}" maxlength="50" />
        </div>
    </td>
    <td>
        <div class="block">
            <label>{"ID of workflow to run"|i18n("design/standard/workflow/eventtype/edit")}</label><div class="labelbreak"></div>
            <input class="box" type="text" name="WorkflowEvent_event_ezmultiplexer_workflow_id_{$event.id}" size="50" value="{$event.data_int1}" maxlength="50" />
        </div>
    </td>
</tr>
</table>
