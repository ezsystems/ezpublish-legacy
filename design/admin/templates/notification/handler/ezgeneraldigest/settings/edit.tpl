{let settings=$handler.settings}

<div class="block">
    <label><input type="checkbox" name="ReceiveDigest_{$handler.id_string}" {$settings.receive_digest|choose( '', checked)} /> {'Receive all messages combined in one digest'|i18n( 'design/admin/notification/handler/ezgeneraldigest/settings/edit' )}</label>
    
</div>

<div class="block">
<fieldset>
<legend>{'Receive digests'|i18n( 'design/admin/notification/handler/ezgeneraldigest/settings/edit' )}</legend>
<table cellspacing="4">
<tr>
<td>
<input type="radio" name="DigestType_{$handler.id_string}" value="3" {eq($settings.digest_type,3)|choose('',checked)} />
</td>
<td>
{'Daily, at'|i18n( 'design/admin/notification/handler/ezgeneraldigest/settings/edit' )}
</td>
<td>
<select name="Time_{$handler.id_string}">
{section name=Time loop=$handler.available_hours}
<option value="{$Time:item}" {if eq( $Time:item,$settings.time )}selected="selected"{/if}>{$Time:item}</option>
{/section}
</select>
</td>
</tr>
<tr>
<td>
<input type="radio" name="DigestType_{$handler.id_string}" value="1" {eq( $settings.digest_type, 1 )|choose( '', checked )} />
</td>
<td>
{'Once per week, on '|i18n( 'design/admin/notification/handler/ezgeneraldigest/settings/edit' )}
</td>
<td>
<select name="Weekday_{$handler.id_string}">
{section name=WeekDays loop=$handler.all_week_days}
<option value="{$WeekDays:item}" {if eq( $WeekDays:item, $settings.day )}selected="selected"{/if}>{$WeekDays:item}</option>
{/section}
</select>
</td>
</tr>
<tr>
<td>
<input type="radio" name="DigestType_{$handler.id_string}" value="2" {eq( $settings.digest_type, 2)|choose( '', checked )} />
</td>
<td>
{'Once per month, on day number'|i18n( 'design/admin/notification/handler/ezgeneraldigest/settings/edit' )}
</td>
<td>
<select name="Monthday_{$handler.id_string}">
{section name=MonthDays loop=$handler.all_month_days}
<option value="{$MonthDays:item}" {if eq( $MonthDays:item, $settings.day )}selected="selected"{/if}>{$MonthDays:item}</option>
{/section}
</select>
</td>
</tr>
</table>

{'If day number is larger than the number of days within the current month, the last day of the current month will be used.'|i18n( 'design/admin/notification/handler/ezgeneraldigest/settings/edit' )}
</fieldset>
</div>
{/let}
