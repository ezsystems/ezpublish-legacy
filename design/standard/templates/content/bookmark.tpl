{let page_limit=30 }
{*     list_count=fetch('content','draft_count')} *}

<form action={concat("content/bookmark/")|ezurl} method="post" >

<div class="maincontentheader">
<h1>{"My bookmarks"|i18n("design/standard/content/view")}</h1>
</div>

{let bookmark_list=fetch('content','bookmarks',array())}

{section show=$bookmark_list}

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
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
{section name=Bookmark loop=$bookmark_list  sequence=array(bgdark,bglight)}
<tr>
    <td class="{$Bookmark:sequence}">
	<a href={concat("/content/view/full/",$Bookmark:item.node_id,"/")|ezurl}>
	{$Bookmark:item.name|wash}
        </a>
	</td>

	<td class="{$Bookmark:sequence}">
	{$Bookmark:item.node.object.content_class.name|wash}
	</td>

	<td class="{$Bookmark:sequence}">
	{$:item.node.object.section_id}
	</td>

	<td class="{$Bookmark:sequence}">
		      <input type="checkbox" name="DeleteIDArray[]" value="{$Bookmark:item.id}" />
	</td>
</tr>
{/section}

<tr>
  <td colspan="3">
  </td>
  <td align="right">
    <input type="image" name="RemoveButton" value="{'Remove'|i18n('design/standard/content/view')}" src={"trash.png"|ezimage} />
  </td>
</tr>
</table>
{*{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/draft/')
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}
*}
{section-else}

<div class="feedback">
<h2>{"You have no bookmarks"|i18n("design/standard/content/view")}</h2>
</div>

{/section}

