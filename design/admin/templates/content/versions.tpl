<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/edit_draft' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<p>
<label>{'Created'|i18n( 'design/admin/content/edit_draft' )}:</label>
{section show=$object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.current.creator.name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/edit_draft' )}
{/section}
</p>
<p>
<label>{'Last modified'|i18n( 'design/admin/content/edit_draft' )}:</label>
{section show=$object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name}
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


















{switch match=$edit_warning}
{case match=1}
<div class="message-warning">
<h2>{"Version not a draft"|i18n( 'design/admin/content/versions' )}</h2>
<ul>
    <li>{'Version %1 is not available for editing anymore, only drafts can be edited.'|i18n( 'design/admin/content/versions',, array( $edit_version ) )}</li>
    <li>{'To edit this version create a copy of it.'|i18n( 'design/admin/content/versions' )}</li>
</ul>
</div>
{/case}
{case match=2}
<div class="message-warning">
<h2>{'Version not yours'|i18n( 'design/admin/content/versions' )}</h2>
<ul>
    <li>{'Version %1 was not created by you, only your own drafts can be edited.'|i18n( 'design/admin/content/versions',, array( $edit_version ) )}</li>
    <li>{'To edit this version create a copy of it.'|i18n( 'design/admin/content/versions' )}</li>
</ul>
</div>
{/case}
{case match=3}
<div class="message-warning">
<h2>{'Unable to create new version'|i18n( 'design/admin/content/versions' )}</h2>
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
     version_list=fetch(content,version_list,hash(contentobject, $object,limit,$page_limit,offset,$view_parameters.offset))
     list_count=fetch(content,version_count, hash(contentobject, $object))}

<form name="versionsform" action={concat( '/content/versions/', $object.id, '/' )|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Versions for <%object_name> [%version_count]'|i18n( 'design/admin/content/versions',, hash( '%object_name', $object.name, '%version_count', $version_list|count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Toggle selection" onclick="ezjs_toggleCheckboxes( document.versionsform, 'DeleteIDArray[]' ); return false;"/></th>    <th>{'Version'|i18n( 'design/admin/content/versions' )}</th>
	<th>{'Status'|i18n( 'design/admin/content/versions' )}</th>
	<th>{'Translations'|i18n( 'design/admin/content/versions' )}</th>
	<th>{'Creator'|i18n( 'design/admin/content/versions' )}</th>
	<th>{'Created'|i18n( 'design/admin/content/versions' )}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Versions loop=$version_list sequence=array( bglight, bgdark )}
<tr class="{$Versions.sequence}">
	<td>
	    {section show=and( or( eq( $Versions.item.status, 0 ),eq( $Versions.item.status, 3), eq( $Versions.item.status, 4 ) ), $can_remove )}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Versions.item.id}" />
	    {section-else}
            <input type="checkbox" name="" value="" disabled="disabled" />
        {/section}
    </td>

	<td>
	<a href={concat( '/content/versionview/', $object.id, '/', $Versions.item.version, '/', $edit_language|not|choose( array( $edit_language, '/' ), '' ) )|ezurl}>{$Versions.item.version}</a>
        {section show=eq( $Versions.item.version, $object.current_version )}*{/section}
	</td>

	<td>
	{$Versions.item.status|choose( 'Draft'|i18n( 'design/admin/content/versions' ), 'Published'|i18n( 'design/admin/content/versions' ), 'Pending'|i18n( 'design/admin/content/versions' ), 'Archived'|i18n( 'design/admin/content/versions' ), 'Rejected'|i18n( 'design/admin/content/versions' ) )}
	</td>

	<td>
	{section var=Languages loop=$Versions.item.language_list}
        {delimiter}<br />{/delimiter}
	<a href={concat('/content/versionview/', $object.id, '/', $Versions.item.version, '/', $Languages.item.language_code, '/' )|ezurl}>{$Languages.item.locale.intl_language_name}</a>{/section}
	</td>

    <td>
	{$Versions.item.creator.name|wash}
	</td>

    <td>
	{$Versions.item.modified|l10n( shortdatetime )}
	</td>

    <td>
    {section show=$can_edit}
        <input type="image" name="CopyVersionButton[{$Versions.item.version}]" src={"copy.gif"|ezimage} alt="{'Copy and edit'|i18n( 'design/admin/content/versions' )}" />
    </td>

    <td>
        {section show=and($Versions.item.status|eq(0),$Versions.item.creator_id|eq( $user_id ) ) }
            <input type="image" name="EditButton[{$Versions.item.version}]" src={"edit.png"|ezimage} alt="{'Edit'|i18n( 'design/admin/content/versions' )}" />
        {/section}
    {/section}
    </td>
</tr>
{/section}

</table>

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
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/versions' )}" {section show=$can_remove|not}disabled="disabled"{/section} />

<input type="hidden" name="EditLanguage" value="{$edit_language}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>
</form>

{/let}















<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
