/***************************************************

     Example of table with informations with jQuery

     Features:
     - Auto resize of colomns with the number of year
     - Ajax request when you change the slider
     - Dynamic erease and increase data

***************************************************/

<script>
$(window).load(function() {
    <?php
    $results=$member->countSessionsRangeYears($startYear, $endYear);
    ?>

    createSlider();
    createTable(<?= json_encode($results) ?>, <?= $startYear ?>, <?= $endYear ?>);

});

function createSlider() {

    $( "#slider-range" ).slider({
      animate: "fast",
      range: true,
      min: <?= $startyear ?>,
      max: <?= $endyear ?>,
      values: [ <?= $startyear ?>, <?= $endyear ?> ],
      slide: function( event, ui ) {
        $( "#amount" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        $( "#attestation_link" ).attr(
            "href",
            "print_attest.php?id=<?= $_GET['id'] ?>&startyear="+ui.values[ 0 ]+"&endyear="+ui.values[ 1 ]
        );
        $( "#appendix_link" ).attr(
            "href",
            "print_attest-appendix.php?id=<?= $_GET['id'] ?>&startyear="+ui.values[ 0 ]+"&endyear="+ui.values[ 1 ]
        );

        $.ajax({
            type: 'GET',
            url: 'ajax/get_shoot_table.php?id=<?= $_GET['id'] ?>&startyear='+ui.values[ 0 ]+'&endyear='+ui.values[ 1 ]+'&rnd='+Math.random(),
            dataType: "json",

            success: function(data, statut) {
                let min = ui.values[ 0 ];
                let max = ui.values[ 1 ];
                createTable(data, min, max);

            },

            error: function(result, statut, error) {
                console.log('result:' + result);
                console.log('statut:' + statut);
                console.log('error:' + error);
            },

        });

      }

    });

    $( "#amount" ).val(
        $( "#slider-range" ).slider( "values", 0 ) +
        " - " +
        $( "#slider-range" ).slider( "values", 1 )
    );

}

function createTable(data, min, max) {
    $("#attestation-data-table thead > th").remove();
    $("#attestation-data-table tr > td").remove();

    for (cpt = min; cpt <= max; cpt++) {
        $ ("#attestation-data-table thead").append("<th>"+cpt+"</th>");

        if (data.hasOwnProperty(cpt)) {
            $ ("#attestation-data-table tr").append("<td><br><b>"+data[cpt]+"</b><br><br><?= $str["shootsession(s)"] ?></td>");
        } else {
            $ ("#attestation-data-table tr").append("<td><br><b>aucune</b> <br><br> <?= $str["shootsession(s)"] ?></td>");
        }

    }

}

</script>
