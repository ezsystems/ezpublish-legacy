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
{if $object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.current.creator.name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/edit_draft' )}
{/if}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/edit_draft' )}:</label>
{if $object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/edit_draft' )}
{/if}
</p>

{* Published version *}
<p>
<label>{'Published version'|i18n( 'design/admin/content/edit_draft' )}:</label>
{if $object.published}
{$object.current_version}
{else}
{'Not yet published'|i18n( 'design/admin/content/edit_draft' )}
{/if}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

<div class="message-warning">

<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Possible edit conflict'|i18n( 'design/admin/content/edit_draft' )}</h2>
    <p>{'The object has already been published by someone else.'|i18n( 'design/admin/content/edit_draft' )}</p>
    <p>{"You should contact the other user(s) to make sure that you are not stepping on anyone's toes."|i18n( 'design/admin/content/edit_draft' )}
    <p>{'Possible actions'|i18n( 'design/admin/content/edit_draft' )}:</p>
    <ul>
        <li>{'Publish data as it is (and overwriting the published data).'|i18n( 'design/admin/content/edit_draft' )}</li>
        <li>{'Go back to editing and show the published data.'|i18n( 'design/admin/content/edit_draft' )}</li>
    </ul>

</div>

<form method="post" action={concat( '/content/edit/', $object.id, '/', $current_version, '/', $edit_language, '/', $edit_language )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Conflicting versions [%draft_count]'|i18n( 'design/admin/content/edit_draft',, hash( '%draft_count', $draft_versions|count ) )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th>{'Version'|i18n( 'design/admin/content/edit_draft' )}</th>
    <th>{'Translations'|i18n( 'design/admin/content/edit_draft' )}</th>
    <th>{'Creator'|i18n( 'design/admin/content/edit_draft' )}</th>
    <th>{'Created'|i18n( 'design/admin/content/edit_draft' )}</th>
    <th>{'Modified'|i18n( 'design/admin/content/edit_draft' )}</th>
    <th>{'Status'|i18n( 'design/admin/content/edit_draft' )}</th>
</tr>

{section var=Drafts loop=$draft_versions sequence=array( bglight, bgdark )}
<tr class="{$Drafts.sequence}">
    {* Version/view. *}
    <td><a href={concat( '/content/versionview/', $object.id, '/', $Drafts.item.version, '/', $Drafts.item.language_list[0].language_code )|ezurl} title="{'View the contents of version #%version. Translation: %translation.'|i18n( 'design/admin/content/edit_draft',, hash( '%version', $Drafts.item.version, '%translation', $Drafts.item.language_list[0].locale.intl_language_name ) )}">{$Drafts.item.version}</a></td>

    {* Translation. *}
    <td>
        {section var=Languages loop=$Drafts.item.language_list}
            {delimiter}<br />{/delimiter}
            <img src="{$Languages.item.language_code|flag_icon}" alt="{$Languages.item.language_code}" />&nbsp;<a href={concat('/content/versionview/', $object.id, '/', $Drafts.item.version, '/', $Languages.item.language_code, '/' )|ezurl} title="{'View the contents of version #%version_number. Translation: %translation.'|i18n( 'design/admin/content/edit_draft',, hash( '%translation', $Languages.item.locale.intl_language_name, '%version_number', $Drafts.item.version ) )}" >{$Languages.item.locale.intl_language_name}</a>
        {/section}
    </td>

    {* Creator. *}
    <td>{$Drafts.item.creator.name|wash}</td>

    {* Created. *}
    <td>{$Drafts.item.created|l10n( shortdatetime )}</td>

    {* Modified. *}
    <td>{$Drafts.item.modified|l10n( shortdatetime )}</td>

    {* Status. *}
    <td>{$Drafts.item.status|choose( 'Draft'|i18n( 'design/admin/content/versions' ),'Published'|i18n( 'design/admin/content/versions' ), 'Pending'|i18n( 'design/admin/content/versions' ), 'Archived'|i18n( 'design/admin/content/versions' ), 'Untouched draft'|i18n( 'design/admin/content/versions' ) )}</td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">

<input class="button" type="submit" name="PublishButton" value="{'Publish data'|i18n( 'design/admin/content/edit' )}" title="{'Publish the contents of the draft that is being edited. The draft will become the published version of the object.'|i18n( 'design/admin/content/edit' )}" />
<input class="button" type="submit" name="ShowPublishedData" value="{'Show the published data'|i18n( 'design/admin/content/edit_draft' )}" title="{'Create a new draft. The contents of the new draft will be copied from the published version.'|i18n( 'design/admin/content/edit_draft' )}" />
<input type="hidden" name="PublishAfterConflict" value="1" />

</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>
</div>

</form>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
