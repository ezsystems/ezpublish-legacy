<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/")|ezurl}>
<script language=jscript src='/extension/xmleditor/dhtml/ezeditor.js'></script>
<link rel="stylesheet" type="text/css" href="/extension//xmleditor/dhtml/toolbar.css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Left part start -->
    <h1 class="top">Edit {$class.name} - {$object.name}</h1>

    {section show=$validation.processed}
        {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}

        <div class="warning">
        <h3 class="warning">Input did not validate</h3>
        <ul>
        	<li>{$UnvalidatedAttributes:item.identifier}: {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id})</li>
        </ul>
        </div>

        {section-else}

        <div class="feedback">
        <h3 class="feedback">Input was stored successfully</h3>
        </div>

        {/section}
    {/section}
    <table class="list" width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr>
        <th width="60%">{"Name"|i18n('content/object')}</th>
        <th width="40%" colspan="3">{"Sort by"|i18n('content/object')}</th>
    </tr>
    {let name=Node sort_fields=hash(1,"Path"|i18n('content/object'),2,"Published"|i18n('content/object'),3,"Modified"|i18n('content/object'),4,"Section"|i18n('content/object'),5,"Depth"|i18n('content/object'),6,"Class Identifier"|i18n('content/object'),7,"Class Name"|i18n('content/object'),8,"Priority"|i18n('content/object'))}
   {let existingParentNodes=$object.parent_nodes}
    {section loop=$assigned_node_array sequence=array(bglight,bgdark)}
    {let parent_node=$Node:item.parent_node_obj}
    <tr>
        <td class="{$Node:sequence}">
	<span class="normal">
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
	</span>
        </td>
        <td class="{$Node:sequence}">
        <span class="normal">
          <select name="SortFieldMap[{$Node:item.id}]">
          {section name=Sort loop=$Node:sort_fields}
            <option value="{$Node:Sort:key}" {section show=eq($Node:Sort:key,$Node:item.sort_field)}selected="selected"{/section}>{$Node:Sort:item}</option>
          {/section}
          </select>
        </span>
        </td>
        <td class="{$Node:sequence}">
        <img src={"move-down.gif"|ezimage} alt="Ascending" />
	<input type="radio" name="SortOrderMap[{$Node:item.id}]" value="1" {section show=eq($Node:item.sort_order,1)}checked="checked"{/section} />
        <img src={"move-up.gif"|ezimage} alt="Descending" />
	<input type="radio" name="SortOrderMap[{$Node:item.id}]" value="0" {section show=eq($Node:item.sort_order,0)}checked="checked"{/section} />
        </td>
        <td class="{$Node:sequence}" align="right">
        <input type="radio" name="MainNodeID" {section show=eq($main_node_id,$Node:item.parent_node)}checked="checked"{/section} value="{$Node:item.parent_node}" />
       {*<input type="checkbox" name="DeleteParentIDArray[]" value="{$Node:item.parent_node}" />*}
       </td>
        <td class="{$Node:sequence}" align="right">
       
      {switch match=$Node:item.parent_node}
      {case in=$Node:existingParentNodes}
        <input type="image" name="{concat('MoveNodeID_',$Node:item.parent_node)}" src={"move.gif"|ezimage} value="{$Node:item.parent_node}"  />
      {/case}
      {case }
      {/case}
      {/switch}
            {section show=$Node:item.from_node_id|gt(0)}
               <input type="image" name="{concat('MoveNodeID_',$Node:item.parent_node)}" src={"move.gif"|ezimage} value="{$Node:item.parent_node}"  />
            {section-else}      
            {/section}   
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
    {switch match=$main_node_id}
	{case match=1}
	{/case}
	{case}
	 <div align="right" class="buttonblock">
	 <input class="button" type="submit" name="BrowseNodeButton" value="{'Add placement(s)'|i18n('content/object')}" />
	{* <input class="button" type="submit" name="DeleteNodeButton" value="{'Remove placement'|i18n('content/object')}" />
	 <input class="button" type="submit" name="MoveNodeButton" value="{'Move placement'|i18n('content/object')}" />*}
	 </div>
	{/case}
    {/switch}
    {section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
    <div class="block">
    <label>{$ContentObjectAttribute:item.contentclass_attribute.name}:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
        {attribute_edit_gui attribute=$ContentObjectAttribute:item}
    </div>
    {/section}

    <div class="buttonblock">
    <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('content/object')}" />
    <input class="button" type="submit" name="VersionsButton" value="{'Versions'|i18n('content/object')}" />
    <input class="button" type="submit" name="TranslateButton" value="{'Translate'|i18n('content/object')}" />
    </div>
    <div class="buttonblock">
    <input class="button" type="submit" name="StoreButton" value="{'Store Draft'|i18n('content/object')}" />
    <input class="button" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('content/object')}" />
    <input class="button" type="submit" name="CancelButton" value="{'Discard'|i18n('content/object')}" />
    </div>
    <!-- Left part end -->
    </td>
    <td width="120" align="right" valign="top" style="padding-left: 16px;">
    <!-- Right part start-->
    <table class="menuboxright" width="120" cellpadding="1" cellspacing="0" border="0">
    <tr>
        <th class="menuheaddark" colspan="3">
        <p class="menuhead">{"Object info"|i18n('content/object')}</p>
        </th>
    </tr>
    <tr>
        <td class="menu">
	    <p class="menufieldlabel">{"Editing version"|i18n('content/object')}:</p>
        </td>
        <td class="menu">
	    <p class="menufield">{$edit_version}</p>
        </td>
    </tr>
    <tr>
        <td class="menu">
	    <p class="menufieldlabel">{"Current version"|i18n('content/object')}:</p>
        </td>
        <td class="menu">
	    <p class="menufield">{$object.current_version}</p>
        </td>
    </tr>

{let name=Translation
     language_index=0
     default_translation=$content_version.translation
     translation_list=array_prepend($content_version.translation_list,$Translation:default_translation)}
{section show=$Translation:translation_list}
    <tr>
        <th class="menuheaddark"  colspan="2">
        <p class="menuhead">{"Translations"|i18n('content/object')}</p>
        </th>
    </tr>

{section loop=$Translation:translation_list}
  {section show=eq($translation_language,$Translation:item.language_code)}
    {set language_index=$Translation:index}
  {/section}
{/section}

{section loop=$Translation:translation_list sequence=array("bgdark","bglight")}
    <tr>
        <td class="{$Translation:sequence}">
          {section show=$Translation:item.locale.is_valid}
            <p class="menufieldlabel">{$Translation:item.locale.intl_language_name}</p>
          {section-else}
            <p class="menufieldlabel">{$Translation:item.language_code} (No locale information available)</p>
          {/section}
        </td>
        <td class="{$Translation:sequence}">
          <input type="radio" name="EditSelectedLanguage" value="{$Translation:item.language_code}" {section show=eq($Translation:index,$Translation:language_index)}checked="checked"{/section} />
        </td>
    </tr>
{/section}

{/section}

{/let}

    <tr>
        <th class="menuheaddark" colspan="2">
        <p class="menuhead">{"Related objects"|i18n('content/object')}</p>
        </th>
    </tr>
    {section name=Object loop=$related_contentobjects sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$Object:sequence}" align="left">
          {content_view_gui view=text_linked content_object=$Object:item}
        </td>
	<td>
          <input type="checkbox" name="DeleteRelationIDArray[]" value="{$Object:item.id}" />
	</td>
    </tr>
    {/section}
    <tr>
        <td colspan="2" align="left">
          <input class="menubutton" type="submit" name="BrowseObjectButton" value="{'Find object'|i18n('content/object')}" />
        </td>
    </tr>
    <tr>
        <td colspan="2" align="left">
          <input class="menubutton" type="submit" name="DeleteRelationButton" value="{'Delete object'|i18n('content/object')}" />
        </td>
    </tr>
    </table>
    
    <!-- Right part end -->
    </td>
</tr>
</table>

</form>
