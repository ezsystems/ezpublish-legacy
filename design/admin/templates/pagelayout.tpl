<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
{include uri="design:page_head.tpl"}

{cache-block keys=array('navigation_tabs',$navigation_part.identifier,$current_user.contentobject_id)}
{* Cache header for each navigation part *}

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />


</head>

<body>
{* Top box START *}

<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #4272b4; background-image:url('{"bgimage.gif"|ezimage(no)}'); background-position: right top; background-repeat: no-repeat;">
<tr>
    <td style="padding: 4px" colspan="13">
    <table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td width="5" style="background-image:url('{"tbox-top-left.gif"|ezimage(no)}'); background-repeat: no-repeat;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
        <td style="border-top: solid 1px #789dce;" width="99%">
        <img src={"1x1.gif"|ezimage} alt="" width="1" height="1" /></td>
        <td width="5" style="background-image:url('{"tbox-top-right.gif"|ezimage(no)}'); background-repeat: no-repeat;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
    </tr>
    <tr>
        <td style="border-left: solid 1px #789dce;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
        <td>
        <table width="100%">
        <tr> 
            <td>
	    <img src={"logo.gif"|ezimage} alt="" /></td>
            <td>
            &nbsp;&nbsp;
            </td>
            <td valign="top">
                <form action={"/content/search/"|ezurl} method="get" style="margin-top: 0px; margin-bottom: 0px; padding: 0px;">
                    <table cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td>
                            <input class="searchbox" type="text" size="20" name="SearchText" id="Search" value="" />
                        </td>  
                        <td>
                            <input class="searchbutton" name="SearchButton" type="submit" value="{'Search'|i18n('design/standard/layout')}" />
                        </td>
                    </tr>
                    </table>
                </form>
            </td>
            <td valign="center">
	        {section show=fetch('content', 'can_instantiate_classes')}
	        <form method="post" action={"content/action"|ezurl}>
                    <table cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td>
                        <select name="ClassID" class="classcreate">
	                    {section name=Classes loop=fetch('content', 'can_instantiate_class_list')}
                            <option value="{$Classes:item.id}">{$Classes:item.name|wash}</option>
                            {/section}
                         </select>
                        </td>
			<td>
                            <input class="classbutton" type="submit" name="NewButton" value="{'New'|i18n('design/standard/node/view')}" />
                        </td>
                    </tr>
                    </table>    
                </form>
                {/section}
            </td>  
            <td align="right">
      {section show=eq($current_user.contentobject_id,$anonymous_user_id)}
      <a class="leftmenuitem"  href={"/user/login/"|ezurl}>{'Login'|i18n('design/standard/layout')}</a>
      {section-else}
      <a class="leftmenuitem" href={"/user/logout/"|ezurl}>{'Logout'|i18n('design/standard/layout')} ({$current_user.contentobject.name|wash}) </a> 
      {/section}
            </td> 
        </tr>  
        </table>
        </td>
        <td style="border-right: solid 1px #789dce;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
    </tr>
    <tr>
        <td style="background-image:url('{"tbox-bottom-left.gif"|ezimage(no)}'); background-repeat: no-repeat;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
        <td style="border-bottom: solid 1px #789dce;">
        <img src={"1x1.gif"|ezimage} alt="" width="1" height="1" /></td>
        <td style="background-image:url('{"tbox-bottom-right.gif"|ezimage(no)}'); background-repeat: no-repeat;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
    </tr>
    </table>

    </td>
</tr>
<tr>
    <td colspan="13">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="5" /></td>
</tr>
<tr>
    <td class="headlogo" width="130">
    {* Admin logo area *}
    &nbsp;
     </td>
    <td class="headlink" width="66">
    {* Content menu *}

    {section show=eq($navigation_part.identifier,'ezcontentnavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Content'|i18n('design/admin/layout') menu_url=concat("/content/view/full/",ezini('NodeSettings','RootNode','content.ini'))}</td>
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Content'|i18n('design/admin/layout') menu_url=concat("/content/view/full/",ezini('NodeSettings','RootNode','content.ini'))}</td>
    {/section}

    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>
    <td class="headlink" width="66">
    {* Media menu *}
    {section show=eq($navigation_part.identifier,'ezmedianavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Media'|i18n('design/admin/layout') menu_url=concat("/content/view/full/",ezini('NodeSettings','MediaRootNode','content.ini'))}</td>
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Media'|i18n('design/admin/layout') menu_url=concat("/content/view/full/",ezini('NodeSettings','MediaRootNode','content.ini'))}</td>
    {/section}

    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>
    <td class="headlink" width="66">
    {* Shop menu *}
    {section show=eq($navigation_part.identifier,'ezshopnavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Shop'|i18n('design/admin/layout') menu_url="/shop/orderlist/"}</td>
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Shop'|i18n('design/admin/layout') menu_url="/shop/orderlist/"}</td>
    {/section}
    
    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>

    <td class="headlink" width="66">

    {* Users menu *}
    {section show=eq($navigation_part.identifier,'ezusernavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Users'|i18n('design/admin/layout') menu_url="/content/view/full/5/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Users'|i18n('design/admin/layout') menu_url="/content/view/full/5/"}
    {/section}
    
    </td>

    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>

    <td class="headlink" width="66">

    {* Set up menu *}
    {section show=eq($navigation_part.identifier,'ezsetupnavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Set up'|i18n('design/admin/layout') menu_url="/setup/menu/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Set up'|i18n('design/admin/layout') menu_url="/setup/menu/"}
    {/section}

    </td>

    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>

    <td class="headlink" width="66">

    {* Personal *}
    {section show=eq($navigation_part.identifier,'ezmynavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Personal'|i18n('design/admin/layout') menu_url="/content/draft/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Personal'|i18n('design/admin/layout') menu_url="/content/draft/"}
    {/section}

    </td>
   <td class="headlogo" width="500">
   &nbsp;</td>
</tr>
<tr>
    <td colspan="13" style="background-image:url('{"bgtilelight.gif"|ezimage(no)}'); background-repeat: repeat;">
    <img src={"1x1.gif"|ezimage} alt="" width="1" height="8" /></td>
</tr>

{/cache-block}

{* Top box END *}

<tr>
    <td rowspan="2" width="130" valign="top" style="padding-right: 0px; padding-left: 0px; padding-top: 0px; background-image:url('{"bgtilelight.gif"|ezimage(no)}'); background-repeat: repeat;">

{* Left menu START *}

{section show=eq($navigation_part.identifier,'ezcontentnavigationpart')}
{include uri="design:parts/content/menu.tpl"}
{/section}

{section show=eq($navigation_part.identifier,'ezmedianavigationpart')}
{include uri="design:parts/media/menu.tpl"}
{/section}

{cache-block keys=array($current_user.contentobject_id,ezpreference('bookmark_menu'),ezpreference('history_menu'),ezpreference('advanced_menu'),$navigation_part.identifier)}

{section show=eq($navigation_part.identifier,'ezshopnavigationpart')}
{include uri="design:parts/shop/menu.tpl"}
{/section}

{section show=eq($navigation_part.identifier,'ezusernavigationpart')}
{include uri="design:parts/user/menu.tpl"}
{/section}

{section show=eq($navigation_part.identifier,'ezsetupnavigationpart')}
{include uri="design:parts/setup/menu.tpl"}
{/section}


{section show=eq($navigation_part.identifier,'ezmynavigationpart')}
{include uri="design:parts/my/menu.tpl"}
{/section}


{* Left menu END *}
</td>
    <td class="mainarea" colspan="12"  valign="top"  style="background-color: #ffffff; background-image:url('{"corner.gif"|ezimage(no)}'); background-repeat: no-repeat; background-position: left top;">

{/cache-block}

    {include uri="design:page_toppath.tpl"}


{* Main area START *}

{include uri="design:page_mainarea.tpl"}

{* Main area END *}

    </td>
</tr>
<tr>
    <td bgcolor="#ffffff" colspan="12" valign="bottom">
    <div align="center" style="padding-top: 0.5em;">
    <p class="small"><a href="http://ez.no">eZ publish&trade;</a> copyright &copy; 1999-2004 <a href="http://ez.no">eZ systems as</a></p>
    </div>
    </td>
</tr>
</table>


<!--DEBUG_REPORT-->

</body>
</html>
