{default with_children=true()
	 is_standalone=true()}
{let page_limit=15
     sitemap_indentation=10
     tree=and($with_children,fetch('content','tree',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset)))
     tree_count=and($with_children,fetch('content','tree_count',hash(parent_node_id,$node.node_id)))}
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

{section show=$is_standalone}
<form method="post" action={"content/action/"|ezurl}>
{/section}

<div class="maincontentheader">
<h1>{"Site map"|i18n("design/standard/node/view")}</h1>
</div>

<h1>{$node_name}</h1>

{section name=ContentObjectAttribute loop=$content_version.contentobject_attributes}
<div class="block">
<label>{$ContentObjectAttribute:item.contentclass_attribute.name}</label><div class="labelbreak"></div>
{attribute_view_gui attribute=$ContentObjectAttribute:item}
</div>
{/section}

{section show=$with_children}

{let name=Tree
     can_remove=false() can_edit=false() can_create=false() can_copy=false()}

{section loop=$tree}
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

<table class="list" width="100%" cellpadding="1" cellspacing="0" border="0">
<tr>
	<th><nobr>{"Name"|i18n("design/standard/node/view")}</nobr></th>
	<th><nobr>{"Class"|i18n("design/standard/node/view")}</nobr></th>
	<th><nobr>{"Section"|i18n("design/standard/node/view")}</nobr></th>
	{section show=$:can_edit}
	<th width="1"><nobr>{"Edit"|i18n("design/standard/node/view")}</nobr></th>
        {/section}
	{section show=$:can_copy}
	<th width="1"><nobr>{"Copy"|i18n("design/standard/node/view")}</nobr></th>
        {/section}
	{section show=$:can_remove}
	<th width="1"><nobr>{"Remove"|i18n("design/standard/node/view")}</nobr></th>
        {/section}
</tr>

{section loop=$tree sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Tree:sequence}">
       	<img src={"1x1.gif"|ezimage} width="{mul(sub($Tree:item.depth,$node.depth)|dec,$sitemap_indentation)}" height="1" alt="" border="0" />
	<a href={concat("content/view/full/",$Tree:item.node_id)|ezurl}><img src={"class_2.png"|ezimage} alt="Folder" border="0"> &nbsp;{$Tree:item.name}</a>
	</td>
	<td class="{$Tree:sequence}">{$Tree:item.object.class_name}</td>
	<td class="{$Tree:sequence}">{$Tree:item.object.section_id}</td>
	{section show=$:can_edit}
	<td class="{$Tree:sequence}">
	{section show=$Tree:item.object.can_edit}
	{switch name=cidsw match=$Tree:item.object.contentclass_id}
	    {case match=4}
	    <a href={concat("user/edit/",$Tree:item.object.id)|ezurl}><img src={"edit.png"|ezimage} alt="{'Edit'|i18n('design/standard/node/view')}" border="0"></a>
	    {/case}
	    {case}
            <a href={concat("content/edit/",$Tree:item.object.id)|ezurl}><img src={"edit.png"|ezimage} alt="{'Edit'|i18n('design/standard/node/view')}" border="0"></a>
	    {/case}
        {/switch}
        {/section} 
        </td>
        {/section}
	{section show=$:can_copy}
        <td class="{$Tree:sequence}">
          <a href={concat("content/copy/",$Tree:item.object.id)|ezurl}><img src={"copy.png"|ezimage} alt="{'Copy'|i18n('design/standard/node/view')}" border="0"></a>
        </td>
        {/section}
	{section show=$:can_remove}
        <td class="{$Tree:sequence}" align="right">
	{section show=$Tree:item.object.can_remove}
             <input type="checkbox" name="DeleteIDArray[]" value="{$Tree:item.node_id}" align="top" />
        {/section} 
        </td>
        {/section}
</tr>
{/section}
<tr>
	<td></td>
	<td></td>
	<td></td>
	{section show=$:can_edit}
	<td></td>
        {/section}
	{section show=$:can_copy}
	<td></td>
        {/section}
	<td align="right">
	{section show=$:can_remove}
        {include uri="design:gui/trash.tpl"}
{*	<input class="button" type="image" src={"remove.png"|ezimage} name="RemoveButton" value="Remove" /> *}
        {/section}
	</td>
</tr>
</table>
{/let}

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/sitemap/',$node.node_id)
         item_count=$tree_count
         view_parameters=$view_parameters
         item_limit=$page_limit}

<div class="buttonblock">
{section show=$content_object.can_create}
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <select name="ClassID">
	      {section name=Classes loop=$content_object.can_create_class_list}
	      <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	      {/section}
         </select>
         <input class="button" type="submit" name="NewButton" value="{'Create here'|i18n('design/standard/node/view')}" />
{/section}

{/section}

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
<input type="hidden" name="ViewMode" value="sitemap" />

</div>

{section show=$is_standalone}
</form>
{/section}

{/default}
{/let}
{/default}
