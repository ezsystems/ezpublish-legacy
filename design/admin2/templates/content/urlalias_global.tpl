{* Errors START *}

{switch match=$info_code}
{case match='feedback-removed'}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The selected aliases were successfully removed.'|i18n( 'design/admin/content/urlalias_global' )}</h2>
    </div>
{/case}
{case match='feedback-removed-all'}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'All global aliases were successfully removed.'|i18n( 'design/admin/content/urlalias_global' )}</h2>
    </div>
{/case}
{case match='error-invalid-language'}
    <div class="message-warning">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The specified language code <%language> is not valid.'|i18n( 'design/admin/content/urlalias_global',, hash('%language', $info_data['language']) )|wash}</h2>
    </div>
{/case}
{case match='error-no-alias-text'}
    <div class="message-warning">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Text is missing for the URL alias'|i18n( 'design/admin/content/urlalias_global' )}</h2>
    <ul>
        <li>{'Enter text in the input box to create a new alias.'|i18n( 'design/admin/content/urlalias_global' )}</li>
    </ul>
    </div>
{/case}
{case match='error-no-alias-destination-text'}
    <div class="message-warning">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Text is missing for the URL alias destination'|i18n( 'design/admin/content/urlalias_global' )}</h2>
    <ul>
        <li>{'Enter some text in the destination input box to create a new alias.'|i18n( 'design/admin/content/urlalias_global' )}</li>
    </ul>
    </div>
{/case}
{case match=error-action-invalid}
    <div class="message-error">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The specified destination URL %url does not exist in the system, cannot create alias for it'|i18n( 'design/admin/content/urlalias_global',, hash('%url', concat( "<", $info_data['aliasText'], ">" ) ) )|wash}</h2>
        <p>{'Ensure that the destination points to a valid entry, one of:'|i18n( 'design/admin/content/urlalias_global' )}</p>
    <ul>
        <li>{'Built-in functionality, e.g. %example.'|i18n( 'design/admin/content/urlalias_global',, hash( '%example', '<i>user/login</i>' ) )}</li>
        <li>{'Existing aliases for the content structure.'|i18n( 'design/admin/content/urlalias_global' )}</li>
    </ul>
    </div>
{/case}
{case match='feedback-alias-cleanup'}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The URL alias was successfully created, but was modified by the system to <%new_alias>'|i18n( 'design/admin/content/urlalias_global',, hash('%new_alias', $info_data['new_alias'] ) )|wash}</h2>
        <ul>
            {if $info_data['node_id']}
                <li>{'Note that the new alias points to a node and will not be displayed in the global list. It can be examined on the URL-Alias page of the node, %node_link.'|i18n( 'design/admin/content/urlalias_global',, hash( '%node_link', concat( '<a href=', concat( 'content/urlalias/', $info_data['node_id'] )|ezurl, '>', concat( 'content/urlalias/', $info_data['node_id'] ), '</a>' ) ) )}</li>
            {/if}
            <li>{'Invalid characters will be removed or transformed to valid characters.'|i18n( 'design/admin/content/urlalias_global' )}</li>
            <li>{'Existing objects or functionality with the same name take precedence on the name.'|i18n( 'design/admin/content/urlalias_global' )}</li>
        </ul>
    </div>
{/case}
{case match='feedback-alias-created'}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The URL alias <%new_alias> was successfully created'|i18n( 'design/admin/content/urlalias_global',, hash('%new_alias', $info_data['new_alias'] ) )|wash}</h2>
    {if $info_data['node_id']}
        <ul>
            <li>{'Note that the new alias points to a node and will not be displayed in the global list. It can be examined on the URL-Alias page of the node, %node_link.'|i18n( 'design/admin/content/urlalias_global',, hash( '%node_link', concat( '<a href=', concat( 'content/urlalias/', $info_data['node_id'] )|ezurl, '>', concat( 'content/urlalias/', $info_data['node_id'] ), '</a>' ) ) )}</li>
        </ul>
    {/if}
</div>
{/case}
{case match='feedback-alias-exists'}
    <div class="message-warning">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The URL alias &lt;%new_alias&gt; already exists, and it points to &lt;%action_url&gt;'|i18n( 'design/admin/content/urlalias_global',, hash( '%new_alias', concat( "<"|wash, '<a href=', $info_data['url']|ezurl, '>', $info_data['new_alias'], '</a>', ">"|wash ), '%action_url', concat( "<"|wash, '<a href=', $info_data['action_url']|ezurl, '>', $info_data['action_url']|wash, '</a>', ">"|wash ) ) )}</h2>
    </div>
{/case}

{case}
{/case}

{/switch}

{* Errors END *}


{def $aliasList=$filter.items}

<form name="aliasform" method="post" action={"content/urltranslator/"|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Globally defined URL aliases [%alias_count]'|i18n( 'design/admin/content/urlalias_global',, hash( '%alias_count', $filter.count ) )|wash}</h1>
{* DESIGN: Mainline *}<div class="header-mainline"></div>
{* DESIGN: Header END *}</div></div>
{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {foreach $limitList as $limitEntry}
        {if eq($limitID, $limitEntry['id'])}
            <span class="current">{$limitEntry['value']}</span>
        {else}
            <a href={concat('/user/preferences/set/admin_urlalias_list_limit/', $limitEntry['id'])|ezurl} title="{'Show %number_of items per page.'|i18n( 'design/admin/content/urlalias_global',, hash( '%number_of', $limitEntry['value'] ) )}">{$limitEntry['value']}</a>
        {/if}
    {/foreach}
    </p>
</div>
<div class="break"></div>

</div>
</div>


{* list here *}
{if eq( count( $aliasList ), 0)}
<div class="block">
<p>{"The global list does not contain any aliases."|i18n( 'design/admin/content/urlalias_global' )}</p>
</div>
{else}
<table class="list" cellspacing="0" >
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/content/urlalias_global' )}" title="{'Invert selection.'|i18n( 'design/admin/content/urlalias_global' )}" onclick="ezjs_toggleCheckboxes( document.aliasform, 'ElementList[]' ); return false;"/></th>
    <th>{'URL alias'|i18n( 'design/admin/content/urlalias_global' )}</th>
    <th>{'Destination'|i18n( 'design/admin/content/urlalias_global' )}</th>
    <th>{'Language'|i18n( 'design/admin/content/urlalias_global' )}</th>
    <th>{'Always available'|i18n( 'design/admin/content/urlalias_global' )}</th>
    <th>{'Type'|i18n( 'design/admin/content/urlalias_global' )}</th>
</tr>
{foreach $aliasList as $element sequence array('bglight', 'bgdark') as $seq}
    <tr class="{$seq}">
        {* Remove. *}
        <td>
            <input type="checkbox" name="ElementList[]" value="{$element.parent}.{$element.text_md5}.{$element.language_object.locale}" />
        </td>

        <td>
            {def $url_alias_path=""}
            {foreach $element.path_array as $el}
                {if ne( $el.action, "nop:" )}
                    {set $url_alias_path=concat($url_alias_path, '/',
                                                '<a href=', concat("/",$el.path)|ezurl, ">",
                                                $el.text|wash,
                                                '</a>')}
                {else}
                    {set $url_alias_path=concat($url_alias_path, '/', $el.text|wash)}
                {/if}
            {/foreach}
            {$url_alias_path}
            {undef $url_alias_path}
        </td>

        <td>
            <a href={$element.action_url|ezurl}>{$element.action_url}</a>
        </td>

        <td>
            <img src="{$element.language_object.locale|flag_icon}" alt="{$element.language_object.locale|wash}" />
            &nbsp;
            {$element.language_object.name|wash}
        </td>
        <td>
            {if $element.always_available}
                yes
            {else}
                no
            {/if}
        </td>
        <td>
            {if $element.alias_redirects}
                {'Redirect'|i18n( 'design/admin/content/urlalias_global' )}
            {else}
                {'Direct'|i18n( 'design/admin/content/urlalias_global' )}
            {/if}
        </td>
    </tr>
{/foreach}
 </table>

<div class="context-toolbar">
    {include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='content/urltranslator/'
         item_count=$filter.count
         view_parameters=$view_parameters
         item_limit=$filter.limit}
</div>
{/if}


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

{* buttons here *}
<div class="block">
<div class="button-left">
    {if $aliasList|count|gt( 0 )}
    <input class="button" type="submit" name="RemoveAliasButton" value="{'Remove selected'|i18n( 'design/admin/content/urlalias_global' )}" title="{'Remove selected aliases from the list above.'|i18n( 'design/admin/content/urlalias_global' )}" onclick="return confirm( '{'Are you sure you want to remove the selected aliases?'|i18n( 'design/admin/content/urlalias_global' )}' );"/>
    <input class="button" type="submit" name="RemoveAllAliasesButton" value="{'Remove all'|i18n( 'design/admin/content/urlalias_global' )}" title="{'Remove all global aliases.'|i18n( 'design/admin/content/urlalias_global' )}" onclick="return confirm( '{'Are you sure you want to remove all global aliases?'|i18n( 'design/admin/content/urlalias_global' )}' );"/>
    {else}
    <input class="button-disabled" type="submit" name="RemoveAliasButton" value="{'Remove selected'|i18n( 'design/admin/content/urlalias_global' )}" title="{'There are no removable aliases.'|i18n( 'design/admin/content/urlalias_global' )}" disabled="disabled" />
    <input class="button-disabled" type="submit" name="RemoveAllAliasesButton" value="{'Remove all'|i18n( 'design/admin/content/urlalias_global' )}" title="{'There are no removable aliases.'|i18n( 'design/admin/content/urlalias_global' )}" disabled="disabled" />
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
<h2 class="context-title">{'Create new alias'|i18n( 'design/admin/content/urlalias' )}</h2>

{* DESIGN: Header END *}</div></div>
{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
{* Alias name field. *}
    <label for="ezcontent_urlalias_global_source">{'New URL alias'|i18n( 'design/admin/content/urlalias_global' )}:</label>
    <input id="ezcontent_urlalias_global_source" class="box" type="text" name="AliasSourceText" value="{$aliasSourceText|wash}" title="{'Enter the URL for the new alias. Use forward slashes (/) to create subentries.'|i18n( 'design/admin/content/urlalias_global' )}" />
</div>

<div class="block">
{* Destination field. *}
    <label for="ezcontent_urlalias_global_destination">{'Destination (path to existing functionality or resource)'|i18n( 'design/admin/content/urlalias_global' )}:</label>
    <input id="ezcontent_urlalias_global_destination" class="box" type="text" name="AliasDestinationText" value="{$aliasDestinationText|wash}" title="{'Enter the destination URL for the new alias. Use forward slashes (/) to create subentries.'|i18n( 'design/admin/content/urlalias_global' )}" />
</div>

<div class="block">
<fieldset>
<legend>{'Language'|i18n( 'design/admin/content/urlalias_global' )}</legend>
{* Language dropdown. *}
<div class="block">
    <select name="LanguageCode" title="{'Choose the language for the new URL alias.'|i18n( 'design/admin/content/urlalias_global' )}">
    {foreach $languages as $language}
               <option value="{$language.locale}">{$language.name|wash}</option>
    {/foreach}
    </select>
</div>

{* All languages flag. *}
<div class="block">
    <input type="checkbox" name="AllLanguages" id="all-languages" value="all-languages" /><label class="radio" for="all-languages" title="{'Makes the alias available in languages other than the one specified.'|i18n( 'design/admin/content/urlalias_global' )}">{'Include in other languages'|i18n( 'design/admin/content/urlalias' )}</label>
</div>
</fieldset>
</div>

{* Alias should redirect *}
<div class="block">
    <input type="checkbox" name="AliasRedirects" id="alias_redirects" value="alias_redirects" checked="checked" />
    <label class="radio" for="alias_redirects" title="{'Alias should redirect to its destination'|i18n( 'design/admin/content/urlalias_global' )}">{'Alias should redirect to its destination'|i18n( 'design/admin/content/urlalias' )}</label>
    <p>With <em>Alias should redirect to its destination</em> checked eZ Publish will redirect to the destination using a HTTP 301 response. Un-check it and the URL will stay the same &#8212; no redirection will be performed.</p>
</div>


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">
{* Create button. *}
    <input class="button" type="submit" name="NewAliasButton" value="{'Create'|i18n( 'design/admin/content/urlalias_global' )}" title="{'Create a new global URL alias.'|i18n( 'design/admin/content/urlalias_global' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

<div class="break"></div>

</div>
{* Generated aliases context block end *}

</form>

{literal}
<script language="JavaScript" type="text/javascript">
<!--
jQuery(function( $ )//called on document.ready
{
    with( document.aliasform )
    {
        for( var i=0; i<elements.length; i++ )
        {
            if( elements[i].type == 'text' && elements[i].name == 'AliasSourceText' )
            {
                elements[i].select();
                elements[i].focus();
                return;
            }
        }
    }
});
-->
</script>
{/literal}
