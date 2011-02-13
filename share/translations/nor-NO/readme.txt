VIKTIG FOR NORSK OVERSETTELSE
(gl@ez.no)

LES:

http://www.sprakrad.no/templates/Page.aspx?id=270
http://www.sprakrad.no/templates/Page.aspx?id=351
http://folk.uio.no/tfredvik/amo/



VANSKELIGE ORD OG BEGREPER:

override:		overstyring
locale:			stedsinformasjon
collaboration:		samarbeid
collaboration message:	samarbeidsmelding (ikke bra)
event:			hendelse
time event:		tidshendelse (ikke bra)
site access:		nettstedstilgang (ikke bra)
browse:			bla igjennom, utforsk, let etter
custom:			spesial-
container:		beholder (ikke bra)
cache:			cache (mellomlagring?)
codepage:		kodeside (ikke bra)
item:			enhet, rad, linje, node/objekt
visibility:		synlighet (ikke bra)
parent/children:	foreldre/barn (ikke bra)
option:			valg/opsjon?
policy:			tilgangsregel
the URL:		URL-en
e-mail:			e-post



TING Å TENKE PÅ:

kolon (:) og andre tegn inside or outside of i18n?



ORDDELING

Vi bruker i utgangspunktet ikke orddeling på norsk. Det er forskjell på "lammelår" og "lamme lår"! Legg merke til at uttalen av "lammelår" og "lamme lår" er forskjellig. Hvis det uttales som ett ord, kan man gå ut i fra at det skal skrives som ett ord.

Ett unntak er når fremmedord/engelske tekniske begreper eller forkortelser settes sammen med norske ord eller bøyes. Da brukes bindestrek.
Eksempler:
e-post
URL-liste
URL-en
ID-nummer



SOURCE ERRORS: (Fiks i 3.5.1)

kernel/classes/datatypes: The account is currenty used the administrator user.
design/admin/content/browse_move_node: Choose a new location the copy of <%object_name> using the radio buttons and click "OK".
design/standard/error/kernel: This site uses siteaccess matching in the URL and you didn't supply one, try inserting a siteaccess name before the module in the URL . (extra space)
design/standard/content/upload: Choose a file from your locale machine and click the "Upload" button. An object will be created according to file type and placed in your chosen location. (local, not locale)
design/standard/content/view: Choose a file from your locale machine and click the "Upload" button. An object will be created according to file type and placed in your chosen location. (local, not locale)



VIS STATISTIKK:

echo "total: "; grep "<translation" share/translations/nor-NO/translation.ts | wc -l; echo "not obsolete:"; grep "<translation" share/translations/nor-NO/translation.ts | grep -v obsolete | wc -l; echo "finished:"; grep "<translation" share/translations/nor-NO/translation.ts | grep -v obsolete | grep -v unfinished | wc -l; echo "unfinished:"; grep "<translation" share/translations/nor-NO/translation.ts | grep unfinished | wc -l
