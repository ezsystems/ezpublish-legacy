//
// Created on: <12-Oct-2004 14:18:58 dl>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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

    for( var i = 0; i < imageList.length; i++ )
        this.preload( imageList[i] );
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


