
{let child_list=fetch('content','list',hash(parent_node_id,$node.node_id,limit,20,offset,$view_parameters.offset,sort_by,array(array(published,true()))))
     child_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}
<h1>{$node.name|wash}</h1>


<div class="selectedsearch">
    <form action={"/content/search/"|ezurl} method="get">
    <input class="searchtext" type="text" size="10" name="SearchText" id="Search" value="" />
    <input class="searchbutton" name="SearchButton" type="submit" value="Search forum" />
    <input type="hidden" name="SearchContentClassID" value="22" />
    <input type="hidden" name="SubTreeArray[]" value="{$node.parent_node_id}" />
    </form>
</div>

<form method="post" action={"content/action/"|ezurl}>

{switch match=$node.object.can_create}
{case match=1}
<div class="buttonblock">
<input class="button" type="submit" name="NewButton" value="New reply" />

    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id.}" />
    <input class="button" type="submit" name="ActionAddToNotification" value="Keep me updated" />

</div>
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

<div class="forum_level4">
<table class="forum" cellspacing="0">
<tr>
    <th>
    Author
    </th>
    <th>
    Topic: {$node.name|wash}
    </th>
</tr>
<tr class="bglightforum">
    <td class="author">
    <p>{$node.object.owner.name|wash}<br />
    {$node.object.owner.data_map.title.content|wash}</p>

    <p>
    {attribute_view_gui attribute=$node.object.owner.data_map.user_image image_class=small}
    </p>

    <p>
    Topics:&nbsp;{fetch('content', 'object_count_by_user_id', hash( 'class_id', 22,
                                                       'user_id', $node.object.owner.id ) )}
						       
    Replies:&nbsp;{fetch('content', 'object_count_by_user_id', hash( 'class_id', 21,
                                                       'user_id', $node.object.owner.id ) )}<br />
    Location:{$node.object.owner.data_map.location.content|wash}<br />
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

    {section show=$node.object.can_edit}
<form method="post" action={"content/action/"|ezurl}>
   <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
</form>
    {/section}
    </td>
    <td class="message">
    <p class="date">({$Child:item.object.published|l10n(datetime)})</p>
    <p>
    {$node.object.data_map.message.content|wash(xhtml)|nl2br|wordtoimage|autolink}
    </p>
    </td>
</tr>
{section name=Child loop=$child_list sequence=array(bgdarkforum,bglightforum)}
<tr class="{$Child:sequence}">
    <td class="author">
    <p>{$Child:item.object.owner.name|wash}<br />
    {$Child:item.object.owner.data_map.title.content|wash}</p>

    <p>
    {attribute_view_gui attribute=$Child:item.object.owner.data_map.user_image image_class=small}
    </p>

    <p>
    Topics:&nbsp;{fetch('content', 'object_count_by_user_id', hash( 'class_id', 22,
                                                       'user_id', $Child:item.object.owner.id ) )}

    Replies:&nbsp;{fetch('content', 'object_count_by_user_id', hash( 'class_id', 21,
                                                       'user_id', $Child:item.object.owner.id ) )}<br />
    Location:{$Child:item.object.owner.data_map.location.content|wash}<br />
    </p>
    <p>
    {let owner_id=$Child:item.object.owner.id}
        {section name=Author loop=$Child:item.object.author_array}
            {section  show=eq($Child:owner_id,$Child:Author:item.contentobject_id)|not()}
                Moderated by: {$Author:item.contentobject.name} 
             {/section}
         {/section}
    {/let}
    </p>

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
   <input type="hidden" name="ContentObjectID" value="{$Child:item.object.id}" />
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
   </form>
   {/case}
   {case match=0}
   {/case}
   {/switch}

    </td>
    <td class="message">
    <h3 class="title" id="msg{$Child:item.node_id}">{$Child:item.name|wash}</h3>
    <p class="date">({$Child:item.object.published|l10n(datetime)})</p>
    <p>
    {$Child:item.object.data_map.message.content|wash(xhtml)|nl2br|wordtoimage|autolink}
    </p>
    </td>
</tr>
{/section}
</table>
</div>

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

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$child_count
         view_parameters=$view_parameters
         item_limit=20}


