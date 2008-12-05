{if and( is_set( $warning_messages), $warning_messages|count|ge(1) )}
    <div class="message-warning">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Problems detected during autoload generation:'|i18n( 'design/admin/setup/extensions' )}</h2>
    <ul>
    {foreach $warning_messages as $warning}
        <li><p>{$warning|break()}</p></li>
    {/foreach}
    </ul>
    </div>
{/if}

<form method="post" action={'/setup/extensions'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Available extensions [%extension_count]'|i18n( 'design/admin/setup/extensions',, hash( '%extension_count', $available_extension_array|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$available_extension_array}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">{'Active'|i18n( 'design/admin/setup/extensions' )}</th>
    <th>{'Name'|i18n( 'design/admin/setup/extensions' )}</th>
</tr>
{section var=Extensions loop=$available_extension_array sequence=array( bglight, bgdark )}
<tr class="{$Extensions.sequence}">

    {* Status. *}
    <td><input type="checkbox" name="ActiveExtensionList[]" value="{$Extensions.item}" {section show=$selected_extension_array|contains($Extensions.item)}checked="checked"{/section} title="{'Activate or deactivate extension. Use the "Apply changes" button to apply the changes.'|i18n( 'design/admin/setup/extensions' )|wash}" /></td>

    {* Name. *}
    <td>{$Extensions.item}</td>

</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'There are no available extensions.'|i18n( 'design/admin/setup/extensions' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
{section show=$available_extension_array}
<input class="button" type="submit" name="ActivateExtensionsButton" value="{'Apply changes'|i18n( 'design/admin/setup/extensions' )}" title="{'Click this button to store changes if you have modified the status of the checkboxes above.'|i18n( 'design/admin/setup/extensions' )}" />
{section-else}
<input class="button-disabled" type="submit" name="ActivateExtensionsButton" value="{'Apply changes'|i18n( 'design/admin/setup/extensions' )}" disabled="disabled" />
{/section}
<input class="button" type="submit" name="GenerateAutoloadArraysButton" value="{'Regenerate autoload arrays for extensions'|i18n( 'design/admin/setup/extensions' )}" title="{'Click this button to regenerate the autoload arrays used by the system for extensions.'|i18n( 'design/admin/setup/extensions' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
