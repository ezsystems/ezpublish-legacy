
{let child_list=fetch('content','list',hash(parent_node_id,$node.node_id,limit,20,offset,$view_parameters.offset,sort_by,array(array(published,true()))))
     child_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}
<h2>{$node.name|wash}</h2>


<table width="100%">
<tr>
    <td>
{section show=$node.depth|le(6)}
<form method="post" action={"content/action/"|ezurl}>

{switch match=$node.object.can_create}
{case match=1}
<input class="button" type="submit" name="NewButton" value="New reply" />

    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />
    <input class="button" type="submit" name="ActionAddToNotification" value="Keep me updated" />

{/case}
{case match=0}
<p>
You need to be logged in to get access to the forums. You can do so <a href={"/user/login/"|ezurl}>here</a>
</p>
{/case}
{/switch}
<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input type="hidden" name="ClassID" value="22" />

</form>
{/section}

{section show=$node.depth|eq(7)}
<form method="post" action={"content/action/"|ezurl}>

{switch match=$node.object.can_create}
{case match=1}
<input class="button" type="submit" name="NewButton" value="New reply" />

    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />
    <input class="button" type="submit" name="ActionAddToNotification" value="Keep me updated" />

{/case}
{case match=0}
<p>
You need to be logged in to get access to the forums. You can do so <a href={"/user/login/"|ezurl}>here</a></p>
{/case}
{case/}
{/switch}
<input type="hidden" name="NodeID" value="{$node.parent_node_id}" />
<input type="hidden" name="ClassID" value="22" />

{/section}

    </td>

    <td align="right">
    <form action={"/content/search/"|ezurl} method="get">
    <input class="searchbox" type="text" size="8" name="SearchText" id="Search" value="" />
    <input class="button" name="SearchButton" type="submit" value="Search forum" />
    <input type="hidden" name="SearchContentClassID" value="22" />
    <input type="hidden" name="SubTreeArray[]" value="{$node.parent_node_id}" />
    </form>
    </td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
    <td class="forumhead" width="80%">
    Topic: {$node.name|wash}
    </td>
    <td class="forumhead" width="20%">
    Author
    </td>
</tr>
<tr>
    <td class="bglightforum">
    <p>
    {$node.object.data_map.message.content|wash(xhtml)|nl2br|wordtoimage|autolink}
    </p>
    </td>
    <td class="bglightforum" valign="top">
    <p>
    {$node.object.owner.name|wash}<br />

    <br />
    {$node.object.published|l10n(datetime)}
    </p>
    <p> 	
    {let owner_id=$node.object.owner.id}

    {section name=Author loop=$node.object.author_array}
    {section  show=eq($owner_id,$Author:item.contentobject_id)|not()}
    Moderated by: {$Author:item.contentobject.name}
    {/section}

    {/section}

    {/let}

    </p>

   {switch match=$node.object.can_edit}
   {case match=1}
<form method="post" action={"content/action/"|ezurl}>

   <br/>
   <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
</form>

   {/case}
   {case match=0}
   {/case}
   {/switch}



    <td>
</tr>
{section name=Child loop=$child_list sequence=array(bgdarkforum,bglightforum)}
<tr>
    <td class="{$Child:sequence}">
    <a name="msg{$Child:item.node_id}"></a><h3 class="forum">{$Child:item.name|wash}</h3>
    <p>
    {$Child:item.object.data_map.message.content|wash(xhtml)|nl2br|wordtoimage|autolink}
    </p>
    </td>
    <td valign="top" class="{$Child:sequence}">
    <p>
    {$Child:item.object.owner.name|wash}<br />

    <br /> {$Child:item.object.published|l10n(datetime)}
    </p>
    <p>

    {let owner_id=$Child:item.object.owner.id}

    {section name=Author loop=$Child:item.object.author_array}
        {section show=eq($Child:owner_id,$:item.contentobject_id)|not()}
            Moderated by: {$:item.contentobject.name}
        {/section}
    {/section}

    {/let}

   {switch match=$Child:item.object.can_edit}
   {case match=1}
<form method="post" action={"content/action/"|ezurl}>
   <br/>
   <input type="hidden" name="ContentObjectID" value="{$Child:item.object.id}" />
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
   </form>
   {/case}
   {case match=0}
   {/case}
   {/switch}

    </p>
    </td>
</tr>
{/section}
</table>

 {section show=$node.depth|le(6)}
<form method="post" action={"content/action/"|ezurl}>

{switch match=$node.object.can_create}
{case match=1}
<input class="button" type="submit" name="NewButton" value="New reply" />

    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />
    <input class="button" type="submit" name="ActionAddToNotification" value="Keep me updated" />
{/case}
{case match=0}
<p>
You need to be logged in to get access to the forums. You can do so <a href={"/user/login/"|ezurl}>here</a></p>
{/case}
{/switch}
<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input type="hidden" name="ClassID" value="22" />

</form>

{/section}


{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$child_count
         view_parameters=$view_parameters
         item_limit=20}


