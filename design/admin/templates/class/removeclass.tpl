<div class="message-warning">

{section show=$DeleteResult|count|gt(1)}
<h2>{'Are you sure you want to remove these classes?'|i18n( 'design/admin/class/removeclass' )}</h2>
{section-else}
<h2>{'Are you sure you want to remove this class?'|i18n( 'design/admin/class/removeclass' )}</h2>
{/section}

<ul>
{section var=Classes loop=$DeleteResult}
	<li>{"Removing class '%1' will result in the removal of %2!"|i18n( 'design/admin/class/removeclass',, array( $Classes.item.className|wash, $Classes.item.objectCount ) )}</li>
{/section}
</ul>

<form action={concat( $module.functions.removeclass.uri, '/', $GroupID )|ezurl} method="post" name="ClassRemove">
<div class="block">
<input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/class/removeclass' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/class/removeclass' )}" />
</div>
</form>

</div>

