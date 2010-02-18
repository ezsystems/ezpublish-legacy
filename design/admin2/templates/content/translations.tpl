<form name="languageform" action={$module.functions.translations.uri|ezurl} method="post" >

<div class="context-block content-translations">
{* DESIGN: Header START *}<div class="box-header">
<h1 class="context-title">{'Available languages for translation of content (%translations_count)'|i18n( 'design/admin/content/translations',, hash( '%translations_count', $available_translations|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/content/translations' )}" title="{'Invert selection.'|i18n( 'design/admin/content/translations' )}" onclick="ezjs_toggleCheckboxes( document.languageform, 'DeleteIDArray[]' ); return false;"/></th>
    <th>{'Language'|i18n( 'design/admin/content/translations' )}</th>
    <th>{'Country/region'|i18n( 'design/admin/content/translations' )}</th>
    <th>{'Locale'|i18n( 'design/admin/content/translations' )}</th>
    <th class="tight">{'Translations'|i18n( 'design/admin/content/translations' )}</th>
    <th class="tight">{'Classes translations'|i18n( 'design/admin/content/translations' )}</th>
</tr>

{section var=Translations loop=$available_translations sequence=array( bglight, bgdark )}
{def $object_count=$Translations.item.object_count}
{def $class_count=$Translations.item.class_count}
<tr class="{$Translations.sequence}">
    {* Remove. *}
    <td>
    {if or($object_count, $class_count)}
        <input type="checkbox" name="DeleteIDArray[]" value="" title="{'The language cannot be removed because it is in use.'|i18n( 'design/admin/content/translations' )}" disabled="disabled" />
    {else}
        <input type="checkbox" name="DeleteIDArray[]" value="{$Translations.item.translation.id}" title="{'Select language for removal.'|i18n( 'design/admin/content/translations' )}" />
    {/if}
    </td>

    {* Language. *}
    <td>
    <img src="{$Translations.item.translation.locale_object.locale_code|flag_icon}" alt="{$Translations.item.translation.locale_object.intl_language_name}" />
    <a href={concat( '/content/translations/', $Translations.item.translation.id )|ezurl}>
    {if $Translations.item.translation.name|wash}
        {$Translations.item.translation.name|wash}
    {else}
        {$Translations.item.translation.locale_object.intl_language_name|wash}
    {/if}</a>
    </td>

    {* Country/region. *}
    <td>{$Translations.item.translation.locale_object.country_name|wash}</td>

    {* Locale. *}
    <td>{$Translations.item.translation.locale_object.locale_code|wash}</td>

    {* Object count *}
    <td class="number" align="right">{$object_count}</td>

    {* Class count *}
    <td class="number" align="right">{$class_count}</td>
</tr>
{undef $object_count}
{undef $class_count}
{/section}
</table>

{* DESIGN: Content END *}</div>

<div class="controlbar">
{* DESIGN: Control bar START *}
<div class="block">
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/translations' )}" title="{'Remove selected languages.'|i18n( 'design/admin/content/translations' )}" />
<input class="button" type="submit" name="NewButton"    value="{'Add language'|i18n( 'design/admin/content/translations' )}" title="{'Add a new language. The new language can then be used when translating content.'|i18n( 'design/admin/content/translations' )}" />
</div>
{* DESIGN: Control bar END *}
</div>

</div>

</form>
