//
// Created on: <12-Oct-2004 14:18:58 dl>
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

/*! \file ezjslibimagepreloader.js
*/

/*!
    \brief
*/

function eZImagePreloader()
{
    this.setupEventHandlers( eZImagePreloader.prototype.onImageLoad,
                             eZImagePreloader.prototype.onImageError,
                             eZImagePreloader.prototype.onImageAbort );
}

eZImagePreloader.prototype.preloadImageList = function( imageList )
{
    this.nImagesCount           = imageList.length;
    this.nProcessedImagesCount  = 0;
    this.nLoadedImagesCount     = 0;
    this.bPreloadDone           = false;

    for( var i in imageList )
    {
        if ( typeof imageList[i] != 'function' )
        {
            this.preload( imageList[i] );
        }
    }
}

eZImagePreloader.prototype.preload = function( imageFilePath )
{
    var image = new Image;

    image.onload  = this.onImageLoadEvent;
    image.onerror = this.onImageErrorEvent;
    image.onabort = this.onImageAbortEvent;

    image.preloader = this;

    image.bLoaded = false;
    image.bError  = false;
    image.bAbort  = false;

    image.src = imageFilePath;

}

eZImagePreloader.prototype.setupEventHandlers = function( onLoad, onError, onAbort )
{
    this.onImageLoadEvent = onLoad;
    this.onImageErrorEvent = onError;
    this.onImageAbortEvent = onAbort;
}

eZImagePreloader.prototype.onImageLoad = function()
{
    this.bLoaded = true;
    this.preloader.nLoadedImagesCount++;
    this.preloader.onComplete();
}

eZImagePreloader.prototype.onImageError = function()
{
    this.bError = true;
    this.preloader.onComplete();
}

eZImagePreloader.prototype.onImageAbort = function()
{
    this.bAbort = true;
    this.preloader.onComplete();
}

eZImagePreloader.prototype.onComplete = function( imageList )
{
    this.nProcessedImagesCount++;
    if( this.nProcessedImagesCount == this.nImagesCount )
    {
        this.bPreloadDone = true;
    }
}

function ezjslib_preloadImageList( filepathList )
{
    var preloader = new eZImagePreloader();
    preloader.preloadImageList( filepathList );
}


