<div class="warning">

{section show=$DeleteResult|count|gt(1)}
    <h2>{'Are you sure you want to remove these class groups?'|i18n( 'design/admin/class/removegroup' )}</h2>
{section-else}
    <h2>{'Are you sure you want to remove this class group?'|i18n( 'design/admin/class/removegroup' )}</h2>
{/section}

<ul>
{section var=ClassGroups loop=$DeleteResult}
	<li>{"Removing class group '%1' will result in the removal of classes %2!"|i18n( 'design/admin/class/removegroup',,array( $ClassGroups.item.groupName, $ClassGroups.item.deletedClassName ) )}</li>
{/section}
</ul>

</div>

<form action={concat( $module.functions.removegroup.uri )|ezurl} method="post" name="GroupRemove">
<input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/class/removegroup' )}">
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/class/removegroup' )}">
</form>
