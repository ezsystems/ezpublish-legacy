<table>
<tr>
  <td width="120" align="left">
  Media player type
  </td>
  <td align="left">  
  <select name="ContentClass_ezmedia_type_{$class_attribute.id}">
  	  <option value="flash">Flash</option>
	  <option value="quick_time">QuickTime</option>
	  <option value="real_player">Real player</option>
	  <option value="windows_media_player">Windows media player</option>
  </select>
  </td>
</tr>
<tr>
  <td  width="100">
  Max file size
  </td> 
  <td align="left">
  <input type="text" name="ContentClass_ezmedia_max_filesize_{$class_attribute.id}" value="{$class_attribute.data_int1}" size="5" maxlength="5" /> MB
  </td> 
</tr>
</table>