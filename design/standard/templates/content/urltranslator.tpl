<form action={concat("content/urltranslator/")|ezurl} method="post" >
<h1>{'URL translator'|i18n('design/standard/content')}</h1>

{section show=$alias_list}

<div class="objectheader">
  <h2>Existing URLs</h2>
</div>
<div class="object">
<table cellspacing="4" cellpadding="0">
<tr>
    <th>{'System URL'}</th>
    <th>{'Virtual URL'}</th>
    <th>{'Type'}</th>
    <th>{'Remove'}</th>
</tr>
{section name=Alias loop=$alias_list show=$alias_list}
<tr>
    <td>
    {section show=$Alias:item.forward_to_id|eq(0)}
        <input type="text" name="URLAliasDestinationValue[{$Alias:item.id}]" value="{$Alias:item.destination_url|wash}" />
    {section-else}
        {section show=$Alias:item.forward_to_id|eq( -1 )}
            Forwards to <a href={$Alias:item.destination_url|ezurl}>{$Alias:item.destination_url|wash}</a><br/>
        {section-else}
            Forwards to <a href={$Alias:item.forward_url.destination_url|ezurl}>{$Alias:item.forward_url.source_url|wash}</a><br/>
        {/section}
    {/section}
    </td>
    <td>
        <input type="text" name="URLAliasSourceValue[{$Alias:item.id}]" value="{$Alias:item.source_url|wash}" />
    </td>
    <td>
    {section show=$Alias:item.is_wildcard|gt(0)}
        Wildcard
    {section-else}
        {section show=$Alias:item.forward_to_id|eq(0)}
            Normal
        {section-else}
            Forwarding
        {/section}
    {/section}
    </td>
    <td align="right">
        <input type="checkbox" name="URLAliasSelection[{$Alias:item.id}]" value="{$Alias:item.id}" />
    </td>
</tr>
{/section}
<tr>
    <td colspan="3">
        <input type="submit" name="StoreURLAliasButton" value="{'Store'|i18n('design/standard/content')}" />
    </td>
    <td align="right">
         <input type="image" name="RemoveURLAliasButton" value="{'Remove'}" src={"trash.png"|ezimage} />
    </td>
</tr>
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/content/urltranslator'
         item_count=$alias_count
         view_parameters=$view_parameters
         item_limit=$alias_limit}


</div>

<p/>

{/section}

{section show=$forward_info}
    {section show=$forward_info.error}
    <div class="error">
        <p>
        The destination URL <b>{$forward_info.destination|wash}</b> does not exist in the system.
        </p>
        <p>
        You can only create forwarding URLs to existing translation URLs.
        </p>
    </div>
    {section-else}
    <div class="feedback">
        <p>
        Created forwarding URL from <b>{$forward_info.source|wash}</b> to <b>{$forward_info.destination|wash}</b>.
        </p>
    </div>
    {/section}
{/section}

{section show=$wildcard_info}
    {section show=$wildcard_info.error}
    {section-else}
    <div class="feedback">
        <p>
        Created wildcard URL, translates from  <b>{$wildcard_info.source|wash}</b> to <b>{$wildcard_info.destination|wash}</b>.
        </p>
    </div>
    {/section}
{/section}

<div class="objectheader">
    <h2>Create new URLs</h2>
</div>
<div class="object">
<table cellspacing="4" cellpadding="0">
<tr>
    <th colspan="3">
        {'Translation'}
    </th>
</tr>
<tr>
    <td>
        {'System URL'}
    </td>
    <td>
        {'Virtual URL'}
    </td>
</tr>
<tr>
    <td>
        <input type="text" name="NewURLAliasDestination" value="{cond(and($translation_info,$translation_info.error),$translation_info.destination,
                                                                          '')|wash}" />
    </td>
    <td>
        <input type="text" name="NewURLAliasSource" value="{cond(and($translation_info,$translation_info.error),$translation_info.source,
                                                                     '')|wash}" />
    </td>
    <td>
        <div class="buttonblock">
        <input type="submit" name="NewURLAliasButton" value="{'Add'|i18n('design/standard/content')}" />
        </div>
    </td>
</tr>
<tr>
    <td>
        {'e.g. /content/view/full/42'}
    </td>
    <td colspan="2">
        {'e.g. /services'}
    </td>
</tr>
<tr>
    <th colspan="3">
        {'Forwarding'}
    </th>
</tr>
<tr>
    <td>
        {'Old virtual URL'}
    </td>
    <td>
        {'Existing virtual URL'}
    </td>
</tr>
<tr>
    <td>
        <input type="text" name="NewForwardURLAliasDestination" value="{cond(and($forward_info,$forward_info.error),$forward_info.destination,
                                                                                 '')|wash}" />
    </td>
    <td>
        <input type="text" name="NewForwardURLAliasSource" value="{cond(and($forward_info,$forward_info.error),$forward_info.source,
                                                                            '')|wash}" />
    </td>
    <td>
        <div class="buttonblock">
        <input type="submit" name="NewForwardURLAliasButton" value="{'Add'|i18n('design/standard/content')}" />
        </div>
    </td>
</tr>
<tr>
    <td>
        {'e.g. /about/service'}
    </td>
    <td colspan="2">
        {'e.g. /services'}
    </td>
</tr>
<tr>
    <th colspan="3">
        {'Wildcard'}
    </th>
</tr>
<tr>
    <td>
        {'Old virtual URL'}
    </td>
    <td>
        {'Existing virtual URL'}
    </td>
</tr>
<tr>
    <td>
        <input type="text" name="NewWildcardURLAliasDestination" value="{cond(and($wildcard_info,$wildcard_info.error),$wildcard_info.source,
                                                                         '')|wash}" />
    </td>
    <td>
        <input type="text" name="NewWildcardURLAliasSource" value="{cond(and($wildcard_info,$wildcard_info.error),$wildcard_info.destination,
                                                                              '')|wash}" />
    </td>
    <td>
        <div class="buttonblock">
        <input type="submit" name="NewWildcardURLAliasButton" value="{'Add'|i18n('design/standard/content')}" />
        </div>
    </td>
</tr>
<tr>
    <td colspan="3">
        <input type="checkbox" name="NewWildcardURLAliasIsForwarding" value="1" checked="checked" />{'Forwarding URL'}<br/>
    </td>
</tr>
<tr>
    <td>
        {literal}e.g. /dev/{1}{/literal}
    </td>
    <td colspan="2">
        {'e.g. /developer/*'}
    </td>
</tr>
</table>

</div>

</form>