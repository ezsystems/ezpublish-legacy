{* Window controls. *}
<div class="menu-block">
<ul>
    {* Content preview. *}
    {section show=ezpreference( 'admin_navigation_content' )}
    <li class="selected">
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a class="enabled" href={'/user/preferences/set/admin_navigation_content/0'|ezurl} title="{'Hide preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
    <li>
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_content/1'|ezurl} title="{'Show preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </div></div></div>
    </li>

    {* Additional information. *}
    {section show=ezpreference( 'admin_navigation_information' )}
    <li class="selected">
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a class="enabled" href={'/user/preferences/set/admin_navigation_information/0'|ezurl} title="{'Hide additional information.'|i18n( 'design/admin/node/view/full' )}">{'Information'|i18n( 'design/admin/node/view/full' )}</a>
    </div></div></div>
    {section-else}
    <li>
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_information/1'|ezurl} title="{'Show additional information.'|i18n( 'design/admin/node/view/full' )}">{'Information'|i18n( 'design/admin/node/view/full' )}</a>
    </div></div></div>
    {/section}
    </li>

    {* Languages. *}
    {section show=ezpreference( 'admin_navigation_languages' )}
    <li class="selected">
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a class="enabled" href={'/user/preferences/set/admin_navigation_languages/0'|ezurl} title="{'Hide available translations.'|i18n( 'design/admin/node/view/full' )}">{'Languages'|i18n( 'design/admin/node/view/full' )}</a>
    </div></div></div>
    {section-else}
    <li>
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_languages/1'|ezurl} title="{'Show available translations.'|i18n( 'design/admin/node/view/full' )}">{'Languages'|i18n( 'design/admin/node/view/full' )}</a>
    </div></div></div>
    {/section}
    </li>

    {* Locations. *}
    {section show=ezpreference( 'admin_navigation_locations' )}
    <li class="selected">
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a class="enabled" href={'/user/preferences/set/admin_navigation_locations/0'|ezurl} title="{'Hide location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations'|i18n( 'design/admin/node/view/full' )}</a>
    </div></div></div>
    {section-else}
    <li>
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_locations/1'|ezurl} title="{'Show location overview.'|i18n('design/admin/node/view/full')}">{'Locations'|i18n( 'design/admin/node/view/full' )}</a>
    </div></div></div>
    {/section}
    </li>

    {* Relations. *}
    {section show=ezpreference( 'admin_navigation_relations' )}
    <li class="selected">
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a class="enabled" href={'/user/preferences/set/admin_navigation_relations/0'|ezurl} title="{'Hide relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations'|i18n( 'design/admin/node/view/full' )}</a>
    </div></div></div>
    {section-else}
    <li>
    <div class="button-bc"><div class="button-bl"><div class="button-br">
        <a href={'/user/preferences/set/admin_navigation_relations/1'|ezurl} title="{'Show relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations'|i18n( 'design/admin/node/view/full' )}</a>
    </div></div></div>
    {/section}
    </li>
</ul>

<div class="break"></div>

</div>
