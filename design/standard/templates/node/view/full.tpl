{default with_children=true()
         is_editable=true()
	 is_standalone=true()}
{let page_limit=15
     list_count=and($with_children,fetch('content','list_count',hash(parent_node_id,$node.node_id)))}
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

{section show=$is_standalone}
<form method="post" action={"content/action"|ezurl}>
{/section}

<table cellspacing="5" cellpadding="0" border="0">
<tr>
	<td>
{*	{$node.name|texttoimage('archtura')}  *}
 	<div class="maincontentheader">
        <h1>{$node_name}</h1>
        </div>
	<input type="hidden" name="TopLevelNode" value="{$content_object.main_node_id}" />
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td valign="top">

    {section name=ContentObjectAttribute loop=$content_version.contentobject_attributes}
    <div class="block">
        <label>{$ContentObjectAttribute:item.contentclass_attribute.name}</label>
	{switch match=$ContentObjectAttribute:item.is_a}
	{case match=ezstring}
    	<p class="box">{attribute_view_gui attribute=$ContentObjectAttribute:item}</p>
	{/case}
	{case}
    	<p class="box">{attribute_view_gui attribute=$ContentObjectAttribute:item}</p>
	{/case}
	{/switch}
    </div>
    {/section}

    </td>
    <td width="120" valign="top">

    {let name=Object  related_objects=$content_version.related_contentobject_array}

      {section name=ContentObject  loop=$Object:related_objects show=$Object:related_objects  sequence=array(bglight,bgdark)}

        <div class="block">
        {content_view_gui view=text_linked content_object=$Object:ContentObject:item}
        </div>
    
      {section-else}
      {/section}
    {/let}

    {section show=$is_standalone}
      {section name=ContentAction loop=$content_object.content_action_list show=$content_object.content_action_list}
      <div class="block">
      <input type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name}" />
      </div>
      {/section}
    {/section}
    </td>
</tr>
</table>

{section show=$is_editable}
   {switch match=$content_object.can_edit}
   {case match=1}
   <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
   {/case}
   {case match=0}
   {/case}
   {/switch}
{/section}


{section show=$with_children}

{let name=Child
     children=fetch('content','list',hash(parent_node_id,$node.node_id,sort_by,$node.sort_array,limit,$page_limit,offset,$view_parameters.offset))
     can_remove=false() can_edit=false() can_create=false() can_copy=false()}

{section show=$:children}

{section loop=$:children}
  {section show=$:item.object.can_remove}
    {set can_remove=true()}
  {/section} 
  {section show=$:item.object.can_edit}
    {set can_edit=true()}
  {/section} 
  {section show=$:item.object.can_create}
    {set can_create=true()}
  {/section} 
{/section}

{set can_copy=$content_object.can_create}

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>
      <nobr>{"Name"|i18n("design/standard/node/view")}</nobr>
    </th>
    <th>
      <nobr>{"Class"|i18n("design/standard/node/view")}</nobr>
    </th>
    {section show=eq($node.sort_array[0][0],'priority')}
    <th>
      <nobr>{"Priority"|i18n("design/standard/node/view")}</nobr>
    </th>
    {/section}
    {section show=$:can_edit}
    <th width="1">
      <nobr>{"Edit"|i18n("design/standard/node/view")}</nobr>
    </th>
    {/section}
    {section show=$:can_copy}
    <th width="1">
      <nobr>{"Copy"|i18n("design/standard/node/view")}</nobr>
    </th>
    {/section}
    {section show=$:can_remove}
    <th width="1">
      <nobr>{"Remove"|i18n("design/standard/node/view")}</nobr>
    </th>
    {/section}
</tr>
{section loop=$:children sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Child:sequence}">
{*        <a href={concat('content/view/full/',$Child:item.node_id)|ezurl}>{node_view_gui view=line content_node=$Child:item}</a>*}
        {node_view_gui view=line content_node=$Child:item}
	</td>
        <td class="{$Child:sequence}">{$Child:item.object.class_name}
	</td>
	{section show=eq($node.sort_array[0][0],'priority')}
	<td width="40" align="left" class="{$Child:sequence}">
	  <input type="text" name="Priority[]" size="2" value="{$Child:item.priority}">
          <input type="hidden" name="PriorityID[]" value="{$Child:item.node_id}">
	</td>
	{/section}

        {section show=$:can_edit}
	<td width="1" class="{$Child:sequence}">
	{section show=$:item.object.can_edit}
        <a href={concat("content/edit/",$Child:item.contentobject_id)|ezurl}><img src={"edit.png"|ezimage} alt="Edit" border="0" /></a>
        {/section}
        </td>
        {/section}
        {section show=$:can_copy}
        <td class="{$Child:sequence}">
          <a href={concat("content/copy/",$Child:item.contentobject_id)|ezurl}><img src={"copy.png"|ezimage} alt="{'Copy'|i18n('design/standard/node/view')}" border="0" /></a>
        </td>
        {/section}

        {section show=$:can_remove}
	<td class="{$Child:sequence}" align="right" width="1">
	{section show=$:item.object.can_remove}
             <input type="checkbox" name="DeleteIDArray[]" value="{$Child:item.node_id}" />
        {/section} 
	</td>
        {/section} 
</tr>
{/section}
<tr>
    <td>
    </td>
    <td>
    </td>
    {section show=eq($node.sort_array[0][0],'priority')}
    <td>
      {section show=and($content_object.can_edit,eq($node.sort_array[0][0],'priority'))}
         <input class="button" type="submit"  name="UpdatePriorityButton" value="{'Update'|i18n('design/standard/node/view')}" />
      {/section}
    </td>
    {/section}
    {section show=$:can_edit}
    <td>
    </td>
    {/section}
    {section show=$:can_copy}
    <td>
    </td>
    {/section}
    {section show=$:can_remove}
    <td align="right">
    {section show=fetch('content','list',hash(parent_node_id,$node.node_id,sort_by,$node.sort_array,limit,$page_limit,offset,$view_parameters.offset))}
      {include uri="design:gui/trash.tpl"}
    {/section}
    </td>
    {/section}
</tr>
</table>

{/section}
{/let}

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}

<div class="buttonblock">

{switch match=$content_object.can_create}
{case match=1}
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <select name="ClassID">
	      {section name=Classes loop=$content_object.can_create_class_list}
	      <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	      {/section}
         </select>
         <input class="button" type="submit" name="NewButton" value="{'New'|i18n('design/standard/node/view')}" />
{/case}
{case match=0}

{/case}
{/switch}

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
<input type="hidden" name="ViewMode" value="full" />

</div>

{/section}


{section show=$is_standalone}
</form>
{/section}

{/default}
{/let}
{/default}
