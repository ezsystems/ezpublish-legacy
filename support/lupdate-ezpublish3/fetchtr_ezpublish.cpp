/**********************************************************************
**   Copyright (C) 2001 eZ systems as.  All rights reserved.
**   This file is based on fetchtr.cpp,
**   which is Copyright (C) 2000 Trolltech AS.
**
**   fetchtr_ezpublish.cpp
**
**   This file is part of Qt Linguist.
**
**   See the file LICENSE included in the distribution for the usage
**   and distribution terms.
**
**   The file is provided AS IS with NO WARRANTY OF ANY KIND,
**   INCLUDING THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR
**   A PARTICULAR PURPOSE.
**
**********************************************************************/

#include <qfile.h>
#include <qfileinfo.h>
#include <qtextstream.h>
#include <qregexp.h>

#include <metatranslator.h>


static QTextStream stream;
static const QString keyprefix = "Key: ";
static const QRegExp sectionRE( "^\[^]]+\\]$" );
static const QRegExp keyValueRE( "[^=]+=.*" );
//static const QRegExp keyValueRE( "[0-9A-Za-z_]+=.*" );

static void parse( MetaTranslator *tor, const QString &initialContext )
{
    QString line, section, key, value, context;
    int equal;
    while ( !stream.atEnd() )
    {
        line = stream.readLine();
        if ( sectionRE.match( line ) == 0 )
            section = line.mid( 1, line.length() - 2 );
        else if ( keyValueRE.match( line ) == 0 )
        {
            equal = line.find( "=" );
            key = keyprefix + line.left( equal );
            value = line.right( line.length() - equal - 1 );
            value.replace( QRegExp( "\\\\n" ), "\n" );  // newline fix

            context = initialContext;
            if ( !section.isEmpty() )
                context += " [" + section + "]";
		    tor->insert( MetaTranslatorMessage( context.latin1(), value.latin1(), key.latin1(),
                                                QString::null, false ) );
        }
    }
}

void fetchtr_ezpublish( QFileInfo *fi, MetaTranslator *tor, bool mustExist )
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

    parse( tor, fi->filePath() );
    file.close();
}
