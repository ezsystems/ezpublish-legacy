<h1>{"Checkout"|i18n('workflow/eventtype/result/event_ezcheckout')}</h1>

<form action={concat('content/view/',$viewmode,'/',$node.node_id,'/')|ezurl} method="post" >
<input type="text" name="WorkflowEvent_event_ezcheckout_input_data" size="8" maxlength="20">
<input type="submit" name="Next" value="next">
</form>

