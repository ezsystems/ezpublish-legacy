//
// Finds i18n data from tpl files
//
// This file is based on fetchtr.cpp from lupdate/Qt Linguist,
// which is Copyright (C) 2000 Trolltech AS (www.trolltech.com).
//
// Gunnstein Lye <gl@ez.no>
// Created on: <10-Dec-2002 18:46:17 gl>
//
// Copyright (C) 1999-2002 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The GNU General Public License is also available online at:
//
// http://www.gnu.org/copyleft/gpl.html
//

#include <qfile.h>
#include <qfileinfo.h>
#include <qtextstream.h>
#include <qregexp.h>

#include <metatranslator.h>


static QTextStream stream;

static QString getString( QString content, int pos, bool reverse )
{
    int tpos = pos;
    bool found = false;
    QChar quote = content[pos];

    while ( !found )
    {
        if ( reverse )
            tpos = content.findRev( quote, tpos - 1 );
        else
            tpos = content.find( quote, tpos + 1 );

        if ( content[tpos-1] != QChar( '\\' ) )
            found = true;

        if ( tpos == -1 )
        {
            qWarning( "lupdate error: end quote not found" );
            return QString::null;
        }
    }

    QString str;
    if ( reverse )
        str = content.mid( tpos + 1, pos - tpos - 1 );
    else
        str = content.mid( pos + 1, tpos - pos - 1 );
    return str;
}

static void parse( MetaTranslator *tor )
{
    QRegExp i18nRE( "\\|[xi]18n\\(" );
    QString content = stream.read();
    QString context, source;
    int startpos, pos = 0;

    while ( pos >= 0 )
    {
        startpos = i18nRE.search( content, pos );
        pos = startpos + i18nRE.matchedLength();
        if ( pos < 0 )
            return;

        source = getString( content, startpos - 1, true );
        if ( source.isNull() )
            return;

        if ( content[startpos+1] == 'x' )
        {
            QString ext = getString( content, pos, false );
            pos += ext.length() + 3;      // two quotes and a comma
        }
        context = getString( content, pos, false );

        if ( context.isNull() )
            return;

        tor->insert( MetaTranslatorMessage( context.latin1(), source.latin1(), "",
                                            QString::null, false ) );
    }
}

void fetchtr_tpl( QFileInfo *fi, MetaTranslator *tor, bool mustExist )
{
    QFile file( fi->filePath() );
    if ( file.open( IO_ReadOnly ) )
        stream.setDevice( &file );
    else if ( mustExist )
    {
        qWarning( "lupdate error: cannot open translation file '%s'",
                  fi->filePath().latin1() );
        return;
    }

    parse( tor );
    file.close();
}
