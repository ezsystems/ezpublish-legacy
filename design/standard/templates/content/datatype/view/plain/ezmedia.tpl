{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{switch name=mediaType match=$attribute.contentclass_attribute.data_text1}
{case match=flash}
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="movie" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
<param name="quality" value="{$attribute.content.quality}" />
<param name="play" value="{if $attribute.content.is_autoplay}true{/if}" />
<param name="loop" value="{if $attribute.content.is_loop}true{/if}" />
<embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
 quality="{$attribute.content.quality}" pluginspage="{$attribute.content.pluginspage}"
 width="{$attribute.content.width}" height="{$attribute.content.height}" play="{if $attribute.content.is_autoplay}true{/if}"
 loop="{if $attribute.content.is_loop}true{/if}" >
</embed> 
</object>
{/case}
{case match=quick_time}
<object
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="movie" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
<param name="controller" value="{if $attribute.content.has_controller}true{/if}" />
<param name="play" value="{if $attribute.content.is_autoplay}true{/if}" />
<param name="loop" value="{if $attribute.content.is_loop}true{/if}" />
<embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
 pluginspage="{$attribute.content.pluginspage}"
 width="{$attribute.content.width}" height="{$attribute.content.height}" play="{if $attribute.content.is_autoplay}true{/if}" 
 loop="{if $attribute.content.is_loop}true{/if}" controller="{if $attribute.content.has_controller}true{/if}" >
</embed> 
</object>
{/case}
{case match=windows_media_player}
<object ID="MediaPlayer"  CLASSID="CLSID:6BF52A52-394A-11D3-B153-00C04F79FAA6" STANDBY="Loading Windows Media Player components..." type="application/x-oleobject"
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="filename" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
<param name="autostart" value="{$attribute.content.is_autoplay}" />
<param name="showcontrols" value="{$attribute.content.has_controller}" />
<embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
 type="application/x-mplayer2" pluginspage="{$attribute.content.pluginspage}"
 width="{$attribute.content.width}" height="{$attribute.content.height}" autostart="{$attribute.content.is_autoplay}" 
 showcontrols="{$attribute.content.has_controller}" >
</embed> 
</object>
{/case}
{case match=real_player}
<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" 
width="{$attribute.content.width}" height="{$attribute.content.height}">
<param name="src" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
<param name="controls" value="{$attribute.content.controls}" />
<param name="autostart" value="{if $attribute.content.is_autoplay}true{/if}" />
<embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
 pluginspage="{$attribute.content.pluginspage}"
 width="{$attribute.content.width}" height="{$attribute.content.height}" autostart="{if $attribute.content.is_autoplay}true{/if}" 
 controls="{$attribute.content.controls}" >
</embed> </object>
{/case}
    {case match=silverlight}
    {literal}
    <script type="text/javascript">
    //<![CDATA[
        function onErrorHandler(sender, args) { }
        function onResizeHandler(sender, args) { }
    //]]>
    </script>
    {/literal}

    <div id="silverlightControlHost">
      <!-- Silverlight plug-in control -->
        <object data="data:application/x-silverlight," type="application/x-silverlight-2-b1" {if $attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/if} {if $attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/if}>
            <param name="source" value="{concat( "content/download/", $attribute.contentobject_id, "/", $attribute.content.contentobject_attribute_id, "/", $attribute.content.original_filename)|ezurl( 'no' )}" />
            <param name="onError" value="onErrorHandler" />
            <param name="onResize" value="onResizeHandler" />
            <a href="http://go.microsoft.com/fwlink/?LinkID=108182" style="text-decoration: none;">
                <img src="http://go.microsoft.com/fwlink/?LinkId=108181" alt="Get Microsoft Silverlight" style="border-style: none;" />
            </a>
        </object>
        <iframe style="visibility: hidden; height: 0; width: 0; border: 0px;"></iframe>
    </div>
    {/case}
{/switch}