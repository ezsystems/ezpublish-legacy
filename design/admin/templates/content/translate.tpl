<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/translate' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">


{* Object ID *}
<p>
<label>{'ID'|i18n( 'design/admin/content/translate' )}:</label>
{$object.id}
</p>

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/translate' )}:</label>
{section show=$object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.current.creator.name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/translate' )}
{/section}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/translate' )}:</label>
{section show=$object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/translate' )}
{/section}
</p>

{* Published version *}
<p>
<label>{'Published version'|i18n( 'design/admin/content/translate' )}:</label>
{section show=$object.published}
{$object.current_version}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/translate' )}
{/section}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

<form name="translationsform" method="post" action={concat( '/content/translate/', $object.id, '/', $edit_version )|ezurl} enctype="multipart/form-data">

{* Removal confirmation *}
{section show=$is_remove_active}


<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Remove translation'|i18n( 'design/admin/content/translate' )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

<h2>{'Are you sure that you want to remove the translation(s)?'|i18n( 'design/admin/content/translate' )}</h2>

<p>{'The following translations (along with translated content) will be removed from the draft:'|i18n( 'design/admin/content/translate' )}</p>

<ul>
{section var=Languages loop=$remove_language_list}
<li>
{section show=$Languages.item.locale.is_valid}
{$Languages.item.locale.intl_language_name}
{section-else}
{'(No locale information available.)'|i18n( 'design/admin/content/translate' )}
{/section}
<input type="hidden" name="RemoveLanguageArray[]" value="{$Languages.item.language_code}" />
</li>
{/section}
</ul>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input type="hidden" name="TranslationLanguageEdit" value="{$translation_language}" />
<input class="button" type="submit" name="RemoveLanguageConfirmationButton" value="{'Yes'|i18n( 'design/admin/content/translate' )}" />
<input class="button" type="submit" name="RemoveLanguageCancelButton" value="{'No'|i18n( 'design/admin/content/translate' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>


{section-else}


{let language_index=0
     translation_list=$content_version.language_list
     trans_list=fetch('content','non_translation_list',hash('object_id',$object.id,'version',$edit_version))}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Translations for <%object_name> [%translation_count]'|i18n( 'design/admin/content/translate',, hash( '%object_name', $object.name, '%translation_count', $translation_list|count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$translation_list}

{section loop=$translation_list}
    {section show=eq( $translation_language, $:item.language_code)}
        {set language_index=$:index}
    {/section}
{/section}

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.translationsform, 'RemoveLanguageArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/translate' )}" /></th>
    <th>{'Language'|i18n( 'design/admin/content/translate' )}</th>
    <th>{'Locale'|i18n( 'design/admin/content/translate' )}</th>
</tr>

{section var=Translations loop=$translation_list sequence=array( bglight, bgdark )}

    <tr class="{$Translations.sequence}">

    <td><input type="checkbox" name="RemoveLanguageArray[]" value="{$Translations.item.language_code}" {section show=eq( $Translations.item.language_code, $content_version.contentobject.default_language)}disabled="disabled"{/section} /></td>

    <td>
    {section show=$Translations.item.locale.is_valid}
        <img src="{$Translations.item.language_code|flag_icon}" alt="{$Translations.item.language_code}" />&nbsp;{$Translations.item.locale.intl_language_name}
    {section-else}
        {'(Unable to display because of unknown locale!)'|i18n( 'design/admin/content/translate' )}
    {/section}
    </td>
    <td>{$Translations.item.language_code}</td>
</tr>
{/section}
</table>

{section-else}
<div class="block">
<p>
{'There are no translations available.'|i18n( 'design/admin/content/translate' )}
</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="DeleteButton" value="{'Remove selected'|i18n( 'design/admin/content/translate' )}" {section show=$content_version.language_list|count|eq(1)}disabled="disabled"{/section} />
</div>
<div class="block">
{section show=$trans_list}
<select name="SelectedLanguage">
{section loop=$trans_list}
<option value="{$:item.locale_code}">{$:item.intl_language_name}</option>
{/section}
</select>
{section-else}
<select name="SelectedLanguage" disabled="disabled">
<option value="-1">{'No languages'|i18n( 'design/admin/content/translate' )}</option>
</select>
{/section}
<input class="button" type="submit" name="AddLanguageButton" value="{'Add translation'|i18n( 'design/admin/content/translate' )}" {section show=$trans_list|not}disabled="disabled"{/section} />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

{/let}

{/section}

</form>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

