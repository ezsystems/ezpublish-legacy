{let object=$node.object
     map=$object.data_map
     comment_limit=3
     list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

<form method="post" action={"content/action/"|ezurl}>

<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000">
<tr>
    <td width="100%" valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="links">
    <strong>{attribute_view_gui attribute=$map.title}</strong><br />
    {attribute_view_gui attribute=$map.photo border_size=1 hspace=10 alignment=right image_class=medium}
    <strong class="small">{"Our Price"|i18n('bookstore')}:</strong> <span class="small">{attribute_view_gui attribute=$map.price}</span><br/>
    <strong class="small">{"Product nr."|i18n('bookstore')}:</strong> <span class="small">{attribute_view_gui attribute=$map.product_nr}</span><br/>
    <br />

    {attribute_view_gui attribute=$map.intro}

    {attribute_view_gui attribute=$map.description}

    <!-- Action START -->
    <div class="block">
    {section name=ContentAction loop=$object.content_action_list show=$object.content_action_list}
    <input type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name|i18n('bookstore')}" />
    <br/><br/>
    {/section}
    </div>
    <!-- Action END -->

{section show=$list_count}
    <strong class="small">Reviews</strong> 
    <p class="links">Number of Reviews: {$list_count}</p>

{section name=Comment loop=fetch('content','list',hash(parent_node_id,$node.node_id,offset,$view_parameters.offset,limit,$comment_limit))}
{let object=$Comment:item.object
     map=$Comment:object.data_map}
      <hr size="1" noshade="noshade" />
      <p>
      {section name=Enum loop=$Comment:map.rating.content.enumobject_list}
       <img src={concat("star-",$Comment:Enum:item.enumvalue,".gif")|ezimage} border="0" alt=""/><br />
      {/section}
      <strong>
      {attribute_view_gui attribute=$Comment:map.title}</strong>, {$Comment:object.published|l10n(shortdate)} <br />
      <strong>Reviewer:</strong> {attribute_view_gui attribute=$Comment:map.reviewer_name} from {attribute_view_gui attribute=$Comment:map.geography}
      <br />
      {attribute_view_gui attribute=$Comment:map.review}
      <br />
      </p>
{/let}
{/section}

{/section}

</td>
</tr>
</table>

<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input class="button" type="submit" name="NewButton" value="{"New review"|i18n}" />
<input type="hidden" name="ClassID" value="23" />


<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

</form>

{/let}