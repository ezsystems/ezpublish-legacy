/*
    eZ Core Accordion: Accordion extension for ez core js library
    Created on: <09-Des-2007 00:00:00 ar>
    
    Copyright (c) 2007-2008 eZ Systems AS
    Licensed under the MIT License:
    http://www.opensource.org/licenses/mit-license.php
    
    Optional Extension: Animation
*/

if ( window.ez !== undefined && window.ez.array.eZextensions.prototype.accordionIndex === undefined )
{

ez.object.extend( ez.array.eZextensions.prototype, {
    accordionIndex: 0,
    accordionTarget: {},
    accordionNavigation: 0,
    accordion: function( navs, settings, target )
    {
        if ( settings ) this.setSettings( settings );
        this.forEach(function( o, i )
        {
            if ( navs && navs[i] )
            {
                navs[i].addEvent('click', ez.fn.bind( this.accordionGoto, this, i ) );
                navs[i].addClass('accordion_navigation');
                if ( i === 0 ) navs[ i ].addClass('current'); 
            }
            if ( i === 0 ) o.addClass('current');
            else this.settings.accordionDontAnimateInit ? o.hide( this.settings ) : o.hide( this.settings, target );
        }, this);
        this.accordionNavigation = navs;
        this.accordionTarget = target;
    },
    accordionGoto: function( i )
    {
        if ( i === this.accordionIndex || this[i] === undefined ) return false;
        var fn = ez.fn.bind(function( i, tag ){
            if ( tag ) i = ez.$$( tag, this[ i ].el );
            if ( i.length ) i[0].el.focus();
        }, this, i, this.settings.accordionAutoFocusTag || '' );
        this[ this.accordionIndex ].removeClass('current');
        if ( this.accordionNavigation && this.accordionNavigation[ this.accordionIndex ] ) this.accordionNavigation[ this.accordionIndex ].removeClass('current');
        if ( this.settings.accordionHideDirectly )
        {
            this[ this.accordionIndex ].hide( this.settings, this.accordionTarget );
            this[ i ].show( this.settings, this.accordionTarget, fn );
        }
        else
        {
            this[ this.accordionIndex ].hide( this.settings, this.accordionTarget, ez.fn.bind( this[ i ].show, this[ i ], this.settings, this.accordionTarget, fn ) );
        }
        this[ i ].addClass('current');
        if ( this.accordionNavigation && this.accordionNavigation[ i ] ) this.accordionNavigation[ i ].addClass('current');
        this.accordionIndex = i;
        return false;
    },
    accordionNext: function()
    {
        if ( this.length === 1 ) return false;
        this.accordionGoto( (this.accordionIndex === this.length -1) ? 0 : this.accordionIndex+1 );
        return false;
        
    },
    accordionPrev: function()
    {
        if ( this.length === 1 ) return false;
        this.accordionGoto( (this.accordionIndex === 0) ? this.length -1 : this.accordionIndex-1 );
        return false;
    }
});

}//if ( window.ez !== undefined && window.ez.array.eZextensions.prototype.accordionIndex === undefined )