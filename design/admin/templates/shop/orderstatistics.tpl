<form action={'/shop/statistics'|ezurl} method="post" name="Statistics">

<div class="context-block">
<h2 class="context-title">{'Product statistics [%count]'|i18n( 'design/admin/shop/orderstatistics',, hash( '%count', $statistic_result[0].product_list|count  ) )}</h2>

{section show=$statistic_result}
<table class="list" cellspacing="0">
<tr>
	<th>{'Product'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th>{'Amount'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th>{'Total (ex. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th>{'Total (inc. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
</tr>
{section var=Products loop=$statistic_result[0].product_list sequence=array( bglight, bgdark )}
<tr class="{$Products.sequence}">
	<td>{content_view_gui view=text_linked content_object=$Products.product}</td>
    <td>{$Products.sum_count}</td>
	<td>{$Products.sum_ex_vat|l10n(currency)}</td>
	<td>{$Products.sum_inc_vat|l10n(currency)}</td>
</tr>
{/section}
<tr>
	<td><b>{'SUM:'|i18n( 'design/admin/shop/orderstatistics' )}</b></td>
    <td>&nbsp;</td>
	<td><b>{$statistic_result[0].total_sum_ex_vat|l10n(currency)}</b></td>
	<td><b>{$statistic_result[0].total_sum_inc_vat|l10n(currency)}</b></td>
</tr>
</table>
{/section}

<div class="controlbar">
<div class="block">

<select name="Year">
    <option value="0" {section show=eq($year,0)}selected="selected"{/section}>[{"All years"|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=YearValue loop=$year_list}
        <option value="{$YearValue}" {section show=eq($YearValue,$year)}selected="selected"{/section}>{$YearValue}</option>
    {/section}
</select>

<select name="Month">
    <option value="0" {section show=eq($month,0)}selected="selected"{/section}>[{"All months"|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=MonthValue loop=$month_list}
        <option value="{$MonthValue}" {section show=eq($MonthValue,$month)}selected="selected"{/section}>{$MonthValue}</option>
    {/section}
</select>

<input class="button" type="submit" name="View" value="{'Update list'|i18n( 'design/admin/shop/orderstatistics' )}" />

</div>
</div>


</div>

</form>
