{switch name=mediaType match=$attribute.contentclass_attribute.data_text1}
{case match=flash}
<object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="movie" value="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}">
<param name="quality" value="{$attribute.content.quality}">
<param name="play" value="{section show=$attribute.content.is_autoplay}true{/section}">
<param name="loop" value="{section show=$attribute.content.is_loop}true{/section}">
<embed src="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}"
 quality="{$attribute.content.quality}" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
 width="{$attribute.content.width}" height="{$attribute.content.height}" play="{section show=$attribute.content.is_autoplay}true{/section}"
 loop="{section show=$attribute.content.is_loop}true{/section}" >
</embed> </object>
{/case}
{case match=quick_time}
<object
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="movie" value="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}">
<param name="controller" value="{section show=$attribute.content.has_controller}true{/section}">
<param name="play" value="{section show=$attribute.content.is_autoplay}true{/section}">
<param name="loop" value="{section show=$attribute.content.is_loop}true{/section}">
<embed src="/content/download/{$attribute.content.contentobject_attribute_id}/{$attribute.content.version}/{$attribute.content.original_filename}"
 pluginspage="http://quicktime.apple.com"
 width="{$attribute.content.width}" height="{$attribute.content.height}" play="{section show=$attribute.content.is_autoplay}true{/section}" 
 loop="{section show=$attribute.content.is_loop}true{/section}" controller="{section show=$attribute.content.has_controller}true{/section}" >
</embed> </object>
{/case}
{/switch}