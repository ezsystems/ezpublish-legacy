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

<form name="extensionform" method="post" action={'/setup/extensions'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header">

<h1 class="context-title">{'Available extensions (%extension_count)'|i18n( 'design/admin/setup/extensions',, hash( '%extension_count', $available_extension_array|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">

{section show=$available_extension_array}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="{'Invert selection.'|i18n( 'design/admin/setup/extensions' )}" title="{'Toggle all.'|i18n( 'design/admin/content/translations' )}" onclick="ezjs_toggleCheckboxes( document.extensionform, 'ActiveExtensionList[]' ); return false;"/></th>
    <th>{'Name'|i18n( 'design/admin/setup/extensions' )}</th>
</tr>
{section var=Extensions loop=$available_extension_array sequence=array( bglight, bgdark )}
<tr class="{$Extensions.sequence}">
    {* Status. *}
    <td><input type="checkbox" name="ActiveExtensionList[]" value="{$Extensions.item}" {if $selected_extension_array|contains($Extensions.item)}checked="checked"{/if} title="{'Activate or deactivate extension. Use the "Update" button to apply the changes.'|i18n( 'design/admin/setup/extensions' )|wash}" /></td>
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

{* DESIGN: Content END *}</div>

<div class='block'>
<div class="controlbar">
{* DESIGN: Control bar START *}
<div class="block">
{if $available_extension_array}
    <input class="button" type="submit" name="ActivateExtensionsButton" value="{'Update'|i18n( 'design/admin/setup/extensions' )}" title="{'Click this button to store changes if you have modified the status of the checkboxes above.'|i18n( 'design/admin/setup/extensions' )}" />
{else}
    <input class="button-disabled" type="submit" name="ActivateExtensionsButton" value="{'Update'|i18n( 'design/admin/setup/extensions' )}" disabled="disabled" />
{/if}
    <input class="button" type="submit" name="GenerateAutoloadArraysButton" value="{'Regenerate autoload arrays for extensions'|i18n( 'design/admin/setup/extensions' )}" title="{'Click this button to regenerate the autoload arrays used by the system for extensions.'|i18n( 'design/admin/setup/extensions' )}" />
</div>
{* DESIGN: Control bar END *}
</div>
</div>

</div>

</form>

{* Highlight "Update" button on changes *}
{literal}
<script type="text/javascript">
$(document).ready(function() {
    var initialExtensionSettings = {};
    var extensionChecks = jQuery('[name=extensionform] :checkbox');

    function styleUpdateButton() {
        var b = jQuery('[name=ActivateExtensionsButton]:first');
        jQuery(extensionChecks).each( function(){
            if (initialExtensionSettings[this.value] !== this.checked) {
                b.removeClass('button').addClass('defaultbutton');
                return false;
            } else {
                b.removeClass('defaultbutton').addClass('button');
            }
        });
    }

    jQuery(extensionChecks).each( function(){
        initialExtensionSettings[this.value] = this.checked;
    }).change(function(){styleUpdateButton();});
});
</script>
{/literal}
