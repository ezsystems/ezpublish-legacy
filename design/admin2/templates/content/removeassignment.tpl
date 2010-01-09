<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h4>{'Object information'|i18n( 'design/admin/content/removeassignment' )}</h4>

{* DESIGN: Header END *}</div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-br"><div class="box-bl"><div class="box-content">

{let content_object=fetch( content, object, hash( object_id, $assignment_data.object_id  ) )
     content_version=fetch( content, version, hash( object_id, $assignment_data.object_id, version_id, $assignment_data.object_version ) )}
<p>
<label>{'ID'|i18n( 'design/admin/content/removeassignment' )}:</label>
{$content_object.id}
</p>

<p>
<label>{'Created'|i18n( 'design/admin/content/removeassignment' )}:</label>
{if $content_object.published}
{$content_object.published|l10n( shortdatetime )}<br />
{$content_object.current.creator.name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/removeassignment' )}
{/if}
</p>

<p>
<label>{'Modified'|i18n( 'design/admin/content/removeassignment' )}:</label>
{if $content_object.modified}
{$content_object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $content_object.content_class.modifier_id ) ).name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/removeassignment' )}
{/if}
</p>

<p>
<label>{'Published version'|i18n( 'design/admin/content/removeassignment' )}:</label>
{if $content_object.published}
{$content_object.current.version}
{else}
{'Not yet published'|i18n( 'design/admin/content/removeassignment' )}
{/if}
</p>


{* Manage versions. *}
<div class="block">
<input class="button-disabled" type="submit" name="" value="{'Manage versions'|i18n( 'design/admin/content/removeassignment' )}" disabled="disabled" />
</div>

</div></div></div></div></div></div>

</div>

<br />

<div class="drafts">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h4>{'Current draft'|i18n( 'design/admin/content/removeassignment' )}</h4>

{* DESIGN: Header END *}</div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Created. *}
<p>
<label>{'Created'|i18n( 'design/admin/content/removeassignment' )}:</label>
{$content_version.created|l10n( shortdatetime )}<br />
{$content_version.creator.name|wash}
</p>

{* Modified. *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/removeassignment' )}:</label>
{$content_version.modified|l10n( shortdatetime )}<br />
{$content_version.creator.name|wash}
</p>

{* Version. *}
<p>
<label>{'Version'|i18n( 'design/admin/content/removeassignment' )}:</label>
{$content_version.version}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent">
<div id="maincontent-design" class="float-break"><div id="fix">
<!-- Maincontent START -->


<form method="post" action={'/content/removeassignment/'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

{if $remove_info.can_remove_all}
<h2 class="context-title">{'Confirm location removal'|i18n( 'design/admin/content/removeassignment' )}</h2>
{else}
<h2 class="context-title">{'Insufficient permissions'|i18n( 'design/admin/content/removeassignment' )}</h2>
{/if}

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">

    <p>{'Some of the locations that are about to be removed contain sub items.'|i18n( 'design/admin/content/removeassignment' )}</p>

    {if $remove_info.can_remove_all}
        <p>{'Removing the locations will also result in the removal of the sub items.'|i18n( 'design/admin/content/removeassignment' )}</p>
        <p>{'Are you sure you want to remove the locations along with their contents?'|i18n( 'design/admin/content/removeassignment' )}</p>
    {else}
        <p>{'The locations marked with red contain items that you do not have permission to remove.'|i18n( 'design/admin/content/removeassignment' )}</p>
        <p>{'Click the "Cancel" button and try removing only the locations that you are allowed to remove.'|i18n( 'design/admin/content/removeassignment' )}</p>
    {/if}

</div>

<table class="list" cellspacing="0">
<tr>
    <th colspan="2">{'Location'|i18n( 'design/admin/content/removeassignment' )}</th>
    <th>{'Sub items'|i18n( 'design/admin/content/removeassignment' )}</th>
</tr>

{section var=remove_item loop=$assignment_data.remove_list sequence=array( bglight, bgdark )}
<tr class="{$remove_item.sequence}{if $remove_item.can_remove|not} object-cannot-remove{/if}">
    {* Object icon. *}
    <td class="tight">{$remove_item.class.identifier|class_icon( small, $remove_item.class.name|wash )}</td>

    {* Location. *}
    <td>
    {section var=path_node loop=$remove_item.node.path|append( $remove_item.node )}
        {$path_node.name|wash}
    {delimiter} / {/delimiter}
    {/section}
    </td>

    {* Sub items. *}
    <td>
    {if $remove_item.child_count|eq( 1 )}
        {'%child_count item'
         |i18n( 'design/admin/content/removeassignment',,
                hash( '%child_count', $remove_item.child_count ) )}
     {else}
        {'%child_count items'
         |i18n( 'design/admin/content/removeassignment',,
                hash( '%child_count', $remove_item.child_count ) )}
     {/if}
     </td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">
    {if $remove_info.can_remove_all}
        <input class="button" type="submit" name="ConfirmRemovalButton" value="{'OK'|i18n( 'design/admin/content/removeassignment' )}" title="{'Remove the locations along with all the sub items.'|i18n( 'design/admin/content/removeassignment' )}" />
    {else}
        <input class="button-disabled" type="submit" name="ConfirmRemovalButton" value="{'OK'|i18n( 'design/admin/content/removeassignment' )}" title="{'You cannot continue because you do not have permission to remove some of the selected locations.'|i18n( 'design/admin/content/removeassignment' )}" disabled="disabled" />
    {/if}
    <input type="submit" class="button" name="CancelRemovalButton" value="{'Cancel'|i18n( 'design/admin/content/removeassignment' )}" title="{'Cancel the removal of locations.'|i18n( 'design/admin/content/removeassignment' )}" />
</div>

{* DESIGN: Control bar END *}</div></div>

</div>

</div>

</form>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
{/let}
