function toggleEvenRows(tableid, show)
{
 var table=document.getElementById(tableid);

 if (table){
   var rows=table.rows;

   var i;
   var style;
   var row;
   var display;

   for(i=0;i<rows.length;i++)
   {
      if (i%2 == 1){
         row = rows[i];
         style = row.style;

         if (!show){
            display='none';
         } else {
            if ( row.currentStyle ){
               // Internet Explorer way
               display = 'block';
            } else {
               // W3C way
               display = 'table-row';
            }
         }

         style.display = display;
      }
   }
 }
}

function addAttribute(selectid,tableid,classid)
{
 var typeSelection=document.getElementById(selectid);
 var attributesTable=document.getElementById(tableid);

 if (attributesTable && typeSelection){
    var selectedOption=typeSelection.options[typeSelection.selectedIndex];
    var success=xajax_addClassAttribute(classid, selectedOption.value);
    return success;
 }
}

function addNewAttributeRows(attribid)
{
    var table=document.getElementById( "AttributesTable" );

    if ( table != null )
    {
        var rows=table.rows;

        var newHeaderRow=table.insertRow(rows.length);
        newHeaderRow.id="AttributeHeaderRow_" + attribid;

        var newHeader1=document.createElement( "th" );
        newHeader1.className="tight";
        newHeaderRow.appendChild(newHeader1);
        newHeader1.id="newHeader" + attribid + "_1";

        var newHeader2=document.createElement( "th" );
        newHeader2.className="wide";
        newHeaderRow.appendChild(newHeader2);
        newHeader2.id="newHeader" + attribid + "_2";

        var newHeader3=document.createElement( "th" );
        newHeader3.className="tight";
        newHeaderRow.appendChild(newHeader3);
        newHeader3.id="newHeader" + attribid + "_3";

        var newRow=table.insertRow(rows.length);
        newRow.id="newRow" + attribid;

        var newCell1=newRow.insertCell(0);
        newCell1.id="newCell" + attribid + "_1";

        var newCell2=newRow.insertCell(1);
        newCell2.colSpan=2;
        newCell2.id="newCell" + attribid + "_2";
    }
    else
    {
        window.alert( 'Unable to find attributes table.' );
    }

    var buttonArray=new Array('RemoveButton','CollapseButton','ExpandButton', 'TogglePlacementButton');
    var button;
    var j;
    for(j=0;j<buttonArray.length;j++)
    {
        button=document.getElementById(buttonArray[j]);
        if(button){
           button.className=button.className.replace('button-disabled','button');
           button.disabled=false;
        }
    }

    var noAttributes=document.getElementById('NoAttributesMessage');
    if (noAttributes){
     noAttributes.parentNode.removeChild(noAttributes);
    }
}

function moveAttributeRows( attribid, direction )
{
    if ( typeof direction == 'string' ) {
        var directionInt = parseInt(direction);
        var directionBool = new Boolean(directionInt);
        direction = directionBool.valueOf();
    }
    var attributesTable=document.getElementById( 'AttributesTable' );

    if ( attributesTable != null )
    {
        var rows=attributesTable.rows;

        var i;
        var element;
        for(i=0;i<rows.length;i++)
        {
            if ( rows[i].id == ( "AttributeHeaderRow_" +  attribid ) )
            {
                attributeHeader = rows[i];
                attribute = rows[i+1];

                if ( direction )
                {
                    //move down
                    if ( i == rows.length -2 )
                    {
                        // last attribute, move to top
                        attribute.parentNode.insertBefore( attribute, rows[0] );
                        attribute.parentNode.insertBefore( attributeHeader, attribute );
                        recalculatePlacement();
                        break;
                    }
                    else
                    {
                        var nextAttributeHeader = rows[i+2];
                        var nextAttribute = rows[i+3];
                        attribute.parentNode.insertBefore( nextAttribute, attributeHeader );
                        attribute.parentNode.insertBefore( nextAttributeHeader, nextAttribute );
                        recalculatePlacement();
                        break;
                    }
                }
                else
                {
                    // move up
                    if ( i == 0 )
                    {
                        // first attribute, move to bottom
                        attribute.parentNode.appendChild( attribute );
                        attribute.parentNode.insertBefore( attributeHeader, attribute );
                        recalculatePlacement();
                        break;
                    }
                    else
                    {
                        var previousAttributeHeader = rows[i-2];
                        attribute.parentNode.insertBefore( attribute, previousAttributeHeader );
                        attribute.parentNode.insertBefore( attributeHeader, attribute );
                        recalculatePlacement();
                        break;
                    }
                }
            }
        }
    }
}

function recalculatePlacement()
{
    var attributesForm=document.getElementById( 'ClassEditForm' );
    var priortyBox = attributesForm.elements;
    j=1;
    for(i=0;i<priortyBox.length;i++ )
    {
        if( (priortyBox[i].id).substring(0, 12 ) =='attrPriority' )
        {
            priortyBox[i].value = j++;
        }
    }
}

function switchPlacementMode( tableid )
{
    var table=document.getElementById(tableid);
    for ( i=0; i<table.rows.length;i+=2 )
    {
        var cell = table.rows[i].cells[2].getElementsByTagName('input');
        if ( cell[0].style.display == 'none' )
        {
            cell[0].style.display = '';
            cell[1].style.display = '';
            cell[2].style.display = 'none';
        }
        else
        {
            cell[0].style.display = 'none';
            cell[1].style.display = 'none';
            cell[2].style.display = '';
        }
    }
}

