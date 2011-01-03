<div id="leftmenu">
<div id="leftmenu-design">

{include uri="design:content/parts/object_information.tpl" object=$object manage_version_button=false()}

</div>
</div>

<div id="maincontent">
<div id="maincontent-design" class="float-break"><div id="fix">
<!-- Maincontent START -->

{switch match=$edit_warning}
{case match=1}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Version is not a draft'|i18n( 'design/admin/content/history' )}</h2>
<ul>
    <li>{'Version %1 is not available for editing anymore. Only drafts can be edited.'|i18n( 'design/admin/content/history',, array( $edit_version ) )}</li>
    <li>{'To edit this version, first create a copy of it.'|i18n( 'design/admin/content/history' )}</li>
</ul>
</div>
{/case}
{case match=2}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Version is not yours'|i18n( 'design/admin/content/history' )}</h2>
<ul>
    <li>{'Version %1 was not created by you. You can only edit your own drafts.'|i18n( 'design/admin/content/history',, array( $edit_version ) )}</li>
    <li>{'To edit this version, first create a copy of it.'|i18n( 'design/admin/content/history' )}</li>
</ul>
</div>
{/case}
{case match=3}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Unable to create new version'|i18n( 'design/admin/content/history' )}</h2>
<ul>
    <li>{'Version history limit has been exceeded and no archived version can be removed by the system.'|i18n( 'design/admin/content/history' )}</li>
    <li>{'You can change your version history settings in content.ini, remove draft versions or edit existing drafts.'|i18n( 'design/admin/content/history' )}</li>
</ul>
</div>
{/case}
{case}
{/case}
{/switch}


{def $page_limit   = 30
     $list_count   = fetch( 'content', 'version_count', hash( 'contentobject', $object ))}

<form name="versionsform" action={concat( '/content/history/', $object.id, '/' )|ezurl} method="post">

<div class="context-block content-history">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Versions for <%object_name> (%version_count)'|i18n( 'design/admin/content/history',, hash( '%object_name', $object.name, '%version_count', $list_count ) )|wash}</h1>
{* DESIGN: Mainline *}<div class="header-mainline"></div>
{* DESIGN: Header END *}</div></div>
{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{if $list_count}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="Toggle selection" onclick="ezjs_toggleCheckboxes( document.versionsform, 'DeleteIDArray[]' ); return false;" /></th>
    <th>{'Version'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Status'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Modified translation'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Creator'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Created'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Modified'|i18n( 'design/admin/content/history' )}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>

{def $version_list = fetch( 'content', 'version_list',  hash( 'contentobject', $object, 'limit', $page_limit, 'offset', $view_parameters.offset ))}
{foreach $version_list as $version sequence array( 'bglight', 'bgdark' ) as $seq}

{def $initial_language = $version.initial_language
     $can_edit_lang = 0}
<tr class="{$seq}">

    {* Remove. *}
    <td>
        {if and( $version.can_remove, array( 0, 3, 4, 5 )|contains( $version.status ) )}
            <input type="checkbox" name="DeleteIDArray[]" value="{$version.id}" title="{'Select version #%version_number for removal.'|i18n( 'design/admin/content/history',, hash( '%version_number', $version.version ) )}" />
        {else}
            <input type="checkbox" name="_Disabled" value="" disabled="disabled" title="{'Version #%version_number cannot be removed because it is either the published version of the object or because you do not have permission to remove it.'|i18n( 'design/admin/content/history',, hash( '%version_number', $version.version ) )}" />
        {/if}
    </td>

    {* Version/view. *}
    <td><a href={concat( '/content/versionview/', $object.id, '/', $version.version, '/', $initial_language.locale )|ezurl} title="{'View the contents of version #%version_number. Translation: %translation.'|i18n( 'design/admin/content/history',, hash( '%version_number', $version.version, '%translation', $initial_language.name ) )}">{$version.version}</a></td>

    {* Status. *}
    <td>{$version.status|choose( 'Draft'|i18n( 'design/admin/content/history' ), 'Published'|i18n( 'design/admin/content/history' ), 'Pending'|i18n( 'design/admin/content/history' ), 'Archived'|i18n( 'design/admin/content/history' ), 'Rejected'|i18n( 'design/admin/content/history' ), 'Untouched draft'|i18n( 'design/admin/content/history' ) )}</td>

    {* Modified translation. *}
    <td>
        <img src="{$initial_language.locale|flag_icon}" width="18" height="12" alt="{$initial_language.locale}" />&nbsp;<a href={concat('/content/versionview/', $object.id, '/', $version.version, '/', $initial_language.locale, '/' )|ezurl} title="{'View the contents of version #%version_number. Translation: %translation.'|i18n( 'design/admin/content/history',, hash( '%translation', $initial_language.name, '%version_number', $version.version ) )}" >{$initial_language.name|wash}</a>
    </td>

    {* Creator. *}
    <td>{$version.creator.name|wash}</td>

    {* Created. *}
    <td>{$version.created|l10n( shortdatetime )}</td>

    {* Modified. *}
    <td>{$version.modified|l10n( shortdatetime )}</td>

    {* Copy button. *}
    <td align="right" class="right">
    {foreach $object.can_edit_languages as $edit_language}
        {if eq( $edit_language.id, $initial_language.id )}
        {set $can_edit_lang = 1}
        {/if}
    {/foreach}

        {if and( $can_edit, $can_edit_lang )}
          {if eq( $version.status, 5 )}
            <input type="image" src={'copy-disabled.gif'|ezimage} name="_Disabled" value="" disabled="disabled" title="{'There is no need to make copies of untouched drafts.'|i18n( 'design/admin/content/history' )}" />
          {else}
            <input type="hidden" name="CopyVersionLanguage[{$version.version}]" value="{$initial_language.locale}" />
            <input type="image" src={'copy.gif'|ezimage} name="HistoryCopyVersionButton[{$version.version}]" value="" title="{'Create a copy of version #%version_number.'|i18n( 'design/admin/content/history',, hash( '%version_number', $version.version ) )}" />
          {/if}
        {else}
            <input type="image" src={'copy-disabled.gif'|ezimage} name="_Disabled" value="" disabled="disabled" title="{'You cannot make copies of versions because you do not have permission to edit the object.'|i18n( 'design/admin/content/history' )}" />
        {/if}
    </td>

    {* Edit button. *}
    <td>
        {if and( array(0, 5)|contains($version.status), $version.creator_id|eq( $user_id ), $can_edit ) }
            <input type="image" src={'edit.gif'|ezimage} name="HistoryEditButton[{$version.version}]" value="" title="{'Edit the contents of version #%version_number.'|i18n( 'design/admin/content/history',, hash( '%version_number', $version.version ) )}" />
        {else}
            <input type="image" src={'edit-disabled.gif'|ezimage} name="HistoryEditButton[{$version.version}]" value="" disabled="disabled" title="{'You cannot edit the contents of version #%version_number either because it is not a draft or because you do not have permission to edit the object.'|i18n( 'design/admin/content/history',, hash( '%version_number', $version.version ) )}" />
        {/if}
    </td>

</tr>
{undef $initial_language $can_edit_lang}
{/foreach}
{undef $version_list}
</table>
{else}
    <div class="block">
    <p>{'This object does not have any versions.'|i18n( 'design/admin/content/history' )}</p>
    </div>
{/if}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/content/history/', $object.id, '///' )
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">
<div class="button-left">
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/history' )}" title="{'Remove the selected versions from the object.'|i18n( 'design/admin/content/history' )}" />
<input type="hidden" name="DoNotEditAfterCopy" value="" />
</div>
{if $object.can_diff}
{def $languages=$object.languages}
<div class="button-right">
<form action={concat( $module.functions.history.uri, '/', $object.id, '/' )|ezurl} method="post">
        <select name="Language">
            {foreach $languages as $lang}
                <option value="{$lang.locale}">{$lang.name|wash}</option>
            {/foreach}
        </select>
        <select name="FromVersion">
            {foreach $object.versions as $ver}
                <option {if eq( $ver.version, $selectOldVersion)}selected="selected"{/if} value="{$ver.version}">{$ver.version|wash}</option>
            {/foreach}
        </select>
        <select name="ToVersion">
            {foreach $object.versions as $ver}
                <option {if eq( $ver.version, $selectNewVersion)}selected="selected"{/if} value="{$ver.version}">{$ver.version|wash}</option>
            {/foreach}
        </select>
    <input type="hidden" name="ObjectID" value="{$object.id}" />
    <input class="button" type="submit" name="DiffButton" value="{'Show differences'|i18n( 'design/admin/content/history' )}" />
</form>
</div>
{/if}

<div class="break"></div>


<div class="block">
<div class="button-left">
<form name="versionsback" action={concat( '/content/history/', $object.id, '/' )|ezurl} method="post">
{if is_set( $redirect_uri )}
<input class="text" type="hidden" name="RedirectURI" value="{$redirect_uri}" />
{/if}
<input class="button" type="submit" name="BackButton" value="{'Back'|i18n( 'design/admin/content/history' )}" />
</form>

</div>
</div>


<div class="break"></div>

</div>

{* DESIGN: Control bar END *}</div></div>

</div>

</div>

{if and( is_set( $object ), is_set( $diff ), is_set( $oldVersion ), is_set( $newVersion ) )|not}
{* Published context block start *}
{* Published window. *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title">{'Published version'|i18n( 'design/admin/content/history' )}</h2>

{* DESIGN: Header END *}</div></div>
{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">


<table class="list" cellspacing="0">
<tr>
    <th>{'Version'|i18n( 'design/admin/content/history' )}</th>
    <th>{"Translations"|i18n("design/admin/content/history")}</th>
    <th>{'Creator'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Created'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Modified'|i18n( 'design/admin/content/history' )}</th>
    <th class="tight">{'Copy translation'|i18n( 'design/admin/content/history' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{def $published_item=$object.current
     $initial_language = $published_item.initial_language}
<tr>

    {* Version/view. *}
    <td><a href={concat( '/content/versionview/', $object.id, '/', $published_item.version, '/', $initial_language.locale )|ezurl} title="{'View the contents of version #%version_number. Translation: %translation.'|i18n( 'design/admin/content/history',, hash( '%version_number', $published_item.version, '%translation', $initial_language.name ) )}">{$published_item.version}</a></td>

    {* Translations *}
    <td>
        {foreach $published_item.language_list as $lang}
            {delimiter}<br />{/delimiter}
            <img src="{$lang.language_code|flag_icon}" width="18" height="12" alt="{$lang.language_code|wash}" />&nbsp;
            <a href={concat("/content/versionview/",$object.id,"/",$published_item.version,"/",$lang.language_code,"/")|ezurl}>{$lang.locale.intl_language_name|wash}</a>
        {/foreach}
    </td>

    {* Creator. *}
    <td>{$published_item.creator.name|wash}</td>

    {* Created. *}
    <td>{$published_item.created|l10n( shortdatetime )}</td>

    {* Modified. *}
    <td>{$published_item.modified|l10n( shortdatetime )}</td>

    {* Copy translation list. *}
    <td align="right" class="right">
        <select name="CopyVersionLanguage[{$published_item.version}]">
            {foreach $published_item.language_list as $lang_list}
                <option value="{$lang_list.language_code}"{if $lang_list.language_code|eq($published_item.initial_language.locale)} selected="selected"{/if}>{$lang_list.locale.intl_language_name|wash}</option>
            {/foreach}
        </select>
    </td>

    {* Copy button *}
    <td>
        {def $can_edit_lang = 0}
        {foreach $object.can_edit_languages as $edit_language}
            {if eq( $edit_language.id, $initial_language.id )}
            {set $can_edit_lang = 1}
            {/if}
        {/foreach}

        {if and( $can_edit, $can_edit_lang )}
            <input type="image" src={'copy.gif'|ezimage} name="HistoryCopyVersionButton[{$published_item.version}]" value="" title="{'Create a copy of version #%version_number.'|i18n( 'design/admin/content/history',, hash( '%version_number', $published_item.version ) )}" />
        {else}
            <input type="image" src={'copy-disabled.gif'|ezimage} name="_Disabled" value="" disabled="disabled" title="{'You cannot make copies of versions because you do not have permission to edit the object.'|i18n( 'design/admin/content/history' )}" />
        {/if}
        {undef $can_edit_lang}
    </td>

</tr>
{undef $initial_language}
</table>

{* DESIGN: Content END *}</div></div></div>
</div>
{* Published context block end *}

{* New drafts context block *}
{* Drafts window. *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title">{'New drafts (%newerDraftCount)'|i18n( 'design/admin/content/history',, hash( '%newerDraftCount', $newerDraftVersionListCount ) )}</h2>

{* DESIGN: Header END *}</div></div>
{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{if $newerDraftVersionList|count|ge(1)}
<table class="list" cellspacing="0">
<tr>
    <th>{'Version'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Modified translation'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Creator'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Created'|i18n( 'design/admin/content/history' )}</th>
    <th>{'Modified'|i18n( 'design/admin/content/history' )}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>

{foreach $newerDraftVersionList as $draft_version
    sequence array( bglight, bgdark ) as $seq}
{def $initial_language = $draft_version.initial_language}
<tr class="{$seq}">

    {* Version/view. *}
    <td><a href={concat( '/content/versionview/', $object.id, '/', $draft_version.version, '/', $initial_language.locale )|ezurl} title="{'View the contents of version #%version_number. Translation: %translation.'|i18n( 'design/admin/content/history',, hash( '%version_number', $draft_version.version, '%translation', $initial_language.name ) )}">{$draft_version.version}</a></td>

    {* Modified translation. *}
    <td>
        <img src="{$initial_language.locale|flag_icon}" width="18" height="12" alt="{$initial_language.locale}" />&nbsp;<a href={concat('/content/versionview/', $object.id, '/', $draft_version.version, '/', $initial_language.locale, '/' )|ezurl} title="{'View the contents of version #%version_number. Translation: %translation.'|i18n( 'design/admin/content/history',, hash( '%translation', $initial_language.name, '%version_number', $draft_version.version ) )}" >{$initial_language.name|wash}</a>
    </td>

    {* Creator. *}
    <td>{$draft_version.creator.name|wash}</td>

    {* Created. *}
    <td>{$draft_version.created|l10n( shortdatetime )}</td>

    {* Modified. *}
    <td>{$draft_version.modified|l10n( shortdatetime )}</td>

    {* Copy button. *}
    <td align="right" class="right">
    {def $can_edit_lang = 0}
    {foreach $object.can_edit_languages as $edit_language}
        {if eq( $edit_language.id, $initial_language.id )}
        {set $can_edit_lang = 1}
        {/if}
    {/foreach}

        {if and( $can_edit, $can_edit_lang )}
            <input type="hidden" name="CopyVersionLanguage[{$draft_version.version}]" value="{$initial_language.locale}" />
            <input type="image" src={'copy.gif'|ezimage} name="HistoryCopyVersionButton[{$draft_version.version}]" value="" title="{'Create a copy of version #%version_number.'|i18n( 'design/admin/content/history',, hash( '%version_number', $draft_version.version ) )}" />
        {else}
            <input type="image" src={'copy-disabled.gif'|ezimage} name="_Disabled" value="" disabled="disabled" title="{'You cannot make copies of versions because you do not have permission to edit the object.'|i18n( 'design/admin/content/history' )}" />
        {/if}
    {undef $can_edit_lang}
    </td>

    {* Edit button. *}
    <td>
        {if and( array(0, 5)|contains($draft_version.status), $draft_version.creator_id|eq( $user_id ), $can_edit ) }
            <input type="image" src={'edit.gif'|ezimage} name="HistoryEditButton[{$draft_version.version}]" value="" title="{'Edit the contents of version #%version_number.'|i18n( 'design/admin/content/history',, hash( '%version_number', $draft_version.version ) )}" />
        {else}
            <input type="image" src={'edit-disabled.gif'|ezimage} name="HistoryEditButton[{$draft_version.version}]" disabled="disabled" value="" title="{'You cannot edit the contents of version #%version_number either because it is not a draft or because you do not have permission to edit the object.'|i18n( 'design/admin/content/history',, hash( '%version_number', $draft_version.version ) )}" />
        {/if}
    </td>

</tr>
{undef $initial_language}
{/foreach}
</table>
{else}
<div class="block">
<p>{'This object does not have any drafts.'|i18n( 'design/admin/content/history' )}</p>
</div>
{/if}

{* DESIGN: Content END *}</div></div></div>
</div>

</form>

{elseif and( is_set( $object ), is_set( $diff ), is_set( $oldVersion ), is_set( $newVersion ) )}
{literal}
<script type="text/javascript">
function show( element, method )
{
    document.getElementById( element ).className = method;
}
</script>
{/literal}
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title">{'Differences between versions %oldVersion and %newVersion'|i18n( 'design/admin/content/history',, hash( '%oldVersion', $oldVersion, '%newVersion', $newVersion ) )}</h2>

{* DESIGN: Header END *}</div></div>
{* DESIGN: Content START *}<div class="box-ml"><div class="box-content">

<div id="diffview">

<div class="context-toolbar">
<div class="button-left">
<p class="table-preferences">
    <a href="JavaScript:void(0);" onclick="show('diffview', 'previous'); return false;">{'Old version'|i18n( 'design/admin/content/history' )}</a>
    <a href="JavaScript:void(0);" onclick="show('diffview', 'inlinechanges'); return false;">{'Inline changes'|i18n( 'design/admin/content/history' )}</a>
    <a href="JavaScript:void(0);" onclick="show('diffview', 'blockchanges'); return false;">{'Block changes'|i18n( 'design/admin/content/history' )}</a>
    <a href="JavaScript:void(0);" onclick="show('diffview', 'latest'); return false;">{'New version'|i18n( 'design/admin/content/history' )}</a>
</p>
</div>
<div class="float-break"></div>
</div>


{foreach $object.data_map as $attr}
<div class="block">
<label>{$attr.contentclass_attribute.name}:</label>
<div class="attribute-view-diff">
        {attribute_diff_gui view=diff attribute=$attr old=$oldVersion new=$newVersion diff=$diff[$attr.contentclassattribute_id]}
</div>
</div>
{/foreach}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="block">
<div class="left">
<form action={concat( '/content/history/', $object.id, '/' )|ezurl} method="post">
<input class="button" type="submit" value="{'Back to history'|i18n( 'design/admin/content/history' )}" />
</form>
</div>
</div>
{/if}

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
