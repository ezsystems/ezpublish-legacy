<?php
//
// Definition of eZMimeType class
//
// Created on: <15-Aug-2002 12:44:57 sp>
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezmimetype.php
*/

/*!
  \class eZMimeType ezmimetype.php
  \brief The class eZMimeType does

*/

class eZMimeType
{
    /*!
     Constructor
    */
    function eZMimeType()
    {
    }

    function mimeTypeFor( $path, $file )
    {
        $extension = false;
        if ( preg_match( "/\.([^.]+)$/", $file, $matches ) )
        {
            $extension = strtolower( $matches[1] );
        }
        eZDebug::writeNotice( $extension, 'extension' );
        if ( array_key_exists ( $extension, $this->MIMETypes ) )
        {
            return $this->MIMETypes[$extension];
        }
        else
        {
            return '';
        }
    }


    var $MIMETypes = array(
        'ez' => 'application/andrew-inset',
        'hqx' => 'application/mac-binhex40',
        'cpt' => 'application/mac-compactpro',
        'doc' => 'application/msword',
        'bin' => 'application/octet-stream',
        'dms' => 'application/octet-stream',
        'lha' => 'application/octet-stream',
        'lzh' => 'application/octet-stream',
        'exe' => 'application/octet-stream',
        'class' => 'application/octet-stream',
        'oda' => 'application/oda',
        'pdf' => 'application/pdf',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        'rtf' => 'application/rtf',
        'sgml' => 'application/sgml',
        'ppt' => 'application/vnd.ms-powerpoint',
        'slc' => 'application/vnd.wap.slc',
        'sic' => 'application/vnd.wap.sic',
        'wmlc' => 'application/vnd.wap.wmlc',
        'wmlsc' => 'application/vnd.wap.wmlscriptc',
        'bcpio' => 'application/x-bcpio',
        'bz2' => 'application/x-bzip2',
        'vcd' => 'application/x-cdlink',
        'pgn' => 'application/x-chess-pgn',
        'cpio' => 'application/x-cpio',
        'csh' => 'application/x-csh',
        'dcr' => 'application/x-director',
        'dir' => 'application/x-director',
        'dxr' => 'application/x-director',
        'dvi' => 'application/x-dvi',
        'spl' => 'application/x-futuresplash',
        'gtar' => 'application/x-gtar',
        'gz' => 'application/x-gzip',
        'tgz' => 'application/x-gzip',
        'hdf' => 'application/x-hdf',
        'js' => 'application/x-javascript',
        'kwd' => 'application/x-kword',
        'kwt' => 'application/x-kword',
        'ksp' => 'application/x-kspread',
        'kpr' => 'application/x-kpresenter',
        'kpt' => 'application/x-kpresenter',
        'chrt' => 'application/x-kchart',
        'kil' => 'application/x-killustrator',
        'skp' => 'application/x-koan',
        'skd' => 'application/x-koan',
        'skt' => 'application/x-koan',
        'skm' => 'application/x-koan',
        'latex' => 'application/x-latex',
        'nc' => 'application/x-netcdf',
        'cdf' => 'application/x-netcdf',
        'rpm' => 'application/x-rpm',
        'sh' => 'application/x-sh',
        'shar' => 'application/x-shar',
        'swf' => 'application/x-shockwave-flash',
        'sit' => 'application/x-stuffit',
        'sv4cpio' => 'application/x-sv4cpio',
        'sv4crc' => 'application/x-sv4crc',
        'tar' => 'application/x-tar',
        'tcl' => 'application/x-tcl',
        'tex' => 'application/x-tex',
        'texinfo' => 'application/x-texinfo',
        'texi' => 'application/x-texinfo',
        't' => 'application/x-troff',
        'tr' => 'application/x-troff',
        'roff' => 'application/x-troff',
        'man' => 'application/x-troff-man',
        'me' => 'application/x-troff-me',
        'ms' => 'application/x-troff-ms',
        'ustar' => 'application/x-ustar',
        'src' => 'application/x-wais-source',
        'zip' => 'application/zip',
        'mid' => 'audio/midi',
        'midi' => 'audio/midi',
        'kar' => 'audio/midi',
        'mpga' => 'audio/mpeg',
        'mp2' => 'audio/mpeg',
        'mp3' => 'audio/mpeg',
        'ra' => 'audio/x-realaudio',
        'pdb' => 'chemical/x-pdb',
        'xyz' => 'chemical/x-pdb',
        'gif' => 'image/gif',
        'ief' => 'image/ief',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'jpe' => 'image/jpeg',
        'png' => 'image/png',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'wbmp' => 'image/vnd.wap.wbmp',
        'ras' => 'image/x-cmu-raster',
        'pnm' => 'image/x-portable-anymap',
        'pbm' => 'image/x-portable-bitmap',
        'pgm' => 'image/x-portable-graymap',
        'ppm' => 'image/x-portable-pixmap',
        'psd' => 'application/x-photoshop',
        'rgb' => 'image/x-rgb',
        'xbm' => 'image/x-xbitmap',
        'xpm' => 'image/x-xpixmap',
        'xwd' => 'image/x-xwindowdump',
        'igs' => 'model/iges',
        'iges' => 'model/iges',
        'msh' => 'model/mesh',
        'mesh' => 'model/mesh',
        'silo' => 'model/mesh',
        'wrl' => 'model/vrml',
        'vrml' => 'model/vrml',
        'css' => 'text/css',
        'asc' => 'text/plain',
        'txt' => 'text/plain',
        'rtx' => 'text/richtext',
        'rtf' => 'text/rtf',
        'sgml' => 'text/sgml',
        'sgm' => 'text/sgml',
        'tsv' => 'text/tab-separated-values',
        'sl' => 'text/vnd.wap.sl',
        'si' => 'text/vnd.wap.si',
        'wml' => 'text/vnd.wap.wml',
        'wmls' => 'text/vnd.wap.wmlscript',
        'etx' => 'text/x-setext',
        'xml' => 'text/xml',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpe' => 'video/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'avi' => 'video/x-msvideo',
        'movie' => 'video/x-sgi-movie',
        'ice' => 'x-conference/x-cooltalk',
        'rmm' => 'audio/x-pn-realaudio',
        'ram' => 'audio/x-pn-realaudio',
        'ra' => 'audio/vnd.rn-realaudio',
        'smi' => 'application/smil',
        'smil' => 'application/smil',
        'rt' => 'text/vnd.rn-realtext',
        'rv' => 'video/vnd.rn-realvideo',
        'rf' => 'image/vnd.rn-realflash',
        'swf' => 'image/vnd.rn-realflash',
        'rf' => 'application/x-shockwave-flash2-preview',
        'swf' => 'application/x-shockwave-flash2-preview',
        'sdp' => 'application/sdp',
        'sdp' => 'application/x-sdp',
        'rm' => 'application/vnd.rn-realmedia',
        'rp' => 'image/vnd.rn-realpix',
        'wav' => 'audio/wav',
        'wav' => 'audio/x-wav',
        'wav' => 'audio/x-pn-wav',
        'wav' => 'audio/x-pn-windows-acm',
        'au' => 'audio/basic',
        'au' => 'audio/x-pn-au',
        'aiff' => 'audio/aiff',
        'af' => 'audio/aiff',
        'aiff' => 'audio/x-aiff',
        'af' => 'audio/x-aiff',
        'aiff' => 'audio/x-pn-aiff',
        'af' => 'audio/x-pn-aiff',
        'html' => 'text/html',
        'htm' => 'text/html'
        );






}







?>
