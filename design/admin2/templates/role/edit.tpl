<form name="roleedit" method="post" action={concat( $module.functions.edit.uri, '/', $role.id, '/' )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'role'|icon( 'normal', 'Role'|i18n( 'design/admin/role/edit' ) )}&nbsp;{'Edit <%role_name> [Role]'|i18n( 'design/admin/role/edit',, hash( '%role_name', $role.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

{* Name. *}
<div class="block">
    <label for="roleName">{'Name'|i18n( 'design/admin/role/edit' )}:</label>
    <input class="box" id="roleName" type="text" name="NewName" value="{$role.name|wash}" />
</div>

{* Policies. *}
<div class="block">
<fieldset>
<legend>{'Policies'|i18n( 'design/admin/role/edit' )}</legend>
{section show=$policies}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/role/edit' )}" title="{'Invert selection.'|i18n( 'design/admin/role/edit' )}" onclick="ezjs_toggleCheckboxes( document.roleedit, 'DeleteIDArray[]' ); return false;" /></th>
    <th>{'Module'|i18n( 'design/admin/role/edit' )}</th>
    <th>{'Function'|i18n( 'design/admin/role/edit' )}</th>
    <th>{'Limitations'|i18n( 'design/admin/role/edit' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Policies loop=$policies sequence=array( bglight, bgdark )}
<tr class="{$Policies.sequence}">

    {* Remove. *}
    <td>
        <input type="checkbox" name="DeleteIDArray[]" value="{$Policies.item.id}" title="{'Select policy for removal.'|i18n( 'design/admin/role/edit' )}" />
    </td>

    {* Module. *}
    <td>
        {if eq( $Policies.item.module_name, '*' )}
        <i>{'all modules'|i18n( 'design/admin/role/edit' )} </i>
        {else}
        {$Policies.item.module_name}
        {/if}
    </td>

    {* Function. *}
    <td>
        {if eq( $Policies.item.function_name, '*' )}
        <i>{'all functions'|i18n( 'design/admin/role/edit' )} </i>
        {else}
        {$Policies.item.function_name}
        {/if}
    </td>

    {* Limitations. *}
    <td>
        {section show=$Policies.item.limitations}
            {section var=Limitations loop=$Policies.item.limitations}
                {$Limitations.item.identifier}(
                    {section var=LimitationValues loop=$Limitations.item.values_as_array_with_names}
                        {$LimitationValues.item.Name|wash}
                        {delimiter}, {/delimiter}
                    {/section})
                   {delimiter}, {/delimiter}
            {/section}
        {section-else}
            <i>{'No limitations'|i18n( 'design/admin/role/edit' )}</i>
        {/section}
    </td>

    {* Edit. *}
    <td>
        <a href={concat( 'role/policyedit/', $Policies.item.id )|ezurl}><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/role/edit' )}" title="{"Edit the policy's function limitations."|i18n( 'design/admin/role/edit' )}" /></a>
    </td>
</tr>
{/section}
</table>
{section-else}
<p>{'There are no policies set up for this role.'|i18n( 'design/admin/role/edit' )}</p>
{/section}

{* Policy manipulation buttons. *}
{if $policies}
<input class="button" type="submit" name="RemovePolicies" value="{'Remove selected'|i18n( 'design/admin/role/edit' )}" title="{'Remove selected policies.'|i18n( 'design/admin/role/edit' )}" />
{else}
<input class="button-disabled" type="submit" name="RemovePolicies" value="{'Remove selected'|i18n( 'design/admin/role/edit' )}" disabled="disabled" />
{/if}

<input class="button" type="submit" name="CreatePolicy" value="{'New policy'|i18n( 'design/admin/role/edit' )}" title="{'Create a new policy.'|i18n( 'design/admin/role/edit' )}" />
</fieldset>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="Apply" value="{'OK'|i18n( 'design/admin/role/edit' )}" />
<input class="button" type="submit" name="Discard" value="{'Cancel'|i18n( 'design/admin/role/edit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>

{literal}
<script language="JavaScript" type="text/javascript">
<!--
    window.onload=function()
    {
        document.getElementById('roleName').select();
        document.getElementById('roleName').focus();
    }
-->
</script>
{/literal}
