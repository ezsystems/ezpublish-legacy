<input type="radio" name="WorkflowEvent_event_ezpublish_publish_{$event.id}" value="0" {$event.data_int1|not|choose("",'checked="checked"')} /> Unpublish object<br />
<input type="radio" name="WorkflowEvent_event_ezpublish_publish_{$event.id}" value="1" {$event.data_int1|not|choose('checked="checked"',"")} /> Publish object
