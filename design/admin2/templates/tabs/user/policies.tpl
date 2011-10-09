{* Policy list window *}
{if $assigned_policies}

<table class="list" cellspacing="0" summary="{'Policy list and the Role that are assignet to current node.'|i18n( 'design/admin/node/view/full' )}">
<tr>
    <th>{'Role'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Limited to'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Module'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Function'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Limitation'|i18n( 'design/admin/node/view/full' )}</th>
</tr>

{* For all roles... *}
{section var=AssignedRoles loop=$assigned_roles}

{* For each policy (if any) within a role... *}
{let role_without_cond_policies = fetch(role,role,hash(role_id,$AssignedRoles.item.id))}
{section var=Policy loop=$:role_without_cond_policies.policies sequence=array( bglight, bgdark )}

<tr class="{$Policy.sequence}">

    {* Role name  *}
    <td>
    {$AssignedRoles.item.name|wash}
    </td>

    {* limitation (if any). *}
    <td>
    {if $AssignedRoles.item.limit_identifier}
        {'%limitation_identifier %limitation_value'|i18n( 'design/admin/node/view/full',, hash( '%limitation_identifier', $AssignedRoles.item.limit_identifier|downcase, '%limitation_value', $AssignedRoles.item.limit_value ) )}
    {/if}
    </td>

    {* Module. *}
    <td>
    {if eq( $Policy.item.module_name, '*' )}
        <i>{'all modules'|i18n( 'design/admin/node/view/full' )}</i>
    {else}
        {$Policy.item.module_name}
    {/if}
    </td>

    {* Policy. *}
    <td>
    {if eq( $Policy.item.function_name, '*' )}
        <i>{'all functions'|i18n( 'design/admin/node/view/full' )}</i>
    {else}
        {$Policy.item.function_name}
    {/if}
    </td>

    {* Limitations. *}
    <td>
    {if ne( $Policy.item.limitations|count, 0 )}
        {section var=Limitation loop=$Policy.item.limitations}
            {$Limitation.identifier|wash}(
            {section var=LimitationValues loop=$Limitation.values_as_array_with_names}
                {$LimitationValues.Name|wash}
                {delimiter}, {/delimiter}
        {/section})
        {delimiter}, {/delimiter}
        {/section}
    {else}
        <i>{'No limitations'|i18n( 'design/admin/node/view/full' )}</i>
    {/if}
    </td>

</tr>
{/section}
{/let}
{/section}

</table>

{else}
<div class="block">
    <p>{'There are no available policies.'|i18n( 'design/admin/node/view/full' )}</p>
</div>
{/if}
