<form action={"/visual/templatelist"|ezurl} method="post">
 <input type="hidden" name="filterString" value="{$filterString}" />
 <div class="context-block">

{* DESIGN: Header START *}

  <div class="box-header">
   <div class="box-tc">
    <div class="box-ml">
     <div class="box-mr">
      <div class="box-tl">
       <div class="box-tr">
        <h1 class="context-title">{'Template management'|i18n( 'design/admin/visual/menuconfig' )}</h1>

{* DESIGN: Mainline *}

        <div class="header-mainline"></div>

{* DESIGN: Header END *}

       </div>
      </div>
     </div>
    </div>
   </div>
  </div>

{* DESIGN: Content START *}

  <div class="box-ml">
   <div class="box-mr">
    <div class="box-content">
     <div class="context-attributes">
      <label>{'SiteAccess'|i18n( 'design/admin/visual/menuconfig' )}:</label>
      <select name="CurrentSiteAccess">

{foreach $siteaccess_list as $siteaccess}

       <option value="{$siteaccess}"{cond( eq( $current_siteaccess, $siteaccess ), ' selected="selected"' )}>
        {$siteaccess}
       </option>

{/foreach}

      </select>&nbsp;
     </div>

{* DESIGN: Content END *}

    </div>
   </div>
  </div>

  <div class="controlbar">

{* DESIGN: Control bar START *}

   <div class="box-bc">
    <div class="box-ml">
     <div class="box-mr">
      <div class="box-tc">
       <div class="box-bl">
        <div class="box-br">
         <div class="block">
          <input class="button" type="submit" value="{'Set'|i18n( 'design/admin/visual/menuconfig' )}" name="SelectCurrentSiteAccessButton" />
         </div>

{* DESIGN: Control bar END *}
 
        </div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
</form>

<div class="context-block">

{* DESIGN: Header START *}

 <div class="box-header">
  <div class="box-tc">
   <div class="box-ml">
    <div class="box-mr">
     <div class="box-tl">
      <div class="box-tr">

{* DESIGN: Mainline and Template filter form START *}

       <table cellspacing="0" width="100%" >
        <tr>
         <th align="left">
	  <h1 class="context-title">{'Template list'|i18n( 'design/admin/visual/templatelist' )}</h1>
         </th>
         <th align="right" >
	  <div class="content-search" >
       	   <form action={"/visual/templatelist"|ezurl} method="post">
       	    <input class="halfbox" type="text" size="15" name="filterString" id="Filter" value="{$filterString}" />
       	    <input class="button" name="FilterButton" type="submit" value="{'Filter'|i18n('design/standard/layout')}" />&nbsp; &nbsp;
       	   </form>
	  </div>
         </th>
        </tr>
       </table>

{* DESIGN: Mainline and Template filter form END *}

       <div class="header-mainline"></div>

{* DESIGN: Header END *}

      </div>
     </div>
    </div>
   </div>
  </div>
 </div>

{* DESIGN: Content START *}

 <div class="box-bc">
  <div class="box-ml">
   <div class="box-mr">
    <div class="box-bl">
     <div class="box-br">
      <div class="box-content">

{def $template_list_limit=ezpreference( 'template_list_limit' )}

{if $template_list_limit|eq( '' )}

    {set template_list_limit=10}

{/if}

       <div class="context-toolbar">
        <div class="block">
         <div class="left">
          <p>
{switch match=$template_list_limit}
{case match=10}
           <span class="current">10</span>
           <a href="/user/preferences/set/template_list_limit/25">25</a>
           <a href="/user/preferences/set/template_list_limit/50">50</a>
{/case}
{case match=25}
           <a href="/user/preferences/set/template_list_limit/10">10</a>
           <span class="current">25</a>
           <a href="/user/preferences/set/template_list_limit/50">50</a>
{/case}
{case match=50}
           <a href="/user/preferences/set/template_list_limit/10">10</a>
           <a href="/user/preferences/set/template_list_limit/25">25</a>
           <span class="current">50</a>
{/case}
{/switch}
          </p>
         </div>
        </div>
       </div>
       <div class="break"></div>

       <table class="list" cellspacing="0">
        <tr>
         <th>&nbsp;</th>
         <th>{'Template'|i18n( 'design/admin/visual/templatelist' )}</th>
         <th>{'Design resource'|i18n( 'design/admin/visual/templatelist' )}</th>
        </tr>

{foreach $template_array as $template max $template_list_limit offset $view_parameters.offset sequence array( bglight, bgdark ) as $bg}

        <tr class="{$bg}">
         <td>
          <form method="post" action={concat( '/visual/templateedit/', $template.base_dir, $template.template )|ezurl()}>
           <input type="hidden" name="RedirectToURI" value="/visual/templatelist">
           <input type="image" src={'edit.gif'|ezimage()} alt="Edit" />
          </form>
         </td>
         <td>
          <a href={concat( '/visual/templateview', $template.template )|ezurl()} title="{'Manage overrides for template.'|i18n( 'design/admin/visual/templatelist' )}">{$template.template}</a>
         </td>
         <td>{$template.base_dir}</td>
        </tr>

{/foreach}

       </table>
       <div class="context-toolbar">

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/visual/templatelist'
         page_uri_suffix=concat('?filterString=',$filterString|urlencode )	
         item_count=$template_count
         view_parameters=$view_parameters
         item_limit=$template_list_limit}

       </div>

{* DESIGN: Content END *}

      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
<div class="context-block">

{* DESIGN: Header START *}

 <div class="box-header">
  <div class="box-tc">
   <div class="box-ml">
    <div class="box-mr">
     <div class="box-tl">
      <div class="box-tr">
       <h2 class="context-title">{'Most common templates'|i18n( 'design/admin/visual/templatelist' )}</h2>

{* DESIGN: Mainline *}

       <div class="header-subline"></div>

{* DESIGN: Header END *}

      </div>
     </div>
    </div>
   </div>
  </div>
 </div>

{* DESIGN: Content START *}

 <div class="box-bc">
  <div class="box-ml">
   <div class="box-mr">
    <div class="box-bl">
     <div class="box-br">
      <div class="box-content">
       <table class="list" cellspacing="0">
        <tr>
         <th>{'Template'|i18n( 'design/admin/visual/templatelist' )}</th>
         <th>{'Design resource'|i18n( 'design/admin/visual/templatelist' )}</th>
        </tr>

{foreach $most_used_template_array as $template sequence array( bglight, bgdark ) as $bg}

        <tr class="{$bg}">
         <td>
          <a href={concat( '/visual/templateview', $template.template )|ezurl()} title="{'Manage overrides for template.'|i18n( 'design/admin/visual/templatelist' )}">{$template.template}</a>
         </td>
         <td>{$template.base_dir}</td>
        </tr>

{/foreach}

       </table>

{* DESIGN: Content END *}

      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
