<form method="post" action={concat('/setup/templatecreate',$template)|ezurl}>
<h1>Create new template override for: {$template}</h1>


<p>
Template will be placed in: design/{$site_base}/override/templates/
</p>

<div class="block">
<label>Template name</label>
</div>

<input type="text" name="TemplateName" value="{$template_name}" />.tpl

<p>
Override keys
</p>

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
    <select name="SectionID">
        <option value="-1">Any</option>
    </select>
    </td>
</tr>
</table>

<p>
Create template as:<br />
<input type="radio" name="TemplateContent" value="EmptyFile" checked="checked"/> Empty file<br />
<input type="radio" name="TemplateContent" value="DefaultCopy" /> Copy of default template<br />
<input type="radio" name="TemplateContent" value="ContainerTemplate" /> Container ( with children )<br />
<input type="radio" name="TemplateContent" value="ViewTemplate" /> View ( without children )<br />
</p>

<div class="buttonblock">
<input class="button" type="submit" value="Create" name="CreateOverrideButton" />
</div>

</form>