<form action="{$return_url}" method="post">

<h1>{"Browse"|i18n}</h1>

<br /><b>Path:</b><br />
&gt;
{section name=Path loop=$parents}
 <a href="/content/browse/{$Path:item.node_id}/{$object_id}/{$edit_version}/">{$Path:item.name}</a> /
{/section}
{$main_node.name}
<br/>

<table width="100%">
<tr>
	<th>
	ID
	</th>
	<th>
	Name
	</th>
	<th>
	Select
	</th>
</tr>
<tr>
	<td class="bglight">
	{$main_node.contentobject_id}
	</td>
	<td class="bglight">
	{$main_node.name}
	</td>
	<td class="bglight">
	{switch name=sw match=$return_type}
	  {case match='NodeID'}
	  <input type="checkbox" name="SelectedNodeIDArray[]" value="{$main_node.node_id}" />
	  {/case}
	  {case}
          <input type="checkbox" name="SelectedObjectIDArray[]" value="{$main_node.contentobject_id}" />
	  {/case}
	{/switch}
	</td>

</tr>
{section name=Object loop=$object_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Object:sequence}">
	{$Object:item.contentobject_id}
	</td>
	<td class="{$Object:sequence}">
	<img src="1x1.gif" width="10" height="1" alt="" />
	<a href="/content/browse/{$Object:item.node_id}/{$object_id}/{$edit_version}/">
	{$Object:item.name}
        </a>
	</td>
	<td class="{$Object:sequence}">
	{switch name=sw match=$return_type}
	  {case match='NodeID'}
	  <input type="checkbox" name="SelectedNodeIDArray[]" value="{$Object:item.node_id}" />
	  {/case}
	  {case}
          <input type="checkbox" name="SelectedObjectIDArray[]" value="{$Object:item.contentobject_id}" />
	  {/case}
	{/switch}
	</td>
</tr>
{/section}
</table>

<input type="hidden" name="BrowseActionName" value="{$browse_action_name}" />
{*<input type="hidden" name="CustomActionButton[{$custom_action_button}]" value="{$custom_action_button}" />*}

<input type="submit" name="SelectButton" value="Select" />

</form>