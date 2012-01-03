//
// tpl.cpp for ezlupdate
//
// This file is based on cpp.cpp from lupdate/Qt Linguist,
// which is Copyright (C) 2009 Trolltech AS (www.trolltech.com).
//
// Gunnstein Lye <gl@ez.no>
// Created on: <5-Jun-2009 09:19:17 gl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2012 eZ Systems AS
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

#include "translator.h"

#include <QtCore/QDebug>
#include <QtCore/QStack>
#include <QtCore/QString>
#include <QtCore/QTextCodec>
#include <QtCore/QTextStream>

QT_BEGIN_NAMESPACE

/* qmake ignore Q_OBJECT */

/*
  The tokenizer maintains the following global variables. The names
  should be self-explanatory.
*/
static QString yyFileName;
static bool yyCodecIsUtf8;
static bool yyForceUtf8;

// the string to read from and current position in the string
static QTextCodec *yySourceCodec;
static bool yySourceIsUnicode;
static QString yyInStr;

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
            tpos = content.lastIndexOf( quote, tpos );
        else
            tpos = content.indexOf( quote, tpos );

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

static QString transcode(const QString &str, bool utf8)
{
    // Removed a and \a from here because it is not handled correctly by the linguist in our case
    static const char tab[] = "bfnrtv"; // removed a
    static const char backTab[] = "\b\f\n\r\t\v"; // removed \a
    const QString in = (!utf8 || yySourceIsUnicode)
        ? str : QString::fromUtf8(yySourceCodec->fromUnicode(str).data());
    QString out;

    out.reserve(in.length());
    for (int i = 0; i < in.length();) {
        ushort c = in[i++].unicode();
        if (c == '\\') {
            if (i >= in.length())
                break;
            c = in[i++].unicode();

            if (c == '\n')
                continue;

            if (c == 'x') {
                QByteArray hex;
                while (i < in.length() && isxdigit((c = in[i].unicode()))) {
                    hex += c;
                    i++;
                }
                out += hex.toUInt(0, 16);
            } else if (c >= '0' && c < '8') {
                QByteArray oct;
                int n = 0;
                oct += c;
                while (n < 2 && i < in.length() && (c = in[i].unicode()) >= '0' && c < '8') {
                    i++;
                    n++;
                    oct += c;
                }
                out += oct.toUInt(0, 8);
            } else {
                const char *p = strchr(tab, c);
                out += QString("\\") + QChar(QLatin1Char(!p ? c : backTab[p - tab]));
            }
        } else {
            out += c;
        }
    }
    return out;
}

static void recordMessage(
    Translator *tor, int line, const QString &context, const QString &text, const QString &comment,
    const QString &extracomment,  bool utf8, bool plural)
{
    TranslatorMessage msg(
        transcode(context, utf8), transcode(text, utf8), transcode(comment, utf8), QString(),
        yyFileName, line, QStringList(),
        TranslatorMessage::Unfinished, plural);
    msg.setExtraComment(transcode(extracomment.simplified(), utf8));
    if ((utf8 || yyForceUtf8) && !yyCodecIsUtf8 && msg.needs8Bit())
        msg.setUtf8(true);
    tor->extend(msg);
}

static void parse( Translator *tor, const QString &filename )
{
    QRegExp i18nRE( "\\|[ \t\r\n]*[xi]18n[ \t\r\n]*\\(" );
    QString content = stream.readAll();
    QString context, source, comment, extracomment;
    int startpos, pos = 0;
    int endpos;

    while ( pos >= 0 )
    {
        source = QString::null;
        context = QString::null;
        comment = QString::null;
        startpos = i18nRE.indexIn( content, pos );
        pos = startpos + i18nRE.matchedLength();
        if ( pos < 0 )
            return;

        source = getString( content, startpos - 1, true, endpos );
        if ( source.isNull() )
        {
            qWarning( "%s:error: Found non-quoted source, skipping translation", qPrintable(filename) );
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
            qWarning( "%s:error: Found non-quoted context, skipping translation", qPrintable(filename) );
            exit( 1 );
//             continue;
        }
        if ( context.indexOf( '\n' ) != -1 )
        {
            qWarning( "%s:error: The context contains newlines, please correct the template.\nThe context was:\n%s", qPrintable(filename), qPrintable(context) );
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

        int line = 0; // We don't support line numbers yet
        bool plural = false; // We don't support plural yet
        bool utf8 = false; // We don't support autodetection yet
        recordMessage(tor, line, context, source, comment, extracomment, utf8, plural);
    }
}


bool loadTPL(Translator &translator, QIODevice &dev, ConversionData &cd)
{
    // Hack: Check if the template is utf8
    QTextStream testStream;
    testStream.setDevice( &dev );
    QString testContent = testStream.readAll();
    if ( ( testContent.startsWith( QLatin1String("{*?template charset="), Qt::CaseInsensitive ) &&
           ( testContent.startsWith( QLatin1String("{*?template charset=utf8?*}"), Qt::CaseInsensitive ) ||
             testContent.startsWith( QLatin1String("{*?template charset=utf-8?*}"), Qt::CaseInsensitive ) ) ) ||
         cd.m_assumeUtf8 )
    {
        stream.setCodec( QTextCodec::codecForName("UTF-8") );
        stream.setAutoDetectUnicode( true );
    }
    else
    {
        stream.setCodec( QTextCodec::codecForLocale() );
        stream.setAutoDetectUnicode( false );
    }
    stream.setDevice( &dev );
    stream.seek( 0 ); // we need to rewind it because the testStream has read all data on the QIODevice

    parse( &translator, cd.m_sourceDir.path() + QDir::separator() + cd.m_sourceFileName );

    return true;
}

int initTPL()
{
    Translator::FileFormat format;
    format.extension = QLatin1String("tpl");
    format.fileType = Translator::FileFormat::SourceCode;
    format.priority = 0;
    format.description = QObject::tr("TPL template files");
    format.loader = &loadTPL;
    format.saver = 0;
    Translator::registerFileFormat(format);
    return 1;
}

Q_CONSTRUCTOR_FUNCTION(initTPL)

QT_END_NAMESPACE
