<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/removeassignment' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-br"><div class="box-bl"><div class="box-content">

{let content_object=fetch( content, object, hash( object_id, $assignment_data.object_id  ) )
     content_version=fetch( content, version, hash( object_id, $assignment_data.object_id, version_id, $assignment_data.object_version ) )}
<p>
<label>{'ID'|i18n( 'design/admin/content/removeassignment' )}:</label>
{$content_object.id}
</p>

<p>
<label>{'Created'|i18n( 'design/admin/content/removeassignment' )}:</label>
{section show=$content_object.published}
{$content_object.published|l10n( shortdatetime )}<br />
{$content_object.current.creator.name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/removeassignment' )}
{/section}
</p>

<p>
<label>{'Modified'|i18n( 'design/admin/content/removeassignment' )}:</label>
{section show=$content_object.modified}
{$content_object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $content_object.content_class.modifier_id ) ).name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/removeassignment' )}
{/section}
</p>

<p>
<label>{'Published version'|i18n( 'design/admin/content/removeassignment' )}:</label>
{section show=$content_object.published}
{$content_object.current.version}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/removeassignment' )}
{/section}
</p>


{* Manage versions. *}
<div class="block">
<input class="button-disabled" type="submit" name="" value="{'Manage versions'|i18n( 'design/admin/content/removeassignment' )}" disabled="disabled" />
</div>

</div></div></div></div></div></div>

</div>

<br />

<div class="drafts">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Current draft'|i18n( 'design/admin/content/removeassignment' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Created. *}
<p>
<label>{'Created'|i18n( 'design/admin/content/removeassignment' )}:</label>
{$content_version.created|l10n( shortdatetime )}<br />
{$content_version.creator.name}
</p>

{* Modified. *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/removeassignment' )}:</label>
{$content_version.modified|l10n( shortdatetime )}<br />
{$content_version.creator.name}
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

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->


<form method="post" action={'/content/removeassignment/'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Confirm location removal'|i18n( 'design/admin/content/removeassignment' )}</h2>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">
    <p>{'Some of the locations that are about to be removed have sub items.'|i18n( 'design/admin/content/removeassignment' )}</p>
    <p>{'Removing the locations will also result in the removal of their sub items.'|i18n( 'design/admin/content/removeassignment' )}</p>
    <p>{'Are you sure you want to remove these locations along with their contents?'|i18n( 'design/admin/content/removeassignment' )}</p>
</div>

{section show=$remove_info.can_remove_all|not}
    <div class="message-confirmation">
        <p>{'Some of the locations cannot be removed, you will need to unselect the locations marked in red.'
            |i18n( 'design/admin/content/removeassignment' )}</p>
    </div>
{/section}

<table class="list" cellspacing="0">
<tr>
    <th colspan="2">{'Location'|i18n( 'design/admin/content/removeassignment' )}</th>
    <th>{'Sub items'|i18n( 'design/admin/content/removeassignment' )}</th>
</tr>

{section var=remove_item loop=$assignment_data.remove_list sequence=array( bglight, bgdark )}
<tr class="{$remove_item.sequence}{section show=$remove_item.can_remove|not} object-cannot-remove{/section}">
    {* Object icon. *}
    <td class="tight">{$remove_item.class.identifier|class_icon( small, $remove_item.class.name )}</td>

    {* Location. *}
    <td>
    {section var=path_node loop=$remove_item.node.path|append( $remove_item.node )}
        {$path_node.name|wash}
    {delimiter} / {/delimiter}
    {/section}
    </td>

    {* Sub items. *}
    <td>
    {section show=$remove_item.child_count|eq( 1 )}
        {'%child_count item'
         |i18n( 'design/admin/content/removeassignment',,
                hash( '%child_count', $remove_item.child_count ) )}
     {section-else}
        {'%child_count items'
         |i18n( 'design/admin/content/removeassignment',,
                hash( '%child_count', $remove_item.child_count ) )}
     {/section}
     </td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>


<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    {section show=$remove_info.can_remove_all}
        <input type="submit" class="button" name="ConfirmRemovalButton" value="{'OK'|i18n( 'design/admin/content/removeassignment' )}" title="{'Remove the locations along with all the sub items.'|i18n( 'design/admin/content/removeassignment' )}" />
    {/section}
    <input type="submit" class="button" name="CancelRemovalButton" value="{'Cancel'|i18n( 'design/admin/content/removeassignment' )}" title="{'Cancel the removal operation and go back to the edit page.'|i18n( 'design/admin/content/removeassignment' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>


<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
