<form name="languageform" action={$module.functions.translations.uri|ezurl} method="post" >

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Available languages for translation of content [%translations_count]'|i18n( 'design/admin/content/translations',, hash( '%translations_count', $available_translations|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/content/translations' )}" title="{'Invert selection.'|i18n( 'design/admin/content/translations' )}" onclick="ezjs_toggleCheckboxes( document.languageform, 'DeleteIDArray[]' ); return false;"/></th>
    <th>{'Language'|i18n( 'design/admin/content/translations' )}</th>
	<th>{'Country'|i18n( 'design/admin/content/translations' )}</th>
	<th>{'Locale'|i18n( 'design/admin/content/translations' )}</th>
	<th class="tight">{'Translations'|i18n( 'design/admin/content/translations' )}</th>
</tr>

{section var=Translations loop=$available_translations sequence=array( bglight, bgdark )}
<tr class="{$Translations.sequence}">
    {* Remove. *}

	<td>

    {section show=eq( $default_language, $Translations.item.translation.locale_object.locale_code )}
    <input type="checkbox" name="DeleteIDArray[]" value="{$Translations.item.translation.id}" title="{'The default language can not be removed.'|i18n( 'design/admin/content/translations' )}" disabled="disabled" /></td>
    {section-else}
    <input type="checkbox" name="DeleteIDArray[]" value="{$Translations.item.translation.id}" title="{'Select language for removal.'|i18n( 'design/admin/content/translations' )}" /></td>
    {/section}

    {* Language. *}
	<td>
    <img src="{$Translations.item.translation.locale_object.locale_code|flag_icon}" alt="{$Translations.item.translation.locale_object.intl_language_name}" /> 
    <a href={concat( '/content/translations/', $Translations.item.translation.id )|ezurl}>
    {section show=$Translations.item.translation.name|wash}
        {$Translations.item.translation.name|wash}
    {section-else}
        {$Translations.item.translation.locale_object.intl_language_name|wash}
    {/section}</a>
    </td>

    {* Country. *}
	<td>{$Translations.item.translation.locale_object.country_name|wash}</td>

    {* Locale. *}
	<td>{$Translations.item.translation.locale_object.locale_code|wash}</td>

    {* Object count *}
	<td class="number" align="right">{$Translations.item.object_count}</td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/translations' )}" title="{'Remove selected languages.'|i18n( 'design/admin/content/translations' )}" />
<input class="button" type="submit" name="NewButton"    value="{'Add language'|i18n( 'design/admin/content/translations' )}" title="{'Add a new language. The new language can then be used when translating content.'|i18n( 'design/admin/content/translations' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
