{* Class window controls. *}
<div class="menu-block">
<ul>

    {* Class group. *}
    {if ezpreference( 'admin_navigation_class_groups' )}
    <li class="enabled">
    <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_class_groups/0'|ezurl} title="{'Hide class groups.'|i18n( 'design/admin/class/view' )}">{'Class groups'|i18n( 'design/admin/class/view' )}</a>
    </div></div></div></div>
    </li>
    {else}
    <li class="disabled">
    <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_class_groups/1'|ezurl} title="{'Show class groups.'|i18n( 'design/admin/class/view' )}">{'Class groups'|i18n( 'design/admin/node/class/view' )}</a>
    </div></div></div></div>
    </li>
    {/if}

    {* Override templates. *}
    {if ezpreference( 'admin_navigation_class_temlates' )}
    <li class="enabled">
    <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_class_temlates/0'|ezurl} title="{'Hide override templates.'|i18n( 'design/admin/class/view' )}">{'Override templates'|i18n( 'design/admin/class/view' )}</a>
    </div></div></div></div>
    </li>
    {else}
    <li class="disabled">
    <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_class_temlates/1'|ezurl} title="{'Show override templates.'|i18n( 'design/admin/class/view' )}">{'Override templates'|i18n( 'design/admin/node/class/view' )}</a>
    </div></div></div></div>
    </li>
    {/if}

{if fetch( content, translation_list )|count|gt( 1 )}
    {* Translations. *}
    {if ezpreference( 'admin_navigation_class_translations' )}
    <li class="enabled">
    <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_class_translations/0'|ezurl} title="{'Hide available translations.'|i18n( 'design/admin/class/view' )}">{'Translations'|i18n( 'design/admin/class/view' )}</a>
    </div></div></div></div>
    </li>
    {else}
    <li class="disabled">
    <div class="button-bc"><div class="button-tl"><div class="button-tr"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_class_translations/1'|ezurl} title="{'Show available translations.'|i18n( 'design/admin/class/view' )}">{'Translations'|i18n( 'design/admin/class/view' )}</a>
    </div></div></div></div>
    </li>
    {/if}
{/if}

</ul>

<div class="break"></div>

</div>
