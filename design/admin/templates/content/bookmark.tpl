{let bookmark_list=fetch( content, bookmarks, array() )}
<form name="bookmarkaction" action={concat( 'content/bookmark/' )|ezurl} method="post" >

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'My bookmarks [%bookmark_count]'|i18n( 'design/admin/content/bookmark',, hash( '%bookmark_count', $bookmark_list|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$bookmark_list}

<table class="list" cellspacing="0"">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/content/bookmark' )}" onclick="ezjs_toggleCheckboxes( document.bookmarkaction, 'DeleteIDArray[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/bookmark' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/content/bookmark' )}</th>
    <th>{'Type'|i18n( 'design/admin/content/bookmark' )}</th>
    <th>{'Section'|i18n( 'design/admin/content/bookmark' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Bookmarks loop=$bookmark_list sequence=array( bglight, bgdark )}
<tr class="{$Bookmarks.sequence}">
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$Bookmarks.item.id}" /></td>
    <td>{$Bookmarks.item.node.object.content_class.identifier|class_icon( small, $Bookmarks.item.node.object.content_class.name )}&nbsp;<a href={concat( '/content/view/full/', $Bookmarks.item.node_id, '/' )|ezurl}>{$Bookmarks.item.node.name|wash}</a></td>
    <td>{$Bookmarks.item.node.object.content_class.name|wash}</td>
    <td>{fetch( section, object, hash( section_id, $Bookmarks.item.node.object.section_id ) ).name|wash}</td>
    <td>
    {section show=$Bookmarks.item.node.object.can_edit}
        <a href={concat( 'content/edit/', $Bookmarks.item.node.contentobject_id )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/content/bookmark' )}" /></a>
    {section-else}
        <img src={'edit_disabled.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/content/bookmark' )}" />
    {/section}
    </td>
</tr>
{/section}
<tr>
</table>

{*
<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/content/draft'
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}
</div>
*}

{section-else}
<p>{'There are no bookmarks in the list.'|i18n( 'design/admin/content/bookmark' )}</p>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/content/bookmark' )}" {section show=$bookmark_list|not}disabled="disabled"{/section} />
<input class="button" type="submit" name="AddButton" value="{'Add bookmarks'|i18n( 'design/admin/content/bookmark' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

{/let}

