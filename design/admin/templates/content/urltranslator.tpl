{section show=$forward_info.error}
    <div class="message-error">
        <h2>{'The requested URL forwarding could not be created.'|i18n( 'design/admin/content/urltranslator' )}<span class="time">{currentdate()|l10n(shortdatetime)}</span></h2>
         <ul>
             <li>
                {'The destination URL <%destination_url> does not exist in the system.'|i18n( 'design/admin/content/urltranslator',, hash( '%destination_url', $forward_info.destination ) )|wash}
            </li>
         </ul>
    </div>
{/section}


<form action={concat( 'content/urltranslator/' )|ezurl} method="post" >

<div class="context-block">
<h2 class="context-title">{'New URL translation'|i18n( 'design/admin/content/urltranslator' )}</h2>
<table class="list" cellspacing="0">
<tr>
<th width="40%">{'Virtual URL'|i18n( 'design/admin/content/urltranslator' )}</th>
<th>{'System URL'|i18n( 'design/admin/content/urltranslator' )}</th>

<th class="tight">&nbsp;</th>
</tr>
<tr>
<td>
{'Example: /services'|i18n( 'design/admin/content/urltranslator' )}
</td>
<td>
{'Example: /content/view/full/42'|i18n( 'design/admin/content/urltranslator' )}
</td>
<td>
&nbsp;
</td>
</tr>
<tr>
<td>
<input type="text" name="NewURLAliasSource" value="{cond(and($translation_info,$translation_info.error),$translation_info.source, '')|wash}" />
</td>
<td>
<input type="text" name="NewURLAliasDestination" value="{cond(and($translation_info,$translation_info.error),$translation_info.destination, '')|wash}" />
</td>
<td>
<input class="button" type="submit" name="NewURLAliasButton" value="{'Add'|i18n( 'design/admin/content/urltranslator' )}" />
</td>
</tr>
</table>
</div>

<div class="context-block">
<h2 class="context-title">{'New URL forwarding'|i18n( 'design/admin/content/urltranslator' )}</h2>
<table class="list" cellspacing="0">
<tr>
<th width="40%">{'Existing virtual URL'|i18n( 'design/admin/content/urltranslator' )}</th>
<th>{'Old virtual URL'|i18n( 'design/admin/content/urltranslator' )}</th>
<th class="tight">&nbsp;</th>
</tr>
<tr>
<td>
{'Example: /services'|i18n( 'design/admin/content/urltranslator' )}
</td>
<td>
{'Example: /about/service'|i18n( 'design/admin/content/urltranslator' )}
</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>
<input type="text" name="NewForwardURLAliasSource" value="{cond(and($forward_info,$forward_info.error),$forward_info.source, '')|wash}" />
</td>
<td>
<input type="text" name="NewForwardURLAliasDestination" value="{cond(and($forward_info,$forward_info.error),$forward_info.destination, '')|wash}" />
</td>
<td>
<input class="button" type="submit" name="NewForwardURLAliasButton" value="{'Add'|i18n( 'design/admin/content/urltranslator' )}" />
</td>
</tr>
</table>
</div>

<div class="context-block">
<h2 class="context-title">{'New URL wildcard'|i18n( 'design/admin/content/urltranslator' )}</h2>
<table class="list" cellspacing="0">
<tr>
<th>{'Existing virtual URL'|i18n( 'design/admin/content/urltranslator' )}</th>
<th>{'Old virtual URL'|i18n( 'design/admin/content/urltranslator' )}</th>
<th>{'Forwarding URL'|i18n( 'design/admin/content/urltranslator' )}</th>
<th class="tight">&nbsp;</th>
</tr>
<tr>
<td>
{'Example: /developer/*'|i18n( 'design/admin/content/urltranslator' )}
</td>
<td>
{'Example:'|i18n( 'design/admin/content/urltranslator' )}&nbsp;{literal}/dev/{1}{/literal}
</td>
<td>
&nbsp;
</td>
<td>
&nbsp;
</td>
</tr>
<tr>
<td>
<input type="text" name="NewWildcardURLAliasSource" value="{cond(and($wildcard_info,$wildcard_info.error),$wildcard_info.destination, '')|wash}" />
</td>
<td>
<input type="text" name="NewWildcardURLAliasDestination" value="{cond(and($wildcard_info,$wildcard_info.error),$wildcard_info.source, '')|wash}" />
</td>
<td>
<input type="checkbox" name="NewWildcardURLAliasIsForwarding" value="1" checked="checked" />
</td>
<td>
<input class="button" type="submit" name="NewWildcardURLAliasButton" value="{'Add'|i18n( 'design/admin/content/urltranslator' )}" />
</td>
</tr>
</table>
</div>

<div class="context-block">
<h2 class="context-title">{'Custom URL translations, forwardings and wildcards [%alias_count]'|i18n(  'design/admin/content/urltranslator' ,, hash( '%alias_count', $alias_list|count ) )}</h2>

{section show=$alias_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th>{'Virtual URL'|i18n( 'design/admin/content/urltranslator' )}</th>
    <th>{'System URL'|i18n( 'design/admin/content/urltranslator' )}</th>
    <th>{'Type'|i18n( 'design/admin/content/urltranslator' )}</th>
</tr>
{section name=Alias loop=$alias_list show=$alias_list}
<tr>
    <td><input type="checkbox" name="URLAliasSelection[{$Alias:item.id}]" value="{$Alias:item.id}" /></td>
    <td>
        <input type="text" name="URLAliasSourceValue[{$Alias:item.id}]" value="{$Alias:item.source_url|wash}" />
    </td>
    <td>
    {section show=$Alias:item.forward_to_id|eq(0)}
        <input type="text" name="URLAliasDestinationValue[{$Alias:item.id}]" value="{$Alias:item.destination_url|wash}" />
    {section-else}
        {section show=$Alias:item.forward_to_id|eq( -1 )}
            {'Forwards to'|i18n( 'design/admin/content/urltranslator' )}&nbsp;<a href={$Alias:item.destination_url|ezurl}>{$Alias:item.destination_url|wash}</a><br/>
        {section-else}
            {'Forwards to'|i18n( 'design/admin/content/urltranslator' )}&nbsp;<a href={$Alias:item.forward_url.destination_url|ezurl}>{$Alias:item.forward_url.source_url|wash}</a><br/>
        {/section}
    {/section}
    </td>
    <td>
    {section show=$Alias:item.is_wildcard|gt(0)}
        {'Wildcard'|i18n( 'design/admin/content/urltranslator' )}
    {section-else}
        {section show=$Alias:item.forward_to_id|eq(0)}
            {'Translation'|i18n( 'design/admin/content/urltranslator' )}
        {section-else}
            {'Forwarding'|i18n( 'design/admin/content/urltranslator' )}
        {/section}
    {/section}
    </td>
</tr>
{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/content/urltranslator'
         item_count=$alias_count
         view_parameters=$view_parameters
         item_limit=$alias_limit}
</div>
{section-else}
<p>{'There are no entries in this list.'|i18n( 'design/admin/content/urltranslator' )}</p>
{/section}

<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="RemoveURLAliasButton" value="{'Remove selected'|i18n( 'design/admin/content/urltranslator' )}" {section show=$alias_list|not}disabled="disabled"{/section} />
<input class="button" type="submit" name="StoreURLAliasButton"  value="{'Apply changes'|i18n( 'design/admin/content/urltranslator' )}" {section show=$alias_list|not}disabled="disabled"{/section} />
</div>
</div>

</div>

</form>
