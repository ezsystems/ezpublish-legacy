{default attribute_base=ContentObjectAttribute}
{switch name=mediaType match=$attribute.contentclass_attribute.data_text1}
{case match=flash}
<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}000000" />
<label>{"Filename"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input class="box" name="{$attribute_base}_data_mediafilename_{$attribute.id}" type="file" />
</div>
<div class="block">
<div class="element">
<label>{"Width"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}" />
</div>
<div class="element">
<label>{"Height"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}" />
</div>
<div class="element">
<label>{"Quality"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<select name="{$attribute_base}_data_media_quality_{$attribute.id}" >
{switch name=Sw match=$attribute.content.quality}
{case match=high}
  <option value="high" SELECTED>{"High"|i18n("design/standard/content/datatype")}</option>
  <option value="best">{"Best"|i18n("design/standard/content/datatype")}</option>
  <option value="low">{"Low"|i18n("design/standard/content/datatype")}</option>
  <option value="autohigh">{"Autohigh"|i18n("design/standard/content/datatype")}</option>
  <option value="autolow">{"Autolow"|i18n("design/standard/content/datatype")}</option> 
{/case}
{case match=best}
  <option value="high">{"High"|i18n("design/standard/content/datatype")}</option>
  <option value="best" SELECTED>{"Best"|i18n("design/standard/content/datatype")}</option>
  <option value="low">{"Low"|i18n("design/standard/content/datatype")}</option>
  <option value="autohigh">{"Autohigh"|i18n("design/standard/content/datatype")}</option>
  <option value="autolow">{"Autolow"|i18n("design/standard/content/datatype")}</option> 
{/case}
{case match=low}
  <option value="high">{"High"|i18n("design/standard/content/datatype")}</option>
  <option value="best">{"Best"|i18n("design/standard/content/datatype")}</option>
  <option value="low" SELECTED>{"Low"|i18n("design/standard/content/datatype")}</option>
  <option value="autohigh">{"Autohigh"|i18n("design/standard/content/datatype")}</option>
  <option value="autolow">{"Autolow"|i18n("design/standard/content/datatype")}</option> 
{/case}
{case match=autohigh}
  <option value="high">{"High"|i18n("design/standard/content/datatype")}</option>
  <option value="best">{"Best"|i18n("design/standard/content/datatype")}</option>
  <option value="low">{"Low"|i18n("design/standard/content/datatype")}</option>
  <option value="autohigh" SELECTED>{"Autohigh"|i18n("design/standard/content/datatype")}</option>
  <option value="autolow">{"Autolow"|i18n("design/standard/content/datatype")}</option> 
{/case}
{case match=autolow}
  <option value="high">{"High"|i18n("design/standard/content/datatype")}</option>
  <option value="best">{"Best"|i18n("design/standard/content/datatype")}</option>
  <option value="low">{"Low"|i18n("design/standard/content/datatype")}</option>
  <option value="autohigh">{"Autohigh"|i18n("design/standard/content/datatype")}</option>
  <option value="autolow" SELECTED>{"Autolow"|i18n("design/standard/content/datatype")}</option>
{/case}
{case}
  <option value="high">{"High"|i18n("design/standard/content/datatype")}</option>
  <option value="best">{"Best"|i18n("design/standard/content/datatype")}</option>
  <option value="low">{"Low"|i18n("design/standard/content/datatype")}</option>
  <option value="autohigh">{"Autohigh"|i18n("design/standard/content/datatype")}</option>
  <option value="autolow">{"Autolow"|i18n("design/standard/content/datatype")}</option>
{/case}
{/switch}
</select>
</div>
<div class="element">
<input type="checkbox" name="{$attribute_base}_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} />&nbsp;<label class="check">{"Autoplay"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="checkbox" name="{$attribute_base}_data_media_is_loop_{$attribute.id}" value="{$attribute.content.is_loop}" {section show=$attribute.content.is_loop}checked{/section} />&nbsp;<label class="check">{"Loop"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
</div>
<div class="break"></div>
</div>

{/case}
{case match=quick_time}

<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}000000" />
<label>{"Filename"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input class="box" name="{$attribute_base}_data_mediafilename_{$attribute.id}" type="file" />
</div>

<div class="block">
<div class="element">
<label>{"Width"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}" />

</div>
<div class="element">
<label>{"Height"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}" />

</div>
<div class="element">
<input type="checkbox" name="{$attribute_base}_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} />&nbsp;<label class="check">{"Autoplay"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>

<input type="checkbox" name="{$attribute_base}_data_media_has_controller_{$attribute.id}" value="{$attribute.content.has_controller}" {section show=$attribute.content.has_controller}checked{/section} />&nbsp;<label class="check">{"Controller"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>

<input type="checkbox" name="{$attribute_base}_data_media_is_loop_{$attribute.id}" value="{$attribute.content.is_loop}" {section show=$attribute.content.is_loop}checked{/section} />&nbsp;<label class="check">{"Loop"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
</div>
<div class="break"></div>
</div>

{/case}
{case match=windows_media_player}
<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}000000" />
<label>{"Filename"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input class="box" name="{$attribute_base}_data_mediafilename_{$attribute.id}" type="file" />
</div>

<div class="block">
<div class="element">
<label>{"Width"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}" />
</div>
<div class="element">
<label>{"Height"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}" />
</div>
<div class="element">
<input type="checkbox" name="{$attribute_base}_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} />&nbsp;<label class="check">{"Autoplay"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="checkbox" name="{$attribute_base}_data_media_has_controller_{$attribute.id}" value="{$attribute.content.has_controller}" {section show=$attribute.content.has_controller}checked{/section} />&nbsp;<label class="check">{"Controller"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
</div>
<div class="break"></div>
</div>

{/case}
{case match=real_player}
<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}000000" />
<label>{"Filename"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input class="box" name="{$attribute_base}_data_mediafilename_{$attribute.id}" type="file" />
</div>
<div class="block">
<div class="element">
<label>{"Width"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_media_width_{$attribute.id}" size="5" value="{$attribute.content.width}" />
</div>
<div class="element">
<label>{"Height"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_media_height_{$attribute.id}" size="5" value="{$attribute.content.height}" />
</div>
<div class="element">
<label>{"Controls"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<select name="{$attribute_base}_data_media_controls_{$attribute.id}" >
{switch name=Sw match=$attribute.content.controls}
{case match=imagewindow}
  <option value="imagewindow" SELECTED>{"ImageWindow"|i18n("design/standard/content/datatype")}</option>
  <option value="all">{"All"|i18n("design/standard/content/datatype")}</option>
  <option value="controlpanel">{"ControlPanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infovolumpanel">{"InfoVolumePanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infopanel">{"InfoPanel"|i18n("design/standard/content/datatype")}</option> 
    {/case}
{case match=All}
  <option value="imagewindow">{"ImageWindow"|i18n("design/standard/content/datatype")}</option>
  <option value="all" SELECTED>{"All"|i18n("design/standard/content/datatype")}</option>
  <option value="controlpanel">{"ControlPanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infovolumpanel">{"InfoVolumePanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infopanel">{"InfoPanel"|i18n("design/standard/content/datatype")}</option>
    {/case}
{case match=controlpanel}
  <option value="imagewindow">{"ImageWindow"|i18n("design/standard/content/datatype")}</option>
  <option value="all">{"All"|i18n("design/standard/content/datatype")}</option>
  <option value="controlpanel" SELECTED>{"ControlPanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infovolumpanel">{"InfoVolumePanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infopanel">{"InfoPanel"|i18n("design/standard/content/datatype")}</option> 
    {/case}
{case match=infovolumpanel}
  <option value="imagewindow">{"ImageWindow"|i18n("design/standard/content/datatype")}</option>
  <option value="all">{"All"|i18n("design/standard/content/datatype")}</option>
  <option value="controlpanel">{"ControlPanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infovolumpanel" SELECTED>{"InfoVolumePanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infopanel">{"InfoPanel"|i18n("design/standard/content/datatype")}</option> 
    {/case}
{case match=infopanel}
  <option value="imagewindow">{"ImageWindow"|i18n("design/standard/content/datatype")}</option>
  <option value="all">{"All"|i18n("design/standard/content/datatype")}</option>
  <option value="controlpanel">{"ControlPanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infovolumpanel">{"InfoVolumePanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infopanel" SELECTED>{"InfoPanel"|i18n("design/standard/content/datatype")}</option> 
    {/case}
{case}
  <option value="imagewindow" SELECTED>{"ImageWindow"|i18n("design/standard/content/datatype")}</option>
  <option value="all">{"All"|i18n("design/standard/content/datatype")}</option>
  <option value="controlpanel">{"ControlPanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infovolumpanel">{"InfoVolumePanel"|i18n("design/standard/content/datatype")}</option>
  <option value="infopanel">{"InfoPanel"|i18n("design/standard/content/datatype")}</option> 
    {/case}
{/switch}
</select>
</div>
<div class="element">
<input type="checkbox" name="{$attribute_base}_data_media_is_autoplay_{$attribute.id}" value="{$attribute.content.is_autoplay}" {section show=$attribute.content.is_autoplay}checked{/section} />&nbsp;<label class="check">{"Autoplay"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
</div>
<div class="break"></div>
</div>

{/case}
{/switch}

{section show=or($attribute.content,$attribute.content.filename)}
<div class="block">
<div class="element">
<label>{"Existing filename"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.filename}</p>
</div>
<div class="element">
<label>{"Existing original filename"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.original_filename}</p>
</div>
<div class="element">
<label>{"Existing mime/type"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.mime_type}</p>
</div>
<div class="break"></div>
</div>
<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_delete_media]" value="{'Remove'|i18n('design/standard/content/datatype')}" />
</div>
{/section}
{/default}