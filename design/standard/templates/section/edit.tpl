<form method="post" action={concat("/section/edit/",$section.id,"/")|ezurl}>

<div class="maincontentheader">
<h1>{"Section edit"|i18n("design/standard/section")}</h1>
</div>

<input type="hidden" name="SectionID" value="{$section.id}" />

<div class="block">
<label>{"Name"|i18n("design/standard/section")}</label><div class="labelbreak"></div>
<input class="box" type="text" name="Name" value="{$section.name}" />
</div>

<div class="block">
<label>{"Navigation Part"|i18n("design/standard/section")}</label><div class="labelbreak"></div>
<select name="NavigationPartIdentifier">
<option value="ezcontentnavigationpart" {section show=eq($section.navigation_part_identifier,'ezcontentnavigationpart')}selected="selected"{/section}>{"Content"|i18n("design/standard/section")}</option>
<option value="ezmedianavigationpart" {section show=eq($section.navigation_part_identifier,'ezmedianavigationpart')}selected="selected"{/section}>{"Media"|i18n("design/standard/section")}</option>
<option value="ezshopnavigationpart" {section show=eq($section.navigation_part_identifier,'ezshopnavigationpart')}selected="selected"{/section}>{"Shop"|i18n("design/standard/section")}</option>
<option value="ezusernavigationpart" {section show=eq($section.navigation_part_identifier,'ezusernavigationpart')}selected="selected"{/section}>{"Users"|i18n("design/standard/section")}</option>
<option value="ezsetupnavigationpart" {section show=eq($section.navigation_part_identifier,'ezsetupnavigationpart')}selected="selected"{/section}>{"Set up"|i18n("design/standard/section")}</option>
<option value="ezmynavigationpart" {section show=eq($section.navigation_part_identifier,'ezmynavigationpart')}selected="selected"{/section}>{"Personal"|i18n("design/standard/section")}</option>
</select>
</div>

{*
<div class="block">
<label>Locale</label><div class="labelbreak"></div>
<input class="box" type="text" name="Locale" value="{$section.locale}" />
</div>
*}
<div class="buttonblock">
<input class="button" type="submit" name="StoreButton" value="{'Store'|i18n('design/standard/section')}" />
</div>

</form>

<br/>
<label>{"About Navigation Parts"|i18n("design/standard/section")}</label><div class="labelbreak"></div>
<p>
{"The eZ publish admin interface is divided into navigation parts. This is a way to group different areas of the site administration. Select the navigation part that should be active when this section is browsed."|i18n("design/standard/section")}
</p>
