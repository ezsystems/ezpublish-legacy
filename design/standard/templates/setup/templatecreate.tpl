<form method="post" action={concat('/setup/templatecreate',$template)|ezurl}>
<h1>Create new template override for: {$template}</h1>

<p>
Template will be placed in: design/{$site_base}/override/templates/
</p>

<div class="objectheader">
<h2>Template name</h2>
</div>

<div class="object">
<input type="text" name="TemplateName" value="{$template_name}" />.tpl
</div>


<div class="objectheader">
<h2>Override keys</h2>
</div>

{switch match=$template_type}
{case match='node_view'}
<div class="object">
<table>
<tr>
    <td>
    <p>
    Class
    </p>
    </td>
    <td>
    <select name="Match[class]">
        <option value="-1">Any</option>
        {section name=Class loop=fetch('content', 'can_instantiate_class_list')}
        <option value="{$Class:item.id}">{$Class:item.name}</option>
        {/section}
    </select>
    </td>
</tr>
<tr>
    <td>
    <p>
    Section
    </p>
    </td>
    <td>
    <select name="Match[section]">
        <option value="-1">Any</option>
    </select>
    </td>
</tr>
<tr>
    <td>
    <p>
    Node
    </p>
    </td>
    <td>
    <input type="text" size="5" value="" name="Match[node]" />
    </td>
</tr>
</table>
</div>
{/case}
{case}

{/case}
{/switch}


<div class="objectheader">
<h2>Base template on</h2>
</div>
<div class="object">

<input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/> Empty file<br />
<input type="radio" name="TemplateContent" value="DefaultCopy" /> Copy of default template<br />
<input type="radio" name="TemplateContent" value="ContainerTemplate" /> Container ( with children )<br />
<input type="radio" name="TemplateContent" value="ViewTemplate" /> View ( without children )<br />
</div>

<div class="buttonblock">
<input class="button" type="submit" value="Create" name="CreateOverrideButton" />
</div>

</form>