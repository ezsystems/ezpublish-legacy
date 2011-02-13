<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Confirm class removal'|i18n( 'design/admin/class/removeclass' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

{if $can_remove}
    {if $DeleteResult|count|gt(1)}
        <h2>{'Are you sure you want to remove the classes?'|i18n( 'design/admin/class/removeclass' )}</h2>
        {else}
        <h2>{'Are you sure you want to remove this class?'|i18n( 'design/admin/class/removeclass' )}</h2>
    {/if}
{else}
    <h2>{'You do not have permission to remove classes.'|i18n( 'design/admin/class/removeclass' )}</h2>
{/if}

{section show=$already_removed}
    {let class_list=''}
    {section var=class loop=$already_removed}
        {set class_list=concat( $class_list, $class.name|wash )}
        {delimiter}{set class_list=concat( $class_list, ', ' )}{/delimiter}
    {/section}
    {if count( $already_removed )|eq( 1 )}
        {'The %1 class was already removed from the group but still exists in other groups.'|i18n( 'design/admin/class/removeclass',, array( $class_list ) )}
    {else}
        {'The %1 classes were already removed from the group but still exist in other groups.'|i18n( 'design/admin/class/removeclass',, array( $class_list ) )}
    {/if}
{/let}
{/section}

{section var=Classes loop=$DeleteResult}
    <ul>
    {if $Classes.item.objectCount|gt( 0 )}
        {if $Classes.item.objectCount|eq( 1 )}
            <li>{"Removing class <%1> will result in the removal of %2 object and all its sub items."|i18n( 'design/admin/class/removeclass',, array( $Classes.item.className|wash, $Classes.item.objectCount ) )|wash}</li>
        {else}
            <li>{'Removing class <%1> will result in the removal of %2 objects and all their sub items.'|i18n( 'design/admin/class/removeclass',, array( $Classes.item.className|wash, $Classes.item.objectCount ) )|wash}</li>
        {/if}
    {/if}


    {section show=$Classes.item.is_removable|not}
    <li>{$Classes.item.reason.text|wash}
        <ul>
            {section var=reason loop=$Classes.item.reason.list}
                {section show=is_set( $reason.list )}
                    <li>{$reason.text|wash}
                    <ul>
                        {section var=sub_reason loop=$reason.list}
                            <li>{$sub_reason.text|wash}</li>
                        {/section}
                    </ul>
                    </li>
                {section-else}
                    <li>{$reason.text|wash}</li>
                {/section}
            {/section}
        </ul>
    </li>
    {/section}
    </ul>
{/section}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

<form action={concat( $module.functions.removeclass.uri, '/', $GroupID )|ezurl} method="post" name="ClassRemove">
    {if $can_remove}
    <input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/class/removeclass' )}" />
    {else}
    <input class="button-disabled" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/class/removeclass' )}" disabled="disabled" />
    {/if}

    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/class/removeclass' )}" />
</form>

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

