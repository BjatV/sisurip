<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require 'libs/phplot/phplot.php';

$data = $this->data;

$plot = new PHPlot(400,250);
$plot->SetImageBorderType('plain');
$plot->SetPlotType('thinbarline');

$plot->SetDataType('text-data');
$plot->SetDataValues($data);
# Main plot title:
$plot->SetTitle('ASAL SURAT');
$plot->SetBackgroundColor('#eeeeff');
$plot->SetDataColors(array('blue'));
# Make a legend for the 3 data sets plotted:
# Turn off X tick labels and ticks because they don't apply here:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->DrawGraph();
?>
