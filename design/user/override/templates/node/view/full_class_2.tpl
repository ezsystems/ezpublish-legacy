{default content_object=$node.object}

<table width="95%" border="0" align="left"  cellpadding="0" cellspacing="0" >
<tr> 
     <td valign="top" bgcolor="#FFFFFF"> 
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
         <td width="100%" height="18" class="header">
         {attribute_view_gui attribute=$content_object.data_map.title}
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
         <span class="small">{$content_object.published|l10n(datetime)}</span>
         <div class="imageright">
         {attribute_view_gui attribute=$content_object.data_map.thumbnail image_class=medium}
         </div>
         {attribute_view_gui attribute=$content_object.data_map.intro}
         {attribute_view_gui attribute=$content_object.data_map.body}
         </td>
     </tr>
     </table>
     </td>
</tr>
</table>

{/default}