{default show_make_valid=true()
         show_make_invalid=true()}

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
  <th>&nbsp;</th>
  <th width="1">{"Edit"|i18n('design/standard/url')}</th>
  <th colspan="2">{"URL"|i18n('design/standard/url')}</th>
  <th>{"Last checked"|i18n('design/standard/url')}</th>
  <th>{"Modified"|i18n('design/standard/url')}</th>
</tr>
{section name=URL loop=$url_list sequence=array(bglight,bgdark)}
<tr>
{let item_class="status_read"}
{section show=$:item.is_valid|not}
  {set item_class="status_inactive"}
{/section}
  <td class="{$:sequence}" width="1"><input type="checkbox" name="URLSelection[]" value="{$:item.id}" /></td>
  <td class="{$:sequence}" width="1">
    <nobr><a href={concat("url/edit/",$:item.id)|ezurl}><img src={"edit.png"|ezimage} alt="{'Edit'|i18n('design/standard/url')}" /></a></nobr>
  </td>
  <td class="{$:sequence}" width="1">
    <nobr><a class="{$:item_class}" href={concat("url/view/",$:item.id)|ezurl}>{$:item.url}</a></nobr>
  </td>
  <td class="{$:sequence}">
    <nobr><a target="_other" href={$:item.url|ezurl}>{"Popup"|i18n('design/standard/url')}</a></nobr>
  </td>
  <td class="{$:sequence}">
    {section show=$:item.last_checked|gt(0)}
      {$:item.last_checked|l10n(shortdatetime)}
    {section-else}
      {"Never"|i18n('design/standard/url')}
    {/section}</td>
  <td class="{$:sequence}">
    {section show=$:item.modified|gt(0)}
      {$:item.modified|l10n(shortdatetime)}
    {section-else}
      {"Unknown"|i18n('design/standard/url')}
    {/section}</td>
  </td>
{/let}
</tr>
{/section}
</table>

{include name=Navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('url/list/',$current_view_id)
         item_count=$url_count
         view_parameters=$view_parameters
         item_limit=$url_limit}
{*
<div class="buttonblock">
{section show=$show_make_valid}
<input class="button" type="submit" name="SetValid" value="{'Make valid'}" />
{/section}
{section show=$show_make_invalid}
<input class="button" type="submit" name="SetInvalid" value="{'Make invalid'}" />
{/section}
</div>
*}
{/default}