{* Errors START *}

{switch match=$info_code}
{case match='feedback-wildcard-removed'}
<div class="message-feedback">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The selected aliases were successfully removed.'|i18n( 'design/admin/content/urlalias_wildcard' )}</h2>
</div>
{/case}
{case match='feedback-wildcard-removed-all'}
<div class="message-feedback">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'All wildcard aliases were successfully removed.'|i18n( 'design/admin/content/urlalias_wildcard' )}</h2>
</div>
{/case}
{case match='error-no-wildcard-text'}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Text is missing for the URL alias'|i18n( 'design/admin/content/urlalias_wildcard' )}</h2>
<ul>
    <li>{'Enter text in the input box to create a new alias.'|i18n( 'design/admin/content/urlalias_wildcard' )}</li>
</ul>
</div>
{/case}
{case match='error-no-wildcard-destination-text'}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Text is missing for the URL alias destination'|i18n( 'design/admin/content/urlalias_wildcard' )}</h2>
<ul>
    <li>{'Enter some text in the destination input box to create a new alias.'|i18n( 'design/admin/content/urlalias_wildcard' )}</li>
</ul>
</div>
{/case}
{case match='feedback-wildcard-created'}
<div class="message-feedback">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The URL alias <%wildcard_src_url> was successfully created'|i18n( 'design/admin/content/urlalias_wildcard',, hash('%wildcard_src_url', $info_data['wildcard_src_url'] ) )|wash}</h2>
</div>
{/case}
{case match='feedback-wildcard-exists'}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The URL alias <%wildcard_src_url> already exists, and it points to <%wildcard_dst_url>'|i18n( 'design/admin/content/urlalias_wildcard',, hash( '%wildcard_src_url', $info_data['wildcard_src_url'], '%wildcard_dst_url', $info_data['wildcard_dst_url'] ) )|wash}</h2>
</div>
{/case}
{case}
{/case}
{/switch}

{* Errors END *}


<form name="wildcardform" method="post" action={"content/urlwildcards/"|ezurl}>

<div class="context-block content-urlalias-wildcard">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Defined URL aliases with wildcard(%wildcard_count)'|i18n( 'design/admin/content/urlalias_wildcard',, hash( '%wildcard_count', $wildcards_count ) )|wash}</h1>
{* DESIGN: Mainline *}<div class="header-mainline"></div>
{* DESIGN: Header END *}</div></div>
{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page selector. *}
<div class="context-toolbar">
<div class="button-left">
    <p class="table-preferences">
    {foreach $limitList as $limitEntry}
        {if eq($limitID, $limitEntry['id'])}
            <span class="current">{$limitEntry['value']}</span>
        {else}
            <a href={concat('/user/preferences/set/admin_urlwildcard_list_limit/', $limitEntry['id'])|ezurl} title="{'Show %number_of items per page.'|i18n( 'design/admin/content/urlalias_wildcard',, hash( '%number_of', $limitEntry['value'] ) )}">{$limitEntry['value']}</a>
        {/if}
    {/foreach}
    </p>
</div>
<div class="break"></div>
</div>


{* list here *}
{if eq( count( $wildcard_list ), 0)}
<div class="block">
<p>{"The URL wildcard list does not contain any aliases."|i18n( 'design/admin/content/urlalias_wildcard' )}</p>
</div>
{else}
<table class="list" cellspacing="0" >
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/content/urlalias_wildcard' )}" title="{'Invert selection.'|i18n( 'design/admin/content/urlalias_wildcard' )}" onclick="ezjs_toggleCheckboxes( document.wildcardform, 'WildcardIDList[]' ); return false;"/></th>
    <th>{'URL alias wildcard'|i18n( 'design/admin/content/urlalias_wildcard' )}</th>
    <th>{'Destination'|i18n( 'design/admin/content/urlalias_wildcard' )}</th>
    <th>{'Type'|i18n( 'design/admin/content/urlalias_wildcard' )}</th>
</tr>
{foreach $wildcard_list as $wildcard sequence array('bglight', 'bgdark') as $seq}
    <tr class="{$seq}">
        {* Remove. *}
        <td>
            <input type="checkbox" name="WildcardIDList[]" value="{$wildcard.id}" />
        </td>

        <td>
            {$wildcard.source_url|wash}
        </td>

        <td>
            {$wildcard.destination_url|wash}
        </td>

        <td>
            {switch match=$wildcard.type}
                {case match=1}
                    {'Forward'|i18n( 'design/admin/content/urlalias_wildcard' )}
                {/case}
                {case match=2}
                    {'Direct'|i18n( 'design/admin/content/urlalias_wildcard' )}
                {/case}
                {case}
                    {'Undefined'|i18n( 'design/admin/content/urlalias_wildcard' )}
                {/case}
            {/switch}
        </td>
    </tr>
{/foreach}
 </table>

<div class="context-toolbar">
    {include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='content/urlwildcards/'
         item_count=$wildcards_count
         view_parameters=$view_parameters
         item_limit=$wildcards_limit}
</div>
{/if}


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

{* buttons here *}
<div class="block">
<div class="button-left">
    {if $wildcard_list|count|gt( 0 )}
    <input class="button" type="submit" name="RemoveWildcardButton" value="{'Remove selected'|i18n( 'design/admin/content/urlalias_wildcard' )}" title="{'Remove selected aliases from the list above.'|i18n( 'design/admin/content/urlalias_wildcard' )}" onclick="return confirm( '{'Are you sure you want to remove the selected wildcards?'|i18n( 'design/admin/content/urlalias_wildcard' )}' );"/>
    <input class="button" type="submit" name="RemoveAllWildcardsButton" value="{'Remove all'|i18n( 'design/admin/content/urlalias_wildcard' )}" title="{'Remove all wildcard aliases.'|i18n( 'design/admin/content/urlalias_wildcard' )}" onclick="return confirm( '{'Are you sure you want to remove all wildcard aliases?'|i18n( 'design/admin/content/urlalias_wildcard' )}' );"/>
    {else}
    <input class="button-disabled" type="submit" name="RemoveWildcardButton" value="{'Remove selected'|i18n( 'design/admin/content/urlalias_wildcard' )}" title="{'There are no removable aliases.'|i18n( 'design/admin/content/urlalias_wildcard' )}" disabled="disabled" />
    <input class="button-disabled" type="submit" name="RemoveAllWildcardsButton" value="{'Remove all'|i18n( 'design/admin/content/urlalias_wildcard' )}" title="{'There are no removable aliases.'|i18n( 'design/admin/content/urlalias_wildcard' )}" disabled="disabled" />
    {/if}
</div>
<div class="break"></div>
</div>

{* DESIGN: Control bar END *}</div></div>
</div>

</div>


{* Generated aliases context block start *}
{* Generated aliases window. *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title">{'Create new URL forwarding with wildcard'|i18n( 'design/admin/content/urlalias' )}</h2>

{* DESIGN: Header END *}</div></div>
{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
{* Wildcard pattern. *}
    <label for="ezcontent_urlalias_wildcard_source">{'New URL wildcard'|i18n( 'design/admin/content/urlalias_wildcard' )}:</label>
    <input id="ezcontent_urlalias_wildcard_source" class="box" type="text" name="WildcardSourceText" value="{$wildcardSourceText|wash}" title="{'Enter the URL for the new wildcard. Example: developer/*'|i18n( 'design/admin/content/urlalias_wildcard' )}" />
</div>

<div class="block">
{* Destination field. *}
    <label for="ezcontent_urlalias_wildcard_destination">{'Destination'|i18n( 'design/admin/content/urlalias_wildcard' )}:</label>
    <input id="ezcontent_urlalias_wildcard_destination" class="box" type="text" name="WildcardDestinationText" value="{$wildcardDestinationText|wash}" title="{'Enter the destination URL for the new wildcard. Example: dev/{1\}'|i18n( 'design/admin/content/urlalias_wildcard' )}" />
</div>

<div class="block">

{* Redirecting URL flag. *}
<div class="block">
    <input type="checkbox" name="WildcardType"{if $wildcardType} checked="checked"{/if} /><label class="radio" title="{'Perform redirecting.'|i18n( 'design/admin/content/urlalias_wildcard' )}">{'Redirecting URL'|i18n( 'design/admin/content/urlalias' )}</label>
</div>

</div>
<div class="break"></div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">
{* Create button. *}
    <input class="button" type="submit" name="NewWildcardButton" value="{'Create'|i18n( 'design/admin/content/urlalias_wildcard' )}" title="{'Create a new wildcard URL alias.'|i18n( 'design/admin/content/urlalias_wildcard' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>



</div>
{* Generated aliases context block end *}

<input type="hidden" name="Offset" value="{$view_parameters.offset}" />

</form>

