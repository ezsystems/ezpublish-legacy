<form action={concat( $module.functions.discountgroupedit.uri, '/', $discount_group.id )|ezurl} method="post" name="DiscountGroupEdit">
<div class="context-block">
<h2 class="context-title">{'Edit <%discount_group> [Discount group]'|i18n( 'design/standard/shop/editdiscountgroup',, hash( '%discount_group', $discount_group.name ) )|wash}</h2>

<div class="context-attributes">

<div class="block">
    <label>{'Name'|i18n( 'design/admin/shop/editdiscountgroup' )}</label>
    <input type="text" name="discount_group_name" value="{$discount_group.name}" size=40>
</div>

</div>

{* Buttons. *}
<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="ApplyButton" value="{'OK'|i18n( 'design/admin/shop/editdiscountgroup' )}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/shop/editdiscountgroup' )}" />
</div>
</div>

</div>


</form>
