<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<td valign="top">
<p>
<b>{"Customer"|i18n("design/standard/shop")}</b>
</p>
<p>
Name: {$order.account_information.first_name} {$order.account_information.last_name}<br />
Email: {$order.account_information.email}<br />
</p>

</td>
<td valign="top">

<p>
<b>{"Address"|i18n("design/standard/shop")}</b>
<p>
Company: {$order.account_information.street1}<br />
Street: {$order.account_information.street2}<br />
Zip: {$order.account_information.zip}<br />
Place: {$order.account_information.place}<br />
State: {$order.account_information.state}<br />
Country: {$order.account_information.country}<br />
</p>
</td>
</tr>
</table>