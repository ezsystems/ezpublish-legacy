{*?template charset=utf8?*}
{let gallery_limit=8
     gallery_pre_items=2
     gallery_post_items=2}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />

<!-- Javascript START -->

<script language="JavaScript">
<!--
{literal}
function OpenWindow ( URL, WinName, Features ) {
	popup = window.open ( URL, WinName, Features );
	if ( popup.opener == null ) {
		remote.opener = window;
	}
	popup.focus();
}
{/literal}

// -->
</script>

<!-- Javascript END -->

{* check if we need a http-equiv refresh *}
{section show=$site.redirect}
<meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />
{/section}

<!-- Meta information START -->

{section name=meta loop=$site.meta}
<meta name="{$meta:key}" content="{$meta:item}" />
{/section}

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<meta name="generator" content="eZ publish" />

<!-- Meta information END -->

</head>

<body>

<!-- Top box START -->

<img src={"images/whiteboxlogo.png"|ezdesign} alt="White box - contemporary art gallery" />

<!-- Top box END -->
{let gallery_list=fetch(content,list,hash(parent_node_id,16))}
<table class="layout" width="700" cellpadding="1" cellspacing="0" border="0">
<tr>
    <td bgcolor="#cccccc">
    &nbsp;&nbsp;:: about<br /> 
    &nbsp;&nbsp;:: about<br /> 
    &nbsp;&nbsp;:: about<br /> 
    </td>
    <td bgcolor="#e8e8e8">
{section name=Gallery loop=$gallery_list}
  {section show=and(eq($DesignKeys:used.node,$Gallery:item.node_id),eq($DesignKeys:used.viewmode,'slideshow'))}
{*  &nbsp;<a href="/content/view/thumbnail/{$Gallery:item.node_id}">{$Gallery:item.name}</a>*}
  <strong>&nbsp;&nbsp;{$Gallery:item.name}</strong>
  {/section}
{/section}
    </td>
</tr>

{*
<tr>
    <td class="pathline" colspan="3">

    <p class="path">
    &nbsp;&nbsp;
    {section name=Path loop=$module_result.path}
        {section show=$Path:item.url}
        <a href="{$Path:item.url}">{$Path:item.text}</a>
        {section-else}
        {$Path:item.text}
        {/section}

        {delimiter}
	/
        {/delimiter}
    {/section}
    </p>

    </td>
</tr>
*}
<tr>
    <td class="mainarea" width="99%" valign="top">

<!-- Main area START -->

{$module_result.content}

<!-- Main area END -->

    </td>
<td width="20%" valign="top" bgcolor="#cccccc" class="links">
            <div align="right">
              <table width="142" border="0" cellpadding="4" cellspacing="1">
{section name=Gallery loop=$gallery_list}
{section show=and(eq($DesignKeys:used.node,$Gallery:item.node_id),eq($DesignKeys:used.viewmode,'slideshow'))}
<tr>
  <td bgcolor="#e2e2e2" class="links"><center>
    <table cellpadding="4" cellspacing="4" border="0">
    <tr>
    {let gallery_page=int(div($DesignKeys:used.view_offset,$gallery_limit))
         gallery_page_offset=mul($Gallery:gallery_page,$gallery_limit)
         gallery_req_offset=max(0,sub($Gallery:gallery_page_offset,$gallery_pre_items))
         gallery_req_limit=sum($gallery_limit,$gallery_post_items,sub($Gallery:gallery_page_offset,$Gallery:gallery_req_offset))
         gallery_item_list=fetch(content,list,hash(parent_node_id,$Gallery:item.node_id,offset,$Gallery:gallery_req_offset,limit,$Gallery:gallery_req_limit))}
    {section name=Item loop=$Gallery:gallery_item_list}

      {* Check for current image *}
{*      {section show=eq($module_result.view_parameters,$Gallery:Item:index)}
      <td>
      {section-else} *}
      <td valign="top" align="left">
{*      {/section} *}

      <a href={concat('content/view/slideshow/',$Gallery:item.node_id,'/offset/',sum($Gallery:Item:index,$Gallery:gallery_req_offset))|ezurl})>{node_view_gui view=small content_node=$Gallery:Item:item}</a>
      </td>
    {delimiter modulo=2}
    </tr>
    <tr>
    {/delimiter}
    {/section}

    {section loop=mod($Gallery:gallery_item_list,2)}
    <td>&nbsp;</td>
    {/section}

    {/let}
    </tr>
    </table></center>
  </td>
</tr>
{section-else}
                <tr>
                  <td bgcolor="#e2e2e2" class="links">&nbsp;<a href="/content/view/thumbnail/{$Gallery:item.node_id}">{$Gallery:item.name}</a></td>
                </tr>
{/section}

{/section}
              </table>
            </div>
            <p align="right">&nbsp;</p>
            </td>
{*
    <td bgcolor="#cccccc" valign="top" width="150">
    <form action="/content/search/" method="get">
    <label class="topline" for="Search">Search:</label><a class="topline" href="/content/advancedsearch/"><span class="small">Advanced search</span></a><div class="labelbreak"></div>
    <input type="text" size="10" name="SearchText" id="Search" value="" />
    <input class="button" name="SearchButton" type="submit" value="{"Search"|i18n('pagelayout')}" />
    </form>
    </td>
*}
</tr>
<tr>
    <td bgcolor="#e2e2e2" colspan="2">
    <div align="left" style="padding: 0.5em;">
    <p class="small"><a href="http://developer.ez.no">eZ publish&trade;</a> copyright &copy; 1999-2002 <a href="http://ez.no">eZ systems as</a></p>
    </div>
    </td>
</tr>
</table>
{/let}

</body>
</html>
{/let}