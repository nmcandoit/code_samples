/*************************************************************************

                   Sample of TCPDF generation


        Features:
        - Make PDF with dynamic informations

************************************************************************/


<?php
include 'header.inc.php';
if($auth) {

if (isset($_GET["id"])) $member=Member::findId($_GET["id"]);

$startyear = $_GET["startyear"];
$endyear = $_GET["endyear"];

$years = $_GET["endyear"] - $_GET["startyear"] + 1;

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

switch($user->lang) {
    case "F":
        $user_lang = "fr";
        break;
    case "N":
        $user_lang = "nl";
        break;
}

$ref = sprintf('%06d/%04d',$member->id ,time()/86400-15000);

$generateStart = microtime(true);

class MYPDF extends TCPDF {
    // Page footer
    public function Footer() {
        global $user_lang,$ref;
        // Make more space in footer for additional text
        $this->SetY(-15);

        $this->SetFont('helvetica', 'N', 10);

        // First line of 3x "sometext"
        $this->MultiCell(62, 10, 'Type: freq_be_fed_'.$user_lang.'_v1', 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M');
        $this->MultiCell(62, 10, 'Ref:'.$ref, 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        //if ($this->getAliasNbPages() > 0) {
        //    $this->MultiCell(62, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 'R', 0, 0, '', '', true, 0, false, true, 10, 'M');
        //}

    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Shootlog');
$pdf->SetTitle('ShootLog - attestation');
$pdf->SetSubject('Attestation');
$pdf->SetKeywords('Attestation, Shooting, Legal');

$pdf->SetFont('Helvetica', '', 10, '', false);

// set margins
$pdf->SetMargins(12, 12, 12, 12);
$pdf->SetAutoPageBreak(TRUE, 0);

$pdf->setPrintHeader(false);


//$pdf->setPrintFooter(true);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetFooterMargin(12, 12, 12, 12);

// ---------------------------------------------------------

// add a page
$pdf->AddPage();

// create some HTML content
$tbl = '
<style>
    .header-table {
        border: 1px solid #ddd;
        color: #333;
    }

    .stand-title {
        font-size: 1.2em;
        font-weight: bold;
    }

</style>

<table class="header-table">
    <tr>
        <td style="width: 30%;">
            <p style="text-align:center;"><img src="/logos/'.Settings::getParameter('logo_l').'" height="50"></p>
            <br>
        </td>
        <td style="width: 70%;">

            <table>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td colspan="2" class="stand-title">'.Settings::getParameter('name').'</td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td style="width: 100px;">'.$str["standaddress"].':</td>
                    <td style="width: 400px;">'.Settings::getSites()[0]['address'].'</td>
                </tr>
                <tr>
                    <td style="width: 100px;">'.$str["siegesoc"].':</td>
                    <td style="width: 400px;">'.Settings::getParameter('address').'</td>
                </tr>
                <tr>
                    <td style="width: 100px;">'.$str["agrementnumber"].':</td>
                    <td style="width: 400px;">'.Settings::getParameter('agrement').'</td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>

';

// output the HTML content
$pdf->writeHTML($tbl, true, false, false, false, '');


$pdf->SetFont('Helvetica', '', 10, '', false);

$tbl = '

<style>

    .header-table {
        border: 1px solid #ddd;
        border-color: #ddd;
        color: #333;
        border-collapse: collapse;
    }

    .header-table tr td {
        height: 20px;
        line-height: 20px;
        border: 1px solid #ddd;
    }

    .informatation-label {
        font-style: italic;
    }

    .informatation-data, .year-count {
        font-weight: bold;
    }

    .year-label {
        background-color: #ccc;
        font-weight: bold;
        text-align: center;
    }

    .year-data, .year-count-unit {
        text-align: center;
    }

    .emission-date {
        text-align: right;
    }

</style>

<table class="header-table">
    <tr>
        <td colspan="60" style="background-color: #ccc;"><h2 style="font-size: 1.1em; text-align: center;">Attestation de fréquentation du stand de tir</h2></td>
    </tr>
    <tr>
        <td colspan="60">
            <div>&nbsp;&nbsp;'.$str["jesoussigne"].' '.$user->name.', '.$str["certparlapr"].'</div>
            <br>
        </td>
    </tr>
    <tr>
        <td colspan="18" class="informatation-label">'.$str["lstname"].' :</td>
        <td colspan="42" class="informatation-data">'.$member->nom.'</td>
    </tr>
    <tr>
        <td colspan="18" class="informatation-label">'.$str["fstname"].' :</td>
        <td colspan="42" class="informatation-data">'.$member->prenom.'</td>
    </tr>
    <tr>
        <td colspan="18" class="informatation-label">'.$str["nationalregisternumber"].' :</td>
        <td colspan="42" class="informatation-data">'.$member->national.'</td>
    </tr>
    <tr>
        <td colspan="18" class="informatation-label">'.$str["domicilie"].' :</td>
        <td colspan="42" class="informatation-data">'.$member->rue.' '.$member->num.((isset($member->bte)&&$member->bte!='')?(' /'.$member->bte):'').', '.$member->cp.' '.$member->ville.'</td>
    </tr>
    <tr>
        <td colspan="60">
            <br>
            <br>
            '.$str["inscritcercledepuis"].' '.date_create($member->date_inscription)->format('j/m/Y').'
            <br>
            '.$str["interessafrequdates"].'
            <br>
        </td>
    </tr>';

if ($years > 6) $years = 6;

$sessions_rawdata = $member->countSessionsRangeYears($startyear, $endyear);

// include actual year in range
$years_array = range($startyear, $endyear);
$sessions_data = array();

foreach($years_array as $year) {
    if (isset($sessions_rawdata[$year])) {
        $sessions_data["$year"] = $sessions_rawdata["$year"];
    } else {
        $sessions_data["$year"] = "aucune";
    }
}

$colspan = 60 / $years;

$tbl .= '<tr>';
foreach($sessions_data as $key => $value) {
    $tbl .= '<td colspan="'.$colspan.'" class="year-label">'.$key.'</td>';
}
$tbl .= '</tr>';

$tbl .= '<tr>';
foreach($sessions_data as $key => $value) {
    $tbl .= '
        <td colspan="'.$colspan.'" class="year-data">
            <br>
            <br>
            <span class="year-count">'.$value.'</span>
            <br>
            <span  class="year-count-unit">'.$str["shootsession(s)"].'</span>
        </td>
    ';
}
$tbl .= '</tr>';

$tbl .='

    <tr>
        <td colspan="60">
            <table style="border: none;">
                <tr>
                    <td colspan="60">
                        <div>De plus celui-ci à reçu une information relative au maniement des armes, aux mesures de sécurité et aux règles du tir sportif.</div>
                    </td>
                </tr>
                <tr><td colspan="60">&nbsp;</td></tr>
                <tr>
                    <td colspan="30"><span class="accountant">'.$user->name.'</span></td>
                    <td colspan="30"><span class="emission-date">Date d’émission : '.date("d/m/y").'</span></td>
                </tr>
            </table>

        </td>
    </tr>

</table>
';

//$tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');

$html = '
<style>

.signature-box {
    line-height: 30px;
    color: #666;
    font-size: 1em;
    font-style: italic;
    border-bottom: 1px solid #ddd;
}

</style>

<br>
<br>
<br>
<table style="border:none; width: 450px;">
    <tr>
        <td class="signature-box">
            <br>
            <br>
            ( Signature du responsable + cachet du stand )

        </td>
        <td></td>
    </tr>
</table>


';

$pdf->writeHTML($html, true, false, true, false, '');

//ini_set( 'error_reporting', E_ALL );
//ini_set( 'display_errors', true );

$full_name =  str_replace(' ','_',$member->prenom)."_".str_replace(' ','_',$member->nom);
$club_name = str_replace(' ','_',Settings::getParameter('name'));

//$yearcount = $member->countSessions($years);
$thisYear=date('Y');

$sessions_summary="";

/*for($y=$thisYear-$years;$y<=$thisYear;$y++){
	$sessions_summary .= $y.':'.(isset($yearcount["$y"])?$yearcount["$y"]:"0").',';
}*/

$qr_data = array();

foreach($years_array as $year) {
    $sessions_summary .= $year.':'.(isset($sessions_rawdata["$year"])?$sessions_rawdata["$year"]:"0").',';
}

$sessions_summary = substr($sessions_summary, 0, -1);

$message = "n=$full_name&c=$club_name&y=$sessions_summary&i=$member->id";

include('generate_signature.php');
$signature = slGenSignature($message);
$qr_text="https://www.shootlog.be/cksig.php?$message&s=$signature";

$tbl = '
<style>
    .bottom-table {
        border: 1px solid #ddd;
        color: #333;
    }

    .stand-title {
        font-size: 1.2em;
        font-weight: bold;
    }

</style>

<table class="bottom-table">
    <tr>
        <td colspan="60" style="background-color: #ccc; height: 20px; line-height: 20px;"><h2 style="font-size: 1.1em; text-align: center;">Section de validation réservée aux autorités</h2></td>
    </tr>
    <tr>

        <td colspan="15">
            <br>
            <p style="text-align:center;"><img src="img/LOGO_SL.jpg" height="40"></p>
            <br>
        </td>
        <td colspan="30" style="text-align:center; font-size: 1.1em;">
            <br>
            <br>
            <br>
            '.$str['scanqrcodetext'].'.
            <br>
        </td>
        <td colspan="15">
            <br>
            <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.urlencode($qr_text).'&choe=UTF-8" height="100">
        </td>
    </tr>
</table>

';

// output the HTML content
$pdf->writeHTML($tbl, true, false, false, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('freq_be_fed_'.$user_lang.'_.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

}

$generateEnd = microtime(true);
$generateTime = $generateEnd - $generateStart;
$documentID = 2; /* Check on database to get the correct ID */

Report::insertGenerateDocumentLine($documentID, $member->id, $user->id, $generateTime);

?>
