{let child_list=fetch('content','list',hash( parent_node_id, $node.node_id,
                                             limit, 20,
                                             offset, $view_parameters.offset,
                                             sort_by, array( array( attribute, false(), 190 ), array(published,false()))))
     child_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

<h1>{$node.name|wash}</h1>

{attribute_view_gui attribute=$node.object.data_map.description}

<div class="selectedsearch">
<form action={"/content/search/"|ezurl} method="get">
    <input class="searchbox" type="text" size="8" name="SearchText" id="Search" value="" />
    <input class="button" name="SearchButton" type="submit" value="Search this forum" />
    <input type="hidden" name="SearchContentClassID" value="22" />
    <input type="hidden" name="SubTreeArray[]" value="{$node.node_id}" />
</form>
</div>

<form method="post" action={"content/action/"|ezurl}>

{switch match=$node.object.can_create}
{case match=1}
<div class="buttonblock">
<input class="button" type="submit" name="NewButton" value="New topic" />

    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />
    <input class="button" type="submit" name="ActionAddToNotification" value="Keep me updated" />
</div>
{/case}
{case match=0}
<p>
You need to be logged in to get access to the forums. You can do so <a href={"/user/login/"|ezurl}>here</a></p>
{/case}
{/switch}
<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input type="hidden" name="ClassID" value="21" />

</form>


<div class="forum_level3">
<table class="forum" cellspacing="0">
<tr>
    <th>
    {"Post"|i18n("design/forum/layout")}
    </th>
    <th>
    {"Author"|i18n("design/forum/layout")}
    </th>
    <th>
    {"Replies"|i18n("design/forum/layout")}
    </th>
    <th>
    {"Last reply"|i18n("design/forum/layout")}
    </th>
</tr>

{section name=Child loop=$child_list sequence=array(bglightforum,bgdarkforum)}
<tr class="{$Child:sequence}">
    <td class="post">
    <p>
    {section show=$Child:item.object.data_map.sticky.content}<img src={"sticky_icon-red.gif"|ezimage} height="20" width="20" align="middle" alt="" />{/section}
    <a href={$Child:item.url_alias|ezurl}>{$Child:item.object.name|wash}</a>
    </p>
    </td>
    <td class="author">
    <p>
    {$Child:item.object.owner.name|wash}<br />
    <span class="forumdate">({$Child:item.object.published|l10n(shortdatetime)})</span>
    </p>
    </td>
    <td class="replies">
    <p>
    {fetch('content','tree_count',hash(parent_node_id,$Child:item.node_id))}
    </p>
    </td>
    <td class="lastreply">
    {let last_reply=fetch('content','list',hash(parent_node_id,$Child:item.node_id,sort_by,array(array('published',false())),limit,1))}
    {section name=Reply loop=$Child:last_reply show=$Child:last_reply}
    <p class="lastreply">
    Last reply: <a href={concat($Child:Reply:item.parent.url_alias,'#msg',$Child:Reply:item.node_id)|ezurl}>{$Child:Reply:item.name|wash}</a><br />
    <span class="date">({$Child:Reply:item.object.published|l10n(shortdatetime)})</span> by <span class="author">{$Child:Reply:item.object.owner.name|wash}</span>
    </p>
    {/section}
    {/let}
    </td>
</tr>
{/section}
</table>
</div>

<form method="post" action={"content/action/"|ezurl}>

{switch match=$node.object.can_create}
{case match=1}
<div class="buttonblock">
<input class="button" type="submit" name="NewButton" value="New topic" />

    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />
    <input class="button" type="submit" name="ActionAddToNotification" value="Keep me updated" />
</div>
{/case}
{case match=0}
<p>
You need to be logged in to get access to the forums. You can do so <a href={"/user/login/"|ezurl}>here</a></p>
{/case}
{/switch}
<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input type="hidden" name="ClassID" value="21" />

</form>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$child_count
         view_parameters=$view_parameters
         item_limit=20}

{/let}
