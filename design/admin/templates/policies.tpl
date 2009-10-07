{let assigned_roles=fetch( user, member_of, hash( id, $node.contentobject_id ) )
     assigned_policies=fetch( user, user_role, hash( user_id, $node.contentobject_id ) )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Available policies [%policy_count]'|i18n( 'design/admin/node/view/full',, hash( '%policy_count', $assigned_policies|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{section show=$assigned_policies}

<table class="list" cellspacing="0">
<tr>
    <th>{'Role'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Module'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Function'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Limitation'|i18n( 'design/admin/node/view/full' )}</th>
</tr>

{* For all roles... *}
{section var=AssignedRoles loop=$assigned_roles sequence=array( bglight, bgdark )}

{* For each policy (if any) within a role... *}
{let role_without_cond_policies = fetch(role,role,hash(role_id,$AssignedRoles.item.id))}
{section var=Policy loop=$:role_without_cond_policies.policies sequence=array( bglight, bgdark )}

<tr class="{$Policy.sequence}">

    {* Role name + limitation (if any). *}
    <td>
    {$AssignedRoles.item.name|wash}
    &nbsp;
    {if $AssignedRoles.item.limit_identifier}
        ({'limited to %limitation_identifier %limitation_value'|i18n( 'design/admin/node/view/full',, hash( '%limitation_identifier', $AssignedRoles.item.limit_identifier|downcase, '%limitation_value', $AssignedRoles.item.limit_value ) )})
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
    {section show=ne( $Policy.item.limitations|count, 0 )}
        {section var=Limitation loop=$Policy.item.limitations}
            {$Limitation.identifier|wash}(
            {section var=LimitationValues loop=$Limitation.values_as_array_with_names}
                {$LimitationValues.Name|wash}
                {delimiter}, {/delimiter}
        {/section})
        {delimiter}, {/delimiter}
        {/section}
    {section-else}
        <i>{'No limitations'|i18n( 'design/admin/node/view/full' )}</i>
    {/section}
    </td>

</tr>
{/section}
{/let}
{/section}

</table>

{section-else}
<div class="block">
    <p>{'There are no available policies.'|i18n( 'design/admin/node/view/full' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/let}
