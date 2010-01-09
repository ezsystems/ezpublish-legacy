<form method="post" action={concat( '/role/assign/', $role_id, '/', $limit_ident )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Select section'|i18n( 'design/admin/role/assign_limited_section' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$section_array}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th>{'Name'|i18n( 'design/admin/role/assign_limited_section' )}</th>
    <th>{'ID'|i18n( 'design/admin/role/assign_limited_section' )}</th>
</tr>
{section var=Sections loop=$section_array sequence=array( bglight, bgdark )}
<tr class="{$Sections.sequence}">

    {* Select. *}
    <td><input type="radio" name="SectionID" value="{$Sections.item.id}" {run-once}checked="checked"{/run-once} /></td>

    {* Name. *}
    <td>{$Sections.item.name|wash}</td>

    {* ID. *}
    <td>{$Sections.item.id}</td>

</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'There are no sections on the system.'|i18n( 'design/admin/role/assign_limited_section' )}</p>
</div>
{/section}


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input type="submit" class="button" name="AssignSectionID" value="{'OK'|i18n( 'design/admin/role/assign_limited_section' )}" />
<input type="submit" class="button" name="AssignSectionCancelButton" value="{'Cancel'|i18n( 'design/admin/role/assign_limited_section' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
