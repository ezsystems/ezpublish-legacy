<form name="languageform" action={$module.functions.translations.uri|ezurl} method="post" >

{* Translation window *}
<div class="context-block">
<h2 class="context-title">{'%translation [Translation]'|i18n( 'design/admin/content/translationview',, hash( '%translation', $translation.locale_object.intl_language_name ) ) }</h2>

<div class="context-attributes">

{* ID *}
<div class="block">
<label>{'ID'|i18n( 'design/admin/content/translationview' )}</label>
{$translation.id}
</div>

{* Locale *}
<div class="block">
<label>{'Locale'|i18n( 'design/admin/content/translationview' )}</label>
{$translation.locale}
</div>

</div>

<div class="controlbar">
<div class="block">
    <input type="hidden" name="DeleteIDArray[]" value="{$translation.id}" />
    <input class="button" type="submit" name="RemoveButton" value="{'Remove'|i18n( 'design/admin/content/translationview' )}" />
</div>
</div>

</div>

</form>


{* Locale window *}
<div class="context-block">
<h2 class="context-title">{'%locale [Locale]'|i18n( 'design/admin/content/translationview',, hash( '%locale', $translation.locale ) ) }</h2>

<div class="context-attributes">
{* Charset *}
<div class="block">
<label>{'Charset'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.charset}
    {$translation.locale_object.charset}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* Allowed charsets *}
<div class="block">
<label>{'Allowed charsets'|i18n( 'design/admin/content/translationview' )}</label>
{section var=Charsets loop=$translation.locale_object.allowed_charsets}
    {$Charsets.item}<br />
{/section}
</div>

{* Country name *}
<div class="block">
<label>{'Country name'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.country_name}
    {$translation.locale_object.country_name}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* Country comment *}
<div class="block">
<label>{'Country comment'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.country_comment}
    {$translation.locale_object.country_comment}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* Country code *}
<div class="block">
<label>{'Country code'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.country_code}
    {$translation.locale_object.country_code}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* Country variation *}
<div class="block">
<label>{'Country variation'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.country_variation}
    {$translation.locale_object.country_variation}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* Language name *}
<div class="block">
<label>{'Language name'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.language_name}
    {$translation.locale_object.language_name}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* International language name *}
<div class="block">
<label>{'International language name'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.intl_language_name}
    {$translation.locale_object.intl_language_name}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* Language code *}
<div class="block">
<label>{'Language code'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.language_code}
    {$translation.locale_object.language_code}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* Language comment *}
<div class="block">
<label>{'Language comment'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.language_comment}
    {$translation.locale_object.language_comment}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* Locale code *}
<div class="block">
<label>{'Locale code'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.locale_code}
    {$translation.locale_object.locale_code}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Full locale code *}
<div class="block">
<label>{'Full locale code'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.locale_full_code}
    {$translation.locale_object.locale_full_code}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* HTTP locale code *}
<div class="block">
<label>{'HTTP locale code'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.http_locale_code}
    {$translation.locale_object.http_locale_code}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Decimal symbol *}
<div class="block">
<label>{'Decimal symbol'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.decimal_symbol}
    {$translation.locale_object.decimal_symbol}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Thousands separator *}
<div class="block">
<label>{'Thousands separator'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.thousands_separator}
    {$translation.locale_object.thousands_separator}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Decimal count *}
<div class="block">
<label>{'Decimal count'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.decimal_count}
    {$translation.locale_object.decimal_count}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Negative symbol *}
<div class="block">
<label>{'Negative symbol'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.negative_symbol}
    {$translation.locale_object.negative_symbol}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Positive symbol *}
<div class="block">
<label>{'Positive symbol'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.positive_symbol}
    {$translation.locale_object.positive_symbol}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Currency decimal symbol *}
<div class="block">
<label>{'Currency decimal symbol'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.currency_decimal_symbol}
    {$translation.locale_object.currency_decimal_symbol}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Currency thousands separator *}
<div class="block">
<label>{'Currency thousands separator'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.currency_thousands_separator}
    {$translation.locale_object.currency_thousands_separator}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Currency decimal count *}
<div class="block">
<label>{'Currency decimal count'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.currency_decimal_count}
    {$translation.locale_object.currency_decimal_count}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Currency negative symbol *}
<div class="block">
<label>{'Currency negative symbol'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.currency_negative_symbol}
    {$translation.locale_object.currency_negative_symbol}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Currency positive symbol *}
<div class="block">
<label>{'Currency positive symbol'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.currency_positive_symbol}
    {$translation.locale_object.currency_positive_symbol}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Currency symbol *}
<div class="block">
<label>{'Currency symbol'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.currency_symbol}
    {$translation.locale_object.currency_symbol}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Currency name *}
<div class="block">
<label>{'Currency name'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.currency_name}
    {$translation.locale_object.currency_name}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* Currency short name *}
<div class="block">
<label>{'Currency short name'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.currency_short_name}
    {$translation.locale_object.currency_short_name}
{section-else}
    {'Not set'|i18n( 'design/admin/content/translationview' )}</label>
{/section}
</div>

{* First day of week *}
<div class="block">
<label>{'First day of week'|i18n( 'design/admin/content/translationview' )}</label>
{section show=$translation.locale_object.is_monday_first}
    {'Monday'|i18n( 'design/admin/content/translationview' )}
{section-else}
    {'Not monday'|i18n( 'design/admin/content/translationview' )}
{/section}
</div>

{* Weekday names *}
<div class="block">
<label>{'Weekday names'|i18n( 'design/admin/content/translationview' )}</label>
{section var=Weekdays loop=$translation.locale_object.weekday_name_list}
    {$Weekdays.item}<br />
{/section}
</div>

{* Weekday numbers *}
<div class="block">
<label>{'Weekday numbers'|i18n( 'design/admin/content/translationview' )}</label>
{section var=Weekdays loop=$translation.locale_object.weekday_number_list}
    {$Weekdays.item}<br />
{/section}
</div>

{* Month names *}
<div class="block">
<label>{'Month names'|i18n( 'design/admin/content/translationview' )}</label>
{section var=Months loop=$translation.locale_object.month_list}
    {$Months.item}<br />
{/section}
</div>

</div>
</div>

