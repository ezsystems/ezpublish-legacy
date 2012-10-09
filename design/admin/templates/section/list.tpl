<form name="sections" method="post" action={'/section/list/'|ezurl}>

{let number_of_items=min( ezpreference( 'admin_section_list_limit' ), 3)|choose( 10, 10, 25, 50 )}

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Sections (%section_count)'|i18n( 'design/admin/section/list',, hash( '%section_count', $section_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page selector. *}
<div class="context-toolbar">
<div class="button-left">
<p class="table-preferences">
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
<div class="float-break"></div>
</div>

{* Section table. *}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="{'Invert selection.'|i18n( 'design/admin/section/list' )}" title="{'Invert selection.'|i18n( 'design/admin/section/list' )}" onclick="ezjs_toggleCheckboxes( document.sections, 'SectionIDArray[]' ); return false;" /></th>
    <th>{'Name'|i18n('design/admin/section/list')}</th>
    <th>{'Identifier'|i18n('design/admin/section/list')}</th>
    <th class="tight">{'ID'|i18n('design/admin/section/list')}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Sections loop=$section_array sequence=array( bglight, bgdark )}
<tr class="{$Sections.sequence}">
    <td><input type="checkbox" name="SectionIDArray[]" value="{$Sections.item.id}" title="{'Select section for removal.'|i18n( 'design/admin/section/list' )}" /></td>
    <td>{'section'|icon( 'small', 'section'|i18n( 'design/admin/section/list' ) )}&nbsp;<a href={concat( '/section/view/', $Sections.item.id )|ezurl}>{$Sections.item.name|wash}</a></td>
    <td>{$Sections.item.identifier}</td>
    <td class="number" align="right">{$Sections.item.id}</td>
    <td>
    {if or( $allowed_assign_sections|contains( $Sections.item.id ), $allowed_assign_sections|contains( '*' ) )}
        <a href={concat( '/section/assign/', $Sections.item.id, '/')|ezurl}><img src={'assign.gif'|ezimage} alt="{'Assign'|i18n( 'design/admin/section/list' )}" title="{'Assign a subtree to the <%section_name> section.'|i18n( 'design/admin/section/list',, hash( '%section_name', $Sections.item.name ) )|wash}" /></a>
    {else}
        <img src={'assign-disabled.gif'|ezimage} alt="{'Assign'|i18n( 'design/admin/section/list' )}" title="{'You are not allowed to assign the <%section_name> section.'|i18n( 'design/admin/section/list',, hash( '%section_name', $Sections.item.name ) )|wash}" />
    {/if}
    </td>
    <td><a href={concat( '/section/edit/',   $Sections.item.id, '/')|ezurl}><img src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/section/list' )}" title="{'Edit the <%section_name> section.'|i18n( 'design/admin/section/list',, hash( '%section_name', $Sections.item.name ) )|wash}" /></a></td>
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

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="RemoveSectionButton" value="{'Remove selected'|i18n( 'design/admin/section/list' )}" title="{'Remove selected sections.'|i18n( 'design/admin/section/list' )}" />
<input class="button" type="submit" name="CreateSectionButton" value="{'New section'|i18n( 'design/admin/section/list' )}" title="{'Create a new section.'|i18n( 'design/admin/section/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

{/let}

</form>

