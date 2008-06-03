//
// Created on: <22-Jun-2007 14:18:58 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezmultioption.js
*/
var DisabledOptions = new Array();

var OldValues = new Array();

var attributeID;


function getAllImageOptions()
{
    var allItputEl = document.getElementsByTagName('input');
    var imageOptions = new Array();
    var counter = 0;
    for ( i = 0; i < allItputEl.length; i++ )
    {
        if ( allItputEl[i].name.slice( 0, 8 ) == 'eZOption' )
	{
	    imageOptions[counter] = allItputEl[i];
	    counter++;
	}
    }
    return imageOptions;
}
function getAllOptionSelects()
{
    var allSelectEl = document.getElementsByTagName('select');
    var selectOptions = new Array();
    var counter = 0;
    for ( i = 0; i < allSelectEl.length; i++ )
    {
        if ( allSelectEl[i].name.slice( 0, 8 ) == 'eZOption' )
	{
	    selectOptions[counter] = allSelectEl[i];
	    counter++;
	}
    }
    return selectOptions;

}
function validate_options( event )
{
    var selectBoxList = getAllOptionSelects( );
    var wrongValue;
    var formOptionsSelectedIndex;
    var validated = true;
    for( var i = 0; i < selectBoxList.length; i++ )
    {
        selectedValue = selectBoxList[i].value;
         formOptionsSelectedIndex = selectBoxList[i].options.selectedIndex;
        if ( selectBoxList[i].options[formOptionsSelectedIndex].disabled )
        {
           alert( "Incorrect selection \""  + selectBoxList[i].options[formOptionsSelectedIndex].text + "\"" );
           validated = false;
        }
    }
    imageOptions = getAllImageOptions();
    for( var j = 0; j < imageOptions.length; j++ )
    {
	if ( imageOptions[j].checked && imageOptions[j].disabled )
	{
	   optionText = document.getElementById( 'td-' + imageOptions[j].id ).childNodes[0].textContent;
           alert( "Incorrect selection \""  + optionText + "\"" );
           validated = false;
	}
    }
    return validated;
}
function connect_validate_options_handler()
{
    var selectBoxList = getAllOptionSelects( );
    selectBoxList[0].form.ActionAddToBasket.onclick = validate_options;

}
function init_options( rules, attributeID1 )
{
    attributeID = attributeID1;
    var selectBoxList = getAllOptionSelects( );
    var selectedValue;

    this.onload = connect_validate_options_handler;

    for( var i = 0; i < selectBoxList.length; i++ )
    {
        selectedValue = selectBoxList[i].value;
        checkOptionsToDisable( rules, selectedValue, attributeID, selectBoxList[i] );
        OldValues[selectBoxList[i].id] =  selectBoxList[i].value;
    }

}

function addOptionToDisabledArray( optionid )
{
   if ( DisabledOptions[optionid] == null )
   {
       DisabledOptions[optionid] = 1;
   }
   else
   {
       DisabledOptions[optionid]++;
   }
}

function checkOptionsToEnable( rules, value, attributeID, node )
{
    var  nodeInfoArray = node.id.split("_");
    var  moptionid;
    if ( node.type == 'radio' )
    {
       splitArray = node.name.split( "][");
       moptionid = splitArray[1].slice(0,-1);
    } 
    else
    {
         moptionid =  nodeInfoArray[2];
    }
    var optionidlist;

    for(  var i = 0; i < rules.length; i++ )
    {
        for (  var j = 0; j < rules[i][1].	length; j++ )
        {
            moption = rules[i][1][j][0];
            if ( moptionid == moption )
            {
               optionidlist = rules[i][1][j][1];

               if (!optionidlist.inArray( value ))               
               {
                   enableOption( rules[i][0], attributeID );
               }
         
            }

        }
    }
}
 

function enableOption( optionID, attributeID )
{
     idstr = attributeID + "_" + optionID;
     if( DisabledOptions[idstr] > 1 )
     {
          DisabledOptions[idstr]--;
     }
     else
     { 
         if ( this.document.getElementById( idstr ) != null && DisabledOptions[idstr] != null )
         {
             this.document.getElementById( idstr ).disabled = false;
             this.document.getElementById( idstr ).className = '';
             DisabledOptions[idstr] = 0;
         }
     }
}


function disableOption( optionID, attributeID )
{
     var idstr;
     idstr = attributeID + "_" + optionID;

     if ( this.document.getElementById( idstr ) != null )
     {
         this.document.getElementById( idstr ).disabled = 'disabled';
         addOptionToDisabledArray( idstr );
     }
}


function checkOptionsToDisable( rules, selectedValue, attributeID, node )
{
    var  nodeInfoArray = node.id.split("_");
    var  moptionid;
    if ( node.type == 'radio' )
    {
       splitArray = node.name.split( "][");
       moptionid = splitArray[1].slice(0,-1);
    } 
    else
    {
         moptionid =  nodeInfoArray[2];
    }
    var optionidlist;

    for(  var i = 0; i < rules.length; i++ )
    {
        for (  var j = 0; j < rules[i][1].length; j++ )
        {
            moption = rules[i][1][j][0];
            if ( moptionid == moption )
            {
               optionidlist = rules[i][1][j][1];

               if (!optionidlist.inArray( selectedValue ))               
               {
                   disableOption( rules[i][0], attributeID );
               }
              
            }

        }
    }

}


function ezmultioption_check_option( node, rules, attributeID )
{

    var optionID;
    var oldValue;
    if ( node.type == 'radio' )
    {
	oldValue = OldValues[ node.name ];
    }
    else
        oldValue = OldValues[ node.id ];

    if ( node.value == oldValue )
    {
	return true;
    }

    if ( oldValue != null )
    {

        checkOptionsToEnable( rules, oldValue, attributeID , node);
    }

    checkOptionsToDisable( rules, node.value, attributeID, node);
    //initSelects();

    if ( node.type == 'radio' )
    {
        OldValues[ node.name ] = node.value;
    }
    else
    {
        if( /MSIE [567]/.test( navigator.appVersion ) )
	{
            disableOptions( node );
        }
        OldValues[ node.id ] = node.value;
    }
    return true;
}

function initSelects()
{
    if( /MSIE [567]/.test( navigator.appVersion ) )
    {
        var sa = document.getElementsByTagName('select');
    
        for ( var sc = 0; sc < sa.length; sc++ )
        {
            disableOptions( sa[sc] );
        }
    }
}

function disableOptions( se )
{
    var os = od = false;

    for ( var oc = 0; oc < se.options.length; oc++ )
    {
        if ( se.options[oc].disabled )
        {
            se.options[oc].className = 'disabled';
            se.options[oc].selected = false;
            od = true;            
        }
        else if ( se.options[oc].selected )
        {
            os = true;            
        }
    }

    if ( od && !os )
    {
        resetOptions( se );
    }
    else
    {
        holdOptions( se );
    }
}

function holdOptions( se )
{
    for ( var oc = 0; oc < se.options.length; oc++ )
    {
        if ( se.options[oc].selected )
        {
            se.options[oc].hold = true;
        }
        else
        {
            se.options[oc].hold = false;
        }
    }
}

function resetOptions( se )
{
    for ( var oc = 0; oc < se.options.length; oc++ )
    {
        if ( se.options[oc].hold )
        {
            se.options[oc].selected = true;
        }
        else
        {
            se.options[oc].selected = false;
        }
    }
}

Array.prototype.inArray = function (value)
// Returns true if the passed value is found in the
// array.  Returns false if it is not.
{
    var i;
    for (i=0; i < this.length; i++) {
        // Matches identical (===), not just similar (==).
        if (this[i] === value) {
            return true;
        }
    }
    return false;
};









