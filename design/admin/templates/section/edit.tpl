<form method="post" action={concat( '/section/edit/', $section.id, '/' )|ezurl}>

<div class="context-block">
<h2 class="context-title">{'Edit'|i18n( 'design/admin/section/edit' )}&nbsp;<i>{$section.name}</i>&nbsp;[{'Section'|i18n( 'design/admin/section/edit' )}]</h2>

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
<option value="ezcontentnavigationpart" {section show=eq( $section.navigation_part_identifier, 'ezcontentnavigationpart')}selected="selected"{/section}>{'Content'|i18n('design/admin/section/edit')}</option>
<option value="ezmedianavigationpart"   {section show=eq( $section.navigation_part_identifier, 'ezmedianavigationpart'  )}selected="selected"{/section}>{'Media'|i18n('design/admin/section/edit')}</option>
<option value="ezshopnavigationpart"    {section show=eq( $section.navigation_part_identifier, 'ezshopnavigationpart'   )}selected="selected"{/section}>{'Shop'|i18n('design/admin/section/edit')}</option>
<option value="ezusernavigationpart"    {section show=eq( $section.navigation_part_identifier, 'ezusernavigationpart'   )}selected="selected"{/section}>{'Users'|i18n('design/admin/section/edit')}</option>
<option value="ezsetupnavigationpart"   {section show=eq( $section.navigation_part_identifier, 'ezsetupnavigationpart'  )}selected="selected"{/section}>{'Setup'|i18n('design/admin/section/edit')}</option>
<option value="ezmynavigationpart"      {section show=eq( $section.navigation_part_identifier, 'ezmynavigationpart'     )}selected="selected"{/section}>{'Personal'|i18n('design/admin/section/edit')}</option>
</select>
</div>

</div>

<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'OK'|i18n('design/standard/section')}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/standard/section')}" />
</div>
</div>

</div>

</form>
