<div class="message-warning">

{section show=$DeleteResult|count|gt(1)}
<h2>{'Are you sure you want to remove these classes?'|i18n( 'design/admin/class/removeclass' )}</h2>
{section-else}
<h2>{'Are you sure you want to remove this class?'|i18n( 'design/admin/class/removeclass' )}</h2>
{/section}

{section show=$already_removed}
{let class_list=''}
{section var=class loop=$already_removed}
{set class_list=concat( $class_list, $class.name )}
{delimiter}{set class_list=concat( $class_list, " ," )}{/delimiter}
{/section}
{section show=count( $already_removed )|eq( 1 )}
{"The class %1 was already removed from the group but still exists in others."|i18n( "design/admin/class/removeclass",, array( $class_list ) )}
{section-else}
{"The classes %1 were already removed from the group but still exist in others."|i18n( "design/admin/class/removeclass",, array( $class_list ) )}
{/section}
{/let}
{/section}

<ul>
{section var=Classes loop=$DeleteResult}
    {section show=ne($Classes.item.objectCount,-1)}
	<li>{"Removing class '%1' will result in the removal of %2!"|i18n( 'design/admin/class/removeclass',, array( $Classes.item.className|wash, $Classes.item.objectCount ) )}</li>    {section-else}
    <li>{"Class '%1' can NOT be removed since one of its object is used as system node!"|i18n( 'design/admin/class/removeclass',, array( $Classes.item.className|wash ) )}</li>
    {/section}
{/section}
</ul>

<form action={concat( $module.functions.removeclass.uri, '/', $GroupID )|ezurl} method="post" name="ClassRemove">
<div class="block">
<input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/class/removeclass' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/class/removeclass' )}" />
</div>
</form>

</div>

