<table width="95%" border="0" align="left"  cellpadding="0" cellspacing="0" >
<tr> 
     <td valign="top" bordercolor="#FFD2A6" bgcolor="#FFFFFF"> 
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
         <td width="100%" height="18" bgcolor="#663366"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif">
         <strong>&nbsp;&nbsp;&nbsp;{attribute_view_gui attribute=$node.object.data_map.title}</strong></font>
         </td>
     </tr>
     </table>                 
     </td>
</tr> 
<tr> 
     <td width="100%" valign="top">
     <table width="100%" border="0" cellpadding="10" cellspacing="0">
     <tr>
         <td width="100%" valign="top">
         <span class="small">{$node.object.published|l10n(datetime)}</span>
         <div class="imageright">
         {attribute_view_gui attribute=$node.object.data_map.thumbnail image_class=medium}
         </div>
         {attribute_view_gui attribute=$node.object.data_map.intro}
         {attribute_view_gui attribute=$node.object.data_map.body}
         </td>
     </tr>
     </table>
     </td>
</tr>
</table>
