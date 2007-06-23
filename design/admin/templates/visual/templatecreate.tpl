{if eq( $error, 'permission_denied' )}

<div class="message-error">
 <h2>{'Could not create template, permission denied.'|i18n( 'design/admin/visual/templatecreate' )}</h2>
</div>

{/if}

{if eq( $error, 'invalid_name' )}

<div class="message-error">
 <h2>{'Invalid name. You can only use the characters a-z, numbers and _.'|i18n( 'design/admin/visual/templatecreate' )}</h2>
</div>

{/if}

<form method="post" action={concat( '/visual/templatecreate', $template )|ezurl()}>
 <input type="hidden" name="type" value="{$type}" />
 <input type="hidden" name="location" value="{$location}" />
 <div class="context-block">

  <div class="box-header">
   <div class="box-tc">
    <div class="box-ml">
     <div class="box-mr">
      <div class="box-tl">
       <div class="box-tr">

{if $type|eq( 'design' )}

        <h1 class="context-title">{'Create new design template for <%template_name> (siteaccess <%current_siteaccess>)'|i18n( 'design/admin/visual/templatecreate',, hash( '%template_name', $template, '%current_siteaccess', $current_siteaccess ) )|wash()}</h1>

{else}

        <h1 class="context-title">{'Create new template override for <%template_name> (siteaccess <%current_siteaccess>)'|i18n( 'design/admin/visual/templatecreate',, hash( '%template_name', $template, '%current_siteaccess', $current_siteaccess ) )|wash}</h1>

{/if}

        <div class="header-mainline"></div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>

  <div class="box-ml">
   <div class="box-mr">
    <div class="box-content">

{if $type|eq( 'design' )}

     <div class="context-attributes">
      <p>

    {if $designs|count()|gt( 0 )}

        {'The newly created template file will be '|i18n( 'design/admin/visual/templatecreate' )}

       <select name="OverridePath">

        {foreach $designs as $design => $path}

        <option value="{$path}" {cond( or( $site_design|eq( $design ), $path|eq( $selected_location ) ), 'selected="selected"' )}>{$path}</option>

        {/foreach}

       </select>

    /templates{$template}.

      </p>
      <div class="block">
       <label>{'Base template on'|i18n( 'design/admin/visual/templatecreate' )}:</label>
       <input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/admin/visual/templatecreate' )}<br />
       <input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/admin/visual/templatecreate' )}<br />
      </div>

    {else}

       {'There is no design available in %location to create the template %template in. Perhaps it already exists in all designs or the webserver does not have write permissions.'|i18n( 'design/admin/visual/templatecreate',, hash( '%location', $location, '%template', concat( '/templates', $template ) ) )}

      </p>

    {/if}

     </div>

{else}

     <div class="context-attributes">
      <p>

    {'The newly created template file will be placed in'|i18n( 'design/admin/visual/templatecreate' )}

      </p>
      <p>
       <select name="OverridePath">

    {foreach $designs as $design => $path}

        <option value="{$path}" {cond( or( $site_design|eq( $design ), $path|eq( $selected_location ) ), 'selected="selected"' )}>{$path}</option>

    {/foreach}

        </select>

/override/templates/.

      </p>
      <div class="block">
       <label>{'Filename'|i18n( 'design/admin/visual/templatecreate' )}:</label>
       <input class="halfbox" type="text" name="TemplateName" value="{$template_name}" />.tpl
      </div>

    {switch match=$template_type}

        {case match='node_view'}

      <div class="block">
       <label>{'Override keys'|i18n( 'design/admin/visual/templatecreate' )}:</label>
       <table>
        <tr>
         <td>{'Class'|i18n( 'design/admin/visual/templatecreate' )}:</td>
         <td>
          <select name="Match[class_identifier]">
           <option value="-1">{'All classes'|i18n( 'design/admin/visual/templatecreate' )}</option>

            {def $contentClass=''}

            {foreach fetch('content', 'can_instantiate_class_list') as $Class}

                {set $contentClass=fetch( content, class, hash( class_id, $Class.id ) )}

           <option value="{$contentClass.identifier}" {cond( eq( $contentClass.id, $override_keys['classID'] ), 'selected="selected"' )}>{$Class.name|wash()}</option>

            {/foreach}

          </select>
         </td>
        </tr>
        <tr>
         <td>{'Section'|i18n( 'design/admin/visual/templatecreate' )}:</td>
         <td>
          <select name="Match[section]">
           <option value="-1">{'All sections'|i18n( 'design/admin/visual/templatecreate' )}</option>

            {foreach fetch( 'content', 'section_list' ) as $Section}

           <option value="{$Section.id}">{$Section.name|wash()}</option>

            {/foreach}

          </select>
         </td>
        </tr>
        <tr>
         <td>{'Node ID'|i18n( 'design/admin/visual/templatecreate' )}:</td>
         <td><input type="text" size="5" value="{$override_keys['nodeID']}" name="Match[node]" /></td>
        </tr>
        <tr>
         <td>{'Parent Node ID'|i18n( 'design/admin/visual/templatecreate' )}:</td>
         <td><input type="text" size="5" value="{$override_keys['parentNodeID']}" name="Match[parent_node]" /></td>
        </tr>
       </table>
      </div>
      <div class="block">
       <label>{'Base template on'|i18n( 'design/admin/visual/templatecreate' )}:</label>
       <input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/admin/visual/templatecreate' )}<br />
       <input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/admin/visual/templatecreate' )}<br />
       <input type="radio" name="TemplateContent" value="ContainerTemplate" />{'Container (with children)'|i18n( 'design/admin/visual/templatecreate' )}<br />
       <input type="radio" name="TemplateContent" value="ViewTemplate" />{'View (without children)'|i18n( 'design/admin/visual/templatecreate' )}<br />
      </div>

        {/case}

        {case match='object_view'}

      <div class="objectheader">
       <h2>{'Override keys'|i18n( 'design/admin/visual/templatecreate' )}</h2>
      </div>
      <div class="object">
       <table>
        <tr>
         <td>
          <p>{'Class'|i18n( 'design/admin/visual/templatecreate' )}</p>
         </td>
         <td>
          <select name="Match[class_identifier]">
           <option value="-1">{'Any'|i18n( 'design/admin/visual/templatecreate' )}</option>

            {foreach fetch('content', 'can_instantiate_class_list') as $Class}

           <option value="{fetch( content, class, hash( class_id, $Class.id ) ).identifier}">{$Class.name|wash()}</option>

            {/foreach}

          </select>
         </td>
        </tr>
        <tr>
         <td>
          <p>{'Section'|i18n( 'design/admin/visual/templatecreate' )}</p>
         </td>
         <td>
          <select name="Match[section]">
           <option value="-1">{'Any'|i18n( 'design/admin/visual/templatecreate' )}</option>

            {foreach fetch( 'content', 'section_list' ) as $Section}

           <option value="{$Section.id}">{$Section.name|wash()}</option>

            {/foreach}

          </select>
         </td>
        </tr>
        <tr>
         <td>
          <p>{'Object'|i18n( 'design/admin/visual/templatecreate' )}</p>
         </td>
         <td>
          <input type="text" size="5" value="" name="Match[object]" />
         </td>
        </tr>
       </table>
      </div>
      <div class="objectheader">
       <h2>{'Base template on'|i18n( 'design/admin/visual/templatecreate' )}</h2>
      </div>
      <div class="object">
       <input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/admin/visual/templatecreate' )}<br />
       <input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/admin/visual/templatecreate' )}<br />
       <input type="radio" name="TemplateContent" value="ContainerTemplate" />{'Container (with children)'|i18n( 'design/admin/visual/templatecreate' )}<br />
       <input type="radio" name="TemplateContent" value="ViewTemplate" />{'View (without children)'|i18n( 'design/admin/visual/templatecreate' )}<br />
      </div>

        {/case}

        {case match='pagelayout'}

      <div class="objectheader">
       <h2>{'Base template on'|i18n( 'design/admin/visual/templatecreate' )}</h2>
      </div>
      <div class="object">
       <input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/admin/visual/templatecreate' )}<br />
       <input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/admin/visual/templatecreate' )}<br />
      </div>

        {/case}

        {case}

      <div class="objectheader">
       <h2>{'Base template on'|i18n( 'design/admin/visual/templatecreate' )}</h2>
      </div>
      <div class="object">
       <input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/admin/visual/templatecreate' )}<br />
       <input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/admin/visual/templatecreate' )}<br />
      </div>

        {/case}

    {/switch}

     </div>

{/if}

    </div>
   </div>
  </div>

  <div class="controlbar">
   <div class="box-bc">
    <div class="box-ml">
     <div class="box-mr">
      <div class="box-tc">
       <div class="box-bl">
        <div class="box-br">
         <div class="block">
          <input class="button" type="submit" name="CreateOverrideButton" value="{'OK'|i18n( 'design/admin/visual/templatecreate' )}" />
          <input class="button" type="submit" name="CancelOverrideButton" value="{'Cancel'|i18n( 'design/admin/visual/templatecreate' )}" />
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>

 </div>
</form>
