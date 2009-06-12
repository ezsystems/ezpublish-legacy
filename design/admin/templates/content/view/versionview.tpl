<form method="post" action={concat( 'content/versionview/', $object.id, '/', $version.version, '/', $language, '/', $from_language )|ezurl}>

<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/view/versionview' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-br"><div class="box-bl"><div class="box-content">

{* Object ID *}
<p>
<label>{'ID'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$object.id}
</p>

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/view/versionview' )}:</label>
{section show=$object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.current.creator.name|wash}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/view/versionview' )}
{/section}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/view/versionview' )}:</label>
{section show=$object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name|wash}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/view/versionview' )}
{/section}
</p>

{* Published version *}
<p>
<label>{'Published version'|i18n( 'design/admin/content/view/versionview' )}:</label>
{section show=$object.status|eq( 1 )} {* status equal to 1 means it is published *}
{$object.main_node.contentobject_version}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/view/versionview' )}
{/section}
</p>

{* Manage versions *}
<div class="block">
{section show=$object.versions|count|gt( 1 )}
<input class="button" type="submit" name="VersionsButton" value="{'Manage versions'|i18n( 'design/admin/content/view/versionview' )}" title="{'View and manage (copy, delete, etc.) the versions of this object.'|i18n( 'design/admin/content/view/versionview' )}" />
{section-else}
<input class="button-disabled" type="submit" name="VersionsButton" value="{'Manage versions'|i18n( 'design/admin/content/view/versionview' )}" disabled="disabled" title="{'You cannot manage the versions of this object because there is only one version available (the one that is being displayed).'|i18n( 'design/admin/content/view/versionview' )}" />
{/section}
</div>

</div></div></div></div></div></div>

</div>
<br />



<div class="versioninfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Version information'|i18n( 'design/admin/content/view/versionview' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-content">

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$version.created|l10n( shortdatetime )}<br />
{$version.creator.name|wash}
</p>

{* Last modified *}
<p>
<label>{'Last modified'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$version.modified|l10n( shortdatetime )}<br />
{$version.creator.name|wash}
</p>

{* Status *}
<p>
<label>{'Status'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$version.status|choose( 'Draft'|i18n( 'design/admin/content/view/versionview' ), 'Published / current'|i18n( 'design/admin/content/view/versionview' ), 'Pending'|i18n( 'design/admin/content/view/versionview' ), 'Archived'|i18n( 'design/admin/content/view/versionview' ), 'Rejected'|i18n( 'design/admin/content/view/versionview' ) )}
</p>

{* Version *}
<p>
<label>{'Version'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$version.version}
</p>

</div></div></div></div></div>


{* View control *}
<div class="view-control">
<div class="box-header"><div class="box-ml"><div class="box-mr">
<h4>{'View control'|i18n( 'design/admin/content/view/versionview' )}</h4>
</div></div></div>
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Translation *}
{if fetch( content, translation_list )|count|gt( 1 )}
    <label>{'Translation'|i18n( 'design/admin/content/view/versionview' )}:</label>
    <div class="block">
    {if $translation_list|count|gt( 1 )}
        {def $locale_object = false}
        {foreach $translation_list as $locale_code}
            {set $locale_object = $locale_code|locale()}
            <p>
            <input type="radio" name="SelectedLanguage" value="{$locale_code}" {if eq( $locale_code, $object_languagecode )}checked="checked"{/if} />
            {if $locale_object.is_valid}
                <img src="{$locale_code|flag_icon}" alt="{$locale_code}" style="vertical-align: middle;" /> {$locale_object.intl_language_name|shorten( 16 )}
            {else}
                {'%1 (No locale information available)'|i18n( 'design/admin/content/view/versionview',, array( $locale_code ) )}
            {/if}
            </p>
        {/foreach}
    {else}
        <input type="radio" name="SelectedLanguage" value="{$version.language_list[0].language_code}" checked="checked" disabled="disabled" />
        {if $version.language_list[0].locale.is_valid}
            <img src="{$version.language_list[0].language_code|flag_icon}" alt="{$version.language_list[0].language_code}" style="vertical-align: middle;" /> {$version.language_list[0].locale.intl_language_name|shorten( 16 )}
        {else}
            {'%1 (No locale information available)'|i18n( 'design/admin/content/view/versionview',, array( $version.language_list[0].language_code ) )}
        {/if}
    {/if}
    </div>
{/if}

{* Location *}
{section show=$version.node_assignments|count|gt( 0 )}
<label>{'Location'|i18n( 'design/admin/content/view/versionview' )}:</label>
<div class="block">
{section show=$version.node_assignments|count|gt( 1 )}
{section var=Locations loop=$version.node_assignments}
<p>
<input type="radio" name="SelectedPlacement" value="{$Locations.item.id}" {section show=eq( $Locations.item.id, $placement )}checked="checked"{/section} />&nbsp;{$Locations.item.parent_node_obj.name|wash}
</p>
{/section}
{section-else}
<p>
<input type="radio" name="SelectedPlacement" value="{$version.node_assignments[0].id}" checked="checked" disabled="disabled" />&nbsp;{$version.node_assignments[0].parent_node_obj.name|wash}
</p>
{/section}
</div>
{/section}

{* Design *}
<label>{'Siteaccess'|i18n( 'design/admin/content/view/versionview' )}:</label>
<div class="block">
{if $site_access_locale_map|count|gt( 1 )}
    {foreach $site_access_locale_map as $related_site_access => $related_site_access_locale}
        <p>
        <input type="radio" name="SelectedSiteAccess" value="{$related_site_access}" {if eq( $related_site_access, $siteaccess )}checked="checked"{/if} />&nbsp;{$related_site_access|wash}
        </p>
    {/foreach}
{else}
    <p>
    <input type="radio" name="SelectedSiteAccess" value="{$site_designs[0]}" checked="checked" disabled="disabled" />&nbsp;{$site_designs[0]|wash}
    </p>
{/if}
</div>

<div class="block">
<input class="button" type="submit" name="ChangeSettingsButton" value="{'Update view'|i18n( 'design/admin/content/view/versionview' )}" title="{'View the version that is currently being displayed using the selected language, location and design.'|i18n( 'design/admin/content/view/versionview' )}" />
</div>

</div>
</div></div></div></div></div></div>
</div>

</div>

</form>



<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

{* Translation mismatch notice *}
{if $object_languagecode|eq( $site_access_locale_map[$siteaccess] )|not}
<div class="message-feedback">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Translation mismatch'|i18n( 'design/admin/content/view/versionview' )}</h2>
    <p>{'Your selected translation does not match the language of your selected siteaccess. This may lead to unexpected results in the preview, however it may also be what you intended.'|i18n( 'design/admin/content/view/versionview' )}</p>
</div>
{/if}

{* Content window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title"><a href={concat( '/class/view/', $object.contentclass_id )|ezurl} onclick="ezpopmenu_showTopLevel( event, 'ClassMenu', ez_createAArray( new Array( '%classID%', {$object.contentclass_id}) ), '{$object.content_class.name|wash(javascript)}', -1 ); return false;">{$object.content_class.identifier|class_icon( normal, $node.class_name )}</a>&nbsp;{$object.name|wash}&nbsp;[{$object.content_class.name|wash}]</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

<div class="box-ml"><div class="box-mr">

<div class="context-information">
<p class="modified">&nbsp;</p>
<p class="translation">
{$object_languagecode|locale().intl_language_name} <img src="{$object_languagecode|flag_icon}" alt="{$object_languagecode}" style="vertical-align: middle;" />
</p>
<p class="full-screen">
<a href={concat("content/versionview/",$object.id,"/",$view_version.version,"/",$language, "/site_access/", $siteaccess)|ezurl} target="_blank"><img src={"images/window_fullscreen.png"|ezdesign} /></a>
</p>
<div class="break"></div>
</div>

{* Content preview in content window. *}
<div class="mainobject-window">

    <iframe src={concat("content/versionview/",$object.id,"/",$view_version.version,"/",$language, "/site_access/", $siteaccess )|ezurl} width="100%" height="800">
    Your browser does not support iframes. Please see this <a href={concat("content/versionview/",$object.id,"/",$view_version.version,"/",$language, "/site_access/", $siteaccess)|ezurl}>link</a> instead.
</iframe>

</div>


</div></div>

{* Buttonbar for content window. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<form method="post" action={concat( 'content/versionview/', $object.id, '/', $version.version, '/', $language, '/', $from_language )|ezurl}>
{* version.status 0 is draft
   object.status 2 is archived *}
{section show=or( and( eq( $version.status, 0 ), $is_creator, $object.can_edit ),
                  and( eq( $object.status, 2 ), $object.can_edit ) )}
<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/content/view/versionview' )}" title="{'Edit the draft that is being displayed.'|i18n( 'design/admin/content/view/versionview' )}" />
<input class="button" type="submit" name="PreviewPublishButton" value="{'Publish'|i18n( 'design/admin/content/view/versionview' )}" title="{'Publish the draft that is being displayed.'|i18n( 'design/admin/content/view/versionview' )}" />
{section-else}
<input class="button-disabled" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/content/view/versionview' )}" disabled="disabled" title="{'This version is not a draft and therefore cannot be edited.'|i18n( 'design/admin/content/view/versionview' )}" />
<input class="button-disabled" type="submit" name="PreviewPublishButton" value="{'Publish'|i18n( 'design/admin/content/view/versionview' )}" disabled="disabled" title="{'Publish the draft that is being displayed.'|i18n( 'design/admin/content/view/versionview' )}" />
{/section}
</form>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
</div>



<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
