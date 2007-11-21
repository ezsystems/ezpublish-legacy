//
// Finds i18n data from tpl files
//
// This file is based on fetchtr.cpp from lupdate/Qt Linguist,
// which is Copyright (C) 2000 Trolltech AS (www.trolltech.com).
//
// Gunnstein Lye <gl@ez.no>
// Created on: <10-Dec-2002 18:46:17 gl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

#include <qfile.h>
#include <qfileinfo.h>
#include <qtextstream.h>
#include <qregexp.h>

#include <metatranslator.h>


static QTextStream stream;

static QString getString( QString content, int pos, bool reverse, int &endpos )
{
    int tpos = pos;
    int qpos = pos;
    bool found = false;
    QChar quote;
    endpos = -1;
    do
    {
        quote = content[tpos];
        if ( reverse )
            --tpos;
        else
            ++tpos;
    } while ( ( quote == ' ' ||
                quote == '\t' ||
                quote == '\r' ||
                quote == '\n' ) &&
              tpos >= 0 &&
              tpos < (int)content.length() );
    if ( tpos < 0 ||
         tpos >= (int)content.length() )
        return QString::null;
    if ( quote != '\'' &&
         quote != '"' )
        return QString::null;
    qpos = tpos;

    while ( !found )
    {
        if ( reverse )
            tpos = content.findRev( quote, tpos );
        else
            tpos = content.find( quote, tpos );

        if ( content[tpos-1] != QChar( '\\' ) )
            found = true;

        if ( tpos == -1 )
        {
            qWarning( "lupdate error: end quote not found" );
            return QString::null;
        }
        if ( !found )
        {
            if ( reverse )
                --tpos;
            else
                ++tpos;
        }
    }

    QString str;
    if ( reverse )
        str = content.mid( tpos + 1, qpos - tpos );
    else
        str = content.mid( qpos, tpos - qpos );
    str = str.replace( "\\'", "'" );
    str = str.replace( "\\\"", "\"" );
    endpos = tpos;
    if ( reverse )
        --endpos;
    else
        ++endpos;
    return str;
}

static void skipComma( const QString &content, int pos, int &endpos )
{
    QChar c;
    endpos = -1;
    do
    {
        c = content[pos];
        ++pos;
    } while( pos < (int)content.length() &&
             ( c == ' ' ||
               c == '\t' ||
               c == '\r' ||
               c == '\n' ) );
    if ( c == ',' )
        endpos = pos;
    return;
}

static void parse( MetaTranslator *tor, const QString &filename )
{
    QRegExp i18nRE( "\\|[ \t\r\n]*[xi]18n[ \t\r\n]*\\(" );
    QString content = stream.read();
    QString context, source, comment;
    int startpos, pos = 0;
    int endpos;

    while ( pos >= 0 )
    {
        source = QString::null;
        context = QString::null;
        comment = QString::null;
        startpos = i18nRE.search( content, pos );
        pos = startpos + i18nRE.matchedLength();
        if ( pos < 0 )
            return;

        source = getString( content, startpos - 1, true, endpos );
        if ( source.isNull() )
        {
            qWarning( filename + ":error: Found non-quoted source, skipping translation" );
            exit( 1 );
//             continue;
        }

        if ( content[startpos+1] == 'x' )
        {
            QString ext = getString( content, pos, false, endpos );
            if ( endpos < 0 )
                continue;
            pos = endpos;
            skipComma( content, pos, endpos );
            if ( endpos < 0 )
                continue;
            pos = endpos;
        }
        context = getString( content, pos, false, endpos );
        if ( endpos < 0 )
        {
            qWarning( filename + ":error: Found non-quoted context, skipping translation" );
            exit( 1 );
//             continue;
        }
        if ( context.find( '\n' ) != -1 )
        {
            qWarning( filename + ":error: The context contains newlines, please correct the template.\nThe context was:\n" + context );
            exit( 1 );
        }
        pos = endpos;
        skipComma( content, pos, endpos );
        if ( endpos >= 0 )
        {
            pos = endpos;

            comment = getString( content, pos, false, endpos );
            if ( endpos >= 0 &&
                 comment.length() == 0 )
                comment = QString::null;
        }

        if ( context.isNull() )
            continue;

        tor->insert( MetaTranslatorMessage( context.latin1(), source.latin1(), comment,
                                            QString::null, false ) );
    }
}

void fetchtr_tpl( QFileInfo *fi, MetaTranslator *tor, bool mustExist, bool assumeUTF8 )
{
    QFile file( fi->filePath() );
    if ( file.open( QIODevice::ReadOnly ) )
    {
        // Hack: Check if the template is utf8
        QTextStream testStream;
        testStream.setDevice( &file );
        QString testContent = testStream.read();
        file.close();
        if ( file.open( QIODevice::ReadOnly ) )
        {
            if ( ( testContent.startsWith( "{*?template charset=", false ) &&
                   ( testContent.startsWith( "{*?template charset=utf8?*}", false ) ||
                     testContent.startsWith( "{*?template charset=utf-8?*}", false ) ) ) ||
                 assumeUTF8 )
            {
                stream.setEncoding( QTextStream::UnicodeUTF8 );
            }
            else
            {
                stream.setEncoding( QTextStream::Locale );
            }
        }
        stream.setDevice( &file );
    }
    else if ( mustExist )
    {
        qWarning( "lupdate error: cannot open translation file '%s'",
                  fi->filePath().latin1() );
        return;
    }

    parse( tor, file.name() );
    file.close();
}
