<form action="{$module.functions.edit.uri}/{$rule_type}/{$rule_id}" method="post" name="Edit">
<h1>Notification registration form</h1>
<table>
{switch match=$rule_type}
  {case match=advanced}
   {include uri="design:notification/rules/ezadvanced.tpl"}
  {/case}
  {case match=general}
   {include uri="design:notification/rules/ezgeneral.tpl"}
  {/case}
  {case match=keyword}
   {include uri="design:notification/rules/ezkeyword.tpl"}
  {/case}
{/switch}
<tr>
<td>
Send Method:
</td>
<td>
<select name="sendMethod">
<option value="email" {section show=eq($userlink_list.send_method,'email')}selected{/section}>Email</option>
<option value="sms" {section show=eq($userlink_list.send_method,'sms')}selected{/section}>SMS</option>
<option value="internal message" {section show=eq($userlink_list.send_method,'internal message')}selected{/section}>Internal message</option>
</select>
</td>
</tr>
<tr>
<td>
Send time:
</td>
<td>
<select name="sendTime_week">
<option value="-1" {section show=eq($userlink_list.send_weekday,-1)}selected{/section}>Immediately</option>
<option value="1" {section show=eq($userlink_list.send_weekday,1)}selected{/section}>monday</option>
<option value="2" {section show=eq($userlink_list.send_weekday,2)}selected{/section}>tuesday</option>
<option value="3" {section show=eq($userlink_list.send_weekday,3)}selected{/section}>wednesday</option>
<option value="4" {section show=eq($userlink_list.send_weekday,4)}selected{/section}>thursday</option>
<option value="5" {section show=eq($userlink_list.send_weekday,5)}selected{/section}>friday</option>
<option value="6" {section show=eq($userlink_list.send_weekday,6)}selected{/section}>saturday</option>
<option value="7" {section show=eq($userlink_list.send_weekday,7)}selected{/section}>sunday</option>
</select>
</td>
<td>
<select name="sendTime_hour">
<option value="-1" {section show=eq($userlink_list.send_time,-1)}selected{/section}>x:00</option>
<option value="1" {section show=eq($userlink_list.send_time,1)}selected{/section}>1:00</option>
<option value="2" {section show=eq($userlink_list.send_time,2)}selected{/section}>2:00</option>
<option value="3" {section show=eq($userlink_list.send_time,3)}selected{/section}>3:00</option>
<option value="4" {section show=eq($userlink_list.send_time,4)}selected{/section}>4:00</option>
<option value="5" {section show=eq($userlink_list.send_time,5)}selected{/section}>5:00</option>
<option value="6" {section show=eq($userlink_list.send_time,6)}selected{/section}>6:00</option>
<option value="7" {section show=eq($userlink_list.send_time,7)}selected{/section}>7:00</option>
<option value="8" {section show=eq($userlink_list.send_time,8)}selected{/section}>8:00</option>
<option value="9" {section show=eq($userlink_list.send_time,9)}selected{/section}>9:00</option>
<option value="10" {section show=eq($userlink_list.send_time,10)}selected{/section}>10:00</option>
<option value="11" {section show=eq($userlink_list.send_time,11)}selected{/section}>11:00</option>
<option value="12" {section show=eq($userlink_list.send_time,12)}selected{/section}>12:00</option>
<option value="13" {section show=eq($userlink_list.send_time,13)}selected{/section}>13:00</option>
<option value="14" {section show=eq($userlink_list.send_time,14)}selected{/section}>14:00</option>
<option value="15" {section show=eq($userlink_list.send_time,15)}selected{/section}>15:00</option>
<option value="16" {section show=eq($userlink_list.send_time,16)}selected{/section}>16:00</option>
<option value="17" {section show=eq($userlink_list.send_time,17)}selected{/section}>17:00</option>
<option value="18" {section show=eq($userlink_list.send_time,18)}selected{/section}>18:00</option>
<option value="19" {section show=eq($userlink_list.send_time,19)}selected{/section}>19:00</option>
<option value="20" {section show=eq($userlink_list.send_time,20)}selected{/section}>20:00</option>
<option value="21" {section show=eq($userlink_list.send_time,21)}selected{/section}>21:00</option>
<option value="22" {section show=eq($userlink_list.send_time,22)}selected{/section}>22:00</option>
<option value="23" {section show=eq($userlink_list.send_time,23)}selected{/section}>23:00</option>
<option value="24" {section show=eq($userlink_list.send_time,24)}selected{/section}>24:00</option>
</select>
</td>
</tr>
</table>

<br />
<table width="100%">
<tr>
<input type="hidden" name="CurrentRuleID" value="{$rule_id}">
<td>{include uri="design:gui/button.tpl" name=Store id_name=StoreRuleButton value=Register}</td>
<td>{include uri="design:gui/button.tpl" name=Discard id_name=DiscardRuleButton value=Discard}</td>
<td width="99%"></td>
</tr>
</table>

</form>


