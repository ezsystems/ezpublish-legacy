<table width="100%" cellpadding="2" cellspacing="0">
{switch name=mediaType match=$attribute.contentclass_attribute.data_text1}
{case match=flash}
<tr>
	<td class="bglight">
	<b>{"Filename"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Width"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Height"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Quality"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Autoplay"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Loop"|i18n}</b>:<br/>
	</td>
</tr>
<tr>
	<td class="bglight" width="200">
	<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}" />
	<input name="ContentObjectAttribute_data_mediafilename_{$attribute.id}" type="file" />
	</td>
	<td class="bglight">
	<input type="text" name="ContentObjectAttribute_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}">
	</td>
	<td class="bglight">
	<input type="text" name="ContentObjectAttribute_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}">
	</td>
	<td class="bglight" width="150">
	<select name="ContentObjectAttribute_data_media_quality_{$attribute.id}" >
	{switch name=Sw match=$attribute.content.quality}
	{case match=high}
  	  <option value="high" SELECTED>High</option>
	  <option value="best">Best</option>
	  <option value="low">Low</option>
	  <option value="autohigh">Autohigh</option>
	  <option value="autolow">Autolow</option> 
        {/case}
	{case match=best}
  	  <option value="high">High</option>
	  <option value="best" SELECTED>Best</option>
	  <option value="low">Low</option>
	  <option value="autohigh">Autohigh</option>
	  <option value="autolow">Autolow</option> 
        {/case}
	{case match=low}
  	  <option value="high">High</option>
	  <option value="best">Best</option>
	  <option value="low" SELECTED>Low</option>
	  <option value="autohigh">Autohigh</option>
	  <option value="autolow">Autolow</option> 
        {/case}
	{case match=autohigh}
  	  <option value="high">High</option>
	  <option value="best">Best</option>
	  <option value="low">Low</option>
	  <option value="autohigh" SELECTED>Autohigh</option>
	  <option value="autolow">Autolow</option> 
        {/case}
	{case match=autolow}
   	  <option value="high">High</option>
	  <option value="best">Best</option>
	  <option value="low">Low</option>
	  <option value="autohigh">Autohigh</option>
	  <option value="autolow" SELECTED>Autolow</option>
        {/case}
	{case}
   	  <option value="high">High</option>
	  <option value="best">Best</option>
	  <option value="low">Low</option>
	  <option value="autohigh">Autohigh</option>
	  <option value="autolow">Autolow</option>
        {/case}
	{/switch}
	</select>
	</td>
	<td class="bglight">
	<input type="checkbox" name="ContentObjectAttribute_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} >
	</td>
	<td class="bglight">
	<input type="checkbox" name="ContentObjectAttribute_data_media_is_loop_{$attribute.id}" value="{$attribute.content.is_loop}" {section show=$attribute.content.is_loop}checked{/section} > 
	</td>
</tr>
{/case}
{case match=quick_time}
<tr>
	<td class="bglight">
	<b>{"Filename"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Width"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Height"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Autoplay"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Controller"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Loop"|i18n}</b>:<br/>
	</td>
</tr>
<tr>
	<td class="bglight" width="200">
	<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}" />
	<input name="ContentObjectAttribute_data_mediafilename_{$attribute.id}" type="file" />
	</td>
	<td class="bglight">
	<input type="text" name="ContentObjectAttribute_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}">
	</td>
	<td class="bglight">
	<input type="text" name="ContentObjectAttribute_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}">
	</td>
	<td class="bglight">
	<input type="checkbox" name="ContentObjectAttribute_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} >
	</td>
	<td class="bglight">
	<input type="checkbox" name="ContentObjectAttribute_data_media_has_controller_{$attribute.id}" value="{$attribute.content.has_controller}" {section show=$attribute.content.has_controller}checked{/section} > 
	</td>
	<td class="bglight">
	<input type="checkbox" name="ContentObjectAttribute_data_media_is_loop_{$attribute.id}" value="{$attribute.content.is_loop}" {section show=$attribute.content.is_loop}checked{/section} > 
	</td>
</tr>
{/case}
{/switch}
</table>