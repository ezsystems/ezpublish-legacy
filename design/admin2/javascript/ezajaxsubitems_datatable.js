var sortableSubitems = function () {

    var confObj;
    var labelsObj;
    var createGroups;
    var createOptions;
    var dataTable;
    var shownColumns;

    //Retrieves multiple values delimited by '|'
    function getCookieSubMultiValue(subName) {
        var sub = YAHOO.util.Cookie.getSub(confObj.cookieName, subName);
        if (sub) { sub = unescape(sub).split('|'); }
        return sub;
    }

    // Adds or replaces subcookie with multiple delimited ('|') values
    // Sets expiry date 10 years from now
    function setCookieSubMultiValue(subName, values) {
        var joined = values.join('|');
        var expiresDate = new Date();
        expiresDate.setFullYear(expiresDate.getFullYear() + 10);

        YAHOO.util.Cookie.setSub(confObj.cookieName, subName, escape(joined), {
            path : "/",
            expires : expiresDate,
            secure : confObj.cookieSecure
        });
    }

    function initDataTable(){
        var formatName = function(cell, record, column, data) {
            cell.innerHTML =  '<a href="' + record.getData('url') + '" title="' + data + '">' + record.getData('class_icon') + '</a>' + '&nbsp;' + '<a href="' + record.getData('url') + '" title="' + data + '">' + data + '</a>';
        }

        var customCheckbox = function(cell, record, column, data) {
            cell.innerHTML = '<input type="checkbox" name="DeleteIDArray[]" value="' + record.getData('node_id') + '" />';
        }

        var customMenu = function(cell, rec, column, data) {
            var createhereMenu = (confObj.classesString != '') ? -1 : "\'child-menu-create-here\'";
            var translationArray = [];
            jQuery(rec.getData('translations')).each(function(i, e) {
                translationArray.push( { 'locale': e,
                                         'name': confObj.languages[e] } );
            });
            var a = new YAHOO.util.Element(document.createElement('a'));
            a.on('click', function(e) {
                ezpopmenu_showTopLevel(e, 'SubitemsContextMenu', { '%nodeID%': rec.getData('node_id'),
                                                                   '%objectID%': rec.getData('contentobject_id'),
                                                                   '%version%': rec.getData('version'),
                                                                   '%languages%': translationArray,
                                                                   '%classList%': confObj.classesString }, rec.getData('name'), rec.getData('node_id'), createhereMenu );
            });
            var div = new YAHOO.util.Element(document.createElement('div'));
            div.addClass('crankfield');
            div.appendTo(a);

            a.appendTo(cell);
        }

        var thumbView = function(cell, record, column, data) {
            var url = record.getData('thumbnail_url');
            if (url) {
                var thBack = 'background: url(' + url + ') no-repeat;';
                var thWidth = ' width: ' + record.getData('thumbnail_width') + 'px;';
                var thHeight = ' height: ' + record.getData('thumbnail_height') + 'px;';
                cell.innerHTML = '<div class="thumbview"><div id="thumbfield" class="thumbfield"></div><span><div style="' + thBack + thWidth + thHeight + '"></div></span></div>';
            }
            else {
                cell.innerHTML = '';
            }
        }

        var translationView = function(cell, record, column, data) {
            var html = '';
            jQuery(data).each(function(i, e) {
                if (record.getData('can_edit') === true) {
                    html += '<a href="' + confObj.editPrefixURL + '/' + record.getData('contentobject_id') + '/f/' + e + '">';
                }
                html += '<img src="' + confObj.flagIcons[e] + '" width="18" height="12" style="margin-right: 4px;" alt="' + e + '" title="' + e + '"/>';
                if (record.getData('can_edit') === true) {
                    html += '</a>'
                }
            });
            cell.innerHTML = html;
        }

        var updatePriority = function(callback, v) {
            var record = this.getRecord(), dataTable = this.getDataTable(), sortedBy = dataTable.get('sortedBy'), paginator = dataTable.get('paginator');

            var onSuccess = function(data) {
                dataTable.getDataSource().flushCache();
                if (sortedBy.key == 'priority') {
                    dataTable.onPaginatorChangeRequest(paginator.getState({'page':paginator.getCurrentPage()}));
                }
            }

            jQuery.ez('ezjscnode::updatepriority', {
                ContentNodeID: record.getData('parent_node_id'),
                ContentObjectID: record.getData('contentobject_id'),
                PriorityID: [record.getData('node_id')],
                Priority: [v]
            }, onSuccess);
            callback(true, v);
        }

        var columnDefs = [
            {key:"checkbox", label:"", formatter:customCheckbox, resizeable:false},
            {key:"crank", label:"", sortable:false, formatter:customMenu, resizeable:false},
            {key:"thumbnail", label:labelsObj.DATA_TABLE_COLS.thumbnail, sortable:false, formatter:thumbView, resizeable:false},
            {key:"name", label:labelsObj.DATA_TABLE_COLS.name, sortable:true, resizeable:true, formatter:formatName},
            {key:"hidden_status_string", label: labelsObj.DATA_TABLE_COLS.visibility, sortable:false, resizeable:true},
            {key:"class_name", label:labelsObj.DATA_TABLE_COLS.type, sortable:true, resizeable:true},
            {key:"creator", label:labelsObj.DATA_TABLE_COLS.modifier, sortable:false, resizeable:true},
            {key:"modified_date", label:labelsObj.DATA_TABLE_COLS.modified, sortable:true, resizeable:true},
            {key:"published_date", label:labelsObj.DATA_TABLE_COLS.published, sortable:true, resizeable:true},
            {key:"translations", label:labelsObj.DATA_TABLE_COLS.translations, sortable:false, resizeable:true, formatter:translationView},
            {key:"section", label:labelsObj.DATA_TABLE_COLS.section, sortable:true, resizeable:true},
            {key:"node_id", label:labelsObj.DATA_TABLE_COLS.nodeid, sortable:true, resizeable:true},
            {key:"node_remote_id", label:labelsObj.DATA_TABLE_COLS.noderemoteid, sortable:false, resizeable:true},
            {key:"contentobject_id", label:labelsObj.DATA_TABLE_COLS.objectid, sortable:true, resizeable:true},
            {key:"contentobject_remote_id", label:labelsObj.DATA_TABLE_COLS.objectremoteid, sortable:false, resizeable:true},
            {key:"contentobject_state", label:labelsObj.DATA_TABLE_COLS.objectstate, sortable:false, resizeable:true},
            {key:"priority", label: labelsObj.DATA_TABLE_COLS.priority, sortable:true, resizeable:true,
                editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: updatePriority, disableBtns:true, validator:YAHOO.widget.DataTable.validateNumber}) }
        ];

        // Hide columns based on cookie with ini setting as fallback
        // If neither cookie or ini is set: show all columns
        // Thumbnail column header has label, but is hidden w/CSS
        if (shownColumns && shownColumns.length != 0) {
            var defsLength = columnDefs.length;
            for (var i = 0, l = defsLength; i < l; i++) {
                var columnDef = columnDefs[i];
                if ((jQuery.inArray(columnDef.key, shownColumns) == -1) && columnDef.label != '')
                    columnDef.hidden = true;
            }
        }

        var sectionParser = function(section) {
            if ( section && section.name )
                return section.name;
            return '?';
        }

        var translationsParser = function(translations) {
            return translations.language_list;
        }

        var creatorParser = function(creator) {
            if ( creator && creator.name )
                return creator.name;
            return '?';
        }

        var dataSource = new YAHOO.util.DataSource(confObj.dataSourceURL);
        dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
        dataSource.maxCacheEntries = 20;    // Caches between paginations. Requires a refresh after async updates to priorities
        dataSource.responseSchema = {
            resultsList: "content.list",
            fields: [
                {key:"name"},
                {key:"hidden_status_string"},
                {key:"class_name"},
                {key:"creator", parser:creatorParser},
                {key:"modified_date"},
                {key:"published_date"},
                {key:"section", parser:sectionParser},
                {key:"translations", parser:translationsParser},
                {key:"version"},
                {key:"node_id"},
                {key:"node_remote_id"},
                {key:"contentobject_id"},
                {key:"contentobject_remote_id"},
                {key:"contentobject_state"},
                {key:"priority"},
                {key:"class_icon"},
                {key:"thumbnail_url"},
                {key:"thumbnail_height"},
                {key:"thumbnail_width"},
                {key:"url"},
                {key:"parent_node_id"},
                {key:"can_edit"}
            ],
            metaFields: {
                totalRecords: "content.total_count" // Access to value in the server response
            }
        };

        var paginator = new YAHOO.widget.Paginator({ rowsPerPage:confObj.rowsPrPage,
                                                     containers: ["bpg"],
                                                     firstPageLinkLabel : labelsObj.ACTION_BUTTONS.first_page,
                                                     lastPageLinkLabel : labelsObj.ACTION_BUTTONS.last_page,
                                                     previousPageLinkLabel : labelsObj.ACTION_BUTTONS.previous_page,
                                                     nextPageLinkLabel : labelsObj.ACTION_BUTTONS.next_page,
                                                     template : '<div class="yui-pg-backward"> {FirstPageLink} {PreviousPageLink} </div>' +
                                                                '{PageLinks}' +
                                                                '<div class="yui-pg-forward"> {NextPageLink} {LastPageLink} </div>'
                                                     });

        paginator.subscribe('render', function () {
            var prevPageLink, prevPageLink, prevPageLinkNode, nextPageLinkNode, tpg;

            tpg = YAHOO.util.Dom.get('tpg');

            // Instantiate the UI Component
            prevPageLink = new YAHOO.widget.Paginator.ui.PreviousPageLink(this);
            nextPageLink = new YAHOO.widget.Paginator.ui.NextPageLink(this);

            // render the UI Component
            prevPageLinkNode = prevPageLink.render(tpg);
            nextPageLinkNode = nextPageLink.render(tpg);

            // Append the generated node into the container
            tpg.appendChild(prevPageLinkNode);
            tpg.appendChild(nextPageLinkNode);
        });

        var buildQueryString = function (state,dt) {
            return "::" + state.pagination.rowsPerPage +
                   "::" + state.pagination.recordOffset +
                   "::" + state.sortedBy.key +
                   "::" + ((state.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "1" : "0") +
                   "::" + confObj.nameFilter +
                   "?ContentType=json";
        }

        var tableConfig = {
            initialRequest: "::" + confObj.rowsPrPage + "::0" + "::" + confObj.sortKey + "::" + confObj.sortOrder + "::" + confObj.nameFilter + "?ContentType=json",   // Initial request for first page of data
            dynamicData: true,                                                                                                             // Enables dynamic server-driven data
            generateRequest: buildQueryString,
            sortedBy : {key:confObj.sortKey,
                        dir:((confObj.sortOrder === 1) ? YAHOO.widget.DataTable.CLASS_ASC : YAHOO.widget.DataTable.CLASS_DESC) },          // Sets UI initial sort arrow
            paginator: paginator,                                                                                                          // Enables pagination
            MSG_LOADING: labelsObj.DATA_TABLE.msg_loading
        };

        subItemsTable = new YAHOO.widget.DataTable( "content-sub-items-list",
                                                        columnDefs,
                                                        dataSource,
                                                        tableConfig );

        // Enables the cell editing when row has can_edit=true
        subItemsTable.subscribe("cellClickEvent", function (oArgs) {
            var target = oArgs.target,
            record = this.getRecord(target);
            if (record.getData('can_edit') === true) {
                this.showCellEditor(target);
            }
        });

        // Update totalRecords on the fly with value from server
        subItemsTable.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {
            oPayload.totalRecords = oResponse.meta.totalRecords;
            return oPayload;
        }

        // Table options

        // Shows dialog, creating one when necessary
        var colLayoutHasChanged = true;
        var showTblOptsDialog = function(e) {
            YAHOO.util.Event.stopEvent(e);

            if (colLayoutHasChanged) {
                // Populate Dialog
                var colOptionsHTML = '<fieldset>';
                colOptionsHTML += '<legend>' + labelsObj.TABLE_OPTIONS.header_noipp + '</legend><div class="block">';

                var rowsPerPageDef = [ {id:1, count:10}, {id:2, count:25}, {id:3, count:50} ];

                var rowsPerPageDefLen = rowsPerPageDef.length;
                for (var i = 0, l = rowsPerPageDefLen; i < l ; i++) {
                    var rowDef = rowsPerPageDef[i];
                    colOptionsHTML += '<div class="table-options-row"><span class="table-options-key">'+ rowDef.count + '</span>';
                    colOptionsHTML += '<span class="table-options-value"><input id="table-option-row-btn-' + rowDef.id + '" type="radio" name="TableOptionValue" value="' + rowDef.count + '"' + ( confObj.rowsPrPage == rowDef.count ? ' checked="checked"' : '' ) + ' /></span></div>';

                    YAHOO.util.Event.on("table-option-row-btn-" + rowDef.id, "click", function(e, a) {
                        paginator.setRowsPerPage(a.count);
                        $.ez.setPreference('admin_list_limit', a.id);
                    }, rowDef);
                }

                colOptionsHTML += '</div></fieldset><br />';
                colOptionsHTML += '<fieldset>';

                var columns = subItemsTable.getColumnSet().keys;
                colOptionsHTML += '<legend>' + labelsObj.TABLE_OPTIONS.header_vtc + '</legend><div class="block">';

                // Create one section in the SimpleDialog for each column
                var columnsLength = columns.length;
                for (var i = 0, l = columnsLength; i < l; i++) {
                    var column = columns[i], label = column.getDefinition().label, key = column.getDefinition().key;

                    // Skip empty columns
                    if (!label || !key)
                        continue;

                    colOptionsHTML += '<div class="table-options-row"><span class="table-options-key">'+ label + '</span>';
                    colOptionsHTML += '<span class="table-options-value"><input id="table-option-col-btn-' + i + '" type="checkbox" name="TableOptionColumn" value="' + key + '"' + ( jQuery.inArray( key, shownColumns ) != -1 ? ' checked="checked"' : ''  ) + ' /></span></div>';

                    YAHOO.util.Event.on("table-option-col-btn-" + i, "click", function(e, a) {
                        if (this.checked) {
                            subItemsTable.showColumn(a);
                        }
                        else {
                            subItemsTable.hideColumn(a);
                        }
                        var shownKeys = [];
                        $('#to-dialog-container input[name=TableOptionColumn]').each(function(i, e) {
                            if ( $(this).prop('checked') )
                                shownKeys.push( $(this).prop('value') );
                        });

                        // Update cookie and local variable
                        setCookieSubMultiValue(confObj.navigationPart, shownKeys);
                        shownColumns = shownKeys;
                    }, key);
                }

                colOptionsHTML += '</div></fieldset>';

                tblOptsDialog.setBody(colOptionsHTML);
                colLayoutHasChanged = false;
            }
            tblOptsDialog.show();
        };

        var hideTblOptsDialog = function(e) {
            this.hide();
        };


        // SimpleDialog for Table options

        var tblOptsDialog = new YAHOO.widget.SimpleDialog("to-dialog-container", { width: "25em",
                                                                                   visible: false,
                                                                                   modal: true,
                                                                                   buttons: [ { text: labelsObj.TABLE_OPTIONS.button_close,
                                                                                                handler: hideTblOptsDialog } ],
                                                                                   fixedcenter: "contained",
                                                                                   constrainToViewport: true });

        var escKeyListener = new YAHOO.util.KeyListener(document, { keys:27 },
                                                                  { fn:tblOptsDialog.hide,
                                                                    scope:tblOptsDialog,
                                                                    correctScope:true } );

        tblOptsDialog.cfg.queueProperty("keylisteners", escKeyListener);
        tblOptsDialog.setHeader(labelsObj.TABLE_OPTIONS.header);
        tblOptsDialog.render();


        // Toolbar buttons: Select, Create new, More actions

        var selectItemsBtnAction = function( type, args, item ) {
            $('#content-sub-items-list').find(':checkbox').prop('checked', item.value);
        }

        var selectItemsBtnInvert = function( type, args, item ) {
            var checks = $('#content-sub-items-list').find(':checkbox');
            checks.each(function(){this.checked = !this.checked;});
        }

        var selectItemsBtnActions = [
            { text: labelsObj.ACTION_BUTTONS.select_sav, id: "ezopt-menu-check", value: 1, onclick: { fn: selectItemsBtnAction } },
            { text: labelsObj.ACTION_BUTTONS.select_sn, id: "ezopt-menu-uncheck", value: 0, onclick: { fn: selectItemsBtnAction } },
            { text: labelsObj.ACTION_BUTTONS.select_inv, id: "ezopt-menu-toggle", onclick: { fn: selectItemsBtnInvert } }
        ];

        var selectItemsBtn = new YAHOO.widget.Button({ type: "menu",
                                                       id: "ezbtn-items",
                                                       label: labelsObj.ACTION_BUTTONS.select,
                                                       name: "select-items-button",
                                                       menu: selectItemsBtnActions,
                                                       container:"action-controls" });

        var createNewBtnAction = function( type, args ) {
            var event = args[0], item = args[1];
            $('form[name=children]').append($('<input type="hidden" name="ClassID" value="' + item.value + '" />')).append($('<input type="hidden" name="NewButton" />')).submit();
        }

        var createNewBtn = new YAHOO.widget.Button({ type: "menu",
                                                     id: "ezbtn-new",
                                                     label: labelsObj.ACTION_BUTTONS.create_new,
                                                     name: "create-new-button",
                                                     menu: createOptions,
                                                     container:"action-controls" });

        // Disable button if user has no available content classes to create objects of
        if (createGroups.length === 0) createNewBtn.set('disabled',true);

        var createNewBtnMenu  = createNewBtn.getMenu();
        createNewBtnMenu.cfg.setProperty("scrollincrement", 5);
        createNewBtnMenu.subscribe("click", createNewBtnAction);

        var createNewBthGroupsLength = createGroups.length;
        for (var i = 0, l = createNewBthGroupsLength; i < l; i++) {
            var groupName = createGroups[i];
            createNewBtnMenu.setItemGroupTitle(groupName, i);
        }


        var moreActBtnAction = function( type, args, item ) {
            if ($('form[name=children] input[name=DeleteIDArray[]]:checked').length == 0)
                return;

            if (item.value == 0) {
                $('form[name=children]').append($('<input type="hidden" name="RemoveButton" />')).submit();
            } else {
                $('form[name=children]').append($('<input type="hidden" name="MoveButton" />')).submit();
            }
        }

        var moreActBtnActions = [
            { text: labelsObj.ACTION_BUTTONS.more_actions_rs, id: "ezopt-menu-remove", value: 0, onclick: { fn: moreActBtnAction }, disabled: false },
            { text: labelsObj.ACTION_BUTTONS.more_actions_ms, id: "ezopt-menu-move", value: 1, onclick: { fn: moreActBtnAction }, disabled: false }
        ];

        var noMoreActBtnActions = [
            { text: labelsObj.ACTION_BUTTONS.more_actions_no, disabled: true }
        ];

        var moreActBtn = new YAHOO.widget.Button({ type: "menu",
                                                   id: "ezbtn-more",
                                                   label: labelsObj.ACTION_BUTTONS.more_actions,
                                                   name: "more-actions-button",
                                                   menu: noMoreActBtnActions,
                                                   container:"action-controls" });

        //  enable 'more actions' when rows are checked
        moreActBtn.getMenu().subscribe("beforeShow", function () {
            if ($('form[name=children] input[name=DeleteIDArray[]]:checked').length == 0) {
                this.clearContent();
                this.addItems(noMoreActBtnActions);
                this.render();
            } else {
                this.clearContent();
                this.addItems(moreActBtnActions);
                this.render();
            }
        });

        var tableOptionsBtn = new YAHOO.widget.Button({ label: labelsObj.ACTION_BUTTONS.table_options,
                                                        id:"ezbtn-options",
                                                        container:"action-controls",
                                                        onclick: { fn: showTblOptsDialog, obj: this, scope: true } });

    return subItemsTable;
    }

    return {
        init: function (conf, labels, groups, options) {
            confObj = conf
            labelsObj = labels;
            createGroups = groups;
            createOptions = options;

            shownColumns = getCookieSubMultiValue(confObj.navigationPart);
            if (shownColumns == null) shownColumns = confObj.defaultShownColumns[confObj.navigationPart];

            dataTable = initDataTable();
        }
    };

}();

