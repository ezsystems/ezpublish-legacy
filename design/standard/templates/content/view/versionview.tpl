<form method="post" action={concat("content/versionview/",$object.id,"/",$object_version,"/",$language|not|choose(array($language,"/"),""))|ezurl}>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
  <td width="90%">
  <h1>{$object.name}</h1>
  Viewing version {$object_version} in language {$object_languagecode}
  </td>
  <td rowspan="2" align="right" valign="top">

  <table cellspacing="0" cellpadding="0" border="0">
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
  
  <tr>
    <td align="left">
    <input type="submit" name="SelectLanguageButton" value="{'Select'|i18n('content/object')}" />
    </td>
  </tr>

  <tr>
    <td>
    <h3>Placement:</h3>
    </td>
  </tr>

  <tr>
    <td>

    <select name="SelectedPlacement" >
    <option value="-1">{"Not specified"|i18n('content/object')}</option>
    {section name=Placement loop=$version.node_assignments}
    <option value="{$Placement:item.id}" {section show=eq($Placement:item.id,$placement)}selected="selected"{/section}>{$Placement:item.parent_node_obj.name}</option>
    {/section}
    </select>

    </td>
  </tr>
  
  <tr>
    <td align="left">
    <input type="submit" name="SelectPlacementButton" value="{'Select'|i18n('content/object')}" />
    </td>
  </tr>

  </table>
  
    </td>
  </tr>
  <tr>

  <td width="90%">
    <table width="100%">
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
  </td>
</tr>
</table>

<input type="hidden" name="ContentObjectID" value="{$object.id}" />
<input type="hidden" name="ContentObjectVersion" value="{$object_version}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$object_languagecode}" />
<input type="hidden" name="ContentObjectPlacementID" value="{$placement}" />

{section show=eq($version.status,0)}
<div class="buttonblock">
<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('content/object')}" />
<input class="button" type="submit" name="PreviewPublishButton" value="{'Publish'|i18n('content/object')}" />
</div>
{/section}


</form>
