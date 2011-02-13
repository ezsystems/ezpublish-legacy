{def $translations=$class.prioritized_languages
     $translations_count=$translations|count}

<form name="translationsform" method="post" action={'class/translation'|ezurl}>
<input type="hidden" name="ContentClassID" value="{$class.id}" />
<input type="hidden" name="ContentClassLanguageCode" value="{$language_code|wash}" />

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Translations (%translations)'|i18n( 'design/admin/class/view',, hash( '%translations', $translations_count ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

<div class="block">
<fieldset>
<legend>{'Existing languages'|i18n( 'design/admin/class/view' )}</legend>

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="{'Invert selection.'|i18n( 'design/admin/class/view' )}" title="{'Invert selection.'|i18n( 'design/admin/class/view' )}" onclick="ezjs_toggleCheckboxes( document.translationsform, 'LanguageID[]' ); return false;"/></th>
    <th>{'Language'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Locale'|i18n( 'design/admin/class/view' )}</th>
    <th class="tight">{'Main'|i18n( 'design/admin/class/view' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Translations loop=$translations sequence=array( bglight, bgdark )}

<tr class="{$Translations.sequence}">

{* Remove. *}
<td>
    <input type="checkbox" name="LanguageID[]" value="{$Translations.item.id}"{if $Translations.item.id|eq($class.initial_language_id)} disabled="disabled"{/if} />
</td>

{* Language name. *}
<td>
<img src="{$Translations.item.locale|flag_icon}" width="18" height="12" alt="{$Translations.item.locale}" />
&nbsp;
{if eq( $Translations.item.locale, $language_code )}
<b><a href={concat( 'class/view/', $class.id, '/(language)/', $Translations.item.locale )|ezurl} title="{'View translation.'|i18n( 'design/admin/class/view' )}">{$Translations.item.name}</a></b>
{else}
<a href={concat( 'class/view/', $class.id, '/(language)/', $Translations.item.locale )|ezurl} title="{'View translation.'|i18n( 'design/admin/class/view' )}">{$Translations.item.name}</a>
{/if}
</td>

{* Locale code. *}
<td>{$Translations.item.locale}</td>

{* Main. *}
<td>

<input type="radio"{if $Translations.item.id|eq($class.initial_language_id)} checked="checked"{/if} name="InitialLanguageID" value="{$Translations.item.id}" title="{'Use these radio buttons to select the desired main language.'|i18n( 'design/admin/class/view' )}" />

</td>

{* Edit. *}
<td>

<a href={concat( 'class/edit/', $class.id, '/(language)/', $Translations.item.locale )|ezurl}><img src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit in <%language_name>.'|i18n( 'design/admin/class/view',, hash( '%language_name', $Translations.item.locale_object.intl_language_name ) )|wash}" title="{'Edit in <%language_name>.'|i18n( 'design/admin/class/view',, hash( '%language_name', $Translations.item.locale_object.intl_language_name ) )|wash}" /></a>

</td>

</tr>

{/section}
</table>

<div class="block">
<div class="button-left">
    {if $translations_count|gt( 1 )}
    <input class="button" type="submit" name="RemoveTranslationButton" value="{'Remove selected'|i18n( 'design/admin/class/view' )}" title="{'Remove selected languages from the list above.'|i18n( 'design/admin/class/view' )}" />
    {else}
    <input class="button-disabled" type="submit" name="RemoveTranslationButton" value="{'Remove selected'|i18n( 'design/admin/class/view' )}" title="{'There is no removable language.'|i18n( 'design/admin/class/view' )}" disabled="disabled" />
    {/if}
</div>

<div class="button-right">
    {if $translations_count|gt( 1 )}
    <input class="button" type="submit" name="UpdateInitialLanguageButton" value="{'Set main'|i18n( 'design/admin/class/view' )}" title="{'Select the desired main language using the radio buttons above then click this button to store the setting.'|i18n( 'design/admin/class/view' )}" />
    {else}
    <input class="button-disabled" type="submit" name="_Disabled" value="{'Set main'|i18n( 'design/admin/class/view' )}" disabled="disabled" title="{'You cannot change the main language because the object is not translated to any other languages.'|i18n( 'design/admin/class/view' )}" />
    {/if}
</div>

<div class="break"></div>
</div>
</fieldset>

</div>

{* DESIGN: Content END *}</div></div></div>

</div>

</form>

{undef $translations
       $translations_count}
