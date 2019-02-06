/*************************************************************************

                    Sample of member cards generation


     Features:
     - Cookies check for printing settings
     - Settings for elements on each card
     - Selection of the place of first card
     - Margins calculation

************************************************************************/


<?php

if (is_numeric($_GET['mLeftCardPage']) && is_numeric($_GET['mTopCardPage']) && is_numeric($_GET['mVInterCardPage']) && is_numeric($_GET['mHInterCardPage'])) {
		setcookie("printMarges[left]",$_GET['mLeftCardPage'],time()+(10 * 365 * 24 * 60 * 60));
		setcookie("printMarges[top]",$_GET['mTopCardPage'],time()+(10 * 365 * 24 * 60 * 60));
		setcookie("printMarges[vinter]",$_GET['mVInterCardPage'],time()+(10 * 365 * 24 * 60 * 60));
		setcookie("printMarges[hinter]",$_GET['mHInterCardPage'],time()+(10 * 365 * 24 * 60 * 60));

		$marginLeft     = $_GET['mLeftCardPage'];
		$marginTop      = $_GET['mTopCadPage'];
		$marginVInter    = $_GET['mVInterCardPage'];
		$marginHInter    = $_GET['mHInterCardPage'];
}

setcookie("printElements[logo]", "nok" ,time()+(10 * 365 * 24 * 60 * 60));
setcookie("printElements[numero]", "nok" ,time()+(10 * 365 * 24 * 60 * 60));
setcookie("printElements[adresse]", "nok" ,time()+(10 * 365 * 24 * 60 * 60));
setcookie("printElements[dateinscription]", "nok" ,time()+(10 * 365 * 24 * 60 * 60));
setcookie("printElements[commissaire]", "nok" ,time()+(10 * 365 * 24 * 60 * 60));

foreach ($_GET['elements'] as $printElement) {
    //echo $printElement;
    setcookie("printElements[$printElement]", "ok" ,time()+(10 * 365 * 24 * 60 * 60));
}

setcookie("clubColor", $_GET['clubcolor'] ,time()+(10 * 365 * 24 * 60 * 60));

include 'header.inc.php';
if($auth)
{

?>

<!DOCTYPE html>
<html>

<head>
    <title>
        <?= $str["docgenshootlog"] ?>
    </title>
</head>

<body onload="window.print()">
    <?php
function write_card() {
	global $str;
    if(isset($_GET['id'])&&is_numeric($_GET['id']))
    {
		$m=Member::findId($_GET["id"]);
		if($m)
		{
            echo '<img class="right" src="barcode_image.php?id=';

            if(strpos(Settings::getParameter("ident"), 'i')!==false) { echo $m->suid().'&amp;type=i" width="144" height="60"/>';}
            else { echo $m->ref.'&amp;type=r" width="96" height="60"/>';}

            echo "<h1 style='color:".$_GET['clubcolor']."'>".Settings::getParameter('name_s')." &emsp; ".DocOptions::getDocumentOption("mc","py")."</h1>";

            if (Photo::isDefined($m->id)) {
              echo '<img class="left" src="member_photo.php?id='.$m->id.'" width="90"/>';
            }
            else { echo '<img class="left" src="img/default_photo.png" width="90"/>';}

            echo "<h2>$m->prenom $m->nom</h2>";

            if (in_array('adresse',$_GET['elements'])) {
                echo '<p>'.$m->rue.'<br>'.$m->cp.' '.$m->ville.'</p>';
            }

            if (in_array('numero',$_GET['elements']) && strpos(Settings::getParameter("ident"), 'i')!==false) {
                echo "<p>".$str["membern"].' '.$m->ref."</p>";
            }

            if (in_array('dateinscription',$_GET['elements'])) {
                echo "<p>".$str["registerdate"].' '.(($m->date_inscription[0]!='0')?date_create($m->date_inscription)->format('j/m/Y'):"inconnue")."</p>";
            }

            if (in_array('commissaire',$_GET['elements']) && $m->type =='C') {
                echo "<p class='comm-role'>".$str["Commissaire"]."</p>";
            }

            if (in_array('logo',$_GET['elements'])) {
            ?>
    <img class="club-logo" src="logos/<?php echo Settings::getParameter('logo_l'); ?>">
    <?php
            }
		}
    }
}
if(isset($_GET['format'])&&$_GET['format']=="ID1") {
?>
    <div class='card'>
        <?php write_card(); ?>
    </div>
    <?php } else { ?>
    <div class='page'>
        <table>
            <tr>
                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="00") write_card(); ?>
                    </div>
                </td>

                <td class="white-td"></td>

                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="01") write_card(); ?>
                    </div>
                </td>
            </tr>
            <tr class="white-tr">
            <tr>
                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="10") write_card(); ?>
                    </div>
                </td>

                <td class="white-td"></td>

                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="11") write_card(); ?>
                    </div>
                </td>
            </tr>
            <tr class="white-tr">
            <tr>
                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="20") write_card(); ?>
                    </div>
                </td>

                <td class="white-td"></td>

                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="21") write_card(); ?>
                    </div>
                </td>
            </tr>
            <tr class="white-tr">
            <tr>
                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="30") write_card(); ?>
                    </div>
                </td>

                <td class="white-td"></td>

                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="31") write_card(); ?>
                    </div>
                </td>
            </tr>
            <tr class="white-tr">
            <tr>
                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="40") write_card(); ?>
                    </div>
                </td>

                <td class="white-td"></td>

                <td>
                    <div class="card">
                        <?php if(isset($_GET['position'])&&$_GET['position']=="41") write_card(); ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <?php

}

?>

    <script src="js/jquery-1.10.2.js"></script>
    <script>
        $(document).ready(function() {
            $marginLeft = "<?php echo $marginLeft; ?>";
            $marginTop = "<?php echo $marginTop; ?>";
            $marginVInter = "<?php echo $marginVInter ?>";
            $marginHInter = "<?php echo $marginHInter ?>";

            $marginRight = 40 - $marginLeft - $marginHInter;
            $marginBottom = 30 - $marginTop - (4 * $marginHInter);

            $(".page").css('padding-left', $marginLeft + 'mm');
            $(".page").css('padding-top', $marginTop + 'mm');
            $(".page").css('padding-right', $marginRight + 'mm');
            $(".page").css('padding-bottom', $marginBottom + 'mm');

            $(".white-td").css('width', $marginHInter + 'mm');
            $(".white-tr").css('height', $marginVInter + 'mm');

        });

    </script>

</body>

</html>

}
?>
