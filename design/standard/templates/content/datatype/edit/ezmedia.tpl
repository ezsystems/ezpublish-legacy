{switch name=mediaType match=$attribute.contentclass_attribute.data_text1}
{case match=flash}

<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}000000" />
<label>{"Filename"|i18n}:</label><div class="labelbreak"></div>
<input class="box" name="ContentObjectAttribute_data_mediafilename_{$attribute.id}" type="file" />
</div>
<div class="block">
<div class="element">
<label>{"Width"|i18n}:</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}" />
</div>
<div class="element">
<label>{"Height"|i18n}:</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}" />
</div>
<div class="element">
<label>{"Quality"|i18n}:</label><div class="labelbreak"></div>
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
</div>
<div class="element">
<input type="checkbox" name="ContentObjectAttribute_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} />&nbsp;<label class="check">{"Autoplay"|i18n}</label><div class="labelbreak"></div>
<input type="checkbox" name="ContentObjectAttribute_data_media_is_loop_{$attribute.id}" value="{$attribute.content.is_loop}" {section show=$attribute.content.is_loop}checked{/section} />&nbsp;<label class="check">{"Loop"|i18n}</label><div class="labelbreak"></div>
</div>
<div class="break"></div>
</div>

{/case}
{case match=quick_time}

<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}" />
<label>{"Filename"|i18n}:</label><div class="labelbreak"></div>
<input class="box" name="ContentObjectAttribute_data_mediafilename_{$attribute.id}" type="file" />
</div>

<div class="block">
<div class="element">
<label>{"Width"|i18n}:</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}" />

</div>
<div class="element">
<label>{"Height"|i18n}:</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}" />

</div>
<div class="element">
<input type="checkbox" name="ContentObjectAttribute_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} />&nbsp;<label class="check">{"Autoplay"|i18n}</label><div class="labelbreak"></div>

<input type="checkbox" name="ContentObjectAttribute_data_media_has_controller_{$attribute.id}" value="{$attribute.content.has_controller}" {section show=$attribute.content.has_controller}checked{/section} />&nbsp;<label class="check">{"Controller"|i18n}</label><div class="labelbreak"></div>

<input type="checkbox" name="ContentObjectAttribute_data_media_is_loop_{$attribute.id}" value="{$attribute.content.is_loop}" {section show=$attribute.content.is_loop}checked{/section} />&nbsp;<label class="check">{"Loop"|i18n}</label><div class="labelbreak"></div>
</div>
<div class="break"></div>
</div>

{/case}
{case match=windows_media_player}
<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}" />
<label>{"Filename"|i18n}:</label><div class="labelbreak"></div>
<input class="box" name="ContentObjectAttribute_data_mediafilename_{$attribute.id}" type="file" />
</div>

<div class="block">
<div class="element">
<label>{"Width"|i18n}:</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}" />
</div>
<div class="element">
<label>{"Height"|i18n}:</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}" />
</div>
<div class="element">
<input type="checkbox" name="ContentObjectAttribute_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} />&nbsp;<label class="check">{"Autoplay"|i18n}</label><div class="labelbreak"></div>
<input type="checkbox" name="ContentObjectAttribute_data_media_has_controller_{$attribute.id}" value="{$attribute.content.has_controller}" {section show=$attribute.content.has_controller}checked{/section} />&nbsp;<label class="check">{"Controller"|i18n}</label><div class="labelbreak"></div>
</div>
<div class="break"></div>
</div>

{/case}
{case match=real_player}
<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}000000" />
<label>{"Filename"|i18n}:</label><div class="labelbreak"></div>
<input class="box" name="ContentObjectAttribute_data_mediafilename_{$attribute.id}" type="file" />
</div>
<div class="block">
<div class="element">
<label>{"Width"|i18n}:</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}" />
</div>
<div class="element">
<label>{"Height"|i18n}:</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}" />
</div>
<div class="element">
<label>{"Controls"|i18n}:</label><div class="labelbreak"></div>
<select name="ContentObjectAttribute_data_media_controls_{$attribute.id}" >
{switch name=Sw match=$attribute.content.controls}
{case match=imagewindow}
 <option value="imagewindow" SELECTED>ImageWindow</option>
<option value="all">All</option>
<option value="controlpanel">ControlPanel</option>
<option value="infovolumpanel">InfoVolumePanel</option>
<option value="infopanel">InfoPanel</option> 
    {/case}
{case match=All}
<option value="imagewindow">ImageWindow</option>
<option value="all" SELECTED>All</option>
<option value="controlpanel">ControlPanel</option>
<option value="infovolumpanel">InfoVolumePanel</option>
<option value="infopanel">InfoPanel</option>
    {/case}
{case match=controlpanel}
 <option value="imagewindow">ImageWindow</option>
<option value="all">All</option>
<option value="controlpanel" SELECTED>ControlPanel</option>
<option value="infovolumpanel">InfoVolumePanel</option>
<option value="infopanel">InfoPanel</option> 
    {/case}
{case match=infovolumpanel}
<option value="imagewindow">ImageWindow</option>
<option value="all">All</option>
<option value="controlpanel">ControlPanel</option>
<option value="infovolumpanel" SELECTED>InfoVolumePanel</option>
<option value="infopanel">InfoPanel</option> 
    {/case}
{case match=infopanel}
 <option value="imagewindow">ImageWindow</option>
<option value="all">All</option>
<option value="controlpanel">ControlPanel</option>
<option value="infovolumpanel">InfoVolumePanel</option>
<option value="infopanel" SELECTED>InfoPanel</option> 
    {/case}
{case}
 <option value="imagewindow" SELECTED>ImageWindow</option>
<option value="all">All</option>
<option value="controlpanel">ControlPanel</option>
<option value="infovolumpanel">InfoVolumePanel</option>
<option value="infopanel">InfoPanel</option> 
    {/case}
{/switch}
</select>
</div>
<div class="element">
<input type="checkbox" name="ContentObjectAttribute_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} />&nbsp;<label class="check">{"Autoplay"|i18n}</label><div class="labelbreak"></div>
</div>
<div class="break"></div>
</div>

{/case}
{/switch}

{section show=or($attribute.content,$attribute.content.filename)}
<div class="block">
<div class="element">
<label>{"Existing filename"|i18n}:</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.filename}</p>
</div>
<div class="element">
<label>{"Existing orignal filename"|i18n}:</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.original_filename}</p>
</div>
<div class="element">
<label>{"Existing mime/type"|i18n}:</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.mime_type}</p>
</div>
<div class="break"></div>
</div>
<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_delete_media]" value="{'Delete'|i18n}" />
</div>
{/section}
