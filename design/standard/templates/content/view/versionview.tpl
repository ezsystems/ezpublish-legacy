<form method="post" action={concat("content/versionview/",$object.id,"/",$object_version,"/",$language|not|choose(array($language,"/"),""))|ezurl}>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
  <td width="90%">
{*    <table width="100%">
    {section name=ContentObjectAttribute loop=$version_attributes}
    <tr>
      <td>
      <b>{$ContentObjectAttribute:item.contentclass_attribute.name}</b><br />
      {attribute_view_gui attribute=$ContentObjectAttribute:item}
      </td>
    </tr>
    {/section}
    </table>
  </td>
</tr>
<tr>
  <td width="90%">
    <h2>Related objects</h2>
    <table width="100%" cellspacing="0">
    {section name=Related loop=$related_contentobject_array show=$related_contentobject_array sequence=array(bglight,bgdark)}
    <tr>
      <td class="{$Related:sequence}">
      {content_view_gui view=text_linked content_object=$Related:item}
      </td>
    </tr>
    {section-else}
    <tr>
      <td class="bglight">
      None
      </td>
    </tr>
    {/section}
    </table>
*}
{section show=$assignment}
  {node_view_gui view=full with_children=false() is_editable=false() is_standalone=false() content_object=$object node_name=$object.name content_node=$assignment.temp_node}
{/section}
  </td>

  <td align="right" valign="top">

  <table cellspacing="0" cellpadding="0" border="0">

  {section show=$version.language_list|gt(1)}
  <tr>
    <td>
  
    <h3>Translation:</h3>
  
    </td>
  </tr>
  
  <tr>
    <td>

    <select name="SelectedLanguage" >
    {section name=Translation loop=$version.language_list}
    <option value="{$Translation:item.locale.locale_code}" {section show=eq($Translation:item.locale.locale_code,$object_languagecode)}selected="selected"{/section}>{$Translation:item.locale.intl_language_name}</option>
    {/section}
    </select>

    </td>
  </tr>
  {/section}

  {let name=Placement node_assignment_list=$version.node_assignments}
  {section show=$Placement:node_assignment_list|gt(1)}
  <tr>
    <td>
    <h3>Placement:</h3>
    </td>
  </tr>

  <tr>
    <td>

    <select name="SelectedPlacement" >
    {section loop=$Placement:node_assignment_list}
    <option value="{$Placement:item.id}" {section show=eq($Placement:item.id,$placement)}selected="selected"{/section}>{$Placement:item.parent_node_obj.name}</option>
    {/section}
    </select>

    </td>
  </tr>
  {/section}
  {/let}
  
  {let name=Sitedesign
       sitedesign_list=fetch('layout','sitedesign_list')}
  {section show=$Sitedesign:sitedesign_list|gt(1)}
  <tr>
    <td>
    <h3>Site Design:</h3>
    </td>
  </tr>

  <tr>
    <td>

    <select name="SelectedSitedesign" >
    {section loop=$Sitedesign:sitedesign_list}
    <option value="{$Sitedesign:item}" {section show=eq($Sitedesign:item,$sitedesign)}selected="selected"{/section}>{$Sitedesign:item}</option>
    {/section}
    </select>

    </td>
  </tr>
  {/section}
  {/let}

  <tr>
    <td align="left">
    <input type="submit" name="ChangeSettingsButton" value="{'Change'|i18n('content/object')}" />
    </td>
  </tr>

  </table>
  
  </td>
</tr>
</table>

<input type="hidden" name="ContentObjectID" value="{$object.id}" />
<input type="hidden" name="ContentObjectVersion" value="{$object_version}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$object_languagecode}" />
<input type="hidden" name="ContentObjectPlacementID" value="{$placement}" />

<div class="buttonblock">
{section show=and(eq($version.status,0),$is_creator,$object.can_edit)}
<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('content/object')}" />
<input class="button" type="submit" name="PreviewPublishButton" value="{'Publish'|i18n('content/object')}" />
{/section}

<input class="button" type="submit" name="VersionsButton" value="{'Versions'|i18n('content/object')}" />
</div>


</form>
