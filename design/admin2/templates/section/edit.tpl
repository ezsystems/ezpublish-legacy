<form method="post" action={concat( '/section/edit/', $section.id, '/' )|ezurl}>

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'section'|icon( 'normal', 'Section'|i18n( 'design/admin/section/edit' ) )}{'Edit <%section_name> [Section]'|i18n( 'design/admin/section/edit',, hash( '%section_name', $section.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<input type="hidden" name="SectionID" value="{$section.id}" />

<div class="context-attributes">

{* Name. *}
<div class="block">
<label for="sectionName">{'Name'|i18n( 'design/admin/section/edit' )}:</label>
<input class="box" id="sectionName" type="text" name="Name" value="{$section.name|wash}" />
</div>

{* Navigation part. *}
<div class="block">
<label for="NavigationPartIdentifier">{'Navigation part'|i18n( 'design/admin/section/edit' )}:</label>
<select id="NavigationPartIdentifier" name="NavigationPartIdentifier">
{section var=part loop=fetch( content, navigation_parts )}
    <option value="{$part.identifier|wash}" {if eq( $section.navigation_part_identifier, $part.identifier )}selected="selected"{/if}>{$part.name|wash}</option>
{/section}
</select>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/section/edit' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/section/edit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>

{literal}
<script language="JavaScript" type="text/javascript">
<!--
    window.onload=function()
    {
        document.getElementById('sectionName').select();
        document.getElementById('sectionName').focus();
    }
-->
</script>
{/literal}

