<h2>{'Are you sure that you want to remove the following order(s)?'|i18n( 'design/admin/shop/removeorder' )}</h2>

{$delete_result}

<form action={concat( $module.functions.removeorder.uri )|ezurl} method="post" name="OrderRemove">
<input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/shop/removeorder' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/shop/removeorder' )}" />
</form>