<h1>{"Order list"|i18n}</h1>



<table width="100%">
{section name="Order" loop=$order_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Order:sequence}">
	{$Order:item.id} - {$Order:item.created|l10n(date)}
	<a href="/shop/orderview/{$Order:item.id}/">[ view ]</a>
	</td>
</tr>
{/section}
</table>