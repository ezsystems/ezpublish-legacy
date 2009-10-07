{if eq( $error, 'permission_denied' )}
<div class="message-error">
<h2>{'Could not create template, permission denied.'|i18n( 'design/standard/design/templatecreate' )}</h2>
</div>
{/if}

{if eq( $error, 'invalid_name' )}
<div class="message-error">
<h2>{'Invalid name. You can only use the characters a-z, numbers and _.'|i18n( 'design/standard/design/templatecreate' )}</h2>
</div>
{/if}

<form method="post" action={concat( '/design/templatecreate', $template )|ezurl}>

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Create new template override for <%template_name>'|i18n( 'design/standard/design/templatecreate',, hash( '%template_name', $template ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<p>{'The newly created template file will be placed in'|i18n( 'design/standard/design/templatecreate' )} design/{$site_design}/override/templates/.</p>

{def $extension_list=ezini('ExtensionSettings','DesignExtensions','design.ini' )}
{if ne($extension_list, array())}
    <div class="block">
    <label>{'Extension'|i18n( 'design/standard/visual/templatecreate' )}:</label>

    <select name="DesignExtension">
        {foreach $extension_list as $extensionName}
            {if eq($design_extension,$extensionName)}
                <option value="{$extensionName}" selected="selected">{$extensionName}</option>
            {else}
                <option value="{$extensionName}">{$extensionName}</option>
            {/if}
        {/foreach}

        {if eq($design_extension,"")}
            <option value="" selected="selected">No extension</option>
        {else}
            <option value="">No extension</option>
        {/if}
    </select>
    </div>
{/if}

<div class="block">
    <label>{'Filename'|i18n( 'design/standard/design/templatecreate' )}</label>
    <input class="halfbox" type="text" name="TemplateName" value="{$template_name}" />.tpl
</div>


{switch match=$template_type}
{case match='node_view'}
<div class="block">
<label>{'Override keys'|i18n( 'design/standard/design/templatecreate' )}</label>

<table>
<tr>
    <td>{'Class'|i18n( 'design/standard/design/templatecreate' )}:</td>
    <td>
    <select name="Match[class_identifier]">
        <option value="-1">{'All classes'|i18n( 'design/standard/design/templatecreate' )}</option>
        {section name=Class loop=fetch('content', 'can_instantiate_class_list')}
        <option value="{fetch( content, class, hash( class_id, $Class:item.id ) ).identifier}">{$Class:item.name}</option>
        {/section}
    </select>
    </td>
</tr>
<tr>
    <td>{'Section'|i18n( 'design/standard/design/templatecreate' )}:</td>
    <td>
    <select name="Match[section]">
        <option value="-1">{'All sections'|i18n( 'design/standard/design/templatecreate' )}</option>
        {section name=Section loop=fetch( 'content', 'section_list' )}
            <option value="{$:item.id}">{$:item.name}</option>
        {/section}
    </select>
    </td>
</tr>
<tr>
    <td>{'Node ID'|i18n( 'design/standard/design/templatecreate' )}:</td>
    <td><input type="text" size="5" value="" name="Match[node]" /></td>
</tr>
</table>
</div>


<div class="block">
<label>{'Base template on'|i18n( 'design/standard/design/templatecreate' )}</label>
<input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/standard/design/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/standard/design/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="ContainerTemplate" />{'Container (with children)'|i18n( 'design/standard/design/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="ViewTemplate" />{'View (without children)'|i18n( 'design/standard/design/templatecreate' )}<br />
</div>

{/case}
{case match='object_view'}
<div class="objectheader">
<h2>{'Override keys'|i18n( 'design/standard/design/templatecreate' )}</h2>
</div>

<div class="object">
<table>
<tr>
    <td>
    <p>
    {'Class'|i18n( 'design/standard/design/templatecreate' )}
    </p>
    </td>
    <td>
    <select name="Match[class_identifier]">
        <option value="-1">{'Any'|i18n( 'design/standard/design/templatecreate' )}</option>
        {section name=Class loop=fetch('content', 'can_instantiate_class_list')}
        <option value="{fetch( content, class, hash( class_id, $Class:item.id ) ).identifier}">{$Class:item.name}</option>
        {/section}
    </select>
    </td>
</tr>
<tr>
    <td>
    <p>
    {'Section'|i18n( 'design/standard/design/templatecreate' )}
    </p>
    </td>
    <td>
    <select name="Match[section]">
        <option value="-1">{'Any'|i18n( 'design/standard/design/templatecreate' )}</option>
        {section name=Section loop=fetch( 'content', 'section_list' )}
            <option value="{$:item.id}">{$:item.name}</option>
        {/section}
    </select>
    </td>
</tr>
<tr>
    <td>
    <p>
    {'Object'|i18n( 'design/standard/design/templatecreate' )}
    </p>
    </td>
    <td>
    <input type="text" size="5" value="" name="Match[object]" />
    </td>
</tr>
</table>
</div>


<div class="objectheader">
<h2>{'Base template on'|i18n( 'design/standard/design/templatecreate' )}</h2>
</div>
<div class="object">

<input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/standard/design/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/standard/design/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="ContainerTemplate" />{'Container (with children)'|i18n( 'design/standard/design/templatecreate' )}<br />
<input type="radio" name="TemplateContent" value="ViewTemplate" />{'View (without children)'|i18n( 'design/standard/design/templatecreate' )}<br />
</div>

{/case}
{case match='pagelayout'}
    <div class="objectheader">
    <h2>{'Base template on'|i18n( 'design/standard/design/templatecreate' )}</h2>
    </div>
    <div class="object">
    <input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/standard/design/templatecreate' )}<br />
    <input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/standard/design/templatecreate' )}<br />
    </div>
{/case}
{case}
    <div class="objectheader">
    <h2>{'Base template on'|i18n( 'design/standard/design/templatecreate' )}</h2>
    </div>
    <div class="object">
    <input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/>{'Empty file'|i18n( 'design/standard/design/templatecreate' )}<br />
    <input type="radio" name="TemplateContent" value="DefaultCopy" />{'Copy of default template'|i18n( 'design/standard/design/templatecreate' )}<br />
    </div>
{/case}
{/switch}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
        <input class="button" type="submit" name="CreateOverrideButton" value="{'OK'|i18n( 'design/standard/design/templatecreate' )}" />
        <input class="button" type="submit" name="CancelOverrideButton" value="{'Cancel'|i18n( 'design/standard/design/templatecreate' )}" />
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
