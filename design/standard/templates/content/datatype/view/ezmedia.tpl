{switch name=mediaType match=$attribute.contentclass_attribute.data_text1}
{case match=flash}
<object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="movie" value="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}">
<param name="quality" value="{$attribute.content.quality}">
<param name="play" value="{section show=$attribute.content.is_autoplay}true{/section}">
<param name="loop" value="{section show=$attribute.content.is_loop}true{/section}">
<embed src="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}"
 quality="{$attribute.content.quality}" pluginspage="{$attribute.content.pluginspage}"
 width="{$attribute.content.width}" height="{$attribute.content.height}" play="{section show=$attribute.content.is_autoplay}true{/section}"
 loop="{section show=$attribute.content.is_loop}true{/section}" >
</embed> 
</object>
{/case}
{case match=quick_time}
<object
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="movie" value="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}">
<param name="controller" value="{section show=$attribute.content.has_controller}true{/section}">
<param name="play" value="{section show=$attribute.content.is_autoplay}true{/section}">
<param name="loop" value="{section show=$attribute.content.is_loop}true{/section}">
<embed src="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}"
 pluginspage="{$attribute.content.pluginspage}"
 width="{$attribute.content.width}" height="{$attribute.content.height}" play="{section show=$attribute.content.is_autoplay}true{/section}" 
 loop="{section show=$attribute.content.is_loop}true{/section}" controller="{section show=$attribute.content.has_controller}true{/section}" >
</embed> 
</object>
{/case}
{case match=windows_media_player}
<object ID="MediaPlayer"  CLASSID="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" STANDBY="Loading Windows Media Player components..." type="application/x-oleobject"
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="filename" value="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}">
<param name="autostart" value="{$attribute.content.is_autoplay}">
<param name="showcontrols" value="{$attribute.content.has_controller}">
<embed src="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}"
 type="application/x-mplayer2" pluginspage="{$attribute.content.pluginspage}"
 width="{$attribute.content.width}" height="{$attribute.content.height}" autostart="{$attribute.content.is_autoplay}" 
 showcontrols="{$attribute.content.has_controller}" >
</embed> 
</object>
{/case}
{case match=real_player}
<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" 
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="src" value="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}">
<param name="controls" value="{$attribute.content.controls">
<param name="autostart" value="{section show=$attribute.content.is_autoplay}true{/section}">
<embed src="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}"
 pluginspage="{$attribute.content.pluginspage}"
 width="{$attribute.content.width}" height="{$attribute.content.height}" autostart="{section show=$attribute.content.is_autoplay}true{/section}" 
 controls="{$attribute.content.controls}" >
</embed> </object>
{/case}
{/switch}