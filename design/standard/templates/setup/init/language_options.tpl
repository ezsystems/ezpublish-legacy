{*?template charset=latin1?*}

<div align="center">
  <h1>{"Language support"|i18n("design/standard/setup/init")}</h1>
</div>
<p>
{"Use the radio buttons to choose the primary language, and the checkboxes to choose additional languages. You may choose more than one additional language."|i18n("design/standard/setup/init")}
</p>
<p>
{section show=$show_unicode_error}
<div class="warning">
  <h2>{"No Unicode support"|i18n('design/standard/setup/init')}</h2>
  <p>{"The database server you connected to does not support Unicode which means that you cannot choose all the languages as you did.
To fix this problem you must do one of the following:"|i18n('design/standard/setup/init')}</p>
  <ul>
    <li>{"Choose only languages that use similar characters, for instance: English and Norwegian will work together while English and Russian won't work."|i18n('design/standard/setup/init')}</li>
    <li>{"Make sure the database server is configured to use Unicode or that it has the latest software which supports Unicode."|i18n('design/standard/setup/init')}</li>
  </ul>
</div>
{/section}

<form method="post" action="{$script}">

<div class="input_highlight">

<table border="0" cellspacing="0" cellpadding="0">

  <tr>
    <th class="normal">{"Primary/Additional"|i18n("design/standard/setup/init")}:</th>
  </tr>

  {section name=Language loop=$language_list}
    <tr>    
      <td class="normal">
	<input type="radio" name="eZSetupDefaultLanguage" value="{$:item.locale_code}" {section show=$Language:item.locale_code|eq('eng-GB')}checked="checked" {/section}/>
        <input type="checkbox" name="eZSetupLanguages[]" value="{$:item.locale_code}" />
	{$:item.intl_language_name}
      </td>
    </tr>
  {/section}

</table>
</div>

</p>

{include uri="design:setup/persistence.tpl"}

<div class="buttonblock">
  {include uri='design:setup/init/navigation.tpl'}
</div>


</form>
</p>

