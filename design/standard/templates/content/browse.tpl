<form action={concat($return_url)|ezurl} method="post">
<div class="maincontentheader">
<h1>{"Browse"|i18n("design/standard/content/view")}</h1>
</div>
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th width="98%">
	{"Name"|i18n("design/standard/content/view")}
	</th>
	<th width="1%">
	{"Select"|i18n("design/standard/content/view")}
	</th>
</tr>
<tr>
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
<tr>
    <td>
    </td>
    <td>
    <div class="buttonblock">
    <input class="button" type="submit" name="SelectButton" value="{'Select'|i18n('design/standard/content/view')}" />
    </div>
    </td>
</tr>
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/browse/',$main_node.node_id)
         item_count=$browse_list_count
         view_parameters=$view_parameters
         item_limit=10}


<input type="hidden" name="BrowseActionName" value="{$browse_action_name}" />
{section show=$custom_action_data}
<input type="hidden" name="{$custom_action_data.name}" value="{$custom_action_data.value}" />
{/section}

</form>
