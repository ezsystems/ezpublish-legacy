<form action={concat( 'content/translations' )|ezurl} method="post" >

<div class="context-block">
{section show=$is_edit}
<h2 class="context-title">{'Change translation for content'|i18n( 'design/admin/content/translationnew' )}</h2>3
{section-else}
<h2 class="context-title">{'New translation for content'|i18n( 'design/admin/content/translationnew' )}</h2>
{/section}

<div class="context-attributes">

{* Translation. *}
<div class="block">
<label>{'Translation'|i18n( 'design/admin/content/translationnew' )}</label>
<select name="LocaleID">
  <option value="-1">{'Custom'|i18n( 'design/admin/content/translationnew' )}</option>
  {section var=Translations loop=fetch( content, locale_list )}
      <option value="{$Translations.item.locale_full_code|wash}">
          {$Translations.item.intl_language_name|wash}{section show=$Translations.item.country_variation} [{$Translations.item.language_comment|wash}]{/section}
      </option>
  {/section}
</select>
</div>

{* Custom name. *}
<div class="block">
<label>{'Name for custom translation'|i18n( 'design/admin/content/translationnew' )}</label>
<input type="edit" name="TranslationName" value=""  size="20" />
</div>

{* Custom locale. *}
<div class="block">
<label>{'Locale for custom translation'|i18n( 'design/admin/content/translationnew' )}</label>
<input type="text" name="TranslationLocale" value="" size="8" />
</div>

</div>

{* Buttons. *}
<div class="controlbar">
<div class="block">
{section show=$is_edit}
    <input class="button" type="submit" name="ChangeButton" value={'OK'|i18n( 'design/admin/content/translationnew')} />
{section-else}
    <input class="button" type="submit" name="StoreButton" value={'OK'|i18n('design/admin/content/translationnew')} />
{/section}

<input class="button" type="submit" name="CancelButton" value={'Cancel'|i18n('design/admin/content/translationnew')} />
</div>
</div>

</div>

</form>
