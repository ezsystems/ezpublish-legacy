{*?template charset=utf8?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />

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

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="700" border="0" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 1px; border-color: black;" bgcolor="#cccccc">
<tr>
   <th>
   Quick links
   </th>
</tr>
<tr>
    <td >
    <a href="/content/view/full/31/">Crossroads Forum</a>
    </td>
    <td >
    <a href="/content/view/news/26">News 24</a>
    </td>
    <td >
    <a href="/content/view/thumbnail/18/">Whitebox art gallery</a>
    </td>
    <td >
    <a href="/content/view/full/65/">the Book corner</a>
    </td>
</tr>
</table>

{let folder_list=fetch(content,list,hash(parent_node_id,24))}
{let news_list=fetch(content,tree,hash(parent_node_id,24,limit,3,sort_by,array(array(published)),class_id,2))}

<table width="700" height="482" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr> 
    <td width="700" height="67" valign="bottom" bgcolor="#FFFFFF"><span class="links"> 
      </span><a href="/content/view/full/24/"><img src={"images/news_top.gif"|ezdesign} width="700" height="67" border="0"></a></td>
  </tr>
<tr> 

<td  valign="top" bgcolor="#FFFFFF">

<table width="100%"  border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">
        <tr> 
          <td width="100" valign="top" bordercolor="#FFFFFF" bgcolor="#FF9900"> 
            <table width="84" border="0" align="center" cellpadding="3" cellspacing="1">

{section name=Folder loop=$folder_list}

              <tr> 
                <td width="76" bgcolor="#FFD2A6" class="links"> 
                     <a href="/content/view/news/{$Folder:item.node_id}">{$Folder:item.name}</a>  <font size="2">&nbsp;</font></td>
              </tr>
{/section}    <tr> 
                <td valign="top" bgcolor="#FF9900"> 


                  <form action="/content/search/" method="get">
                   <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br>
                   
                 search:</font> <font size="2"> 
                             	<input  type="text" size="10" name="SearchText" id="Search" value="" />
                         	<input class="button" name="SearchButton" type="submit" value="{"Search"|i18n('pagelayout')}" />

                    </font></p>
                </td>
              </tr>
            </table>
          </td>
          

     <td width="600" align="left" valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
       <table width="600" border="0" cellspacing="0" cellpadding="3">
          <tr>
             <td>
              <table width="616" border="0" cellspacing="0" cellpadding="3">
               <tr> 
                <td width="600" bgcolor="#000000" class="links"> 
                  <font color="#FFFFFF">&gt;{currentdate()|l10n(datetime)}</font></td>
              </tr>
              <tr>
                <td height="17" class="links">

		    <p class="path">
                       &nbsp;&nbsp;
                 {section name=Path loop=$module_result.path offset=2}
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
               </table>
            </td>
         </tr>
       <tr>
    <td width="600" border="0" align="left" cellpadding="0" cellspacing="0" >


  <table width="600" border="0" align="left" cellpadding="0" cellspacing="0"> 
    <tr>
<td width="20 ">
  <table width="20"  border="0"  cellpadding="0" cellspacing="0">
  </table>

</td>
      <td valign="top">      
      {$module_result.content}
      </td>

         <td width="145" rowspan="4" bgcolor="#FFF4EA" align="right" valign="top" class="links">
                <table width="145" border="0" align="left">
               <tr>             
                  <td valign="top" bgcolor="#FFFFFF">
                    <table width="145" border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                       <td width="145" height="19" align="left" valign="top" bgcolor="#663366"> 
                       <div align="left">&nbsp;&nbsp;&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#FFFFFF">Latest 
                          update....</font> </strong></font></div>
                       </td>
                    </tr>
                  </table>
                 </td>
              </tr>
{section name=News loop=$news_list max=1}
<tr>
<td width="125" valign="top"  >
       <a href="/content/view/news/{$News:item.node_id}">{$News:item.name}</a>
  	{node_view_gui view=small content_node=$News:item}

</td>
</tr>
{/section} 
{section name=News loop=$news_list offset=1}
              <tr> 
                <td  width="125"  bgcolor="#FFF4EA" class="links"> 

                     <a href="/content/view/news/{$News:item.node_id}">{$News:item.name}</a>
                </td>
              </tr>
{/section}   
       </table>
           </td>
       </tr>
   </table>
    </td>
</tr>
<tr>
<td>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td bgcolor="#000000">&nbsp;</td>
           </tr>
          </table>
</td>
</tr>
</table>
</body>
</html>
