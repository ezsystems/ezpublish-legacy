<form method="post" action={concat( '/section/edit/', $section.id, '/' )|ezurl}>

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'section'|icon( 'normal', 'Section'|i18n( 'design/admin/section/edit' ) )}{'Edit <%section_name> [Section]'|i18n( 'design/admin/section/edit',, hash( '%section_name', $section.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<input type="hidden" name="SectionID" value="{$section.id}" />

<div class="context-attributes">

{* Name. *}
<div class="block">
<label>{'Name'|i18n( 'design/admin/section/edit' )}</label>
<input class="box" type="text" name="Name" value="{$section.name}" />
</div>

{* Navigation part. *}
<div class="block">
<label>{'Navigation Part'|i18n( 'design/admin/section/edit' )}</label><div class="labelbreak"></div>
<select name="NavigationPartIdentifier">
<option value="ezcontentnavigationpart" {section show=eq( $section.navigation_part_identifier, 'ezcontentnavigationpart')}selected="selected"{/section}>{'Content'|i18n( 'design/admin/section/edit' )}</option>
<option value="ezmedianavigationpart"   {section show=eq( $section.navigation_part_identifier, 'ezmedianavigationpart'  )}selected="selected"{/section}>{'Media'|i18n( 'design/admin/section/edit' )}</option>
<option value="ezshopnavigationpart"    {section show=eq( $section.navigation_part_identifier, 'ezshopnavigationpart'   )}selected="selected"{/section}>{'Shop'|i18n( 'design/admin/section/edit' )}</option>
<option value="ezusernavigationpart"    {section show=eq( $section.navigation_part_identifier, 'ezusernavigationpart'   )}selected="selected"{/section}>{'Users'|i18n( 'design/admin/section/edit' )}</option>
<option value="ezvisualnavigationpart"   {section show=eq( $section.navigation_part_identifier, 'ezvisualnavigationpart'  )}selected="selected"{/section}>{'Design'|i18n( 'design/admin/section/edit' )}</option>
<option value="ezsetupnavigationpart"   {section show=eq( $section.navigation_part_identifier, 'ezsetupnavigationpart'  )}selected="selected"{/section}>{'Setup'|i18n( 'design/admin/section/edit' )}</option>
<option value="ezmynavigationpart"      {section show=eq( $section.navigation_part_identifier, 'ezmynavigationpart'     )}selected="selected"{/section}>{'Personal'|i18n( 'design/admin/section/edit' )}</option>
</select>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'OK'|i18n('design/admin/section/edit')}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/admin/section/edit')}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
