<form action={concat($return_url)|ezurl} method="post">
<div class="maincontentheader">
<h1>{"Browse"|i18n}</h1>
</div>
{* <b>Path:</b><br />
&gt;
{section name=Path loop=$parents}
 <a href={concat("/content/browse/",$Path:item.node_id,"/")|ezurl}>{$Path:item.name}</a> /
{/section}
{$main_node.name}
<p class="comment">The path should be moved to the designated place outside this template. th[eZ]</p>
*}
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th width="1%">
	ID:
	</th>
	<th width="98%">
	Name:
	</th>
	<th width="1%">
	Select:
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
          {switch name=sw match=$selection_type}
	      {case match='Single'}
              <input type="radio" name="SelectedNodeIDArray[]" value="{$main_node.node_id}" />
              {/case}
	      {case}
	      <input type="checkbox" name="SelectedNodeIDArray[]" value="{$main_node.node_id}" />
	      {/case}
	  {/switch}
	  {/case}
	  {case}
          {switch name=sw match=$selection_type}
	      {case match='Single'}
              <input type="radio" name="SelectedObjectIDArray[]" value="{$main_node.contentobject_id}" />
              {/case}
	      {case}
              <input type="checkbox" name="SelectedObjectIDArray[]" value="{$main_node.contentobject_id}" />
	      {/case}
	  {/switch}
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
	<img src={"1x1-transparent.gif"|ezimage} width="10" height="1" alt="" />
	<a href={concat("/content/browse/",$Object:item.node_id,"/")|ezurl}>
	{$Object:item.name}
        </a>
	</td>
	<td class="{$Object:sequence}">
	{switch name=sw match=$return_type}
	  {case match='NodeID'}
          {switch name=sw match=$selection_type}
	      {case match='Single'}
              <input type="radio" name="SelectedNodeIDArray[]" value="{$Object:item.node_id}" />
              {/case}
	      {case}
	      <input type="checkbox" name="SelectedNodeIDArray[]" value="{$Object:item.node_id}" />
	      {/case}
	  {/switch}
	  {/case}
	  {case}
          {switch name=sw match=$selection_type}
	      {case match='Single'}
              <input type="radio" name="SelectedObjectIDArray[]" value="{$Object:item.contentobject_id}" />
              {/case}
	      {case}
              <input type="checkbox" name="SelectedObjectIDArray[]" value="{$Object:item.contentobject_id}" />
	      {/case}
	  {/switch}
	  {/case}
	{/switch}
	</td>
</tr>
{/section}
</table>

<p class="comment">I don't really understand how the list above works... The two first lines getting the same style, and the indenting seems strange. Do this show some kind of parent/child relationship? th[eZ]</p>

<input type="hidden" name="BrowseActionName" value="{$browse_action_name}" />
{*<input type="hidden" name="CustomActionButton[{$custom_action_button}]" value="{$custom_action_button}" />*}

<div class="buttonblock">
<input class="button" type="submit" name="SelectButton" value="Select" />
</div>

</form>