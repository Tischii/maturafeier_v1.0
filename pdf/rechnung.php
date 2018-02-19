<?php
//============================================================+
// License: GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2016 Nils Reimers - PHP-Einfach.de
// This is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// Nachfolgend erhaltet ihr basierend auf der open-source Library TCPDF (https://tcpdf.org/)
// ein einfaches Script zur Erstellung von PDF-Dokumenten, hier am Beispiel einer Rechnung.
// Das Aussehen der Rechnung ist mittels HTML definiert und wird per TCPDF in ein PDF-Dokument übersetzt.
// Die meisten HTML Befehle funktionieren sowie einige inline-CSS Befehle. Die Unterstützung für CSS ist
// aber noch stark eingeschränkt. TCPDF läuft ohne zusätzliche Software auf den meisten PHP-Installationen.
// Gerne könnt ihr das Script frei anpassen und auch als Basis für andere dynamisch erzeugte PDF-Dokumente nutzen.
// Im Ordner tcpdf/ befindet sich die Version 6.2.3 der Bibliothek. Unter https://tcpdf.org/ könnt ihr erfahren, ob
// eine aktuellere Variante existiert und diese ggf. einbinden.
//
// Weitere Infos: http://www.php-einfach.de/experte/php-codebeispiele/pdf-per-php-erstellen-pdf-rechnung/ | https://github.com/PHP-Einfach/pdf-rechnung/
session_start();

if(null==isset($_SESSION['userid'])) {
    header('Location: ../login.php');

}

$userid = $_SESSION['userid'];
$benutzer = $_SESSION['benutzer'];

$pdo = new PDO('mysql:host=e77773-mysql.services.easyname.eu;dbname=u120219db1', 'u120219db1', 'maturafeier');
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$statement = $pdo->prepare("SELECT rechnungsnummer, anzahl FROM teilnehmer WHERE id = :userid");
$result = $statement->execute(array(':userid' => $userid));
while($row = $statement->fetch()) {
    $rechnungs_nummer = $row['rechnungsnummer'];
    $anzahl = $row['anzahl'];
}

$sql = $pdo->prepare("SELECT vorname, nachname FROM user WHERE id = :userid");
$erg = $sql->execute(array(':userid' => $userid));
while($line = $sql->fetch()) {
    $name = $line['vorname'].' '.$line['nachname'];
}


$rechnungs_datum = date("d.m.Y");
$pdfAuthor = "Maturafeier";


$rechnungs_header = '
<img src="/data/web/e77773/html/pdf/logo.png" width="160">
www.maturafeier-htl3r.at';

$rechnungs_footer = '';

//Auflistung eurer verschiedenen Posten im Format [Produktbezeichnuns, Menge, Einzelpreis]
$preis = 20.00;
$gesamtpreis = $anzahl * $preis;

//Höhe eurer Umsatzsteuer. 0.19 für 19% Umsatzsteuer
$umsatzsteuer = 0.0;

$pdfName = "Rechnung_".$rechnungs_nummer.".pdf";


//////////////////////////// Inhalt des PDFs als HTML-Code \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


// Erstellung des HTML-Codes. Dieser HTML-Code definiert das Aussehen eures PDFs.
// tcpdf unterstützt recht viele HTML-Befehle. Die Nutzung von CSS ist allerdings
// stark eingeschränkt.

$html = '
<table cellpadding="0" cellspacing="0" style="width: 100%;">
	<tr >
		<td>'.nl2br(trim($rechnungs_header)).'</td>
	   <td style="text-align: right">
Best&auml;tigungsnummer '.$rechnungs_nummer.'<br>
Best&auml;tigungsdatum: '.$rechnungs_datum.'<br>
		</td>
	</tr>

	<tr>
		 <td style="font-size:1.3em; font-weight: bold;">
<br><br><br><br>
Kosten für '.$name.'
<br>
		 </td>
	</tr>


	
</table>
Für die Teilnahme an der Maturafeier entsteht nachfolgender Betrag
<br><br><br>

<table cellpadding="5" cellspacing="0" style="width: 100%;" border="0">
	<tr style="background-color: #cccccc; padding:5px;">
		<td style="padding:5px; text-align: center; "><b>Personenanzahl</b></td>
		<td style="text-align: center;"><b>Preis pro Person</b></td>
	</tr>
	<tr>
		<td style="text-align: center;">'.$anzahl.'</td>
		<td style="text-align: center;">'.number_format($preis, 2, ',', '').' €</td>		
    </tr>
	</table>
<hr>
<table cellpadding="5" cellspacing="0" style="width: 100%;" border="0">
            <tr>
                <td style="text-align: center;"><b>Gesamtsumme: </b></td>
                <td style="text-align: center;"><b>'.number_format($gesamtpreis, 2, ',', '').' Euro</b></td>
            </tr>			
        </table>
<br><br><br>


Bitte den offenen Betrag bis 31.März 2018 an folgende Bankdaten einzahlen oder bar an
Hannah Schmidsberger:<br><br>

<table cellpadding="0" cellspacing="0">
<tr style="padding:0px;">
	<th style="padding:5px;"><b>Empfänger:</b></th><td>Hannah Schmidsberger</td>
</tr>
<tr style="padding:5px;">
	<th><b>IBAN:</b></th><td>AT63 3300 0000 0241 0025</td>
</tr>
<tr style="padding:5px;">
	<th><b>BIC:</b></th><td>RLBBAT2E</td>
</tr>
<tr style="padding:5px;">
	<th><b>Verwendungszweck:</b></th><td>'.$name.' ('.$anzahl.')</td>
</tr>
</table>
<br>
<br>
<br>

ACHTUNG:<br>Dies ist keine Rechnung. Die überwiesenen Beträge werden gesammelt von Hannah Schmidsberger an den Betreiber des Gösser Schlössl übergeben.<br>
Eine Rückerstattung kann nur bis 25.Mai 2018 gewährleistet werden.

<br><br><br><br><br><br><br>

<hr>

<h3>Wichtige Informationen</h3>
<br>
<table cellpadding="0" cellspacing="0">
<tr style="padding:0px;">
	<th style="padding:5px;"><b>Wann:</b></th><td>08.06.2018, 18 Uhr</td>
</tr>
<tr style="padding:5px;">
	<th><b>Wo:</b></th><td>Gösser Schlössl, Geßlgasse 4A, 1230 Wien</td>
</tr>
<tr style="padding:5px;">
	<th><b>Kontakt:</b></th><td>Kontakt: hannah.schmidsberger@htl.rennweg.at, 0660/3524708</td>
</tr>
</table>



';



#$html .= nl2br($footer);







//////////////////////////// Erzeugung eures PDF Dokuments \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

// TCPDF Library laden
require_once('tcpdf/tcpdf.php');

// Erstellung des PDF Dokuments
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Dokumenteninformationen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($pdfAuthor);
$pdf->SetTitle('Rechnung_'.$rechnungs_nummer);
$pdf->SetSubject('Rechnung_'.$rechnungs_nummer);


// Header und Footer Informationen
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Auswahl des Font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Auswahl der MArgins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Automatisches Autobreak der Seiten
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Image Scale
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Schriftart
$pdf->SetFont('dejavusans', '', 10);

// Neue Seite
$pdf->AddPage();

// Fügt den HTML Code in das PDF Dokument ein
$pdf->writeHTML($html, true, false, true, false, '');

//Ausgabe der PDF

//Variante 1: PDF direkt an den Benutzer senden:
//ob_end_clean();
//$pdf->Output($pdfName, 'I');

//Variante 2: PDF im Verzeichnis abspeichern:
ob_end_clean();
$pdf->Output(dirname(__FILE__).'/Rechnungen/'.$pdfName, 'F');
$pdf->Output($pdfName, 'I');

?>