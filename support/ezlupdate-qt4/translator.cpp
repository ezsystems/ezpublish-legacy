/****************************************************************************
**
** Copyright (C) 1992-2005 Trolltech AS. All rights reserved.
**
** This file is part of the Qt Linguist of the Qt Toolkit.
**
** This file may be used under the terms of the GNU General Public
** License version 2.0 as published by the Free Software Foundation
** and appearing in the file LICENSE.GPL included in the packaging of
** this file.  Please review the following information to ensure GNU
** General Public Licensing requirements will be met:
** http://www.trolltech.com/products/qt/opensource.html
**
** If you are unsure which license is appropriate for your use, please
** review the following information:
** http://www.trolltech.com/products/qt/licensing.html or contact the
** sales department at sales@trolltech.com.
**
** This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING THE
** WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
**
****************************************************************************/

#include "qplatformdefs.h"

#include "translator.h"

#ifndef QT_NO_TRANSLATION

#include "qfileinfo.h"
#include "qstring.h"
#include "qcoreapplication.h"
#include "qdatastream.h"
#include "qfile.h"
#include "qmap.h"
#include "qalgorithms.h"
#include "qhash.h"
#include "qglobal.h"

#if defined(Q_OS_UNIX)
#define QT_USE_MMAP
#endif

// most of the headers below are already included in qplatformdefs.h
// also this lacks Large File support but that's probably irrelevant
#if defined(QT_USE_MMAP)
// for mmap
#include <sys/mman.h>
#include <errno.h>
#endif

#include <stdlib.h>

/*
$ mcookie
3cb86418caef9c95cd211cbf60a1bddd
$
*/

// magic number for the file
static const int MagicLength = 16;
static const uchar magic[MagicLength] = {
    0x3c, 0xb8, 0x64, 0x18, 0xca, 0xef, 0x9c, 0x95,
    0xcd, 0x21, 0x1c, 0xbf, 0x60, 0xa1, 0xbd, 0xdd
};

static bool match(const char* found, const char* target)
{
    // 0 means anything, "" means empty
    return found == 0 || qstrcmp(found, target) == 0;
}

#if defined(Q_C_CALLBACKS)
extern "C" {
#endif

/*
  Yes, unfortunately, we have code here that depends on endianness.
  The candidate is big endian (it comes from a .qm file) whereas the
  target endianness depends on the system Qt is running on.
*/
#ifdef Q_OS_TEMP
static int __cdecl cmp_uint32_little(const void* target, const void* candidate)
#else
static int cmp_uint32_little(const void* target, const void* candidate)
#endif
{
    const uchar* t = (const uchar*) target;
    const uchar* c = (const uchar*) candidate;
    return t[3] != c[0] ? (int) t[3] - (int) c[0]
         : t[2] != c[1] ? (int) t[2] - (int) c[1]
         : t[1] != c[2] ? (int) t[1] - (int) c[2]
                   : (int) t[0] - (int) c[3];
}

#ifdef Q_OS_TEMP
static int __cdecl cmp_uint32_big(const void* target, const void* candidate)
#else
static int cmp_uint32_big(const void* target, const void* candidate)
#endif
{
    const uint* t = (const uint*) target;
    const uint* c = (const uint*) candidate;
    return (*t > *c ? 1 : (*t == *c ? 0 : -1));
}

#if defined(Q_C_CALLBACKS)
}
#endif

static uint elfHash(const char * name)
{
    const uchar *k;
    uint h = 0;
    uint g;

    if (name) {
        k = (const uchar *) name;
        while (*k) {
            h = (h << 4) + *k++;
            if ((g = (h & 0xf0000000)) != 0)
                h ^= g >> 24;
            h &= ~g;
        }
    }
    if (!h)
        h = 1;
    return h;
}

extern bool qt_detectRTLLanguage();

class TranslatorPrivate
{
public:
    struct Offset {
        Offset()
            : h(0), o(0) { }
        Offset(const TranslatorMessage& m, int offset)
            : h(m.hash()), o(offset) { }

        bool operator<(const Offset &other) const {
            return (h != other.h) ? h < other.h : o < other.o;
        }
        bool operator==(const Offset &other) const {
            return h == other.h && o == other.o;
        }
        uint h;
        uint o;
    };

    enum { Contexts = 0x2f, Hashes = 0x42, Messages = 0x69 };

    TranslatorPrivate(Translator *qq) : q(qq), unmapPointer(0), unmapLength(0) {}
    // Translator must finalize this before deallocating it

    Translator *q;
    // for mmap'ed files, this is what needs to be unmapped.
    char *unmapPointer;
    unsigned int unmapLength;

    // for squeezed but non-file data, this is what needs to be deleted
    QByteArray messageArray;
    QByteArray offsetArray;
    QByteArray contextArray;

#ifndef QT_NO_TRANSLATION_BUILDER
    QMap<TranslatorMessage, void *> messages;
#endif

    bool do_load(const uchar *data, int len);

};


/*!
    \class Translator

    \brief The Translator class provides internationalization support for text
    output.

    \ingroup i18n
    \ingroup environment
    \mainclass

    An object of this class contains a set of TranslatorMessage
    objects, each of which specifies a translation from a source
    language to a target language. Translator provides functions to
    look up translations, add new ones, remove them, load and save
    them, etc.

    The most common use of Translator is to: load a translator file
    created with \l{Qt Linguist Manual}, install it using
    QApplication::installTranslator(), and use it via QObject::tr().
    For example:

    \code
    int main(int argc, char ** argv)
    {
        QApplication app(argc, argv);

        Translator translator(0);
        translator.load("french.qm", ".");
        app.installTranslator(&translator);

        MyWidget m;
        app.setMainWidget(&m);
        m.show();

        return app.exec();
    }
    \endcode
    Note that the translator must be created \e before the
    application's main window.

    Most applications will never need to do anything else with this
    class. The other functions provided by this class are useful for
    applications that work on translator files.

    We call a translation a "messsage". For this reason, translation
    files are sometimes referred to as "message files".

    It is possible to lookup a translation using findMessage() (as
    tr() and QApplication::translate() do) and contains(), to insert a
    new translation messsage using insert(), and to remove one using
    remove().

    Translation tools often need more information than the bare source
    text and translation, for example, context information to help
    the translator. But end-user programs that are using translations
    usually only need lookup. To cater for these different needs,
    Translator can use stripped translator files that use the minimum
    of memory and which support little more functionality than
    findMessage().

    Thus, load() may not load enough information to make anything more
    than findMessage() work. save() has an argument indicating
    whether to save just this minimum of information or to save
    everything.

    "Everything" means that for each translation item the following
    information is kept:

    \list
    \i The \e {translated text} - the return value from tr().
    \i The input key:
        \list
        \i The \e {source text} - usually the argument to tr().
        \i The \e context - usually the class name for the tr() caller.
        \i The \e comment - a comment that helps disambiguate different uses
           of the same text in the same context.
        \endlist
    \endlist

    The minimum for each item is just the information necessary for
    findMessage() to return the right text. This may include the
    source, context and comment, but usually it is just a hash value
    and the translated text.

    For example, the "Cancel" in a dialog might have "Anuluj" when the
    program runs in Polish (in this case the source text would be
    "Cancel"). The context would (normally) be the dialog's class
    name; there would normally be no comment, and the translated text
    would be "Anuluj".

    But it's not always so simple. The Spanish version of a printer
    dialog with settings for two-sided printing and binding would
    probably require both "Activado" and "Activada" as translations
    for "Enabled". In this case the source text would be "Enabled" in
    both cases, and the context would be the dialog's class name, but
    the two items would have disambiguating comments such as
    "two-sided printing" for one and "binding" for the other. The
    comment enables the translator to choose the appropriate gender
    for the Spanish version, and enables Qt to distinguish between
    translations.

    Note that when Translator loads a stripped file, most functions
    do not work. The functions that do work with stripped files are
    explicitly documented as such.

    \sa TranslatorMessage QApplication::installTranslator()
    QApplication::removeTranslator() QObject::tr() QApplication::translate()
*/

/*!
    \enum Translator::SaveMode

    This enum type defines how Translator writes translation
    files. There are two modes:

    \value Everything  files are saved with all available information
    \value Stripped  files are saved with just enough information for
        end-user applications

    Note that when Translator loads a stripped file, most functions do
    not work. The functions that do work with stripped files are
    explicitly documented as such.
*/

/*!
    Constructs an empty message file object with parent \a parent that
    is not connected to any file.
*/

Translator::Translator(QObject * parent)
    : QTranslator(parent)
{
    d = new TranslatorPrivate(this);
}

/*!
    Destroys the object and frees any allocated resources.
*/

Translator::~Translator()
{
    if (QCoreApplication::instance())
        QCoreApplication::instance()->removeTranslator(this);
    clear();
    delete d;
}

/*!
    Loads \a filename + \a suffix (".qm" if the \a suffix is
    not specified), which may be an absolute file name or relative
    to \a directory. The previous contents of this translator object
    is discarded.

    If the file name does not exist, other file names are tried
    in the following order:

    \list 1
    \i File name without \a suffix appended.
    \i File name with text after a character in \a search_delimiters
       stripped ("_." is the default for \a search_delimiters if it is
       an empty string) and \a suffix.
    \i File name stripped without \a suffix appended.
    \i File name stripped further, etc.
    \endlist

    For example, an application running in the fr_CA locale
    (French-speaking Canada) might call load("foo.fr_ca",
    "/opt/foolib"). load() would then try to open the first existing
    readable file from this list:

    \list 1
    \i /opt/foolib/foo.fr_ca.qm
    \i /opt/foolib/foo.fr_ca
    \i /opt/foolib/foo.fr.qm
    \i /opt/foolib/foo.fr
    \i /opt/foolib/foo.qm
    \i /opt/foolib/foo
    \endlist

    \sa save()
*/

bool Translator::load(const QString & filename, const QString & directory,
                       const QString & search_delimiters,
                       const QString & suffix)
{
    clear();

    QString prefix;

    if (filename[0] == QLatin1Char('/')
#ifdef Q_WS_WIN
         || (filename[0].isLetter() && filename[1] == QLatin1Char(':')) || filename[0] == QLatin1Char('\\')
#endif
        )
        prefix = QLatin1String("");
    else
        prefix = directory;

    if (prefix.length()) {
        if (prefix[int(prefix.length()-1)] != QLatin1Char('/'))
            prefix += QLatin1Char('/');
    }

    QString fname = filename;
    QString realname;
    QString delims;
    delims = search_delimiters.isNull() ? QString::fromLatin1("_.") : search_delimiters;

    for (;;) {
        QFileInfo fi;

        realname = prefix + fname + (suffix.isNull() ? QString::fromLatin1(".qm") : suffix);
        fi.setFile(realname);
        if (fi.isReadable())
            break;

        realname = prefix + fname;
        fi.setFile(realname);
        if (fi.isReadable())
            break;

        int rightmost = 0;
        for (int i = 0; i < (int)delims.length(); i++) {
            int k = fname.lastIndexOf(delims[i]);
            if (k > rightmost)
                rightmost = k;
        }

        // no truncations? fail
        if (rightmost == 0)
            return false;

        fname.truncate(rightmost);
    }

    // realname is now the fully qualified name of a readable file.

    bool ok = false;

#ifdef QT_USE_MMAP

#ifndef MAP_FILE
#define MAP_FILE 0
#endif
#ifndef MAP_FAILED
#define MAP_FAILED -1
#endif

    int fd = -1;
    if (!realname.startsWith(QLatin1String(":")))
        fd = QT_OPEN(QFile::encodeName(realname), O_RDONLY,
#if defined(Q_OS_WIN)
                 _S_IREAD | _S_IWRITE
#else
                 0666
#endif
                );
    if (fd >= 0) {
        struct stat st;
        if (!fstat(fd, &st)) {
            char *ptr;
            ptr = reinterpret_cast<char *>(
                        mmap(0, st.st_size,             // any address, whole file
                             PROT_READ,                 // read-only memory
                             MAP_FILE | MAP_PRIVATE,    // swap-backed map from file
                             fd, 0));                   // from offset 0 of fd
            if (ptr && ptr != reinterpret_cast<char *>(MAP_FAILED)) {
                d->unmapPointer = ptr;
                d->unmapLength = st.st_size;
                ok = true;
            }
        }
        ::close(fd);
    }
#endif // QT_USE_MMAP

    if (!ok) {
        QFile file(realname);
        if (!file.exists())
            return false;
        d->unmapLength = file.size();
        d->unmapPointer = new char[d->unmapLength];

        if (file.open(QIODevice::ReadOnly))
            ok = (d->unmapLength == (uint)file.read(d->unmapPointer, d->unmapLength));

        if (!ok) {
            delete [] d->unmapPointer;
            d->unmapPointer = 0;
            d->unmapLength = 0;
            return false;
        }
    }

    return d->do_load(reinterpret_cast<const uchar *>(d->unmapPointer), d->unmapLength);
}

/*!
  \overload
  \fn bool Translator::load(const uchar *data, int len)

  Loads the .qm file data \a data of length \a len into the
  translator.

  The data is not copied. The caller must be able to guarantee that \a data
  will not be deleted or modified.
*/
bool Translator::load(const uchar *data, int len)
{
    clear();
    return d->do_load(data, len);
}

bool TranslatorPrivate::do_load(const uchar *data, int len)
{
    if (len < MagicLength || memcmp(data, magic, MagicLength) != 0) {
        q->clear();
        return false;
    }

    QByteArray array = QByteArray::fromRawData((const char *) data, len);
    QDataStream s(&array, QIODevice::ReadOnly);
    bool ok = true;

    s.device()->seek(MagicLength);

    quint8 tag = 0;
    quint32 blockLen = 0;
    s >> tag >> blockLen;
    while (tag && blockLen) {
        if ((quint32) s.device()->pos() + blockLen > (quint32) len) {
            ok = false;
            break;
        }

        if (tag == TranslatorPrivate::Contexts) {
            contextArray = QByteArray(array.constData() + s.device()->pos(), blockLen);
        } else if (tag == TranslatorPrivate::Hashes) {
            offsetArray = QByteArray(array.constData() + s.device()->pos(), blockLen);
        } else if (tag == TranslatorPrivate::Messages) {
            messageArray = QByteArray(array.constData() + s.device()->pos(), blockLen);
        }

        if (!s.device()->seek(s.device()->pos() + blockLen)) {
            ok = false;
            break;
        }
        tag = 0;
        blockLen = 0;
        if (!s.atEnd())
            s >> tag >> blockLen;
    }

    return ok;
}

#ifndef QT_NO_TRANSLATION_BUILDER

/*!
    Saves this message file to \a filename, overwriting the previous
    contents of \a filename. If \a mode is \c Everything (the
    default), all the information is preserved. If \a mode is \c
    Stripped, any information that is not necessary for findMessage()
    is stripped away.

    \sa load()
*/

bool Translator::save(const QString & filename, SaveMode mode)
{
    QFile file(filename);
    if (file.open(QIODevice::WriteOnly)) {
        squeeze(mode);

        QDataStream s(&file);
        s.writeRawData((const char *)magic, MagicLength);
        quint8 tag;

        if (!d->offsetArray.isEmpty()) {
            tag = (quint8)TranslatorPrivate::Hashes;
            quint32 oas = (quint32)d->offsetArray.size();
            s << tag << oas;
            s.writeRawData(d->offsetArray, oas);
        }
        if (!d->messageArray.isEmpty()) {
            tag = (quint8)TranslatorPrivate::Messages;
            quint32 mas = (quint32)d->messageArray.size();
            s << tag << mas;
            s.writeRawData(d->messageArray, mas);
        }
        if (!d->contextArray.isEmpty()) {
            tag = (quint8)TranslatorPrivate::Contexts;
            quint32 cas = (quint32)d->contextArray.size();
            s << tag << cas;
            s.writeRawData(d->contextArray, cas);
        }
        return true;
    }
    return false;
}

#endif

/*!
    Empties this translator of all contents.

    This function works with stripped translator files.
*/

void Translator::clear()
{
    if (d->unmapPointer && d->unmapLength) {
#if defined(QT_USE_MMAP)
        munmap(d->unmapPointer, d->unmapLength);
#else
        delete [] d->unmapPointer;
#endif
        d->unmapPointer = 0;
        d->unmapLength = 0;
    }

    d->messageArray.clear();
    d->offsetArray.clear();
    d->contextArray.clear();
#ifndef QT_NO_TRANSLATION_BUILDER
    d->messages.clear();
#endif

    QEvent ev(QEvent::LanguageChange);
    QCoreApplication::sendEvent(QCoreApplication::instance(), &ev);
}

#ifndef QT_NO_TRANSLATION_BUILDER

/*!
    Converts this message file to the compact format used to store
    message files on disk.

    You should never need to call this directly; save() and other
    functions call it as necessary. \a mode is for internal use.

    \sa save() unsqueeze()
*/

void Translator::squeeze(SaveMode mode)
{
    if (d->messages.isEmpty()) {
        if (mode == Stripped)
            unsqueeze();
        else
            return;
    }

    QMap<TranslatorMessage, void *> messages = d->messages;
    clear();

    QMap<TranslatorPrivate::Offset, void *> offsets;

    QDataStream ms(&d->messageArray, QIODevice::WriteOnly);
    QMap<TranslatorMessage, void *>::const_iterator it, next;
    int cpPrev = 0, cpNext = 0;
    for (it = messages.constBegin(); it != messages.constEnd(); ++it) {
        cpPrev = cpNext;
        next = it;
        ++next;
        if (next == messages.constEnd())
            cpNext = 0;
        else
            cpNext = (int) it.key().commonPrefix(next.key());
        offsets.insert(TranslatorPrivate::Offset(it.key(), ms.device()->pos()), (void *)0);
        it.key().write(ms, mode == Stripped, (TranslatorMessage::Prefix)qMax(cpPrev, cpNext + 1));
    }

    QMap<TranslatorPrivate::Offset, void *>::Iterator offset;
    offset = offsets.begin();
    QDataStream ds(&d->offsetArray, QIODevice::WriteOnly);
    while (offset != offsets.end()) {
        TranslatorPrivate::Offset k = offset.key();
        ++offset;
        ds << (quint32)k.h << (quint32)k.o;
    }

    if (mode == Stripped) {
        QMap<QByteArray, int> contextSet;
        for (it = messages.constBegin(); it != messages.constEnd(); ++it)
            ++contextSet[it.key().context()];

        quint16 hTableSize;
        if (contextSet.size() < 200)
            hTableSize = (contextSet.size() < 60) ? 151 : 503;
        else if (contextSet.size() < 2500)
            hTableSize = (contextSet.size() < 750) ? 1511 : 5003;
        else
            hTableSize = (contextSet.size() < 10000) ? 15013 : 3 * contextSet.size() / 2;

        QMultiMap<int, const char *> hashMap;
        QMap<QByteArray, int>::const_iterator c;
        for (c = contextSet.constBegin(); c != contextSet.constEnd(); ++c)
            hashMap.insert(elfHash(c.key()) % hTableSize, c.key());

        /*
          The contexts found in this translator are stored in a hash
          table to provide fast lookup. The context array has the
          following format:

              quint16 hTableSize;
              quint16 hTable[hTableSize];
              quint8  contextPool[...];

          The context pool stores the contexts as Pascal strings:

              quint8  len;
              quint8  data[len];

          Let's consider the look-up of context "FunnyDialog".  A
          hash value between 0 and hTableSize - 1 is computed, say h.
          If hTable[h] is 0, "FunnyDialog" is not covered by this
          translator. Else, we check in the contextPool at offset
          2 * hTable[h] to see if "FunnyDialog" is one of the
          contexts stored there, until we find it or we meet the
          empty string.
        */
        d->contextArray.resize(2 + (hTableSize << 1));
        QDataStream t(&d->contextArray, QIODevice::WriteOnly);

        quint16 *hTable = new quint16[hTableSize];
        memset(hTable, 0, hTableSize * sizeof(quint16));

        t << hTableSize;
        t.device()->seek(2 + (hTableSize << 1));
        t << (quint16)0; // the entry at offset 0 cannot be used
        uint upto = 2;

        QMap<int, const char *>::const_iterator entry = hashMap.constBegin();
        while (entry != hashMap.constEnd()) {
            int i = entry.key();
            hTable[i] = (quint16)(upto >> 1);

            do {
	            const char *con = entry.value();
                uint len = (uint)qstrlen(con);
                len = qMin(len, 255u);
                t << (quint8)len;
                t.writeRawData(con, len);
                upto += 1 + len;
                ++entry;
            } while (entry != hashMap.constEnd() && entry.key() == i);
            do {
                t << (quint8) 0; // empty string
                ++upto;
            } while ((upto & 0x1) != 0); // offsets have to be even
        }
        t.device()->seek(2);
        for (int j = 0; j < hTableSize; j++)
            t << hTable[j];
        delete [] hTable;

        if (upto > 131072) {
            qWarning("Translator::squeeze: Too many contexts");
            d->contextArray.clear();
        }
    }
}


/*!
    Converts this message file into an easily modifiable data
    structure, less compact than the format used in the files.

    You should never need to call this function; it is called by
    insert() and friends as necessary.

    \sa squeeze()
*/

void Translator::unsqueeze()
{
    if (!d->messages.isEmpty() || d->messageArray.isEmpty())
        return;

    QDataStream s(&d->messageArray, QIODevice::ReadOnly);
    for (;;) {
        TranslatorMessage m(s);
        if (m.hash() == 0)
            break;
        d->messages.insert(m, (void *)0);
    }
}


/*!
    Returns true if this message file contains a message with the key
    (\a context, \a sourceText, \a comment); otherwise returns false.

    This function works with stripped translator files.

    (This is is a one-liner that calls findMessage().)
*/

bool Translator::contains(const char* context, const char* sourceText,
                            const char* comment) const
{
    return !findMessage(context, sourceText, comment).translation().isNull();
}


/*!
    Inserts \a message into this message file.

    This function does \e not work with stripped translator files. It
    may appear to, but that is not dependable.

    \sa remove()
*/

void Translator::insert(const TranslatorMessage& message)
{
    unsqueeze();
    d->messages.remove(message); // safer
    d->messages.insert(message, (void *) 0);
}

/*!
  \fn void Translator::insert(const char *context, const char
 *sourceText, const QString &translation)
  \overload
  \obsolete

  Inserts the \a sourceText and \a translation into the translator
  with the given \a context.
*/

/*!
    Removes \a message from this translator.

    This function works with stripped translator files.

    \sa insert()
*/

void Translator::remove(const TranslatorMessage& message)
{
    unsqueeze();
    d->messages.remove(message);
}


/*!
  \fn void Translator::remove(const char *, const char *)
  \overload
  \obsolete

  Removes the translation associated to the key (\a context, \a sourceText,
  "") from this translator.
*/
#endif

/*!  Returns the TranslatorMessage for the key
     (\a context, \a sourceText, \a comment). If none is found,
     also tries (\a context, \a sourceText, "").
*/

TranslatorMessage Translator::findMessage(const char *context, const char *sourceText,
                                          const char *comment) const
{
    if (context == 0)
        context = "";
    if (sourceText == 0)
        sourceText = "";
    if (comment == 0)
        comment = "";

#ifndef QT_NO_TRANSLATION_BUILDER
    if (!d->messages.isEmpty()) {
        QMap<TranslatorMessage, void *>::const_iterator it;

        it = d->messages.find(TranslatorMessage(context, sourceText, comment));
        if (it != d->messages.constEnd())
            return it.key();

        if (comment[0]) {
            it = d->messages.find(TranslatorMessage(context, sourceText, ""));
            if (it != d->messages.constEnd())
                return it.key();
        }
        return TranslatorMessage();
    }
#endif

    if (d->offsetArray.isEmpty())
        return TranslatorMessage();

    /*
        Check if the context belongs to this Translator. If many
        translators are installed, this step is necessary.
    */
    if (!d->contextArray.isEmpty()) {
        quint16 hTableSize = 0;
        QDataStream t(d->contextArray);
        t >> hTableSize;
        uint g = elfHash(context) % hTableSize;
        t.device()->seek(2 + (g << 1));
        quint16 off;
        t >> off;
        if (off == 0)
            return TranslatorMessage();
        t.device()->seek(2 + (hTableSize << 1) + (off << 1));

        quint8 len;
        char con[256];
        for (;;) {
            t >> len;
            if (len == 0)
                return TranslatorMessage();
            t.readRawData(con, len);
            con[len] = '\0';
            if (qstrcmp(con, context) == 0)
                break;
        }
    }

    size_t numItems = d->offsetArray.size() / (2 * sizeof(quint32));
    if (!numItems)
        return TranslatorMessage();

    for (;;) {
        quint32 h = elfHash(QByteArray(sourceText) + comment);

        char *r = (char *) bsearch(&h, d->offsetArray, numItems,
                                   2 * sizeof(quint32),
                                   (QSysInfo::ByteOrder == QSysInfo::BigEndian) ? cmp_uint32_big
                                   : cmp_uint32_little);
        if (r != 0) {
            // go back on equal key
            while (r != d->offsetArray.constData() && cmp_uint32_big(r - 8, r) == 0)
                r -= 8;

            QDataStream s(d->offsetArray);
            s.device()->seek(r - d->offsetArray.constData());

            quint32 rh, ro;
            s >> rh >> ro;

            QDataStream ms(d->messageArray);
            while (rh == h) {
                ms.device()->seek(ro);
                TranslatorMessage m(ms);
                if (match(m.context(), context)
                        && match(m.sourceText(), sourceText)
                        && match(m.comment(), comment))
                    return m;
                if (s.atEnd())
                    break;
                s >> rh >> ro;
            }
        }
        if (!comment[0])
            break;
        comment = "";
    }
    return TranslatorMessage();
}

/*!
    Returns true if this translator is empty, otherwise returns false.
    This function works with stripped and unstripped translation files.
*/
bool Translator::isEmpty() const
{
    return !d->unmapPointer && !d->unmapLength && d->messageArray.isEmpty() &&
           d->offsetArray.isEmpty() && d->contextArray.isEmpty() && d->messages.isEmpty();
}


#ifndef QT_NO_TRANSLATION_BUILDER

/*!
    Returns a list of the messages in the translator. This function is
    rather slow. Because it is seldom called, it's optimized for
    simplicity and small size, rather than speed.

    If you want to iterate over the list, you should iterate over a
    copy, e.g.
    \code
    QList<TranslatorMessage> list = myTranslator.messages();
    QList<TranslatorMessage>::Iterator it = list.begin();
    while (it != list.end()) {
        process_message(*it);
        ++it;
    }
  \endcode
*/

QList<TranslatorMessage> Translator::messages() const
{
    ((Translator *) this)->unsqueeze();
    return d->messages.keys();
}

#endif

/*!
    \class TranslatorMessage

    \brief The TranslatorMessage class contains a translator message and its
    properties.

    \ingroup i18n
    \ingroup environment

    This class is of no interest to most applications. It is useful
    for translation tools such as \l{Qt Linguist Manual}{Qt Linguist}.
    It is provided simply to make the API complete and regular.

    For a Translator object, a lookup key is a triple (\e context, \e
    {source text}, \e comment) that uniquely identifies a message. An
    extended key is a quadruple (\e hash, \e context, \e {source
    text}, \e comment), where \e hash is computed from the source text
    and the comment. Unless you plan to read and write messages
    yourself, you need not worry about the hash value.

    TranslatorMessage stores this triple or quadruple and the relevant
    translation if there is any.

    \sa Translator
*/

/*!
    Constructs a translator message with the extended key (0, 0, 0, 0)
    and an empty string as translation.
*/

TranslatorMessage::TranslatorMessage()
    : h(0)
{
}


/*!
    Constructs an translator message with the extended key (\e h, \a
    context, \a sourceText, \a comment), where \e h is computed from
    \a sourceText and \a comment, and possibly with a \a translation.
*/

TranslatorMessage::TranslatorMessage(const char * context,
                                        const char * sourceText,
                                        const char * comment,
                                        const QString& translation)
    : cx(context), st(sourceText), cm(comment), tn(translation)
{
    // 0 means we don't know, "" means empty
    if (cx == (const char*)0)
        cx = "";
    if (st == (const char*)0)
        st = "";
    if (cm == (const char*)0)
        cm = "";
    h = elfHash(st + cm);
}


/*!
    Constructs a translator message read from the \a stream. The
    resulting message may have any combination of content.

    \sa Translator::save()
*/

TranslatorMessage::TranslatorMessage(QDataStream & stream)
    : h(0)
{
    QString str16;
    char tag;
    quint8 obs1;

    for (;;) {
        tag = 0;
        if (!stream.atEnd())
            stream.readRawData(&tag, 1);
        switch((Tag)tag) {
        case Tag_End:
            if (h == 0)
                h = elfHash(st + cm);
            return;
        case Tag_SourceText16: // obsolete
            stream >> str16;
            st = str16.toLatin1();
            break;
        case Tag_Translation:
            stream >> tn;
            break;
        case Tag_Context16: // obsolete
            stream >> str16;
            cx = str16.toLatin1();
            break;
        case Tag_Hash:
            stream >> h;
            break;
        case Tag_SourceText:
            stream >> st;
            break;
        case Tag_Context:
            stream >> cx;
            if (cx.isEmpty()) // for compatibility
                cx = 0;
            break;
        case Tag_Comment:
            stream >> cm;
            break;
        case Tag_Obsolete1: // obsolete
            stream >> obs1;
            break;
        default:
            h = 0;
            st = 0;
            cx = 0;
            cm = 0;
            tn.clear();
            return;
        }
    }
}


/*!
    Constructs a copy of translator message \a m.
*/

TranslatorMessage::TranslatorMessage(const TranslatorMessage & m)
    : cx(m.cx), st(m.st), cm(m.cm), tn(m.tn)
{
    h = m.h;
}


/*!
    Assigns message \a m to this translator message and returns a
    reference to this translator message.
*/

TranslatorMessage & TranslatorMessage::operator=(
        const TranslatorMessage & m)
{
    h = m.h;
    cx = m.cx;
    st = m.st;
    cm = m.cm;
    tn = m.tn;
    return *this;
}


/*!
    \fn uint TranslatorMessage::hash() const

    Returns the hash value used internally to represent the lookup
    key. This value is zero only if this translator message was
    constructed from a stream containing invalid data.

    The hashing function is unspecified, but it will remain unchanged
    in future versions of Qt.
*/

/*!
    \fn const char *TranslatorMessage::context() const

    Returns the context for this message (e.g. "MyDialog").
*/

/*!
    \fn const char *TranslatorMessage::sourceText() const

    Returns the source text of this message (e.g. "&Save").
*/

/*!
    \fn const char *TranslatorMessage::comment() const

    Returns the comment for this message (e.g. "File|Save").
*/

/*!
    \fn void TranslatorMessage::setTranslation(const QString & translation)

    Sets the translation of the source text to \a translation.

    \sa translation()
*/

/*!
    \fn QString TranslatorMessage::translation() const

    Returns the translation of the source text (e.g., "&Sauvegarder").

    \sa setTranslation()
*/

/*!
    \enum TranslatorMessage::Prefix

    Let (\e h, \e c, \e s, \e m) be the extended key. The possible
    prefixes are

    \value NoPrefix  no prefix
    \value Hash  only (\e h)
    \value HashContext  only (\e h, \e c)
    \value HashContextSourceText  only (\e h, \e c, \e s)
    \value HashContextSourceTextComment  the whole extended key, (\e
        h, \e c, \e s, \e m)

    \sa write() commonPrefix()
*/

/*!
    Writes this translator message to the \a stream. If \a strip is
    false (the default), all the information in the message is
    written. If \a strip is true, only the part of the extended key
    specified by \a prefix is written with the translation (\c
    HashContextSourceTextComment by default).

    \sa commonPrefix()
*/

void TranslatorMessage::write(QDataStream & stream, bool strip,
                                Prefix prefix) const
{
    char tag;

    tag = (char)Tag_Translation;
    stream.writeRawData(&tag, 1);
    stream << tn;

    if (!strip)
        prefix = HashContextSourceTextComment;

    switch (prefix) {
    case HashContextSourceTextComment:
        tag = (char)Tag_Comment;
        stream.writeRawData(&tag, 1);
        stream << cm;
        // fall through
    case HashContextSourceText:
        tag = (char)Tag_SourceText;
        stream.writeRawData(&tag, 1);
        stream << st;
        // fall through
    case HashContext:
        tag = (char)Tag_Context;
        stream.writeRawData(&tag, 1);
        stream << cx;
        // fall through
    default:
        tag = (char)Tag_Hash;
        stream.writeRawData(&tag, 1);
        stream << h;
    }

    tag = (char)Tag_End;
    stream.writeRawData(&tag, 1);
}


/*!
    Returns the widest lookup prefix that is common to this translator
    message and to message \a m.

    For example, if the extended key is for this message is (71,
    "PrintDialog", "Yes", "Print?") and that for \a m is (71,
    "PrintDialog", "No", "Print?"), this function returns \c
    HashContext.

    \sa write()
*/

TranslatorMessage::Prefix TranslatorMessage::commonPrefix(
        const TranslatorMessage& m) const
{
    if (h != m.h)
        return NoPrefix;
    if (cx != m.cx)
        return Hash;
    if (st != m.st)
        return HashContext;
    if (cm != m.cm)
        return HashContextSourceText;
    return HashContextSourceTextComment;
}


/*!
 Returns true if the extended key of this object is equal to that of
 \a m; otherwise returns false.
*/

bool TranslatorMessage::operator==(const TranslatorMessage& m) const
{
    return h == m.h && cx == m.cx && st == m.st && cm == m.cm;
}


/*!
    \fn bool TranslatorMessage::operator!=(const TranslatorMessage& m) const

    Returns true if the extended key of this object is different from
    that of \a m; otherwise returns false.
*/


/*!
    Returns true if the extended key of this object is
    lexicographically before than that of \a m; otherwise returns
    false.
*/

bool TranslatorMessage::operator<(const TranslatorMessage& m) const
{
    return h != m.h ? h < m.h
           : (cx != m.cx ? cx < m.cx
             : (st != m.st ? st < m.st : cm < m.cm));
}


/*!
    \fn bool TranslatorMessage::operator<=(const TranslatorMessage& m) const

    Returns true if the extended key of this object is
    lexicographically before that of \a m or if they are equal;
    otherwise returns false.
*/

/*!
    \fn bool TranslatorMessage::operator>(const TranslatorMessage& m) const

    Returns true if the extended key of this object is
    lexicographically after that of \a m; otherwise returns false.
*/

/*!
    \fn bool TranslatorMessage::operator>=(const TranslatorMessage& m) const

    Returns true if the extended key of this object is
    lexicographically after that of \a m or if they are equal;
    otherwise returns false.
*/

/*!
    \fn QString Translator::find(const char *context, const char *sourceText, const char * comment) const

    Use findMessage() instead.
*/

#endif // QT_NO_TRANSLATION
