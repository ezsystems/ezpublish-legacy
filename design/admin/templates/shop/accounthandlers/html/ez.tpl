{* Name. *}
<div class="block">
<label>{'Name'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
{let customer_user=fetch( content, object, hash( object_id, $order.user_id ) )}
<a href={$customer_user.main_node.url_alias|ezurl}>{$order.account_information.first_name}&nbsp;{$order.account_information.last_name}</a>
{/let}
</div>

{* Email. *}
<div class="block">
<label>{'E-mail'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
<a href="mailto:{$order.account_information.email}">{$order.account_information.email}</a>
</div>

{* Address. *}
<div class="block">
<fieldset>
<legend>{'Address'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</legend>
<label>{'Company'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
{$order.account_information.street1}
<label>{'Street'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
{$order.account_information.street2}
<label>{'Zip code'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
{$order.account_information.zip}
<label>{'Place'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
{$order.account_information.place}
<label>{'State'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
{$order.account_information.state}
<label>{'Country'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
{$order.account_information.country}
</fieldset>
</div>
