            <table width="384" height="381" border="0" align="center" cellpadding="5" cellspacing="0" bordercolor="#000000">
              <tr>
                <td valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="links"><font size="4">This weeks recommendations!! </font>
                  <hr size="1" noshade></td>
              </tr>
              <tr>
                <td width="374" valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="links">
{section name=Book loop=fetch('content','list',hash(parent_node_id,$node.node_id,limit,2))}
                  {node_view_gui view=book_list content_node=$Book:item}
                  <hr size="1" noshade>
{/section}
                  <p>&nbsp;</p>
                </td>
              </tr>
            </table>
