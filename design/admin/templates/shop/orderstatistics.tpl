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
     $quantity_text = ''
     $sum_ex_vat_text = ''
     $sum_inc_vat_text = ''
     $br_tag = ''}

<table class="list" cellspacing="0">
<tr>
	<th class="wide">{'Product'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight">{'Quantity'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight">{'Total (ex. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight">{'Total (inc. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
</tr>
{section var=Products loop=$statistic_result[0].product_list sequence=array( bglight, bgdark )}

    {set quantity_text = ''
         sum_ex_vat_text = ''
         sum_inc_vat_text = ''
         br_tag = ''}

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

        {set quantity_text = concat( $quantity_text, $br_tag, $info.sum_count) }
        {set sum_ex_vat_text = concat($sum_ex_vat_text, $br_tag, $info.sum_ex_vat|l10n( 'currency', $locale, $symbol )) }
        {set sum_inc_vat_text = concat($sum_inc_vat_text, $br_tag, $info.sum_inc_vat|l10n( 'currency', $locale, $symbol )) }

        {if $br_tag|not()}
            {set br_tag = '<br />'}
        {/if}

    {/foreach}

    <tr class="{$Products.sequence}">
        {if and( $Products.product, $Products.product.main_node )}
            {let node_url=$Products.product.main_node.url_alias}
                <td class="name">{$Products.product.class_identifier|class_icon( small, $Products.product.class_name )}&nbsp;{if $node_url}<a href={$node_url|ezurl}>{/if}{$Products.product.name|wash}{if $node_url}</a>{/if}</td>
            {/let}
        {else}
            <td class="name">{false()|class_icon( small )}&nbsp;{$Products.name|wash}</td>
        {/if}
        <td class="number" align="right">{$quantity_text}</td>
        <td class="number" align="right">{$sum_ex_vat_text}</td>
        <td class="number" align="right">{$sum_inc_vat_text}</td>
    </tr>

{/section}

{set sum_ex_vat_text = ''
     sum_inc_vat_text = ''
     br_tag = ''}

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

    {set sum_ex_vat_text = concat($sum_ex_vat_text, $br_tag, $info.sum_ex_vat|l10n( 'currency', $locale, $symbol )) }
    {set sum_inc_vat_text = concat($sum_inc_vat_text, $br_tag, $info.sum_inc_vat|l10n( 'currency', $locale, $symbol )) }

    {if $br_tag|not()}
        {set br_tag = '<br />'}
    {/if}

{/foreach}

<tr>
    <td class="name">{concat( '<strong>', 'SUM'|i18n( 'design/admin/shop/orderstatistics' ), '</strong>:' )}</td>
    <td>&nbsp;</td>
    <td class="number" align="right"><strong>{$sum_ex_vat_text}</strong></td>
    <td class="number" align="right"><strong>{$sum_inc_vat_text}</strong></td>
</tr>


</table>

{undef}

{section-else}
<div class="block">
<p>{'The list is empty.'|i18n( 'design/admin/shop/orderstatistics' )}
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">

<select name="Year" title="{'Select the year for which you want to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {if eq($year,0)}selected="selected"{/if}>[{'All years'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=YearValue loop=$year_list}
        <option value="{$YearValue}" {if eq($YearValue,$year)}selected="selected"{/if}>{$YearValue}</option>
    {/section}
</select>

<select name="Month" title="{'Select the month for which you want to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {if eq($month,0)}selected="selected"{/if}>[{'All months'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=MonthItem loop=$month_list}
        <option value="{$MonthItem.value}" {if eq($MonthItem.value,$month)}selected="selected"{/if}>{$MonthItem.name|wash}</option>
    {/section}
</select>

<input class="button" type="submit" name="View" value="{'Show'|i18n( 'design/admin/shop/orderstatistics' )}" title="{'Update the list using the values specified by the menus to the left.'|i18n( 'design/admin/shop/orderstatistics' )}" />

</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
