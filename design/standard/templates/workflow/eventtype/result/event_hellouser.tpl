<form action={$return_uri|ezurl} method="post" >

<div class="maincontentheader">
<h1>{"Hello"|i18n("design/standard/workflow/event")} {$user_name}</h1>
</div>
{*
<div class="block">
<input type="text" name="WorkflowEvent_event_ezcheckout_input_data" size="8" maxlength="20" />
</div>
*}
<div class="buttonblock">
<input type="submit" name="Next" value="{'Next'|i18n('design/standard/workflow/event')}" />
</div>

</form>
