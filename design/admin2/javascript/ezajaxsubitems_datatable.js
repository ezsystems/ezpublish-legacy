var sortableSubitems = function () {

    var confObj;
    var labelsObj;
    var createGroups;
    var createOptions;
    var dataTable;
    var menuItems;
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
            secure : confObj.cookieSecure,
            domain : confObj.cookieDomain
        });
    }

    function initDataTable(){
        var formatName = function(cell, record, column, data) {
            cell.innerHTML =  record.getData('class_icon') + '&nbsp' + '<a href="' + record.getData('url') + '" title="' + data + '">' + data + '</a>';
        }

        var customCheckbox = function(cell, record, column, data) {
            cell.innerHTML = '<input type="checkbox" name="DeleteIDArray[]" value="' + record.getData('node_id') + '" />';
        }

        var customButton = function(cell, record, column, data) {
            cell.innerHTML = '<button class="yui-dt-button" type="button"><div class="hide">Click</div></button>';
        }
        
        var updatePriority = function(callback, v) {
            var record = this.getRecord(), dataTable = this.getDataTable(), sortedBy = dataTable.get('sortedBy'), paginator = dataTable.get('paginator');
            
            var onSuccess = function(data) {
                if (sortedBy.key == 'priority') {
                    dataTable.onPaginatorChangeRequest(paginator.getState({'page':paginator.getCurrentPage()}));
                }
            }
            
            jQuery.post(jQuery.ez.url + 'call/ezjscnode::updatepriority', { ContentNodeID: record.getData('parent_node_id'), 
                                                                            PriorityID: [record.getData('node_id')], 
                                                                            Priority:  [v] }, onSuccess );
            callback(true, v);
        }

        var columnDefs = [
            {key:"", label:"", formatter:customCheckbox},
            {key:"", label:"", sortable:false, formatter:customButton},
            {key:"name", label:labelsObj.DATA_TABLE_COLS.name, sortable:true, resizeable:true, formatter:formatName},
            {key:"hidden_status_string", label: labelsObj.DATA_TABLE_COLS.visibility, sortable:false, resizeable:true},
            {key:"class_name", label:labelsObj.DATA_TABLE_COLS.type, sortable:true, resizeable:true},
            {key:"creator", label:labelsObj.DATA_TABLE_COLS.modifier, sortable:false, resizeable:true},
            {key:"modified_date", label:labelsObj.DATA_TABLE_COLS.modified, sortable:true, resizeable:true},
            {key:"published_date", label:labelsObj.DATA_TABLE_COLS.published, sortable:true, resizeable:true},
            {key:"section", label:labelsObj.DATA_TABLE_COLS.section, sortable:true, resizeable:true},
            {key:"node_id", label:labelsObj.DATA_TABLE_COLS.nodeid, sortable:true, resizeable:true},
            {key:"contentobject_id", label:labelsObj.DATA_TABLE_COLS.contentid, sortable:true, resizeable:true},
            {key:"priority", label: labelsObj.DATA_TABLE_COLS.priority, sortable:true, resizeable:true, 
                editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: updatePriority, disableBtns:true, validator:YAHOO.widget.DataTable.validateNumber}) }
        ];

        // Hide columns based on cookie with ini setting as fallback
        // If neither cookie or ini is set: show all columns
        if (shownColumns && shownColumns.length != 0) {
            var defsLength = columnDefs.length;
            for (var i = 0, l = defsLength; i < l; i++) {
                var columnDef = columnDefs[i];
                if ((jQuery.inArray(columnDef.key, shownColumns) == -1) && columnDef.key != '')
                    columnDef.hidden = true;
            }
        }

        var sectionParser = function(section) {
            return section.name;
        }

        var creatorParser = function(creator) {
            return creator.name;
        }

        var dataSource = new YAHOO.util.DataSource(confObj.dataSourceURL);
        dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
        dataSource.responseSchema = {
            resultsList: "content.list",
            fields: [
                {key:"name"},
                {key:"modified_date"},
                {key:"hidden_status_string"},
                {key:"class_name"},
                {key:"creator", parser:creatorParser},
                {key:"section", parser:sectionParser},
                {key:"priority"},
                {key:"url"},
                {key:"class_icon"},
                {key:"node_id"},
                {key:"parent_node_id"},
                {key:"published_date"},
                {key:"contentobject_id"},
                {key:"version"}
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
                   "?ContentType=json";
        }

        var tableConfig = {
            initialRequest: "::" + confObj.rowsPrPage + "::0" + "::" + confObj.sortKey + "::" + confObj.sortOrder + "?ContentType=json",   // Initial request for first page of data
            dynamicData: true,                                                                                                             // Enables dynamic server-driven data
            generateRequest: buildQueryString,
            sortedBy : {key:confObj.sortKey, 
                        dir:((confObj.sortOrder === "1") ? YAHOO.widget.DataTable.CLASS_ASC : YAHOO.widget.DataTable.CLASS_DESC) },        // Sets UI initial sort arrow
            paginator: paginator,                                                                                                          // Enables pagination
            MSG_LOADING: labelsObj.DATA_TABLE.msg_loading
        };

        subItemsTable = new YAHOO.widget.DataTable( "content-sub-items-list",
                                                        columnDefs,
                                                        dataSource,
                                                        tableConfig );

        // Cell editing
        var highlightEditableCell = function(oArgs) {
            var elCell = oArgs.target;
            if (YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
                this.highlightCell(elCell);
            }
        }

        subItemsTable.subscribe("cellMouseoverEvent", highlightEditableCell);
        subItemsTable.subscribe("cellMouseoutEvent", subItemsTable.onEventUnhighlightCell);
        subItemsTable.subscribe("cellClickEvent", subItemsTable.onEventShowCellEditor);

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
                        jQuery.post( jQuery.ez.url.replace( 'ezjscore/', 'user/preferences/set_and_exit/admin_list_limit/' ) + a.id );
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
                            if ($(this).attr('checked') == true)
                                shownKeys.push( $(this).attr('value') );
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
                                                                                   buttons: [ { text: "Close", 
                                                                                                handler: hideTblOptsDialog } ],
                                                                                   fixedcenter: true,
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
            $('#content-sub-items-list').find(':checkbox').attr('checked', item.value);
        }

        var selectItemsBtnActions = [
            { text: labelsObj.ACTION_BUTTONS.select_sav, id: "ezopt-menu-check", value: 1, onclick: { fn: selectItemsBtnAction } },
            { text: labelsObj.ACTION_BUTTONS.select_sn, id: "ezopt-menu-uncheck", value: 0, onclick: { fn: selectItemsBtnAction } }
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

        //  enable menuitems when rows are checked
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


    // Menu items are taken from the DOM array menuArray used by the ezpopupmenu.js
    function menuContent() {

        // Accepted menus from menuArray (note: using keys, since not all menus have headerID)
        var whiteList = ['advanced', 'contextmenu'];
        var items = [];

        items.push({ text: labelsObj.CONTEXT_MENU.edit, id: "ezopt-menu-edit", url: confObj.editURL});
        items.push({ text: labelsObj.CONTEXT_MENU.preview, id: "ezopt-menu-preview", url: confObj.previewURL});

        $(menuArray).each(function() {                                      // Loop array returned from jquery
            $.each(this, function(key, value) {                             // Loop menuArray
                var k = jQuery.trim(key).toLowerCase();
                if (jQuery.inArray(k, whiteList) != -1) {                   // Validate menu against whiteList
                    if (this.elements && this.elements.length != 0) {       // check for valid menu items
                        $.each( this.elements, function(key, value) {       // Loop menu element per group
                            if ($('#' + key).text() && value.url) {         // Check if element has html-markup'ed text
                                items.push({ text: $('#' + key).text(), id:"ezopt-" + key, url: value.url });
                            }
                        });
                    }
                }
            });
        });

        return items;
    }


    // Updates URL-"macros" in menuitems according to the selected node
    function formatMenuItems(menu, nodeId, objectId, version){ 
        
        $.each(menu.getItems(), function(index, item) {
            var url = item.cfg.getProperty('url');
            url = url.replace('%nodeID%', nodeId);
            url = url.replace('%objectID%', objectId);
            url = url.replace('%currentURL%', confObj.currentURL);
            url = url.replace('%version%', version);
            item.cfg.setProperty('url', url);
        });
    }


    // Action wrapper for context menu
    function formatMenuItemsAction(type, args, dt){ 
        
        var row = dt.getTrEl(this.contextEventTarget);
        var record = dt.getRecord(row);
        var nodeId = record.getData('node_id');
        var objectId = record.getData('contentobject_id');
        var version = record.getData('version');
        
        formatMenuItems(this, nodeId, objectId, version);
    }


    // Replaces all menu items with the macro URLs parsed from the menuArray
    var resetMenuItemsAction = function(type, args, menu) {
        menu.clearContent();
        menu.addItems(menuItems);
        menu.render();
    }


    // Unselect all selected rows in datatable
    var unselectTableAction = function(type, args, dt) {
        $.each(dt.getSelectedRows(), function(i, e) {
            dt.unselectRow(e);
        });
    }


    // Hide the focus outline for MenuItem instances
    var hideFocusAction = function(type, args, menu) {
        $.each(menu.getItems(), function(i,e) {
            e.element.firstChild.hideFocus = true;
        });
    }


    // Context menu on right-click
    function initContextMenu() {

        var contextMenuItemAction = function(type, args, dt) {
            var task = args[1];
            if (!task)
                return;

            var row = dt.getTrEl(this.contextEventTarget);
            if (!row)
                return;
        }

        // Selects a table row based on the context event
        var contextMenuSelectAction = function(type, args, dt) {
            var row = dt.getTrEl(this.contextEventTarget);
            dt.selectRow(row);
        }

        var contextMenu = new YAHOO.widget.ContextMenu("ezdt-context-menu", {trigger:dataTable.getTbodyEl()});
        contextMenu.cfg.setProperty("scrollincrement", 5);
        contextMenu.cfg.setProperty("lazyLoad", true);
        contextMenu.addItems(menuItems);

        //contextMenu.subscribe("render", hideFocusAction, contextMenu);

        // Render the ContextMenu instance to the parent container of the DataTable
        contextMenu.render(dataTable.configs.element);
        contextMenu.clickEvent.subscribe(contextMenuItemAction, dataTable);
        contextMenu.subscribe('beforeShow', formatMenuItemsAction, dataTable);
        contextMenu.subscribe('beforeShow', contextMenuSelectAction, dataTable);
        contextMenu.subscribe('hide', unselectTableAction, dataTable);
        contextMenu.subscribe('hide', resetMenuItemsAction, contextMenu);
    }


    function initLeftClickMenu() {

        var leftClickMenu = new YAHOO.widget.Menu("ezdt-leftclick-menu");
        leftClickMenu.cfg.setProperty("scrollincrement", 5);
        leftClickMenu.cfg.setProperty("lazyLoad", true);
        leftClickMenu.addItems(menuItems);
        leftClickMenu.render(dataTable.configs.element);

        // Hide menu when clicking Esc
        dataTable.subscribe("keydown", function(e){
            if (e.keyCode == 27) {
                leftClickMenu.hide();
                this.focus();
            }
        });

        leftClickMenu.subscribe('hide', unselectTableAction, dataTable);
        leftClickMenu.subscribe('hide', resetMenuItemsAction, leftClickMenu);

        // NOTE: Regardless of this attempt, the focus is stuck on the clicked button
        leftClickMenu.subscribe('show', function(args){
            leftClickMenu.focus();
        });

        //leftClickMenu.subscribe("render", hideFocusAction, leftClickMenu);

        dataTable.subscribe("buttonClickEvent", function(args){
            var trgt = args.target;
            var row = this.getTrEl(trgt);
            var record = this.getRecord(trgt);
            var nodeId = record.getData('node_id');
            var objectId = record.getData('contentobject_id');
            var version = record.getData('version');

            // Mark clicked row as selected
            this.selectRow(row);

            // Set alignment of menu
            leftClickMenu.cfg.setProperty("context", [trgt, "tr", "bl"]);

            // Update menu items & show
            formatMenuItems(leftClickMenu, nodeId, objectId, version);
            leftClickMenu.show();
        });
        
    }

    return {
        init: function (conf, labels, groups, options) {
            confObj = conf
            labelsObj = labels;
            createGroups = groups;
            createOptions = options;

            shownColumns = getCookieSubMultiValue(confObj.navigationPart);
            if (shownColumns == null) shownColumns = confObj.defaultShownColumns[confObj.navigationPart];

            menuItems = menuContent();      // Parsing DOM object menuArray once
            dataTable = initDataTable();    // dataTable used by menus
            initContextMenu();              // dataTable's context menu. Available in Opera via ctrl+left-click
            initLeftClickMenu();            // dataTable's left-click alternative to the context menu
        }
    };
 
}();

