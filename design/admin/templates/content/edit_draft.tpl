<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/edit_draft' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Object ID *}
<p>
<label>{'ID'|i18n( 'design/admin/content/edit_draft' )}:</label>
{$object.id}
</p>

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/edit_draft' )}:</label>
{section show=$object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.current.creator.name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit_draft' )}
{/section}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/edit_draft' )}:</label>
{section show=$object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit_draft' )}
{/section}
</p>

{* Published version *}
<p>
<label>{'Published version'|i18n( 'design/admin/content/edit_draft' )}:</label>
{section show=$object.published}
{$object.current_version}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit_draft' )}
{/section}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

{let has_own_drafts=false()
     has_other_drafts=false()
     current_creator=fetch(user,current_user)}
{section loop=$draft_versions}
    {section show=eq($item.creator_id,$current_creator.contentobject_id)}
        {set has_own_drafts=true()}
    {section-else}
        {set has_other_drafts=true()}
    {/section}
{/section}
<form method="post" action={concat( 'content/edit/', $object.id )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{$object.class_identifier|class_icon( normal, $object.class_name )}&nbsp;{'Edit <%object_name> [%class_name]'|i18n( 'design/admin/content/edit_draft',, hash( '%object_name', $object.name, '%class_name', $class.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="context-attributes">

<div class="block">

<p>{'The currently published version is %version and was published at %time.'|i18n( 'design/admin/content/edit_draft',, hash( '%version', $object.current_version, '%time', $object.published|l10n( datetime ) ) )}</p>
<p>{'The last modification was done at %modified.'|i18n( 'design/admin/content/edit_draft',,hash( '%modified', $object.modified|l10n( datetime ) ) )}</p>
<p>{'The object is owned by %owner.'|i18n( 'design/admin/content/edit_draft',, hash( '%owner', $object.owner.name ) )}</p>

{section show=and( $has_own_drafts, $has_other_drafts )}
<p>{'This object is already being edited by someone else including you. You can either continue editing one of your drafts or you can create a new draft.'|i18n( 'design/admin/content/edit_draft' )}</p>
{section-else}
    {section show=$has_own_drafts}
    <p>{'This object is already being edited by you. You can either continue editing one of your drafts or you can create a new draft.'|i18n( 'design/admin/content/edit_draft' )}</p>
    {/section}
    {section show=$has_other_drafts}
    <p>{'This object is already being edited by someone else. You should either contact the person about the draft or create a new draft for personal editing.'|i18n( 'design/admin/content/edit_draft' )}</p>
    {/section}
{/section}

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</div>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Current drafts [%draft_count]'|i18n( 'design/admin/content/edit_draft',, hash( '%draft_count', $draft_versions|count ) )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
   <th class="tight">&nbsp;</th>
    <th>{'Version'|i18n( 'design/admin/content/edit_draft' )}</th>
    <th>{'Name'|i18n( 'design/admin/content/edit_draft' )}</th>
    <th>{'Creator'|i18n( 'design/admin/content/edit_draft' )}</th>
    <th>{'Created'|i18n( 'design/admin/content/edit_draft' )}</th>
    <th>{'Modified'|i18n( 'design/admin/content/edit_draft' )}</th>
</tr>
{section var=Drafts loop=$draft_versions sequence=array( bglight, bgdark )}
<tr class="{$Drafts.sequence}">

{* Remove. *}
<td>
{section show=eq( $Drafts.item.creator_id, $current_creator.contentobject_id )}
<input type="radio" name="SelectedVersion" value="{$Drafts.item.version}" {run-once}checked="checked"{/run-once} />
{/section}
</td>

{* Version. *}
<td>{$Drafts.item.version}</td>

{* Name. *}
<td><a href={concat( 'content/versionview/', $object.id, '/', $Drafts.item.version )|ezurl}>{$Drafts.item.version_name|wash}</a></td>

{* Creator. *}
<td>{$Drafts.item.creator.name|wash}</td>

{* Created. *}
<td>{$Drafts.item.created|l10n( shortdatetime )}</td>

{* Modified. *}
<td>{$Drafts.item.modified|l10n( shortdatetime )}</td>

</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<input type="hidden" name="ContentObjectLanguageCode" value="{$edit_language}" />
<div class="block">

{section show=$has_own_drafts}
<input class="button" type="submit" name="EditButton" value="{'Edit selected'|i18n( 'design/admin/content/edit_draft' )}" />
{section-else}
<input class="button-disabled" type="submit" name="EditButton" value="{'Edit selected'|i18n( 'design/admin/content/edit_draft' )}" disabled="disabled" />
{/section}

<input class="button" type="submit" name="NewDraftButton" value="{'New draft'|i18n( 'design/admin/content/edit_draft' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>
{/let}


<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
