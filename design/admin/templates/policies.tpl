{let policies=fetch( user, user_role, hash( user_id, $node.object.id ) )}

<div class="context-block">
<h2 class="context-title">{'Policies'|i18n('__FIX_ME__')}</h2>

{section show=count( $policies )}
<table class="list" cellspacing="0">
<tr>
    <th>{'Module'|i18n( 'design/standard/role' )}</td>
    <th>{'Function'|i18n( 'design/standard/role' )}</td>
    <th>{'Limitation'|i18n( 'design/standard/role' )}</td>
</tr>
    {section var=Policy loop=$policies sequence=array(bglight,bgdark)}
    <tr class="{$Policy.sequence}">
        <td>
            {$Policy.moduleName}
        </td>
        <td>
        {section show=eq( $Policy.functionName, '*' )}
        <i>{'all'|i18n('__FIX_ME__')}</i>
        {section-else}
            {$Policy.functionName}
        {/section}
        </td>
        <td>
            {section show=eq($Policy.limitation,'*')}
                <i>{'None'|i18n('')}</i>
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
{/section}

</div>
{/let}