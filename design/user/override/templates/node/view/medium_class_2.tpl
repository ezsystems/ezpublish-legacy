
<table width="450" border="0" align="left" cellpadding="0" cellspacing="0" >
              <tr> 
                <td height="19" valign="top" bordercolor="#FFD2A6" bgcolor="#FFFFFF"> 
                  <table width="393" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="393" height="18" bgcolor="#663366"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">
<strong>&nbsp;&nbsp;&nbsp; 
                        <font color="#FFFFFF">
{attribute_view_gui attribute=$node.object.data_map.title} - title</font></strong></font></td>
                    </tr>
                  </table>                 
                </td>
              </tr> 

              <tr> 
               <td width="449" valign="top">
                    <table width="393" height="124" border="0" cellpadding="10" cellspacing="0">
                    <tr>
                      <td width="373" valign="top">
<p>
<div class="imageright">
{attribute_view_gui attribute=$node.object.data_map.thumbnail image_class=medium}
</div>
{attribute_view_gui attribute=$node.object.data_map.intro}


    <strong>
  <a href={concat("/content/view/news/",$node.node_id,"/")|ezurl}>Read more....</a></strong></span></p>
                        <p></p></td>
                    </tr>
                  </table>
            </td>
           </tr>
  </table>
