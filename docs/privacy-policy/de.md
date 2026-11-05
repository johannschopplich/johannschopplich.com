<!--
Vorlage für die Datenschutzerklärungen aller Sites; en.md ist die Übersetzung. {{#x}}…{{/x}} gilt nur, wenn die Site x nutzt, {{^x}}…{{/x}} nur, wenn nicht.
- aesculap-apotheke-gera.de: hetzner, itProvider, ionos, youtube, health, extra (Impfcheck und Notdienstplan)
- zahnarzt-schopplich.de: hetzner, itProvider, ionos, health
- bildhauer-volkmar-kuehn.de: hetzner, itProvider, ionos
- dachsbau-tautenhain.de: cloudflare, hetzner, itProvider, ionos
- realtroll.de: hetzner, ionos, youtube, youtubePoster, comments
- johannschopplich.com: hetzner, ionos, youtube, youtubePoster, colorScheme
- kirby.tools: cloudflare, cloudflareAnalytics, cloudflareEmail, ionos, colorScheme, paddle
-->

Stand: {{date}}

Diese Website setzt keine {{^cloudflareAnalytics}}Analyse-, {{/cloudflareAnalytics}}Tracking- oder Werbedienste ein.{{#cloudflareAnalytics}} Zugriffe zählen wir nur mit Cloudflare Web Analytics, ohne Cookies.{{/cloudflareAnalytics}} {{#youtube}}Videos von YouTube laden erst, wenn Sie sie starten. {{/youtube}}Personenbezogene Daten verarbeiten wir nur, soweit es für den Betrieb der Website{{#cloudflareAnalytics}}, für die Reichweitenmessung{{/cloudflareAnalytics}}{{#comments}}, für Ihre Kommentare{{/comments}}{{#paddle}}, für Ihre Lizenzen{{/paddle}} und für die Beantwortung Ihrer Anfragen nötig ist.

## Verantwortlich

Verantwortlich für die Datenverarbeitung auf dieser Website ist:

{{controller}}

## Aufruf der Website

Wenn Sie diese Website aufrufen, überträgt Ihr Browser technisch bedingt Daten an den Server, darunter Ihre IP-Adresse, die aufgerufene Adresse, die zuvor besuchte Seite (Referrer) sowie Angaben zu Browser und Betriebssystem. Der Server braucht sie, um Ihnen die Seite auszuliefern. Rechtsgrundlage ist unser berechtigtes Interesse an einem sicheren und funktionierenden Webauftritt (Art. 6 Abs. 1 lit. f DSGVO).

{{#cloudflare}}
{{#hetzner}}Die Seiten selbst{{/hetzner}}{{^hetzner}}Die Website{{/hetzner}} liefert die Cloudflare, Inc., 101 Townsend St, San Francisco, CA 94107, USA, über ihr weltweites Servernetz aus. Cloudflare verarbeitet die Verbindungsdaten dabei in unserem Auftrag (Art. 28 DSGVO), auch um Angriffe abzuwehren. Ein Protokoll der Seitenaufrufe erhalten wir von Cloudflare nicht. Die Daten können dabei in die USA und andere Länder außerhalb der EU gelangen. Cloudflare ist nach dem EU-US Data Privacy Framework zertifiziert, für das ein Angemessenheitsbeschluss der EU-Kommission besteht (Art. 45 DSGVO), und hat zusätzlich die Standardvertragsklauseln der EU-Kommission vereinbart (Art. 46 Abs. 2 lit. c DSGVO). Mehr dazu in der [Datenschutzerklärung von Cloudflare](https://www.cloudflare.com/privacypolicy/).
{{/cloudflare}}

{{#hetzner}}
{{#cloudflare}}Texte und Bilder liegen auf einem Server{{/cloudflare}}{{^cloudflare}}Die Website läuft auf einem Server{{/cloudflare}} der Hetzner Online GmbH, Industriestr. 25, 91710 Gunzenhausen, in einem Rechenzentrum in Deutschland{{#itProvider}}, den ein IT-Dienstleister für uns betreibt{{/itProvider}}.{{#cloudflare}} Auch die Bilder liefert Cloudflare aus. Nur wenn Cloudflare ein Bild noch nicht zwischengespeichert hat, holt es das Bild von diesem Server, der dabei Ihre IP-Adresse erhält.{{/cloudflare}} Ein Protokoll der Seitenaufrufe führt der Server nicht. Nur wenn beim Ausliefern ein Fehler auftritt, schreibt er einen Eintrag in ein Fehlerprotokoll: Datum und Uhrzeit, IP-Adresse, die aufgerufene Domain und Anfrage, gegebenenfalls die zuvor besuchte Seite und die Fehlermeldung. Wir nutzen es, um Fehler zu beheben und Angriffe abzuwehren; nach 30 Tagen wird es automatisch gelöscht.
{{/hetzner}}

Sie sind nicht verpflichtet, uns Daten bereitzustellen. Ohne die Verbindungsdaten lässt sich die Website aber nicht anzeigen.

{{#cloudflareAnalytics}}

## Reichweitenmessung

Um zu sehen, wie oft unsere Seiten aufgerufen werden und wie schnell sie laden, nutzen wir Cloudflare Web Analytics der Cloudflare, Inc. (siehe oben). Ein kleines Skript liest beim Laden einer Seite die Ladezeiten aus der Performance-Schnittstelle Ihres Browsers und meldet sie mit der Adresse der aufgerufenen Seite (ohne Parameter) und der Herkunftsseite an Cloudflare, sobald die Seite geladen ist, bei jedem Seitenwechsel und wenn Sie die Seite verlassen oder ausblenden. Aus Ihrer IP-Adresse und der Browserkennung leitet Cloudflare Land, Browser, Betriebssystem und Gerätetyp ab. Cookies oder andere Speicher auf Ihrem Gerät nutzt es nicht; Cloudflare erstellt dabei nach eigenen Angaben keine Fingerabdrücke einzelner Besucher. Wir sehen nur zusammengefasste Zahlen. Cloudflare bewahrt die einzelnen Messwerte sieben Tage auf und verdichtet sie danach; abrufbar sind sie für sechs Monate. Rechtsgrundlage ist unser berechtigtes Interesse, Reichweite und Ladezeiten unserer Website zu kennen (Art. 6 Abs. 1 lit. f DSGVO). Für die Übermittlung in die USA gilt das oben zu Cloudflare Gesagte.
{{/cloudflareAnalytics}}

## Speicherung auf Ihrem Gerät

Beim Aufruf der Website setzen wir keine Cookies und speichern nichts auf Ihrem Gerät. Schriften und Skripte liefern wir selbst aus, nicht über fremde Anbieter{{#cloudflareAnalytics}} – mit Ausnahme des Messskripts von Cloudflare (siehe oben){{/cloudflareAnalytics}}.

{{#colorScheme}}
Ihr Browser speichert lokal (Local Storage), ob Sie helles oder dunkles Design sehen, damit Ihre Wahl beim nächsten Besuch erhalten bleibt. Die Angabe verlässt Ihr Gerät nicht. Diese Speicherung ist für die von Ihnen gewünschte Funktion unbedingt erforderlich (§ 25 Abs. 2 Nr. 2 TDDDG).
{{/colorScheme}}

{{#storageTriggers}}
Cookies oder ähnliche Techniken kommen erst ins Spiel, wenn Sie {{storageTriggers}}. Die Einzelheiten stehen in den folgenden Abschnitten.
{{/storageTriggers}}

Links zu anderen Websites sind gewöhnliche Links. Erst wenn Sie einen anklicken, verbindet sich Ihr Browser mit der anderen Website; dort gilt deren Datenschutzerklärung.

{{#youtube}}

## Videos von YouTube

Unsere Website zeigt Videos, die bei YouTube liegen. Anbieter ist die Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland.

{{#youtubePoster}}
Das Vorschaubild eines Videos lädt Ihr Browser beim Aufruf der Seite von einem Bildserver von YouTube (i.ytimg.com). Google erhält dabei Ihre IP-Adresse, die Adresse unserer Seite und Angaben zu Ihrem Browser; Cookies setzt dieser Bildserver nicht. Rechtsgrundlage ist unser berechtigtes Interesse, Ihnen eine Vorschau des Videos zu zeigen (Art. 6 Abs. 1 lit. f DSGVO).
{{/youtubePoster}}
{{^youtubePoster}}
Bis Sie ein Video starten, sehen Sie nur ein Vorschaubild von unserem Server; Ihr Browser verbindet sich nicht mit YouTube.
{{/youtubePoster}}

Erst wenn Sie auf „Video abspielen“ klicken, lädt Ihr Browser den Player von YouTube. Wir binden ihn über die Adresse youtube-nocookie.com ein. Google erhält dabei Ihre IP-Adresse, die Adresse unserer Seite sowie Angaben zu Browser und Gerät; YouTube kann außerdem Cookies oder ähnliche Techniken auf Ihrem Gerät einsetzen. Wir laden das Video nur, weil Sie es ausdrücklich abrufen (§ 25 Abs. 2 Nr. 2 TDDDG). Rechtsgrundlage ist unser berechtigtes Interesse, Ihnen das Video auf Ihren Wunsch zu zeigen (Art. 6 Abs. 1 lit. f DSGVO).

Google kann die Daten auch in die USA übermitteln. Die Google LLC ist nach dem EU-US Data Privacy Framework zertifiziert, für das ein Angemessenheitsbeschluss der EU-Kommission besteht (Art. 45 DSGVO). Wie Google die Daten verwendet, beschreibt die [Datenschutzerklärung von Google](https://policies.google.com/privacy).
{{/youtube}}

{{#comments}}

## Kommentare

Wenn Sie einen Kommentar schreiben, speichern wir den Namen, den Sie angeben, Ihren Text und den Zeitpunkt. Wir lesen jeden Kommentar, bevor er erscheint; danach ist er mit Ihrem Namen öffentlich unter dem Beitrag zu sehen. Ihre IP-Adresse und Ihre E-Mail-Adresse speichern wir dabei nicht. Rechtsgrundlage ist unser berechtigtes Interesse, unter den Beiträgen eine Diskussion zu ermöglichen (Art. 6 Abs. 1 lit. f DSGVO). Ihr Kommentar bleibt stehen, solange der Beitrag online ist; auf Ihren Wunsch löschen wir ihn jederzeit.

Über jeden neuen Kommentar benachrichtigt uns eine E-Mail mit Ihrem Namen und einem Auszug Ihres Textes. Sie wird über den Dienst Resend der Plus Five Five, Inc., San Francisco, USA, versendet, der in unserem Auftrag handelt (Art. 28 DSGVO). Resend ist nach dem EU-US Data Privacy Framework zertifiziert (Art. 45 DSGVO).

Sobald Sie das Formular benutzen, setzen wir ein Sitzungs-Cookie (kirby_session). Es schützt das Formular vor gefälschten Anfragen und läuft nach zwei Stunden ab. Das Cookie ist für den von Ihnen gewünschten Versand unbedingt erforderlich (§ 25 Abs. 2 Nr. 2 TDDDG).

Zum Schutz vor Spam nutzen wir Cloudflare Turnstile der Cloudflare, Inc., 101 Townsend St, San Francisco, CA 94107, USA. Das Prüfprogramm lädt erst, wenn Sie das Kommentarformular zum ersten Mal benutzen. Dann verarbeitet Cloudflare Ihre IP-Adresse sowie Angaben zu Browser, Gerät und Ihrer Interaktion, um Menschen von automatisierten Programmen zu unterscheiden; beim Absenden übermittelt unser Server Ihre IP-Adresse zur Prüfung an Cloudflare. Name und Text Ihres Kommentars erhält Cloudflare nicht. Rechtsgrundlage ist unser berechtigtes Interesse, das Formular vor Missbrauch zu schützen (Art. 6 Abs. 1 lit. f DSGVO); was Turnstile auf Ihrem Gerät speichert, ist für den von Ihnen gewünschten Versand unbedingt erforderlich (§ 25 Abs. 2 Nr. 2 TDDDG). Cloudflare kann die Daten in die USA übermitteln. Cloudflare ist nach dem EU-US Data Privacy Framework zertifiziert (Art. 45 DSGVO) und hat zusätzlich die Standardvertragsklauseln der EU-Kommission vereinbart (Art. 46 Abs. 2 lit. c DSGVO). Mehr dazu in den [Datenschutzhinweisen zu Turnstile](https://www.cloudflare.com/turnstile-privacy-policy/).
{{/comments}}

{{#paddle}}

## Kauf von Lizenzen

Unsere Lizenzen verkaufen wir über Paddle. Ihr Vertragspartner beim Kauf ist die Paddle.com Market Limited, Judd House, 18–29 Mora Street, London EC1V 8BT, Vereinigtes Königreich, die als Händler (Merchant of Record) auftritt. Paddle verarbeitet Ihre Zahlungs-, Rechnungs- und Kontaktdaten als eigener Verantwortlicher; dafür gilt die [Datenschutzerklärung von Paddle](https://www.paddle.com/legal/privacy). Das Bezahlfenster von Paddle lädt erst, wenn Sie auf den Kaufen-Knopf klicken, zusammen mit einem Skript von ProfitWell, einem Dienst von Paddle; Paddle kann dabei Cookies oder ähnliche Techniken einsetzen, die für die Bezahlung nötig sind. Für das Vereinigte Königreich besteht ein Angemessenheitsbeschluss der EU-Kommission (Art. 45 DSGVO).

Nach dem Kauf erhalten wir von Paddle die Angaben, die wir für Ihre Lizenz brauchen: Ihre E-Mail-Adresse, die Bestellnummer, das gekaufte Produkt und den Lizenzinhaber, den Sie angegeben haben. Wir nutzen sie, um Ihre Lizenz zu erstellen, zu prüfen und zu verwalten (Art. 6 Abs. 1 lit. b DSGVO). Die Lizenzdaten liegen in einer Datenbank bei Cloudflare (siehe oben); E-Mails zu Ihrer Lizenz, etwa Anmeldelinks, versenden wir über Resend der Plus Five Five, Inc., San Francisco, USA, die nach dem EU-US Data Privacy Framework zertifiziert ist (Art. 45 DSGVO). Beide handeln in unserem Auftrag (Art. 28 DSGVO). Wir bewahren die Lizenzdaten auf, solange Ihre Lizenz besteht, und darüber hinaus, soweit steuerliche Aufbewahrungspflichten es verlangen (§ 147 AO).

Ihre Lizenzen verwalten Sie im Kundenportal hub.kirby.tools, das ebenfalls über Cloudflare läuft. Dort melden Sie sich mit Ihrer E-Mail-Adresse an, entweder über einen Anmeldelink, den wir Ihnen über Resend schicken, oder zusammen mit Ihrer Bestellnummer. Nach der Anmeldung setzen wir ein Sitzungs-Cookie, das Sie sieben Tage angemeldet hält; es ist für das von Ihnen gewünschte Portal unbedingt erforderlich (§ 25 Abs. 2 Nr. 2 TDDDG). Das Bezahlfenster von Paddle lädt dort ebenfalls erst, wenn Sie ein Upgrade kaufen.{{#cloudflareAnalytics}} Auch dort zählen wir Zugriffe mit Cloudflare Web Analytics (siehe oben).{{/cloudflareAnalytics}}
{{/paddle}}

{{extra}}

## Kontakt per {{contactChannels}}

Wenn Sie uns per {{contactChannels}} erreichen, verarbeiten wir Ihre Angaben – etwa Name, Kontaktdaten und den Inhalt Ihrer Nachricht –, um Ihr Anliegen zu bearbeiten. Geht es um einen Vertrag oder eine Bestellung, ist Rechtsgrundlage Art. 6 Abs. 1 lit. b DSGVO, bei sonstigen Anfragen unser berechtigtes Interesse, Ihnen zu antworten (Art. 6 Abs. 1 lit. f DSGVO). Ihre Angaben sind freiwillig; ohne sie können wir Ihnen aber nicht antworten.

{{#health}}
Enthält Ihre Nachricht Angaben zu Ihrer Gesundheit, verarbeiten wir sie {{health}} nach Art. 9 Abs. 2 lit. h DSGVO in Verbindung mit § 22 Abs. 1 Nr. 1 lit. b BDSG. Dabei sind wir an die berufliche Schweigepflicht gebunden.
{{/health}}

{{#ionos}}
{{#cloudflareEmail}}E-Mails an unsere Adresse nimmt Cloudflare (siehe oben) entgegen und leitet sie an unser Postfach weiter. {{/cloudflareEmail}}Unser E-Mail-Postfach betreibt die IONOS SE, Elgendorfer Str. 57, 56410 Montabaur, in Rechenzentren in Deutschland.
{{/ionos}}

{{#health}}
E-Mails sind nicht Ende-zu-Ende-verschlüsselt. Bitte schicken Sie uns deshalb keine sensiblen Gesundheitsangaben per E-Mail. {{healthHint}}
{{/health}}

Wir löschen Ihre Anfrage, sobald sie erledigt ist. {{#retention}}{{retention}}{{/retention}}{{^retention}}Länger bewahren wir sie nur auf, wenn das Gesetz es verlangt.{{/retention}}

## Ihre Rechte

Sie haben nach der DSGVO das Recht auf:

- Auskunft über die zu Ihnen gespeicherten Daten (Art. 15)
- Berichtigung unrichtiger Daten (Art. 16)
- Löschung Ihrer Daten (Art. 17)
- Einschränkung der Verarbeitung (Art. 18)
- Datenübertragbarkeit (Art. 20)
- Widerspruch gegen die Verarbeitung (Art. 21, siehe unten)

Wenden Sie sich dafür einfach an uns, zum Beispiel per E-Mail an [{{email}}](mailto:{{email}}). Automatisierte Entscheidungen oder Profiling setzen wir nicht ein.

Außerdem können Sie sich bei einer Datenschutz-Aufsichtsbehörde beschweren (Art. 77 DSGVO). Für uns zuständig ist {{authority}}.

## Widerspruchsrecht

Soweit wir Daten auf Grundlage unseres berechtigten Interesses verarbeiten (Art. 6 Abs. 1 lit. f DSGVO), können Sie dieser Verarbeitung jederzeit aus Gründen widersprechen, die sich aus Ihrer besonderen Situation ergeben (Art. 21 Abs. 1 DSGVO). Wir verarbeiten die Daten dann nicht mehr, es sei denn, wir können zwingende schutzwürdige Gründe nachweisen, die Ihre Interessen überwiegen, oder die Verarbeitung dient der Geltendmachung, Ausübung oder Verteidigung von Rechtsansprüchen.
