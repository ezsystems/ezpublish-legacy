// JavaScript Document
function handleSelect(type,args,obj) {
    var dates = args[0];
    var date = dates[0];
    var year = date[0], month = date[1], day = date[2];

    var objIDArray = obj.id.split( '_' );
    var id = objIDArray[3];
    var datatype = objIDArray[1];
    var base = objIDArray[0];

    var txtYear = document.getElementsByName( base + '_' + datatype + '_year_' + id );
    txtYear[0].value = year;

    var txtMonth = document.getElementsByName( base + '_' + datatype + '_month_' + id );
    txtMonth[0].value = month;

    var txtDay = document.getElementsByName( base + '_' + datatype + '_day_' + id );
    txtDay[0].value = day;

    window['cal'+id].hide();
}

function showDatePicker( base, id, datatype ) {
    var calIconID = base + '_' + datatype + '_cal_' + id;
    var calContainerID = base + '_' + datatype + '_cal_container_' + id;
    var calContainer = document.getElementById( calContainerID );

    var xy = YAHOO.util.Dom.getXY( calIconID );

    calContainer.style.left = ( xy[0] + 26 ) + 'px';
    calContainer.style.top = ( xy[1] + 30 ) + 'px';
    calContainer.style.display = 'block';

    window['cal'+id] = new YAHOO.widget.Calendar( base + '_' + datatype + '_cal_' + id , calContainerID, { close: true, 
                                                                                              mindate: "1/1/1970",
                                                                                              LOCALE_WEEKDAYS: "medium" } );
    window['cal'+id].render();
    window['cal'+id].selectEvent.subscribe( handleSelect, window['cal'+id], true );
}