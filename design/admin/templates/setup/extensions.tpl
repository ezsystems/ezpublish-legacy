<form method="post" action={'/setup/extensions'|ezurl}>

<div class="context-block">
<h2 class="context-title">{'Available extensions [%extension_count]'|i18n( 'design/admin/setup/extensions',, hash( '%extension_count', $available_extension_array|count ) )}</h2>

{section show=$available_extension_array}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">{'Active'|i18n( 'design/admin/setup/extensions' )}</th>
    <th>{'Name'|i18n( 'design/admin/setup/extensions' )}</th>
</tr>
{section var=Extensions loop=$available_extension_array sequence=array( bglight, bgdark )}
<tr class="{$Extensions.sequence}">
    <td><input type="checkbox" name="ActiveExtensionList[]" value="{$Extensions.item}" {section show=$selected_extension_array|contains($Extensions.item)}checked="checked"{/section} /></td>
    <td>{$Extensions.item}</td>
</tr>
{/section}
</table>

{section-else}
<p>{'There are no availalbe extensions.'|i18n( 'design/admin/setup/extensions' )}</p>
{/section}

<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="ActivateExtensionsButton" value="{'Apply changes'|i18n( 'design/admin/setup/extensions' )}" {section show=$available_extension_array|not}disabled="disabled"{/section} />
</div>
</div>

</div>

</form>
