<div class="context-block">
<h2 class="context-title">{'Class <%1>'|i18n( 'design/admin/class/view',, array( $class.name) )|wash}</h2>

<div class="context-information">
    <p>{'Last modified: %time, %username'|i18n( 'design/admin/class/view',, hash( '%username',$class.modifier.contentobject.name, '%time', $class.modified|l10n( shortdatetime ) ) )}</p>
</div>

<div class="context-attributes">

<div class="block">
    <label>{'Name'|i18n( 'design/admin/class/view' )}:</label>
    {$class.name|wash}
</div>

<div class="block">
    <label>{'Identifier'|i18n( 'design/admin/class/view' )}:</label>
    {$class.identifier|wash}
</div>

<div class="block">
    <label>{'Object name pattern'|i18n( 'design/admin/class/view' )}:</label>
    {$class.contentobject_name|wash}
</div>

<div class="block">
    <label>{'Container'|i18n( 'design/admin/class/view' )}:</label>
    {section show=$class.is_container|eq(1)}
        {'Yes'|i18n( 'design/admin/class/view' )}
    {section-else}
        {'No'|i18n( 'design/admin/class/view' )}
    {/section}
</div>

<div class="block">
    <label>{'Object count'|i18n( 'design/admin/class/view' )}:</label>
    {$class.object_count}
</div>


<h2>{'Attributes'|i18n( 'design/admin/class/view' )}</h2>
<table class="class_list" width="100%" cellpadding="0" cellspacing="0" border="0">

{section name=Attributes loop=$attributes sequence=array( bglight, bgdark )}

<tr>
    <td colspan="3"><b>{$:number}. {$:item.name|wash} ({$:item.data_type.information.name|wash}) (id:{$:item.id})</b></td>
</tr>

<tr>

    <td class="{$Attributes:sequence}">
        <input type="hidden" name="ContentAttribute_id[]" value="{$Attributes:item.id}" />
        <input type="hidden" name="ContentAttribute_position[]" value="{$Attributes:item.placement}" />

        <div class="block">
            <label>{'Name'|i18n( 'design/admin/class/view' )}</label>
            <p>{$Attributes:item.name|wash}</p>
        </div>
    </td>

    <td class="{$Attributes:sequence}">
        <div class="block">
            <label>{'Identifier'|i18n( 'design/admin/class/view' )}</label>
            <p>{$Attributes:item.identifier|wash}</p>
        </div>
    </td>



<td class="{$Attributes:sequence}" rowspan="2" width="20%" valign="top">
<div class="block">
<label>{'Flags'|i18n( 'design/admin/class/view' )}</label>
</div>

        <div class="block">
            <p>{section show=$Attributes:item.is_required}{'Is required'|i18n( 'design/admin/class/view' )}{section-else}{'Is not required'|i18n( 'design/admin/class/view' )}{/section}</p>
        </div>

        {section show=$Attributes:item.data_type.is_indexable}
        <div class="block">
            <p>{section show=$Attributes:item.is_searchable}{'Is searchable'|i18n( 'design/admin/class/view' )}{section-else}{'Is not searchable'|i18n( 'design/admin/class/view' )}{/section}</p>
        </div>
        {section-else}
        <div class="block">
            <p>{'Is not searchable'|i18n( 'design/admin/class/view' )}</p>
        </div>
        {/section}

        {section show=$Attributes:item.data_type.is_information_collector}
        <div class="block">
            <p>{section show=$Attributes:item.is_information_collector}{'Collects information'|i18n( 'design/admin/class/view' )}{section-else}{'Does not collect information'|i18n( 'design/admin/class/view' )}{/section}</p>
        </div> 
        {section-else}
        <div class="block">
            <p>{'Does not collect information'|i18n( 'design/admin/class/view' )}</p>
        </div>
        {/section}

        <div class="block">
            <p>{section show=$Attributes:item.can_translate|eq(0)}{'Translation is disabled'|i18n( 'design/admin/class/view' )}{section-else}{'Translation is enabled'|i18n( 'design/admin/class/view' )}{/section}</p>
        </div>
    </td>
</tr>

<tr>
    <td class="{$Attributes:sequence}" colspan="2">
        {class_attribute_view_gui class_attribute=$Attributes:item}
    </td>
</tr>
{/section}

</table>

</div>

<div class="controlbar">
    <div class="block">
        <form>
            <input class="button" type="submit" name="" value="{'Edit'|i18n( 'design/admin/class/edit' )}" />
            <input class="button" type="submit" name="" value="{'Remove'|i18n( 'design/admin/class/edit' )}" />
        </form>
    </div>
</div>

</div>


{* Member of groups. *}
<div class="context-block">
<h2 class="context-title">{'Member of class groups [%1]'|i18n( 'design/admin/class/view',, array( $class.ingroup_list|count) )}</h2>
<table class="list" cellspacing="0">
<tr>
    <th>{'Name'|i18n( 'design/admin/class/view' )}</th>
</tr>
{section var=Groups loop=$class.ingroup_list sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">
    <td><a href={concat( '/class/classlist/', $Groups.item.group_id )|ezurl}>{$Groups.item.group_name|wash}</a></td>
</tr>
{/section}
</table>
</div>


{* Override templates. *}
{let override_templates=fetch( class, override_template_list, hash( class_id, $class.id ) )}
<div class="context-block">
<h2 class="context-title">{'Override templates [%1]'|i18n( 'design/admin/class/view',, array( $override_templates|count ) )}</h2>
<table class="list" cellspacing="0">
<tr>
    <th>{'SiteAccess'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Override'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Source template'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Override template'|i18n( 'design/admin/class/view' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Overrides loop=$override_templates sequence=array( bglight, bgdark )}
<tr class="{$Overrides.sequence}">
    <td>{$Overrides.item.siteaccess}</td>
    <td>{$Overrides.item.block}</td>
    <td><a href={concat( '/setup/templateview/', $Overrides.item.source )|ezurl}>{$Overrides.item.source}</td>
    <td>{$Overrides.item.target}</td>
    <td><a href={concat( '/setup/templateedit/', $Overrides.item.target)|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/class/view' )}" /></a></td>
</tr>
{/section}
</table>

</div>

{/let}

