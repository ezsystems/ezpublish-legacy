{section show=$validation.processed}
{section show=$validation.groups}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Input did not validate'|i18n( 'design/admin/class/view' )}</h2>
<ul>
{section var=item loop=$validation.groups}
    <li>{$item.text}</li>
{/section}
</ul>
</div>
{/section}
{/section}


<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{$class.identifier|class_icon( 'normal', $class.name )}&nbsp;{'%class_name [Class]'|i18n( 'design/admin/class/view',, hash( '%class_name', $class.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

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
<table class="special" cellspacing="0">

{section var=Attributes loop=$attributes sequence=array( bglight, bgdark )}

<tr>
    <th colspan="3">{$Attributes.number}.&nbsp;{$Attributes.item.name|wash}&nbsp;[{$Attributes.item.data_type.information.name|wash}]&nbsp;(id:{$Attributes.item.id})</td>
</tr>

<tr class="{$Attributes.sequence}">
    <td>
        <input type="hidden" name="ContentAttribute_id[]" value="{$Attributes.item.id}" />
        <input type="hidden" name="ContentAttribute_position[]" value="{$Attributes.item.placement}" />

        <div class="block">
            <label>{'Name'|i18n( 'design/admin/class/view' )}:</label>
            <p>{$Attributes.item.name|wash}</p>
        </div>
    </td>

    <td class="{$Attributes.sequence}">
        <div class="block">
            <label>{'Identifier'|i18n( 'design/admin/class/view' )}:</label>
            <p>{$Attributes.item.identifier|wash}</p>
        </div>
    </td>
    <td rowspan="2">
<div class="block">
<label>{'Flags'|i18n( 'design/admin/class/view' )}:</label>
</div>

        <div class="block">
            <p>{section show=$Attributes.item.is_required}{'Is required'|i18n( 'design/admin/class/view' )}{section-else}{'Is not required'|i18n( 'design/admin/class/view' )}{/section}</p>
        </div>

        {section show=$Attributes.item.data_type.is_indexable}
        <div class="block">
            <p>{section show=$Attributes.item.is_searchable}{'Is searchable'|i18n( 'design/admin/class/view' )}{section-else}{'Is not searchable'|i18n( 'design/admin/class/view' )}{/section}</p>
        </div>
        {section-else}
        <div class="block">
            <p>{'Is not searchable'|i18n( 'design/admin/class/view' )}</p>
        </div>
        {/section}

        {section show=$Attributes.item.data_type.is_information_collector}
        <div class="block">
            <p>{section show=$Attributes.item.is_information_collector}{'Collects information'|i18n( 'design/admin/class/view' )}{section-else}{'Does not collect information'|i18n( 'design/admin/class/view' )}{/section}</p>
        </div>
        {section-else}
        <div class="block">
            <p>{'Does not collect information'|i18n( 'design/admin/class/view' )}</p>
        </div>
        {/section}

        <div class="block">
            <p>{section show=$Attributes.item.can_translate|eq(0)}{'Translation is disabled'|i18n( 'design/admin/class/view' )}{section-else}{'Translation is enabled'|i18n( 'design/admin/class/view' )}{/section}</p>
        </div>
    </td>
</tr>

<tr class="{$Attributes.sequence}">
    <td colspan="2">
        {class_attribute_view_gui class_attribute=$Attributes.item}
    </td>
</tr>
{/section}

</table>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
        <form action={concat( '/class/edit/', $class.id )|ezurl} method="post">
            <input class="button" type="submit" name="" value="{'Edit'|i18n( 'design/admin/class/view' )}" title="{'Edit this class.'|i18n( 'design/admin/class/view' )}" />
            {* <input class="button" type="submit" name="" value="{'Remove'|i18n( 'design/admin/class/view' )}" /> *}
        </form>
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>





{*-- Class group Start --*}
<form action={concat( $module.functions.view.uri, '/', $class.id )|ezurl} method="post">
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Member of class groups [%group_count]'|i18n( 'design/admin/class/view',, hash( '%group_count', $class.ingroup_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th class="wide">{'Class group'|i18n( 'design/admin/class/view' )}</th>
</tr>
{section var=Groups loop=$class.ingroup_list sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">
    <td class="tight"><input type="checkbox" name="group_id_checked[]" value="{$Groups.item.group_id}" title="{'Select class group for removal.'|i18n( 'design/admin/class/view' )}" /></td>
    <td class="wide">{$Groups.item.group_name|classgroup_icon( small, $Groups.item.group_name )}&nbsp;<a href={concat( '/class/classlist/', $Groups.item.group_id )|ezurl}>{$Groups.item.group_name|wash}</a></td>
</tr>
{/section}
</table>
{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    <input class="button" type="submit" name="RemoveGroupButton" value="{'Remove from selected'|i18n( 'design/admin/class/view' )}" title="{'Remove the <%class_name> class from the selected class groups.'|i18n( 'design/admin/class/view',, hash( '%class_name', $class.name ) )|wash}" />
    </div>
    <div class="block">
    {section show=sub( count( $class.group_list ),count( $class.ingroup_list ) )}
        <select name="ContentClass_group" title="{'Select a group which the <%class_name> class should be added to.'|i18n( 'design/admin/class/view',, hash( '%class_name', $class.name ) )|wash}">
        {section name=AllGroup loop=$class.group_list}
            {section show=$class.ingroup_id_list|contains( $AllGroup:item.id )|not}
                <option value="{$AllGroup:item.id}/{$AllGroup:item.name}">{$AllGroup:item.name|wash}</option>
            {/section}
        {/section}
        </select>
        <input class="button" type="submit" name="AddGroupButton" value="{'Add to class group'|i18n( 'design/admin/class/view' )}" title="{'Add the <%class_name> class to the group specified in the menu on the left.'|i18n( 'design/admin/class/view',, hash( '%class_name', $class.name ) )|wash}" />
    {section-else}
        <select name="ContentClass_group" disabled="disabled" title="{'The <%class_name> class already exists within all class groups.'|i18n( 'design/admin/class/view',, hash( '%class_name', $class_name ) )|wash}">
        <option value="-1">{'No group'|i18n( 'design/admin/class/view' )}</option>
        </select>
        <input class="button-disabled" type="submit" name="AddGroupButton" value="{'Add to class group'|i18n( 'design/admin/class/view' )}" disabled="disabled" title="{'The <%class_name> class already exists within all class groups.'|i18n( 'design/admin/class/view',, hash( '%class_name', $class_name ) )|wash}" />
    {/section}
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
</div>
</form>
{*-- Class group End --*}

{*-- Override templates start. --*}
{let override_templates=fetch( class, override_template_list, hash( class_id, $class.id ) )}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Override templates [%1]'|i18n( 'design/admin/class/view',, array( $override_templates|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{section show=$override_templates|count}
<table class="list" cellspacing="0">
<tr>
    <th>{'Siteaccess'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Override'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Source template'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Override template'|i18n( 'design/admin/class/view' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Overrides loop=$override_templates sequence=array( bglight, bgdark )}
<tr class="{$Overrides.sequence}">
    <td>{$Overrides.item.siteaccess}</td>
    <td>{$Overrides.item.block}</td>
    <td><a href={concat( '/design/templateview', $Overrides.item.source )|ezurl} title="{'View the override template for the <%override_name> override.'|i18n( 'design/admin/class/view',, hash( '%override_name', $Overrides.item.block ) )|wash}" >{$Overrides.item.source}</td>
    <td>{$Overrides.item.target}</td>
    <td><a href={concat( '/design/templateedit/', $Overrides.item.target)|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/class/view' )}" title="{'Edit the override template for the <%override_name> override.'|i18n( 'design/admin/class/view',, hash( '%override_name', $Overrides.item.block ) )|wash}" /></a></td>
</tr>
{/section}
</table>
{section-else}
{'This class does not have any override templates.'|i18n( 'design/admin/class/view' )}
{/section}
{*DESIGN: Content END *}</div></div></div></div></div></div>

</div>
{/let}
{*-- Override templates end. --*}
