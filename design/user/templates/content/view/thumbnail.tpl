{let page_limit=8
     list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

<form method="post" action="/content/action/">

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td>
	{$node.object.name|texttoimage('archtura')}
	</td>
</tr>
</table>

<table width="100%">
<tr>
{section name=Child loop=fetch('content','list',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset))}
	<td>
          <center>
            <table border="1" align="center" cellpadding="4" cellspacing="6" bordercolor="#000000" bgcolor="#E2E2E2">
              <tr bordercolor="#000000" bgcolor="#000000"> 
                <td colspan="3" valign="top" bgcolor="#ffffff">
{*	<a href="{$module.functions.view.uri}/full/{$Child:item.node_id}">*}
        <a href="{concat('/content/view/slideshow/',$node.node_id,'/offset/',sum($view_parameters.offset,$Child:index))}">
	{content_view_gui view=image_small content_node=$Child:item}
        </a>
{*	</a>*}
                </td>
              </tr>
            </table>
          </center>
	</td>
{delimiter modulo=ceil(div($list_count,2))}
</tr>
<tr><td>&nbsp;</td>
</tr>
<tr>
{/delimiter}
{/section}
</tr>
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('content/view','/thumbnail/',$node.node_id)
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}

{*
{switch match=$node.object.can_create}
{case match=1}
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <input type="submit" name="NewButton" value="New" />
         <select name="ClassID">
	      {section name=Classes loop=$node.object.can_create_class_list}
	      <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	      {/section}
         </select>
{/case}
{case match=0}
  You are not allowed to create child objects
{/case}
{/switch}

<input class="button" type="submit" name="RemoveButton" value="Remove object(s)" />

<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
*}

</form>