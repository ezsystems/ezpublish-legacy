<div class="warning">

{section show=$delete_result|count|gt(1)}
    <h2>{'Are you sure you want to remove these sections?'|i18n( 'design/admin/section/confirmremove' )}</h2>
{section-else}
    <h2>{'Are you sure you want to remove this section?'|i18n( 'design/admin/section/confirmremove' )}</h2>
{/section}

<ul>
{section name=Result loop=$delete_result}
    <li>{$Result:item.name} ({$Result:item.id})</li>
{/section}
</ul>
<p>{'Removing a section may corrupt permission settings, template output and other things in the system. Proceed only if you know what you are doing.'|i18n( 'design/admin/section/confirmremove' )}</p>
</div>

<form action={concat( $module.functions.list.uri )|ezurl} method="post" name="SectionRemove">
<input class="button" type="submit" name="ConfirmRemoveSectionButton" value="{'OK'|i18n( 'design/admin/section/confirmremove' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/section/confirmremove' )}" />
</form>
