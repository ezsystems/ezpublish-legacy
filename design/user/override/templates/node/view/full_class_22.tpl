{let object=$node.object
     map=$object.data_map
     comment_limit=3
     list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}
<form method="post" action={"content/action/"|ezurl}>

<table width="417" height="381" border="0" align="center" cellpadding="5" cellspacing="0" bordercolor="#000000">
              <tr>
                <td width="407" valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="links">
 
<p class="heading">
  <strong>{attribute_view_gui attribute=$map.title} by {attribute_view_gui attribute=$map.author}</strong>
</p>
<p>
  {attribute_view_gui attribute=$map.photo border_size=1 hspace=10 alignment=right image_class=medium}
  <strong>{"Availability"|i18n('bookstore')}:</strong>{attribute_view_gui attribute=$map.availability}<br>
  <strong>{"Publisher"|i18n('bookstore')}:</strong>{attribute_view_gui attribute=$map.publisher}<br>
  <strong>{"Our Price"|i18n('bookstore')}:</strong>{attribute_view_gui attribute=$map.price}<br>
  <br>
  <strong><a href="#">Customer review:</a></strong><br>
  <img src={concat("star-",3,".gif")|ezimage}><br>

<!-- Action START -->
    <div class="block">
    {section name=ContentAction loop=$object.content_action_list show=$object.content_action_list}
    <input type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name|i18n('bookstore')}" />
    <br/><br/>
    {/section}
    </div>
<!-- Action END -->
</p>


{section show=$list_count}
                  <p class="heading">
                    <strong>Comments </strong> 
                  </p>
                  <p class="links">Number of Reviews: {$list_count}</p>

{section name=Comment loop=fetch('content','list',hash(parent_node_id,$node.node_id,offset,$view_parameters.offset,limit,$comment_limit))}
{let object=$Comment:item.object
     map=$Comment:object.data_map}
                  <hr size="1" noshade>
                  <p>
                    <strong>{attribute_view_gui attribute=$Comment:map.rating}<br>
                    {attribute_view_gui attribute=$Comment:map.title}</strong>, {$Comment:object.published|l10n(shortdate)} <br>
                    <strong>Reviewer:</strong> {attribute_view_gui attribute=$Comment:map.reviewer_name} from {attribute_view_gui attribute=$Comment:map.geography}
                    <br>
                    <br>
                    {attribute_view_gui attribute=$Comment:map.review}
                    <br>
                  </p>
{/let}
{/section}

{/section}

</td>
</tr>
</table>

</form>

{/let}