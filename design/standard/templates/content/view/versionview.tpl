<form method="post" action={concat("content/versionview/",$object.id,"/",$object_version,"/",$language|not|choose(array($language,"/"),""))|ezurl}>



{section show=$assignment}
  {node_view_gui view=full with_children=false() is_editable=false() is_standalone=false() content_object=$object node_name=$object.name content_node=$assignment.temp_node}
{/section}

<div class="block">
{section show=$version.language_list|gt(1)}
<div class="element">
<label>{"Translation"|i18n("design/standard/content/view")}</label><div class="labelbreak"></div>
  
<select name="SelectedLanguage" >
{section name=Translation loop=$version.language_list}
<option value="{$Translation:item.locale.locale_code}" {section show=eq($Translation:item.locale.locale_code,$object_languagecode)}selected="selected"{/section}>{$Translation:item.locale.intl_language_name}</option>
{/section}
</select>
</div>

{/section}
{let name=Placement node_assignment_list=$version.node_assignments}
{section show=$Placement:node_assignment_list|gt(1)}

<div class="element">
<label>{"Placement"|i18n("design/standard/content/view")}</label><div class="labelbreak"></div>

<select name="SelectedPlacement" >
{section loop=$Placement:node_assignment_list}
<option value="{$Placement:item.id}" {section show=eq($Placement:item.id,$placement)}selected="selected"{/section}>{$Placement:item.parent_node_obj.name}</option>
{/section}
</select>
</div>

{/section}
{/let}
  
{let name=Sitedesign
   sitedesign_list=fetch('layout','sitedesign_list')}
{section show=$Sitedesign:sitedesign_list|gt(1)}

<div class="element">
<label>{"Site Design"|i18n("design/standard/content/view")}</label><div class="labelbreak"></div>

<select name="SelectedSitedesign" >
{section loop=$Sitedesign:sitedesign_list}
<option value="{$Sitedesign:item}" {section show=eq($Sitedesign:item,$sitedesign)}selected="selected"{/section}>{$Sitedesign:item}</option>
{/section}
</select>
</div>

{/section}
{/let}
<div class="break"></div>
</div>

<div class="buttonblock">
<input class="button" type="submit" name="ChangeSettingsButton" value="{'Change'|i18n('design/standard/content/view')}" />
</div>

<input type="hidden" name="ContentObjectID" value="{$object.id}" />
<input type="hidden" name="ContentObjectVersion" value="{$object_version}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$object_languagecode}" />
<input type="hidden" name="ContentObjectPlacementID" value="{$placement}" />

<div class="buttonblock">
{section show=and(eq($version.status,0),$is_creator,$object.can_edit)}
<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/content/view')}" />
<input class="button" type="submit" name="PreviewPublishButton" value="{'Publish'|i18n('design/standard/content/view')}" />
{/section}

<input class="button" type="submit" name="VersionsButton" value="{'Versions'|i18n('design/standard/content/view')}" />
</div>


</form>
