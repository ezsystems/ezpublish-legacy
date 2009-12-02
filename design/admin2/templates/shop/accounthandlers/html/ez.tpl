{* Name. *}
<div class="block">
<label>{'Name'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
{let customer_user=fetch( content, object, hash( object_id, $order.user_id ) )}
<a href={$customer_user.main_node.url_alias|ezurl}>{$order.account_information.first_name}&nbsp;{$order.account_information.last_name}</a>
{/let}
</div>

{* Email. *}
<div class="block">
<label>{'Email'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
<a href="mailto:{$order.account_information.email}">{$order.account_information.email}</a>
</div>

{* Address. *}
<div class="block">
<fieldset>
<legend>{'Address'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</legend>

<table class="list" cellspacing="0">
<tr>
    <td>{'Company'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.street1}</td>
</tr>
<tr>
    <td>{'Street'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.street2}</td>
</tr>
<tr>
    <td>{'Zip code'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.zip}</td>
</tr>
<tr>
    <td>{'Place'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.place}</td>
</tr>
<tr>
    <td>{'State'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.state}</td>
</tr>
<tr>
    <td>{'Country/region'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.country}</td>
</tr>
</table>

</fieldset>
</div>
