{let policies=fetch( user, user_role, hash( user_id, $node.object.id ) )}

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Available policies [%policies_count]'|i18n( 'design/admin/node/view/full',, hash( '%policies_count', $policies|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{section show=count( $policies )}
<table class="list" cellspacing="0">
<tr>
    <th>{'Module'|i18n( 'design/standard/role' )}</td>
    <th>{'Function'|i18n( 'design/admin/node/view/full' )}</td>
    <th>{'Limitation'|i18n( 'design/admin/node/view/full' )}</td>
</tr>
    {section var=Policy loop=$policies sequence=array( bglight, bgdark )}
    <tr class="{$Policy.sequence}">
        <td>
        {section show=eq( $Policy.moduleName, '*' )}
        <i>{'all modules'|i18n( 'design/admin/node/view/full' )}</i>
        {section-else}
            {$Policy.moduleName}
        {/section}
        </td>
        <td>
        {section show=eq( $Policy.functionName, '*' )}
        <i>{'all functions'|i18n( 'design/admin/node/view/full' )}</i>
        {section-else}
            {$Policy.functionName}
        {/section}
        </td>
        <td>
            {section show=eq($Policy.limitation,'*')}
                <i>{'No limitations'|i18n('')}</i>
            {section-else}
                {section var=Limitation loop=$Policy.limitation}
                  {$Limitation.identifier|wash}(
                      {section var=LimitationValues loop=$Limitation.values_as_array_with_names}
                          {$LimitationValues.Name|wash}
                          {delimiter}, {/delimiter}
                      {/section})
                      {delimiter}, {/delimiter}
                {/section}
             {/section}
        </td>
    </tr>
    {/section}
</table>
{section-else}
<table class="list" cellspacing="0">
<tr>
    <td>{'There are no available policies.'|i18n( 'design/admin/node/view/full' )}</td>
</tr>
</table>
{/section}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
{/let}
