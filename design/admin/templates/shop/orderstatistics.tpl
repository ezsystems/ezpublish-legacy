<form action={'/shop/statistics'|ezurl} method="post" name="Statistics">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Product statistics [%count]'|i18n( 'design/admin/shop/orderstatistics',, hash( '%count', $statistic_result[0].product_list|count  ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$statistic_result[0].product_list}

{def $currency = false()
     $locale = false()
     $symbol = false()
     $product_info_count = false()}

<table class="list" cellspacing="0">
<tr>
	<th class="wide">{'Product'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight">{'Quantity'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight">{'Total (ex. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight">{'Total (inc. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
</tr>
{section var=Products loop=$statistic_result[0].product_list sequence=array( bglight, bgdark )}

{set product_info_count = $Products.product_info|count()}
{foreach $Products.product_info as $currency_code => $info}
{if $currency_code}
    {set currency = fetch( 'shop', 'currency', hash( 'code', $currency_code ) )}
{else}
    {set currency = false()}
{/if}

{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{else}
    {set locale = false()
         symbol = false()}
{/if}

<tr class="{$Products.sequence}">
    {if $product_info_count}
        {if and( $Products.product, $Products.product.main_node )}
            {let node_url=$Products.product.main_node.url_alias}
                <td rowspan="{$product_info_count}">{$Products.product.class_identifier|class_icon( small, $Products.product.class_name )}&nbsp;{section show=$node_url}<a href={$node_url|ezurl}>{/section}{$Products.product.name|wash}{section show=$node_url}</a>{/section}</td>
            {/let}
        {else}
            <td rowspan="{$product_info_count}">{false()|class_icon( small )}&nbsp;{$Products.name|wash}</td>
        {/if}
        {set product_info_count = false()}
    {/if}
    <td class="number" align="right">{$info.sum_count}</td>
	<td class="number" align="right">{$info.sum_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
	<td class="number" align="right">{$info.sum_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{/foreach}
{/section}
{def $total_sum_info_count = $statistic_result[0].total_sum_info|count()}

{foreach $statistic_result[0].total_sum_info as $currency_code => $info}

{if $currency_code}
    {set currency = fetch( 'shop', 'currency', hash( 'code', $currency_code ) )}
{else}
    {set currency = false()}
{/if}

{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{else}
    {set locale = false()
         symbol = false()}
{/if}

<tr>
    {if $total_sum_info_count}
	    <td rowspan="{$total_sum_info_count}">{concat( '<strong>', 'SUM'|i18n( 'design/admin/shop/orderstatistics' ), '</strong>:' )}</td>
        {set total_sum_info_count = false()}
    {/if}
    <td>&nbsp;</td>
	<td class="number" align="right"><strong>{$info.sum_ex_vat|l10n( 'currency', $locale, $symbol )}</strong></td>
	<td class="number" align="right"><strong>{$info.sum_inc_vat|l10n( 'currency', $locale, $symbol )}</strong></td>
</tr>

{/foreach}

</table>

{undef $currency $locale $symbol $product_info_count $total_sum_info_count}

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
