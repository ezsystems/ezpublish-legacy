{def $section         = fetch( 'section', 'object', hash( 'section_id', $node.object.section_id ) )
    $visible_columns  = ezini('SubItems', 'VisibleColumns', 'admininterface.ini')
    $locales          = fetch( 'content', 'translation_list' ) }

{literal}
<script type="text/javascript">
(function() {
{/literal}

var availableLanguages = {ldelim}{*
    *}{foreach $locales as $language}{*
        *}'{$language.locale_code|wash(javascript)}': '{$language.intl_language_name|wash(javascript)}'{*
        *}{delimiter},{/delimiter}{*
    *}{/foreach}{*
*}{rdelim};

var icons = {ldelim}{*
    *}{foreach $locales as $locale}{*
        *}'{$locale.locale_code}': '{$locale.locale_code|flag_icon()}'{*
        *}{delimiter},{/delimiter}{*
    *}{/foreach}{*
*}{rdelim};

var vcols = {ldelim}

{foreach $visible_columns as $index => $object}
    {$index} : '{$object}'.split(';'){delimiter},

{/delimiter}
{/foreach}

{rdelim};

var confObj = {ldelim}


{switch match=$node.sort_field}
{case match='2'}    sortKey: "published_date",{/case}
{case match='3'}    sortKey: "modified_date",{/case}
{case match='4'}    sortKey: "section",{/case}
{case match='7'}    sortKey: "class_name",{/case}
{case match='8'}    sortKey: "priority",{/case}
{case match='9'}    sortKey: "name",{/case}
{case}    sortKey: "published_date",{/case}
{/switch}

    dataSourceURL: "{concat('ezjscore/call/ezjscnode::subtree::', $node.node_id)|ezurl('no')}",
    editPrefixURL: {'/content/edit/'|ezurl},
    rowsPrPage: {$number_of_items},
    sortOrder: {$node.sort_order},
    nameFilter: '{$view_parameters.namefilter}',
    defaultShownColumns: vcols,
    navigationPart: "{$section.navigation_part_identifier}",
    cookieName: "eZSubitemColumns",
    cookieSecure: false,
    cookieDomain: "{ezsys(hostname)}",
    languages: availableLanguages,
    classesString: {$node.classes_js_array},
    flagIcons: icons

{rdelim}


var labelsObj = {ldelim}


    DATA_TABLE: {ldelim}

                        msg_loading: "{'Loading ...'|i18n( 'design/admin/node/view/full' )|wash('javascript')}"
                    {rdelim},

    DATA_TABLE_COLS: {ldelim}

                        thumbnail: "{'Thumbnail'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        name: "{'Name'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        visibility: "{'Visibility'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        type: "{'Type'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        modifier: "{'Modifier'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        modified: "{'Modified'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        published: "{'Published'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        section: "{'Section'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        translations: "{'Translations'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        priority: "{'Priority'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        nodeid: "{'Node ID'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        objectid: "{'Object ID'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        noderemoteid: "{'Node remote ID'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        objectremoteid: "{'Object remote ID'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        objectstate: "{'Object state'|i18n( 'design/admin/node/view/full' )|wash('javascript')}"
                    {rdelim},

    TABLE_OPTIONS: {ldelim}

                        header: "{'Table options'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        header_noipp: "{'Number of items per page:'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        header_vtc: "{'Visible table columns:'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        button_close: "{'Close'|i18n( 'design/admin/node/view/full' )|wash('javascript')}"
                   {rdelim},

    ACTION_BUTTONS: {ldelim}

                        select: "{'Select'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        select_sav: "{'Select all visible'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        select_sn: "{'Select none'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        select_inv: "{'Invert selection'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        create_new: "{'Create new'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        more_actions: "{'More actions'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        more_actions_rs: "{'Remove selected'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        more_actions_ms: "{'Move selected'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        more_actions_no: "{'Use the checkboxes to select one or more items.'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        table_options: "{'Table options'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        first_page: "&laquo;&nbsp;{'first'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        previous_page: "&lsaquo;&nbsp;{'prev'|i18n( 'design/admin/node/view/full' )|wash('javascript')}",
                        next_page: "{'next'|i18n( 'design/admin/node/view/full' )|wash('javascript')}&nbsp;&rsaquo;",
                        last_page: "{'last'|i18n( 'design/admin/node/view/full' )|wash('javascript')}&nbsp;&raquo;"
                    {rdelim}


{rdelim};

{if and( $node.is_container,  $node.can_create)}
    {if $node.path_array|contains( ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
        {def $group_id = array( ezini( 'ClassGroupIDs', 'Users', 'content.ini' ),
                                ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ) )}
    {elseif $node.path_array|contains( ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
        {def $group_id = array( ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ),
                                ezini( 'ClassGroupIDs', 'Content', 'content.ini' ),
                                ezini( 'ClassGroupIDs', 'Media', 'content.ini' ) )}
    {else}
        {def $group_id = false()}
    {/if}

    {def $can_create_classes = fetch( 'content', 'can_instantiate_class_list', hash( 'parent_node', $node,
                                                                                     'filter_type', 'exclude',
                                                                                     'group_id', $group_id,
                                                                                     'group_by_class_group', true() ) )}

    var createGroups = [

    {foreach $can_create_classes as $group}
        "{$group.group_name}"
        {delimiter}
        ,
        {/delimiter}
    {/foreach}

    ];

    var createOptions = [

    {foreach $can_create_classes as $group}
        [
        {foreach $group.items as $can_create_class}
            {if $can_create_class.can_instantiate_languages}
            {ldelim} text: "{$can_create_class.name|wash()}", value: {$can_create_class.id} {rdelim}

            {/if}
            {delimiter}{if $can_create_class.can_instantiate_languages},{/if}{/delimiter}
        {/foreach}
        ]
        {delimiter}
        ,
        {/delimiter}
    {/foreach}
    ];

{else}
    var createGroups = [];
    var createOptions = [];
{/if}

{literal}
YUILoader.require(['datatable', 'button', 'container', 'cookie', 'element']);
YUILoader.onSuccess = function() {
    sortableSubitems.init(confObj, labelsObj, createGroups, createOptions);
};
var options = [];
YUILoader.insert(options, 'js');

})();
{/literal}
{undef $section $visible_columns $locales}
</script>

<div id="action-controls-container">
    <div id="action-controls"></div>
    <div id="tpg"></div>
</div>
<div id="content-sub-items-list" class="content-navigation-childlist"></div>
<div id="bpg"></div>

<div id="to-dialog-container"></div>
