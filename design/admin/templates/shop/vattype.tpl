<form action={'shop/vattype'|ezurl} method="post" name="VatType">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'VAT types [%vat_types]'|i18n( 'design/admin/shop/vattype',, hash( '%vat_types', $vattype_array|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$vattype_array}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th>{'Name'|i18n( 'design/admin/shop/vattype' )}</th>
    <th>{'Percentage'|i18n( 'design/admin/shop/vattype' )}</th>
</tr>

{section var=Vattypes loop=$vattype_array sequence=array( bglight, bgdark )}
<tr class="{$Vattypes.sequence}">
    <td><input type="checkbox" name="vatTypeIDList[]" value="{$Vattypes.item.id}" /></td>
    <td><input type="text" name="vattype_name_{$Vattypes.item.id}" value="{$Vattypes.item.name}" size="15"></td>
    <td><input type="text" name="vattype_percentage_{$Vattypes.item.id}" value="{$Vattypes.item.percentage}" size="4">&nbsp;%</td>
</tr>
{/section}
</table>
{section-else}

{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="RemoveVatTypeButton" value="{'Remove selected'|i18n( 'design/admin/shop/vattype' )}" {section show=$vattype_array|not}disabled="disabled"{/section}/>
    <input class="button" type="submit" name="AddVatTypeButton" value="{'New VAT type'|i18n( 'design/admin/shop/vattype' )}" />
<div class="right">
    <input class="button" type="submit" name="SaveVatTypeButton" value="{'Apply changes'|i18n( 'design/admin/shop/vattype' )}" {section show=$vattype_array|not}disabled="disabled"{/section}/>
</div>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
