<form method="post" action={concat( 'content/versionview/', $object.id, '/', $object_version, '/', $language )|ezurl}>

<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Version information'|i18n( 'design/admin/content/view/versionview' )}</h4>

</div></div></div></div></div></div>

<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Number *}
<p>
<label>{'Version number'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$version.version}
</p>

{* Status *}
<p>
<label>{'Status'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$version.status|choose( 'Draft'|i18n( 'design/admin/content/view/versionview' ), 'Published / current'|i18n( 'design/admin/content/view/versionview' ), 'Pending'|i18n( 'design/admin/content/view/versionview' ), 'Archived'|i18n( 'design/admin/content/view/versionview' ), 'Rejected'|i18n( 'design/admin/content/view/versionview' ) )}
</p>

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$version.created|l10n( shortdatetime )}<br />
{$version.creator.name}
</p>

{* Last modified *}
<p>
<label>{'Last modified'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$version.modified|l10n( shortdatetime )}<br />
{$version.creator.name}
</p>

<div class="block">
{* Manage versions *}
{section show=$allow_versions_button}
<input class="button" type="submit" name="VersionsButton" value="{'Manage versions'|i18n( 'design/admin/content/view/versionview' )}" />
{section-else}
<input class="button" type="submit" name="VersionsButton" value="{'Manage versions'|i18n( 'design/admin/content/view/versionview' )}" disabled="disabled" title="{'You do not have permissions to manage versions.'i18n( 'design/admin/content/view/versionview' )}" />
{/section}
</div>

</div></div></div></div>


{* Preview control *}
<div class="preview-control">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">
<h4>{'Preview control'|i18n( 'design/admin/content/view/versionview' )}</h4>
</div></div></div></div>
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Translation *}
<label>{'Translation'|i18n( 'design/admin/view/versionview' )}:</label>
<div class="block">
{section var=Translations loop=$version.language_list}
<p>
<input type="radio" name="SelectedLanguage" value="{$Translations.item.language_code}" {section show=eq( $Translations.item.locale.locale_code, $object_languagecode )}checked="checked"{/section} />
{section show=$Translations.item.locale.is_valid}
<img src={concat( '/share/icons/flags/', $Translations.item.language_code, '.gif' )|ezroot} alt="($Translations.item.language_code)" style="vertical-align: middle;" /> {$Translations.item.locale.intl_language_name|shorten( 16 )}
{section-else}
{'%1 (No locale information available)'|i18n( 'design/admin/content/view/versionview',, array( $Translations.item.language_code ) )}
{/section}
</p>
{/section}
</div>

{* Location *}
<label>{'Location'|i18n( 'design/admin/content/view/versionview' )}:</label>
<div class="block">
{section var=Locations loop=$version.node_assignments}
<p>
<input type="radio" name="SelectedPlacement" value="{$Locations.item.id}" {section show=eq( $Locations.item.id, $placement )}checked="checked"{/section} />&nbsp;{$Locations.item.parent_node_obj.name|wash}
</p>
{/section}
</div>

{* Design *}
{let site_designs=fetch( layout, sitedesign_list )}
<label>{'Design'|i18n( 'design/admin/content/view/versionview' )}:</label>
<div class="block">
{section var=Designs loop=$site_designs}
<p>
<input type="radio" name="SelectedSitedesign" value="{$Designs.item}" {section show=eq( $Designs.item, $sitedesign )}checked="checked"{/section}>&nbsp;{$Designs.item|wash}
</p>
{/section}
</div>
{/let}

<input type="hidden" name="ContentObjectID" value="{$object.id}" />
<input type="hidden" name="ContentObjectVersion" value="{$object_version}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$object_languagecode}" />
<input type="hidden" name="ContentObjectPlacementID" value="{$placement}" />

<div class="block">
<input class="button" type="submit" name="ChangeSettingsButton" value="{'Show selected'|i18n( 'design/admin/content/view/versionview' )}" />
</div>

</div>
</div></div></div></div></div></div>
</div>


</div></div></div></div>
</div>


</div></div>

</div>

</div>
</div>

</form>


<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

{section show=$assignment}

{* Content window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title"><a href={concat( '/class/view/', $node.object.contentclass_id )|ezurl} onclick="ezpopmenu_showTopLevel( event, 'ClassMenu', ez_createAArray( new Array( '%classID%', {$node.object.contentclass_id}) ), '{$node.class_name|wash(javascript)}', -1 ); return false;">{$node.class_identifier|class_icon( normal, $node.class_name )}</a>&nbsp;{$node.name|wash}&nbsp;[{$node.class_name|wash}]</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

<div class="box-ml"><div class="box-mr">

<div class="context-information">
<p class="modified">&nbsp;</p>
<p class="translation">
{$object_languagecode|locale().intl_language_name} <img src={concat( '/share/icons/flags/', $object_languagecode, '.gif' )|ezroot} alt="{$object_languagecode}" style="vertical-align: middle;" />
</p>
<div class="break"></div>
</div>

{* Content preview in content window. *}

<div class="mainobject-window">
    {node_view_gui content_node=$node view=admin_preview}
</div>


</div></div>

{* Buttonbar for content window. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<form method="post" action={concat( 'content/versionview/', $object.id, '/', $object_version, '/', $language )|ezurl}>
{section show=and( eq( $version.status, 0 ), $is_creator, $object.can_edit )}
<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/content/view/versionview' )}" />
{* <input class="button" type="submit" name="PreviewPublishButton" value="{'Send for publishing'|i18n( 'design/admin/content/view/versionview' )}" /> *}
{section-else}
<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/content/view/versionview' )}" disabled="disabled" title="{'This version can not be edited because it is not a draft. Only drafts can be edited.'|i18n( 'design/admin/content/view/versionview' )}" />
{* <input class="button" type="submit" name="PreviewPublishButton" value="{'Send for publishing'|i18n( 'design/admin/content/view/versionview' )}" disabled="disabled" /> *}
{/section}
</form>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
</div>

{/section}



<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
