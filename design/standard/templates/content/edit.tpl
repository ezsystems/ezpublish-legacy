{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}
<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>
<table class="layout" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Left part start -->
    <div class="maincontentheader">
    <h1>{"Edit"|i18n("design/standard/content/edit")} {$class.name} - {$object.name}</h1>
    </div>

    {section show=$validation.processed}
        {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}

        <div class="warning">
        <h2>{"Input did not validate"|i18n("design/standard/content/edit")}</h2>
        <ul>
        	<li>{$UnvalidatedAttributes:item.identifier}: {$UnvalidatedAttributes:item.name}</li>
        </ul>
        </div>

        {section-else}

        <div class="feedback">
        <h2>{"Input was stored successfully"|i18n("design/standard/content/edit")}</h2>
        </div>

        {/section}
    {/section}
    <table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <th width="60%">{"Location"|i18n("design/standard/content/edit")}:</th>
        <th colspan="1">{"Sort by"|i18n("design/standard/content/edit")}:</th>
        <th colspan="2">{"Ordering"|i18n("design/standard/content/edit")}:</th>
        <th colspan="1">{"Main"|i18n("design/standard/content/edit")}:</th>
        <th colspan="1">{"Move"|i18n("design/standard/content/edit")}:</th>
    </tr>
    {let name=Node sort_fields=hash(1,"Path"|i18n("design/standard/content/edit"),2,"Published"|i18n("design/standard/content/edit"),3,"Modified"|i18n("design/standard/content/edit"),4,"Section"|i18n("design/standard/content/edit"),5,"Depth"|i18n("design/standard/content/edit"),6,"Class Identifier"|i18n("design/standard/content/edit"),7,"Class Name"|i18n("design/standard/content/edit"),8,"Priority"|i18n("design/standard/content/edit"))}
   {let existingParentNodes=$object.parent_nodes}
    {section loop=$assigned_node_array sequence=array(bglight,bgdark)}
    {let parent_node=$Node:item.parent_node_obj}
    <tr>
        <td class="{$Node:sequence}">
	{switch match=$Node:parent_node.node_id}
	{case match=1}
	Top node
	{/case}
	{case}
        {section name=Path loop=$Node:parent_node.path}
	{$Node:Path:item.name} /
	{/section}
        {$Node:parent_node.name}
	{/case}
	{/switch} / {$object.name}
        </td>
        <td class="{$Node:sequence}">
          <select name="SortFieldMap[{$Node:item.id}]">
          {section name=Sort loop=$Node:sort_fields}
            <option value="{$Node:Sort:key}" {section show=eq($Node:Sort:key,$Node:item.sort_field)}selected="selected"{/section}>{$Node:Sort:item}</option>
          {/section}
          </select>
        </td>
        <td class="{$Node:sequence}" width="25">
	<nobr><img src={"asc.gif"|ezimage} alt="Ascending" /><input type="radio" name="SortOrderMap[{$Node:item.id}]" value="1" {section show=eq($Node:item.sort_order,1)}checked="checked"{/section} /></nobr>
	</td>
        <td class="{$Node:sequence}" width="25">
	<nobr><img src={"desc.gif"|ezimage} alt="Descending" /><input type="radio" name="SortOrderMap[{$Node:item.id}]" value="0" {section show=eq($Node:item.sort_order,0)}checked="checked"{/section} /></nobr>
        </td>
        <td class="{$Node:sequence}" align="right">
        <input type="radio" name="MainNodeID" {section show=eq($main_node_id,$Node:item.parent_node)}checked="checked"{/section} value="{$Node:item.parent_node}" />
        </td>
        <td class="{$Node:sequence}" align="right">
        {switch match=$Node:item.parent_node}
        {case in=$Node:existingParentNodes}
         <input type="image" name="{concat('MoveNodeID_',$Node:item.parent_node)}" src={"move.gif"|ezimage} value="{$Node:item.parent_node}"  />
        {/case}
        {case}
          {section show=$Node:item.from_node_id|gt(0)}
            <input type="image" name="{concat('MoveNodeID_',$Node:item.parent_node)}" src={"move.gif"|ezimage} value="{$Node:item.parent_node}"  />
          {section-else}      
          {/section}   
         {/case}
        {/switch}
        </td>
        <td class="{$Node:sequence}" align="right">
     {section show=eq($Node:item.parent_node,$main_node_id)|not}
        <input type="image" name="{concat('RemoveNodeID_',$Node:item.parent_node)}" src={"remove.png"|ezimage} value="{$Node:item.parent_node}"  />
     {/section}
        </td>
    </tr>
    {/let}
    {/section}
    {/let}
    {/let}
 </table>
 <div align="right" class="buttonblock">
  <input class="button" type="submit" name="BrowseNodeButton" value="{'Add location(s)'|i18n('design/standard/content/edit')}" />
 </div>

    {section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
    <div class="block">
    <label>{$ContentObjectAttribute:item.contentclass_attribute.name}:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
        {attribute_edit_gui attribute=$ContentObjectAttribute:item}
    </div>
    {/section}

    <div class="buttonblock">
    <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('design/standard/content/edit')}" />
    </div>
    <div class="buttonblock">
    <input class="button" type="submit" name="StoreButton" value="{'Store Draft'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/standard/content/edit')}" />
    </div>
    <!-- Left part end -->
    </td>
    <td width="120" align="right" valign="top" style="padding-left: 16px;">
    <!-- Right part start-->

    <!-- Object info box start-->
    <table class="menuboxright" width="120" cellpadding="1" cellspacing="0" border="0">
    <tr>
        <th class="menuheaddark" colspan="2">
        <p class="menuhead">{"Object info"|i18n("design/standard/content/edit")}</p>
        </th>
    </tr>
    <tr>
        <td class="menu" colspan="2">
	    <p class="menufieldlabel">{"Created:"|i18n("design/standard/content/edit")}</p>
	    {section show=$object.published}
	    <p class="menufield">{$object.published|l10n(date)}</p>
	    {section-else}
	    <p class="menufield">
	    {"Not yet published"|i18n("design/standard/content/edit")}
	    </p>
	    {/section}
        </td>
    </tr>
    <tr>
        <th class="menuheaddark" colspan="2">
        <p class="menuhead">{"Version info"|i18n("design/standard/content/edit")}</p>
        </th>
    </tr>
    <tr>
        <td class="menu">
	    <p class="menufieldlabel">{"Editing:"|i18n("design/standard/content/edit")}</p>
        </td>
        <td class="menu" width="1">
	    <p class="menufield">{$edit_version}</p>
        </td>
    </tr>
    <tr>
        <td class="menu">
	    <p class="menufieldlabel">{"Current"|i18n("design/standard/content/edit")}:</p>
        </td>
        <td class="menu" width="1">
	    <p class="menufield">{$object.current_version}</p>
        </td>
    </tr>
    <tr>
        <td class="menu" colspan="2" align="right">
          <input class="menubutton" type="submit" name="VersionsButton" value="{'Manage'|i18n('design/standard/content/edit')}" />
        </td>
    </tr>
    <!-- Object info box end-->

    <!-- Translation box start-->
{let name=Translation
     language_index=0
     default_translation=$content_version.translation
     other_translation_list=$content_version.translation_list
     translation_list=array_prepend($Translation:other_translation_list,$Translation:default_translation)}
{section show=$Translation:translation_list}
    <tr>
        <th class="menuheaddark" colspan="2">
        <p class="menuhead">{"Translations"|i18n("design/standard/content/edit")}</p>
        </th>
    </tr>

{section loop=$Translation:translation_list}
  {section show=eq($edit_language,$Translation:item.language_code)}
    {set language_index=$Translation:index}
  {/section}
{/section}

{section loop=$Translation:translation_list sequence=array("bgdark","bglight")}
    <tr>
        <td class="{$Translation:sequence}" colspan="{$Translation:other_translation_list|gt(0)|choose(2,1)}">
          {section show=$Translation:item.locale.is_valid}
            <p class="menufieldlabel">{$Translation:item.locale.intl_language_name}</p>
          {section-else}
            <p class="menufieldlabel">{$Translation:item.language_code} (No locale information available)</p>
          {/section}
        </td>
{section show=$Translation:other_translation_list|gt(0)}
        <td class="{$Translation:sequence}">
          <input type="radio" name="EditSelectedLanguage" value="{$Translation:item.language_code}" {section show=eq($Translation:index,$Translation:language_index)}checked="checked"{/section} />
        </td>
{/section}
    </tr>
{/section}
    <tr>
        <td colspan="2" align="right">
	  <input class="menubutton" type="submit" name="TranslateButton" value="{'Manage'|i18n('design/standard/content/edit')}" />
{section show=$Translation:other_translation_list|gt(0)}
          <input class="menubutton" type="submit" name="EditLanguageButton" value="{'Edit'|i18n('design/standard/content/edit')}" />
{/section}
        </td>
    </tr>
{/section}

{/let}
    <!-- Translation box end-->

    <tr>
        <th class="menuheaddark" colspan="2">
        <p class="menuhead">{"Related objects"|i18n("design/standard/content/edit")}</p>
        </th>
    </tr>
    {section name=Object loop=$related_contentobjects sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$Object:sequence}" align="left">
          {content_view_gui view=text_linked content_object=$Object:item}
	  <span class="small">&lt;object id='{$Object:item.id}' /&gt;</span>
        </td>
	<td>
          <input type="checkbox" name="DeleteRelationIDArray[]" value="{$Object:item.id}" />
	</td>
    </tr>
    {/section}
    <tr>
        <td align="left">
          <input class="menubutton" type="submit" name="BrowseObjectButton" value="{'Find'|i18n('design/standard/content/edit')}" />
	</td>
        <td align="right">
          <input class="menubutton" type="submit" name="DeleteRelationButton" value="{'Remove'|i18n('design/standard/content/edit')}" />
        </td>
    </tr>
    <tr>
        <td colspan="1" align="left">
	<input class="menubutton" type="submit" name="NewButton" value="{'New'|i18n('design/standard/content/edit')}" />
	</td>
        <td colspan="1" align="right">
	<select	name="ClassID">
	    {section name=Classes loop=$object.can_create_class_list}
	    <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	    {/section}
	</select>
	<input type="hidden" name="NodeID" value="{$surplus_node}" />
	</td>
    </tr>
    </table>
    
    <!-- Right part end -->
    </td>
</tr>
</table>

</form>
