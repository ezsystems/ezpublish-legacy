<form action={'/shop/statistics'|ezurl} method="post" name="Statistics">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Product statistics [%count]'|i18n( 'design/admin/shop/orderstatistics',, hash( '%count', $statistic_result[0].product_list|count  ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$statistic_result[0].product_list}
<table class="list" cellspacing="0">
<tr>
	<th class="wide">{'Product'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight">{'Quantity'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight">{'Total (ex. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight">{'Total (inc. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
</tr>
{section var=Products loop=$statistic_result[0].product_list sequence=array( bglight, bgdark )}
<tr class="{$Products.sequence}">
    {section show=and( $Products.product, $Products.product.main_node )}
    {let node_url=$Products.product.main_node.url_alias}
    <td>{$Products.product.class_identifier|class_icon( small, $Products.product.class_name )}&nbsp;{section show=$node_url}<a href={$node_url|ezurl}>{/section}{$Products.product.name|wash}{section show=$node_url}</a>{/section}</td>
    {/let}
    {section-else}
    <td>{false()|class_icon( small )}&nbsp;{$Products.name|wash}</td>
    {/section}
    <td class="number" align="right">{$Products.sum_count}</td>
	<td class="number" align="right">{$Products.sum_ex_vat|l10n(currency)}</td>
	<td class="number" align="right">{$Products.sum_inc_vat|l10n(currency)}</td>
</tr>
{/section}
<tr>
	<td><strong>{'SUM'|i18n( 'design/admin/shop/orderstatistics' )}</strong>:</td>
    <td>&nbsp;</td>
	<td class="number" align="right"><strong>{$statistic_result[0].total_sum_ex_vat|l10n(currency)}</strong></td>
	<td class="number" align="right"><strong>{$statistic_result[0].total_sum_inc_vat|l10n(currency)}</strong></td>
</tr>
</table>
{section-else}
<div class="block">
<p>{'The list is empty.'|i18n( 'design/admin/shop/orderstatistics' )}
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">

<select name="Year" title="{'Select the year for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($year,0)}selected="selected"{/section}>[{'All years'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=YearValue loop=$year_list}
        <option value="{$YearValue}" {section show=eq($YearValue,$year)}selected="selected"{/section}>{$YearValue}</option>
    {/section}
</select>

<select name="Month" title="{'Select the month for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($month,0)}selected="selected"{/section}>[{'All months'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=MonthItem loop=$month_list}
        <option value="{$MonthItem.value}" {section show=eq($MonthItem.value,$month)}selected="selected"{/section}>{$MonthItem.name|wash}</option>
    {/section}
</select>

<input class="button" type="submit" name="View" value="{'Show'|i18n( 'design/admin/shop/orderstatistics' )}" title="{'Update the list using the values specified by the menus on the left.'|i18n( 'design/admin/shop/orderstatistics' )}" />

</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
