<form action={concat("content/urltranslator/")|ezurl} method="post" >
<h1>{'URL translator'|i18n('design/standard/content')}</h1>

{section show=$alias_list}

<table>
{section name=Alias loop=$alias_list show=$alias_list}
<tr>
    <td>
    <input type="text" name="URLAliasSourceValue[{$Alias:item.id}]" value="{$Alias:item.source_url|wash}" />
    {section show=$Alias:item.forward_to_id|eq(0)}
        <input type="text" name="URLAliasDestinationValue[{$Alias:item.id}]" value="{$Alias:item.destination_url|wash}" />
    {section-else}
        Forwards to <i>{$Alias:item.forward_url.source_url|wash}</i>
    {/section}
    </td>
</tr>
{/section}
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/urltranslator/')
         item_count=$alias_count
         view_parameters=$view_parameters
         item_limit=$alias_limit}


<input type="submit" name="StoreURLAliasButton" value="{'Store'|i18n('design/standard/content')}" />

<br/>

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

<table cellspacing="4" cellpadding="0">
<tr>
    <th>
        {'Translation'}
    </th>
    <th>
        {'Forwarding'}
    </th>
</tr>
<tr>
    <td>
        {'Virtual URL'}
    </td>
    <td>
        {'Source URL'}
    </td>
</tr>
<tr>
    <td class="bglight">
        <input type="text" name="NewURLAliasSource" value="{cond(and($translation_info,$translation_info.error),$translation_info.source,
                                                                     '')|wash}" />
    </td>
    <td class="bglight">
        <input type="text" name="NewForwardURLAliasSource" value="{cond(and($forward_info,$forward_info.error),$forward_info.source,
                                                                            '')|wash}" />
    </td>
</tr>
<tr>
    <td>
        {'System URL'}
    </td>
    <td>
        {'Destination URL'}
    </td>
</tr>
<tr>
    <td class="bglight">
        <input type="text" name="NewURLAliasDestination" value="{cond(and($translation_info,$translation_info.error),$translation_info.destination,
                                                                          '')|wash}" />
    </td>
    <td class="bglight">
        <input type="text" name="NewForwardURLAliasDestination" value="{cond(and($forward_info,$forward_info.error),$forward_info.destination,
                                                                                 '')|wash}" />
    </td>
</tr>
<tr>
    <td class="bglight">
        <div class="buttonblock">
        <input type="submit" name="NewURLAliasButton" value="{'Add'|i18n('design/standard/content')}" />
        </div>
    </td>
    <td class="bglight">
        <div class="buttonblock">
        <input type="submit" name="NewForwardURLAliasButton" value="{'Add'|i18n('design/standard/content')}" />
        </div>
    </td>
</tr>
</table>

</form>