<form method="post" action={'/section/list/'|ezurl}>

{let number_of_items=min( ezpreference( 'admin_section_list_limit' ), 3)|choose( 10, 10, 25, 50 )}

<div class="context-block">
<h2 class="context-title">{'Sections [%section_count]'|i18n( 'design/admin/section/list',, hash( '%section_count', $section_count ) )}</h2>

{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
<p>
{switch match=$number_of_items}
{case match=25}
<a href={'/user/preferences/set/admin_section_list_limit/1'|ezurl}>10</a>
<span class="current">25</span>
<a href={'/user/preferences/set/admin_section_list_limit/3'|ezurl}>50</a>
{/case}

{case match=50}
<a href={'/user/preferences/set/admin_section_list_limit/1'|ezurl}>10</a>
<a href={'/user/preferences/set/admin_section_list_limit/2'|ezurl}>25</a>
<span class="current">50</span>
{/case}

{case}
<span class="current">10</span>
<a href={'/user/preferences/set/admin_section_list_limit/2'|ezurl}>25</a>
<a href={'/user/preferences/set/admin_section_list_limit/3'|ezurl}>50</a>
{/case}

{/switch}
</p>
</div>
<div class="break"></div>
</div>
</div>

{* Section table. *}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th>{'Name'|i18n('design/admin/section/list')}</th>
    <th>{'ID'|i18n('design/admin/section/list')}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Sections loop=$section_array sequence=array( bglight, bgdark )}
<tr class="{$Sections.sequence}">
    <td><input type="checkbox" name="SectionIDArray[]" value="{$Sections.item.id}" /></td>
    <td>{'section'|icon( 'small', 'section'|i18n( 'design/admin/section/list' ) )}&nbsp;<a href={concat( '/section/view/', $Sections.item.id )|ezurl}>{$Sections.item.name}</a></td>
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

{/let}

</form>

