<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>Object info</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<p>
<label>{"Created"|i18n("design/standard/content/edit")}:</label>
{section show=$object.published}
{$object.published|l10n(date)}<br />
{$object.current.creator.name}
{section-else}
{"Not yet published"|i18n("design/standard/content/edit")}
{/section}
</p>
<p>
<label>{"Last Modified"|i18n("design/standard/content/edit")}:</label>
{section show=$object.modified}
{$object.modified|l10n(date)}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name}
{section-else}
{"Not yet published"|i18n("design/standard/content/edit")}
{/section}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

<form enctype="multipart/form-data" name="translationsform" method="post" action={concat( '/content/translate/', $object.id, '/', $edit_version )|ezurl}>

{* Validation feedback *}
{section show=$validation.processed}
    {section var=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
    <div class="message-warning">
    <h2>{'Input did not validate'|i18n( 'design/admin/content/translate' )}</h2>
    <ul>
    	<li>{$UnvalidatedAttributes.item.name|wash}: {$UnvalidatedAttributes.item.description}</li>
    </ul>
    </div>
    {section-else}
    <div class="message-feedback">
    <h2>{'%language input was stored successfully'|i18n( 'design/admin/content/translate',, hash( '%language', $validation.locale.intl_language_name ) )}</h2>
    </div>
    {/section}
{/section}


{* Removal confirmation *}
{section show=$is_remove_active}
<p>{'Are you sure that you want to remove the following translations for <%object_name>?'|i18n( 'design/admin/content/translate',, hash( '%object_name', $object.name ) )|wash}</p>
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

<input type="hidden" name="TranslationLanguageEdit" value="{$translation_language}" />
<input class="button" type="submit" name="RemoveLanguageConfirmationButton" value="{'OK'|i18n( 'design/admin/content/translate' )}" />
<input class="button" type="submit" name="RemoveLanguageCancelButton" value="{'Cancel'|i18n( 'design/admin/content/translate' )}" />

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
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.translationsform, 'RemoveLanguageArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/trash' )}" /></th>
    <th>{'Language'|i18n( 'design/admin/content/translate' )}</th>
    <th>{'Locale'|i18n( 'design/admin/content/translate' )}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Translations loop=$translation_list sequence=array( bglight, bgdark )}

    <tr class="{$Translations.sequence}">

    <td><input type="checkbox" name="RemoveLanguageArray[]" value="{$Translations.item.language_code}" {section show=eq( $Translations.item.language_code, $content_version.contentobject.default_language)}disabled="disabled"{/section} /></td>

    <td>
    {section show=$Translations.item.locale.is_valid}
        <img src={concat( '/share/icons/flags/', $Translations.item.language_code, '.gif')|ezroot}>&nbsp;{$Translations.item.locale.intl_language_name}
    {section-else}
        {'(Unable to display because of unknown locale!)'|i18n( 'design/admin/content/translate' )}
    {/section}
    </td>
    <td>{$Translations.item.language_code}</td>
    <td><a href={concat( 'content/translate/', $content_version.contentobject.id, '/', $content_version.version, '/', $Translations.item.language_code )|ezurl}><img src={'trash.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Translate'|i18n( 'design/admin/node/view/full' )}" /></a></td>
<td><a href={concat( 'content/edit/', $content_version.contentobject.id, '/', $content_version.version, '/', $Translations.item.language_code )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit'|i18n( 'design/admin/node/view/full' )}" /></a></td>
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
<input class="button" type="submit" name="DeleteButton" value="{'Remove selected'|i18n('design/standard/content/translate')}" {section show=$content_version.language_list|count|eq(1)}disabled="disabled"{/section} />
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
<option value="-1">{'No languages'|i18n('design/standard/content/translate')}</option>
</select>
{/section}
<input class="button" type="submit" name="AddLanguageButton" value="{'Add translation'|i18n('design/standard/content/translate')}" {section show=$trans_list|not}disabled="disabled"{/section} />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

{/let}




{* ---------------------- Side-by-side translation ------------------------ *}

{section show=$translation_language}

<div class="content-translation">
<div class="context-block">

<input type="hidden" name="TranslationLanguageEdit" value="{$translation_language}" />

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{$original_locale.intl_language_name} ({$original_locale.locale_code}) --&gt; {$translation_locale.intl_language_name} ({$translation_locale.locale_code})</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

{section name=ContentAttribute loop=$content_attributes}
{section-exclude match=$content_attribute_map[$ContentAttribute:item.contentclassattribute_id].contentclass_attribute.data_type.properties.translation_allowed|not}

<div class="block">

<label>{$ContentAttribute:item.contentclass_attribute.name|wash}:</label>

<div class="original">
{attribute_view_gui attribute=$ContentAttribute:item}
</div>

<div class="translation">
{let translation=$content_attribute_map[$ContentAttribute:item.contentclassattribute_id]}
    {section show=$ContentAttribute:translation}
        {section show=and(eq($ContentAttribute:translation.contentclass_attribute.can_translate,0),
                          ne($object.default_language,$ContentAttribute:translation.language_code) ) }
           {attribute_view_gui attribute=$ContentAttribute:translation}
        {section-else}
           {attribute_edit_gui attribute=$ContentAttribute:translation}
        {/section}
    {/section}
{/let}
</div>

</div>

{/section}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'Store draft'|i18n( 'design/standard/content/translate' )}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>
{/section}

{/section}


</div>
</div>
</form>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

