{let browse_indentation=10}
<form action={concat($return_url)|ezurl} method="post">

<div class="maincontentheader">
<h1>{"Browse"|i18n("design/standard/content/view")} - {$main_node.name|wash}</h1>
</div>

<p>{'To select objects, choose the appriate radiobutton or checkbox(es), and click the "Choose" button.'|i18n("design/standard/content/view")}</p>
<p>{'To select an object that is a child of one of the displayed objects, click the object name and you will get a list of the children of the object.'|i18n("design/standard/content/view")}</p>

<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th width="69%">
	{"Name"|i18n("design/standard/content/view")}
	</th>
	<th width="30%">
	{"Class"|i18n("design/standard/content/view")}
	</th>
	<th width="30%">
	{"Section"|i18n("design/standard/content/view")}
	</th>
	<th width="1%">
	{"Select"|i18n("design/standard/content/view")}
	</th>
</tr>
<tr>
	<td class="bglight">
	{$main_node.name|wash}
	</td>

	<td class="bglight">
	{$main_node.object.content_class.name|wash}
	</td>

	<td class="bglight">
	{$main_node.object.section_id}
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
{section name=Object loop=$object_array sequence=array(bgdark,bglight)}
<tr>
	<td class="{$Object:sequence}">
        <img src={"1x1.gif"|ezimage} width="{mul(sub($:item.depth,$main_node.depth),$browse_indentation)}" height="1" alt="" border="0" />
	<a href={concat("/content/browse/",$Object:item.node_id,"/")|ezurl}>
	{$Object:item.name|wash}
        </a>
	</td>

	<td class="{$Object:sequence}">
	{$Object:item.object.content_class.name|wash}
	</td>

	<td class="{$Object:sequence}">
	{$:item.object.section_id}
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
    </td>
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
{/let}
