{* Window controls. *}
<div class="menu-block">
<ul>
    {* Content preview. *}
    <li>
    {section show=ezpreference( 'admin_navigation_content' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_content/0'|ezurl} title="{'Hide preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_content/1'|ezurl} title="{'Show preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>

    {* Additional information. *}
    <li>
    {section show=ezpreference( 'admin_navigation_information' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_information/0'|ezurl} title="{'Hide additional information.'|i18n( 'design/admin/node/view/full' )}">{'Information'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_information/1'|ezurl} title="{'Show additional information.'|i18n( 'design/admin/node/view/full' )}">{'Information'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>

    {* Languages. *}
    <li>
    {section show=ezpreference( 'admin_navigation_languages' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_languages/0'|ezurl} title="{'Hide available translations.'|i18n( 'design/admin/node/view/full' )}">{'Languages'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_languages/1'|ezurl} title="{'Show available translations.'|i18n( 'design/admin/node/view/full' )}">{'Languages'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>

    {* Locations. *}
    <li>
    {section show=ezpreference( 'admin_navigation_locations' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_locations/0'|ezurl} title="{'Hide location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_locations/1'|ezurl} title="{'Show location overview.'|i18n('design/admin/node/view/full')}">{'Locations'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>

    {* Relations. *}
    <li>
    {section show=ezpreference( 'admin_navigation_relations' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_relations/0'|ezurl} title="{'Hide relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_relations/1'|ezurl} title="{'Show relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>

    {* Roles. *}
    <li>
    {section show=ezpreference( 'admin_navigation_roles' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_roles/0'|ezurl} title="{'Hide role overview.'|i18n( 'design/admin/node/view/full' )}">{'Roles'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_roles/1'|ezurl} title="{'Show role overview.'|i18n( 'design/admin/node/view/full' )}">{'Roles'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>

    {* Policies *}
    <li>
    {section show=ezpreference( 'admin_navigation_policies' )}
        <a class="enabled" href={'/user/preferences/set/admin_navigation_policies/0'|ezurl} title="{'Hide policy overview.'|i18n( 'design/admin/node/view/full' )}">{'Policies'|i18n( 'design/admin/node/view/full' )}</a>
    {section-else}
        <a href={'/user/preferences/set/admin_navigation_policies/1'|ezurl} title="{'Show policy overview.'|i18n( 'design/admin/node/view/full' )}">{'Policies'|i18n( 'design/admin/node/view/full' )}</a>
    {/section}
    </li>
</ul>
</div>

