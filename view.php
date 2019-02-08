<?php
include'../_inc/header.inc.php';
if (empty($adminLID)){
	header('Location: ../login/?er=333');
	die();
}
else {
	$monthData = $TapHandler->graph30days();
}
?>
<!DOCTYPE HTML>
<html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script type="text/javascript" src="common/js/form_init.js" data-name="" id="form_init_script"></script>
    <link rel="stylesheet" type="text/css" href="theme/default/css/default.css" id="theme" />
    <link rel="stylesheet" type="text/css" href="../stylesAdmin.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Day');
      data.addColumn('number', 'Tap-|n');
      data.addRows([
	  <?php print $monthData ?>
      ]);

      // Set chart options
      var options = {'title':'Tap-|n 30-Day Snapshot'};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart_month'));
      chart.draw(data, options);
    }
	</script>
    <title>
      Gladius :: Manually Tap-|n Student
    </title>
  </head>

  <body>
<style>
#docContainer .fb_cond_applied{
	display:none;
}
</style>
<noscript>
<style>
#docContainer .fb_cond_applied{
	display:inline-block;
}
</style>
</noscript>
<form class="fb-toplabel fb-100-item-column selected-object" id="docContainer" action="../frmTapIn.php" enctype="multipart/form-data" method="POST" novalidate="novalidate" data-form="manual_iframe" data-colorbox="true">
  <div class="fb-form-header fb-item-alignment-center" id="fb-form-header1"
  style="max-height: 123px; height: 47px;">
   <img title="Gladius Membership Management" style="display: inline;" alt="Gladius Strategy Management" src="../images/logoGladius_small.png"/>
  </div>
  <div class="section" id="section1">
    <div class="column ui-sortable" id="column1">
      <div class="fb-item" id="item2" style="max-width: 70px; opacity: 1;float:right">

      </div>
      <div class="fb-item fb-75-item-column" id="item1">
        <div class="fb-header">
          <h2 style="display: inline;">
            Tap-|n&trade; 30-Day Summary
          </h2>
        </div>
      </div>
      <div class="fb-item" id="item3">
          <hr style="max-width: 960px;">
          <img title="" id="item2_img_0" style="float:Right;" alt="" src="../images/logoTap-In_titleicon.png"/>
      </div>
      <div id="chart_month" style="max-width: 100%; height: auto"></div>
    </div>
  </div>
  <div class="fb-footer fb-item-alignment-right" id="fb-submit-button-div"></div>
</form>
<!-- Default Statcounter code for Gladius
https://gladius.proeli.us -->
<script type="text/javascript">
var sc_project=11822907;
var sc_invisible=1;
var sc_security="4c698cfc";
</script>
<script type="text/javascript"
src="https://www.statcounter.com/counter/counter.js"
async></script>
<noscript><div class="statcounter"><a title="Web Analytics
Made Easy - StatCounter" href="http://statcounter.com/"
target="_blank"><img class="statcounter"
src="//c.statcounter.com/11822907/0/4c698cfc/1/" alt="Web
Analytics Made Easy - StatCounter"></a></div></noscript>
<!-- End of Statcounter Code -->
</body>
</html>