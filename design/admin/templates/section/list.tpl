<form method="post" action={'/section/list/'|ezurl}>

<div class="context-block">
<h2 class="context-title">{'Sections'|i18n( 'design/admin/section/list' )}&nbsp;[{$section_array|count}]</h2>

{* Section table. *}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th>{'Name'|i18n('design/admin/section/list')}</th>
    <th>{'ID'|i18n('design/admin/section/list')}</th>
    <th>{'Assign'|i18n('design/admin/section/list')}</th>
    <th>{'Edit'|i18n('design/admin/section/list')}</th>
</tr>
{section var=Sections loop=$section_array sequence=array( bglight, bgdark )}
<tr class="{$Sections.sequence}">
    <td><input type="checkbox" name="SectionIDArray[]" value="{$Sections.item.id}" /></td>
    <td>{$Sections.item.name}</td>
    <td>{$Sections.item.id}</td>
    <td><a href={concat("/section/assign/",$Sections.item.id,"/")|ezurl}><img src={"attach.png"|ezimage} alt="{'Assign'|i18n('design/standard/section')}" /></a></td>
    <td><a href={concat("/section/edit/",$Sections.item.id,"/")|ezurl}><img src={"edit.png"|ezimage} alt="{'Edit'|i18n('design/standard/section')}" /></a></td>
</tr>
{/section}
</table>

{* Navigator. *}
<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/section/list'
         item_count=$section_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>

{* Buttons. *}
<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="RemoveSectionButton" value="{'Remove selected'|i18n( 'design/admin/section/list' )}" />
<input class="button" type="submit" name="CreateSectionButton" value="{'New section'|i18n( 'design/admin/section/list' )}" />
</div>
</div>

</div>

</form>
