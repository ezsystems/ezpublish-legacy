<form name="roleedit" action={concat( $module.functions.edit.uri, '/', $role.id, '/' )|ezurl} method="post" >

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'role'|icon( 'normal', 'Role'|i18n( '/design/admin/role/edit' ) )}&nbsp;{'Edit <%role_name> [Role]'|i18n( 'design/admin/role/edit',, hash( '%role_name', $role.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
    <label>{'Name'|i18n( 'design/admin/role/edit' )}</label><div class="labelbreak"></div>
    <input class="box" type="edit" name="NewName" value="{$role.name|wash}" />
</div>

<div class="block">
<label>{'Policies'|i18n( 'design/admin/role/edit' )}</label><div class="labelbreak" />

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/role/edit' )}" title="{'Invert selection.'|i18n( 'design/admin/role/edit' )}" onclick="ezjs_toggleCheckboxes( document.roleedit, 'DeleteIDArray[]' ); return false;" /></th>
    <th>{'Module'|i18n( 'design/admin/role/edit' )}</th>
    <th>{'Function'|i18n( 'design/admin/role/edit' )}</th>
    <th>{'Limitations'|i18n( 'design/admin/role/edit' )}</th>
    <th>&nbsp;</th>
</tr>

{section var=Policies loop=$policies sequence=array( bglight, bgdark )}
<tr class="{$Policies.sequence}">
    <td>
        <input type="checkbox" name="DeleteIDArray[]" value="{$Policies.item.id}" />
    </td>
    <td>
         {section show=eq( $Policies.item.module_name, '*' )}
             <i>{'all modules'|i18n( 'design/admin/role/edit' )} </i>
             {section-else}
             {$Policies.item.module_name}
        {/section}
    </td>
    <td>
         {section show=eq( $Policies.item.function_name, '*' )}
             <i>{'all functions'|i18n( 'design/admin/role/edit' )} </i>
             {section-else}
             {$Policies.item.function_name}
        {/section}
    </td>
    <td>
        {section show=$Policies.item.limitations}
          {section var=Limitations loop=$Policies.item.limitations}
              {$Limitations.item.identifier}(
              {section var=LimitationValues loop=$Limitations.item.values_as_array_with_names}
                  {$LimitationValues.item.Name}
                  {delimiter}, {/delimiter}
              {/section})
              {delimiter}, {/delimiter}
          {/section}
        {section-else}
          <i>{'No limitations'|i18n( 'design/admin/role/edit' )}</i>
        {/section}
    </td>
    <td>
        <a href={concat( 'role/policyedit/', $Policies.item.id )|ezurl}><img class="button" src={'edit.png'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/role/edit' )}" title="{"Edit the policy's function limitations"|i18n( 'design/admin/role/edit' )}" /></a>
    </td>
</tr>
{/section}
</table>


<input class="button" type="submit" name="RemovePolicies" value="{'Remove selected'|i18n( 'design/admin/role/edit' )}" title="{'Remove selected policies'|i18n( 'design/admin/role/edit' )}" />
<input class="button" type="submit" name="CreatePolicy" value="{'New policy'|i18n( 'design/admin/role/edit' )}" />

</div>
</div>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="Apply" value="{'OK'|i18n( 'design/admin/role/edit' )}" />
<input class="button" type="submit" name="Discard" value="{'Cancel'|i18n( 'design/admin/role/edit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>
</form>

