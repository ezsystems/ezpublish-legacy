<form action={'shop/vattype'|ezurl} method="post" name="VatType">

<div class="context-block">
<h2 class="context-title">{'VAT types [%vat_types]'|i18n( 'design/admin/shop/vattype',, hash( '%vat_types', $vattype_array|count ) )}</h2>

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

<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="RemoveVatTypeButton" value="{'Remove selected'|i18n( 'design/standard/shop' )}" {section show=$vattype_array|not}disabled="disabled"{/section}/>
    <input class="button" type="submit" name="AddVatTypeButton" value="{'New VAT type'|i18n( 'design/standard/shop' )}" />
<div class="right">
    <input class="button" type="submit" name="SaveVatTypeButton" value="{'Apply changes'|i18n( 'design/standard/shop' )}" {section show=$vattype_array|not}disabled="disabled"{/section}/>
</div>
</div>
</div>

</div>



</form>