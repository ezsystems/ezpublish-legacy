{section show=eq( $error, 'permission_denied' )}
<div class="message-error">
<h2>{'Could not create template, permission denied.'|i18n( 'design/admin/setup/templatecreate' )}</h2>
</div>
{/section}

{section show=eq( $error, 'invalid_name' )}
<div class="message-error">
<h2>{'Invalid name. You can only use the characters a-z, numbers and _.'|i18n( 'design/admin/setup/templatecreate' )}</h2>
</div>
{/section}

<form method="post" action={concat( '/setup/templatecreate', $template )|ezurl}>

<div class="context-block">
<h2 class="context-title">{'Create new template override for <%template_name>'|i18n( 'design/admin/setup/templatecreate',, hash( '%template_name', $template ) )|wash}</h2>

<div class="context-attributes">

<p>{'The newly created template file will be placed in'|i18n( 'design/admin/setup/templatecreate' )} design/{$site_design}/override/templates/.</p>

<div class="block">
    <label>{'Filename'|i18n( 'design/admin/setup/templatecreate' )}</label>
    <input class="halfbox" type="text" name="TemplateName" value="{$template_name}" />.tpl
</div>


{switch match=$template_type}
{case match='node_view'}
<div class="block">
<label>{'Override keys'|i18n( 'design/admin/setup/templatecreate' )}</label>

<table>
<tr>
    <td>{'Class'|i18n( 'design/admin/setup/templatecreate' )}:</td>
    <td>
    <select name="Match[class_identifier]">
        <option value="-1">{'All classes'|i18n( 'design/admin/setup/templatecreate' )}</option>
        {section name=Class loop=fetch('content', 'can_instantiate_class_list')}
        <option value="{fetch( content, class, hash( class_id, $Class:item.id ) ).identifier}">{$Class:item.name}</option>
        {/section}
    </select>
    </td>
</tr>
<tr>
    <td>{'Section'|i18n( 'design/admin/setup/templatecreate' )}:</td>
    <td>
    <select name="Match[section]">
        <option value="-1">{'All sections'|i18n( 'design/admin/setup/templatecreate' )}</option>
        {section name=Section loop=fetch( 'content', 'section_list' )}
            <option value="{$:item.id}">{$:item.name}</option>
        {/section}
    </select>
    </td>
</tr>
<tr>
    <td>{'Node ID'|i18n( 'design/admin/setup/templatecreate' )}:</td>
    <td><input type="text" size="5" value="" name="Match[node]" /></td>
</tr>
</table>
</div>


<div class="block">
<label>{'Base template on'|i18n( 'design/admin/setup/templatecreate' )}</label>
<input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/admin/setup/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/admin/setup/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="ContainerTemplate" />{'Container (with children)'|i18n( 'design/admin/setup/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="ViewTemplate" />{'View (without children)'|i18n( 'design/admin/setup/templatecreate' )}<br />
</div>

{/case}
{case match='object_view'}
<div class="objectheader">
<h2>{'Override keys'|i18n( 'design/admin/setup/templatecreate' )}</h2>
</div>

<div class="object">
<table>
<tr>
    <td>
    <p>
    {'Class'|i18n( 'design/admin/setup/templatecreate' )}
    </p>
    </td>
    <td>
    <select name="Match[class_identifier]">
        <option value="-1">{'Any'|i18n( 'design/admin/setup/templatecreate' )}</option>
        {section name=Class loop=fetch('content', 'can_instantiate_class_list')}
        <option value="{fetch( content, class, hash( class_id, $Class:item.id ) ).identifier}">{$Class:item.name}</option>
        {/section}
    </select>
    </td>
</tr>
<tr>
    <td>
    <p>
    {'Section'|i18n( 'design/admin/setup/templatecreate' )}
    </p>
    </td>
    <td>
    <select name="Match[section]">
        <option value="-1">{'Any'|i18n( 'design/admin/setup/templatecreate' )}</option>
        {section name=Section loop=fetch( 'content', 'section_list' )}
            <option value="{$:item.id}">{$:item.name}</option>
        {/section}
    </select>
    </td>
</tr>
<tr>
    <td>
    <p>
    {'Object'|i18n( 'design/admin/setup/templatecreate' )}
    </p>
    </td>
    <td>
    <input type="text" size="5" value="" name="Match[object]" />
    </td>
</tr>
</table>
</div>


<div class="objectheader">
<h2>{'Base template on'|i18n( 'design/admin/setup/templatecreate' )}</h2>
</div>
<div class="object">

<input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/admin/setup/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/admin/setup/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="ContainerTemplate" />{'Container (with children)'|i18n( 'design/admin/setup/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="ViewTemplate" />{'View (without children)'|i18n( 'design/admin/setup/templatecreate' )}<br />
</div>

{/case}
{case match='pagelayout'}
    <div class="objectheader">
    <h2>{'Base template on'|i18n( 'design/admin/setup/templatecreate' )}</h2>
    </div>
    <div class="object">
    <input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/admin/setup/templatecreate' )}<br />
    <input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/admin/setup/templatecreate' )}<br />
    </div>
{/case}
{case}
    <div class="objectheader">
    <h2>{'Base template on'|i18n( 'design/admin/setup/templatecreate' )}</h2>
    </div>
    <div class="object">
    <input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/admin/setup/templatecreate' )}<br />
    <input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/admin/setup/templatecreate' )}<br />
    </div>
{/case}
{/switch}

</div>

<div class="controlbar">
    <div class="block">
        <input class="button" type="submit" name="CreateOverrideButton" value="{'OK'|i18n( 'design/admin/setup/templatecreate' )}" />
        <input class="button" type="submit" name="CancelOverrideButton" value="{'Cancel'|i18n( 'design/admin/setup/templatecreate' )}" />
    </div>
</div>

</div>

</form>