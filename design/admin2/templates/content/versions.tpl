<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h4>{'Object information'|i18n( 'design/admin/content/versions' )}</h4>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{* Object ID *}
<p>
<label>{'ID'|i18n( 'design/admin/content/versions' )}:</label>
{$object.id}
</p>

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/versions' )}:</label>
{if $object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.current.creator.name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/versions' )}
{/if}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/versions' )}:</label>
{if $object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/versions' )}
{/if}
</p>

{* Published version*}
<p>
<label>{'Published version'|i18n( 'design/admin/content/versions' )}:</label>
{if $object.published}
{$object.current_version}
{else}
{'Not yet published'|i18n( 'design/admin/content/versions' )}
{/if}
</p>

{* DESIGN: Content END *}</div></div></div>

</div>

</div>
</div>

<div id="maincontent">
<div id="maincontent-design" class="float-break"><div id="fix">
<!-- Maincontent START -->

{switch match=$edit_warning}
{case match=1}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Version is not a draft'|i18n( 'design/admin/content/versions' )}</h2>
<ul>
    <li>{'Version %1 is not available for editing anymore. Only drafts can be edited.'|i18n( 'design/admin/content/versions',, array( $edit_version ) )}</li>
    <li>{'To edit this version, first create a copy of it.'|i18n( 'design/admin/content/versions' )}</li>
</ul>
</div>
{/case}
{case match=2}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Version is not yours'|i18n( 'design/admin/content/versions' )}</h2>
<ul>
    <li>{'Version %1 was not created by you. You can only edit your own drafts.'|i18n( 'design/admin/content/versions',, array( $edit_version ) )}</li>
    <li>{'To edit this version, first create a copy of it.'|i18n( 'design/admin/content/versions' )}</li>
</ul>
</div>
{/case}
{case match=3}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Unable to create new version'|i18n( 'design/admin/content/versions' )}</h2>
<ul>
    <li>{'Version history limit has been exceeded and no archived version can be removed by the system.'|i18n( 'design/admin/content/versions' )}</li>
    <li>{'You can change your version history settings in content.ini, remove draft versions or edit existing drafts.'|i18n( 'design/admin/content/versions' )}</li>
</ul>
</div>
{/case}
{case}
{/case}
{/switch}


{let page_limit=30
     list_count=fetch(content,version_count, hash(contentobject, $object))}

<form name="versionsform" action={concat( '/content/versions/', $object.id, '/' )|ezurl} method="post">

<div class="context-block content-versions">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Versions for <%object_name> [%version_count]'|i18n( 'design/admin/content/versions',, hash( '%object_name', $object.name, '%version_count', $list_count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{if $list_count}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Toggle selection" onclick="ezjs_toggleCheckboxes( document.versionsform, 'DeleteIDArray[]' ); return false;" /></th>
    <th>{'Version'|i18n( 'design/admin/content/versions' )}</th>
    <th>{'Status'|i18n( 'design/admin/content/versions' )}</th>
    <th>{'Modified translation'|i18n( 'design/admin/content/versions' )}</th>
    <th>{'Creator'|i18n( 'design/admin/content/versions' )}</th>
    <th>{'Created'|i18n( 'design/admin/content/versions' )}</th>
    <th>{'Modified'|i18n( 'design/admin/content/versions' )}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Version loop=fetch( content, version_list, hash( contentobject, $object,
                                                              limit, $page_limit,
                                                              offset, $view_parameters.offset ) ) sequence=array( bglight, bgdark )}
{def $initial_language = $Versions.item.initial_language}
<tr class="{$Versions.sequence}">

    {* Remove. *}
    <td>
        {if and( $Versions.item.can_remove, array( 0, 3, 4, 5 )|contains( $Versions.item.status ) )}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Versions.item.id}" title="{'Select version #%version_number for removal.'|i18n( 'design/admin/content/versions',, hash( '%version_number', $Versions.item.version ) )}" />
        {else}
            <input type="checkbox" name="_Disabled" value="" disabled="disabled" title="{'Version #%version_number cannot be removed because it is either the published version of the object or because you do not have permission to remove it.'|i18n( 'design/admin/content/versions',, hash( '%version_number', $Versions.item.version ) )}" />
        {/if}
    </td>

    {* Version/view. *}
    <td><a href={concat( '/content/versionview/', $object.id, '/', $Versions.item.version, '/', $initial_language.locale )|ezurl} title="{'View the contents of version #%version_number. Translation: %translation.'|i18n( 'design/admin/content/versions',, hash( '%version_number', $Versions.item.version, '%translation', $initial_language.name ) )}">{$Versions.item.version}</a></td>

    {* Status. *}
    <td>{$Versions.item.status|choose( 'Draft'|i18n( 'design/admin/content/versions' ), 'Published'|i18n( 'design/admin/content/versions' ), 'Pending'|i18n( 'design/admin/content/versions' ), 'Archived'|i18n( 'design/admin/content/versions' ), 'Rejected'|i18n( 'design/admin/content/versions' ), 'Untouched draft'|i18n( 'design/admin/content/versions' ) )}</td>

    {* Modified translation. *}
    <td>
        <img src="{$initial_language.locale|flag_icon}" alt="{$initial_language.locale}" />&nbsp;<a href={concat('/content/versionview/', $object.id, '/', $Versions.item.version, '/', $initial_language.locale, '/' )|ezurl} title="{'View the contents of version #%version_number. Translation: %translation.'|i18n( 'design/admin/content/versions',, hash( '%translation', $initial_language.name, '%version_number', $Versions.item.version ) )}" >{$initial_language.name|wash}</a>
    </td>

    {* Creator. *}
    <td>{$Versions.item.creator.name|wash}</td>

    {* Created. *}
    <td>{$Versions.item.created|l10n( shortdatetime )}</td>

    {* Modified. *}
    <td>{$Versions.item.modified|l10n( shortdatetime )}</td>

    {* Copy button. *}
    <td align="right" class="right">
    {def $can_edit_lang = 0}
    {section loop=$object.can_edit_languages}
        {if eq( $:item.id, $initial_language.id )}
        {set $can_edit_lang = 1}
        {/if}
    {/section}

    {section show=and( $can_edit, $can_edit_lang )}
        {section show=eq( $Versions.item.status, 5 )}
            <input class="button-disabled" type="submit" name="_Disabled" value="{'Copy'|i18n( 'design/admin/content/versions' )}" disabled="disabled" title="{'There is no need to make copies of untouched drafts.'|i18n( 'design/admin/content/versions' )}" />
        {section-else}
        <select name="CopyVersionLanguage[{$Versions.item.version}]">
            {section var=Languages loop=$Versions.item.language_list}
                <option value="{$Languages.item.language_code}"{if $Languages.item.language_code|eq($Versions.item.initial_language.locale)} selected="selected"{/if}>{$Languages.item.locale.intl_language_name|wash}</option>
            {/section}
        </select>&nbsp;<input class="button" type="submit" name="CopyVersionButton[{$Versions.item.version}]" value="{'Copy'|i18n( 'design/admin/content/versions' )}" title="{'Create a copy of version #%version_number.'|i18n( 'design/admin/content/versions',, hash( '%version_number', $Versions.item.version ) )}" />
        {/section}
    {section-else}
        <input class="button-disabled" type="submit" name="_Disabled" value="{'Copy'|i18n( 'design/admin/content/versions' )}" disabled="disabled" title="{'You cannot make copies of versions because you do not have permission to edit the object.'|i18n( 'design/admin/content/versions' )}" />
    {/section}
    {undef $can_edit_lang}
    </td>

    {* Edit button. *}
    <td>
        {if and( array(0, 5)|contains($Versions.item.status), $Versions.item.creator_id|eq( $user_id ), $can_edit ) }
        <input class="button" type="submit" name="EditButton[{$Versions.item.version}]" value="{'Edit'|i18n( 'design/admin/content/versions' )}" title="{'Edit the contents of version #%version_number.'|i18n( 'design/admin/content/versions',, hash( '%version_number', $Versions.item.version ) )}" />
        {else}
        <input class="button-disabled" type="submit" name="_Disabled" value="{'Edit'|i18n( 'design/admin/content/versions' )}" disabled="disabled" title="{'You cannot edit the contents of version #%version_number either because it is not a draft or because you do not have permission to edit the object.'|i18n( 'design/admin/content/versions',, hash( '%version_number', $Versions.item.version ) )}" />
        {/if}
    </td>

</tr>
{undef $initial_language}
{/section}
</table>
{else}
<div class="block">
<p>{'This object does not have any versions.'|i18n( 'design/admin/content/versions' )}</p>
</div>
{/if}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/content/versions/', $object.id, '///' )
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="button-left">
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/versions' )}" title="{'Remove the selected versions from the object.'|i18n( 'design/admin/content/versions' )}" />
<input type="hidden" name="DoNotEditAfterCopy" value="" />
</div>

<div class="button-right">
{if is_set( $redirect_uri )}
<input class="text" type="hidden" name="RedirectURI" value="{$redirect_uri}" />
{/if}
<input class="button" type="submit" name="BackButton" value="{'Back'|i18n( 'design/admin/content/versions' )}" />
</div>
<div class="float-break"></div>

{* DESIGN: Control bar END *}</div></div>

</div>

</div>
</form>

{/let}


<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
