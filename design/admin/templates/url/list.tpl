{let item_type=ezpreference( 'admin_url_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )}

<div class="context-block">

{switch match=$view_mode}
{case match='valid'}
    <h2 class="context-title">{'Valid URLs [%url_list_count]'|i18n( 'design/admin/url/list',, hash( '%url_list_count', $url_list_count) )}</h2>
{/case}

{case match='invalid'}
    <h2 class="context-title">{'Invalid URLs [%url_list_count]'|i18n( 'design/admin/url/list',, hash( '%url_list_count', $url_list_count) )}</h2>
{/case}

{case}
    <h2 class="context-title">{'All URLs [%url_list_count]'|i18n( 'design/admin/url/list',, hash( '%url_list_count', $url_list_count) )}</h2>
{/case}
{/switch}

{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/admin_url_list_limit/1'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_url_list_limit/3'|ezurl}>50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_url_list_limit/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/admin_url_list_limit/2'|ezurl}>25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_url_list_limit/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/admin_url_list_limit/3'|ezurl}>50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="right">
<p>
{switch match=$view_mode}
{case match='valid'}
<a href={'/url/list/all'|ezurl}>{'All'|i18n( 'design/admin/url/list' )}</a>
<span class="current">{'Valid'|i18n( 'design/admin/url/list' )}</span>
<a href={'/url/list/invalid'|ezurl}>{'Invalid'|i18n( 'design/admin/url/list' )}</a>
{/case}

{case match='invalid'}
<a href={'/url/list/all'|ezurl}>{'All'|i18n( 'design/admin/url/list' )}</a>
<a href={'/url/list/valid'|ezurl}>{'Valid'|i18n( 'design/admin/url/list' )}</a>
<span class="current">{'Invalid'|i18n( 'design/admin/url/list' )}</span>
{/case}

{case}
<span class="current"><a href={'/url/list/all'|ezurl}>{'All'|i18n( 'design/admin/url/list' )}</span>
<a href={'/url/list/valid'|ezurl}>{'Valid'|i18n( 'design/admin/url/list' )}</a>
<a href={'/url/list/invalid'|ezurl}>{'Invalid'|i18n( 'design/admin/url/list' )}</a>
{/case}
{/switch}
</p>
</div>
<div class="break"></div>
</div>
</div>

{section show=$url_list}
<table class="list" cellspacing="0">

<tr>
  <th>{'Address'|i18n( 'design/admin/url/list' )}</th>
  <th>{'Status'|i18n( 'design/admin/url/list' )}</th>
  <th>{'Checked'|i18n( 'design/admin/url/list' )}</th>
  <th>{'Modified'|i18n( 'design/admin/url/list' )}</th>
  <th class="tight">&nbsp;</th>
</tr>

{section var=urls loop=$url_list sequence=array( bglight, bgdark )}

<tr class="{$urls.sequence}">

  {* URL & popup. *}
  <td>{'url'|icon( 'small', 'URL'|i18n( 'design/admin/url/list' ) )}&nbsp;<a href={concat( 'url/view/', $urls.item.id)|ezurl}>{$urls.item.url}</a>
  (<a href="{$urls.item.url}" target="_blank">{'open'|i18n( 'design/admin/url/list' )}</a>)
  </td>

  {* Status. *}
  <td>
  {section show=$urls.is_valid}
      {'Valid'|i18n( 'design/admin/url/list' )}
  {section-else}
      {'Invalid'|i18n( 'design/admin/url/list' )}
  {/section}
  </td>

  {* Last checked. *}
  <td>
    {section show=$urls.item.last_checked|gt( 0 )}
      {$urls.item.last_checked|l10n( shortdatetime )}
    {section-else}
        {'Never'|i18n( 'design/admin/url/list' )}
    {/section}
  </td>

  {* Last modified. *}
  <td>
    {section show=$urls.item.modified|gt( 0 )}
      {$urls.item.modified|l10n( shortdatetime )}
    {section-else}
      {'Unknown'|i18n( 'design/admin/url/list' )}
    {/section}
  </td>

  {* Edit. *}
  <td><a href={concat( 'url/edit/', $urls.item.id )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/url/list ')}" /></a></td>

</tr>
{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/url/list/', $view_mode )
         item_count=$url_list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{section-else}

<p>{'The list is empty.'|i18n( 'design/admin/url/list' )}</p>

{/section}

</div>

{/let}