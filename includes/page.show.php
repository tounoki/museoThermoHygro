<?php
/*****************************************************************************************
** © 2011 POULAIN Nicolas – nicolas.poulain@ouvaton.org **
** **
** Ce fichier est une partie du logiciel libre MuseoThermoHygro, licencié **
** sous licence "CeCILL version 2". **
** La licence est décrite plus précisément dans le fichier : LICENSE.txt **
** **
** ATTENTION, CETTE LICENCE EST GRATUITE ET LE LOGICIEL EST **
** DISTRIBUÉ SANS GARANTIE D'AUCUNE SORTE **
** ** ** ** **
** This file is a part of the free software project MuseoThermoHygro,
** licensed under the "CeCILL version 2". **
**The license is discribed more precisely in LICENSES.txt **
** **
**NOTICE : THIS LICENSE IS FREE OF CHARGE AND THE SOFTWARE IS DISTRIBUTED WITHOUT ANY **
** WARRANTIES OF ANY KIND **
*****************************************************************************************/
if (stristr($_SERVER['REQUEST_URI'], "page."))
	die(_('Vous vous engagez sur une voie risquée et votre IP est enregistrée :(')) ;

//echo '<p>'.$_SERVER['REQUEST_URI'].'</p>' ;
//print_r($_REQUEST) ;

$d = new device(1) ;
$d->loadDataFromID() ;

?>
<h3><?php echo _('Temperature and hygrometry by time') ?></h3>
<div id="plotareaA"></div>
<h3><?php echo _('Delta temperature and hygrometry per day') ?></h3>
<div id="plotareaB"></div>
<h3><?php echo _('Repartition of couple of measures') ?></h3>
<div id="plotareaC"></div>

<script language="javascript" type="text/javascript">
$(function() {

	function celsiusFormatter(v, axis) {
			return v.toFixed(axis.tickDecimals) + "°C";
		}
	function hygroFormatter(v, axis) {
			return v.toFixed(axis.tickDecimals) + "%HR";
		}	
	
	
	var data = [
		{
			label: "<?php  echo _('temperature') ?>",
			data: [
				<?php
				$measures = $d->getMeasures('2014-01-01','2014-12-31') ;
				foreach ( $measures as $m ) {
					$date = new DateTime( $m->getData('dateAndTime') );
					$dt = $date->format('U')*1000 ;
					echo "[".$dt.",".$m->getData('temperature')."],\n" ;
				}
				?>
			]
		},
		{
		label: "<?php  echo _('hygrometry') ?>",
		data: [ <?php
		foreach ( $measures as $m ) {
			$date = new DateTime( $m->getData('dateAndTime') );
			$dt = $date->format('U')*1000 ;
			echo "[".$dt.",".$m->getData('hygrometry')."],\n" ;
			}
		?> ],
		yaxis: 2
		}
	];
 	
	var plotarea = $("#plotareaA");
	plotarea.css("height", "250px");
	plotarea.css("width", "95%");
	$.plot( plotarea , data , {
		xaxis: { mode: "time" },
		yaxes: [
			{
				min: 5, 
				max: 30, 
				tickFormatter: celsiusFormatter 
				},
			{
				position: 'right', 
				min: 35, 
				max: 65, 
				tickFormatter: hygroFormatter
				} 
			],
		});
	
	
	// max delta temp and hygrometry per day
	var dataB = [
		{ // data for T°
			label: "<?php  echo _('delta temperature per day') ?>",
			data:  [
				<?php
				$delta = $d->getDeltaPerDay('2014-01-01','2014-12-31') ;
				foreach ( $delta as $day => $statOfDay ) {
					$date = new DateTime( $day );
					$dayJS = $date->format('U')*1000 ;
					echo "[".$dayJS.",".$statOfDay['deltaT']."],\n" ;
				}
				?>
			]
		},
		{ // data for %HR
			label: "<?php  echo _('delta hygrometry per day') ?>",
			yaxis: 2,
			data:  [
				<?php
				foreach ( $delta as $day => $statOfDay ) {
					$date = new DateTime( $day );
					$dayJS = $date->format('U')*1000 ;
					echo "[".$dayJS.",".$statOfDay['deltaH']."],\n" ;
				}
				?>
			]
		}		
		] ;
		
	var plotareaB = $("#plotareaB");
	plotareaB.css("height", "250px");
	plotareaB.css("width", "95%");
	$.plot( plotareaB , dataB , {
		xaxis: { mode: "time" },
		yaxes: [
			{
				min: 0, 
				max: 20, 
				tickFormatter: celsiusFormatter 
				},
			{ 
				position: 'right', 
				min: 0, 
				max: 20, 
				tickFormatter: hygroFormatter} 
			],
		legend: {
			show: true,
			margin: 10,
			backgroundOpacity: 0.5
			},
		points: {
			show: true,
			radius: 3
			},
		lines: {
			show: true
			}
		}) ; // end of plotareaB
		
		
	// couple of measures - third chart
	var dataC = [
		{ // data for T°
			label: "<?php  echo _('repartition of measures') ?>",
			data:  [
				<?php
				foreach ( $measures as $m ) {
					echo "[".$m->getData('temperature').",".$m->getData('hygrometry')."],\n" ;
				}
				?>
			]
		} ] ;
		
	var plotareaC = $("#plotareaC");
	plotareaC.css("height", "250px");
	plotareaC.css("width", "90%");
	var plotC = $.plot( plotareaC , dataC , {
		xaxis: { min: 5, max: 30, tickFormatter: celsiusFormatter },
		yaxis: { min: 20, max: 80, tickFormatter: hygroFormatter },
		legend: {
			show: true,
			margin: 10,
			backgroundOpacity: 0.5
			},
		points: {
			show: true,
			radius: .5
			},
		lines: {
			show: false
			}
		}) ; // end of plotareaB
		
	// draw a rectangle for ideal zone
	
	var ctx = plotC.getCanvas().getContext("2d");
	ctx.beginPath();
	var o = plotC.pointOffset({ x: 18, y: 50}) ;
	ctx.moveTo(o.left, o.top);
	var p = plotC.pointOffset({ x: 22, y: 50}) ;
	ctx.lineTo(p.left, p.top );
	var q = plotC.pointOffset({ x: 22, y: 60}) ;
	ctx.lineTo(q.left, q.top );
	var r = plotC.pointOffset({ x: 18, y: 60}) ;
	plotareaC.append("<div style='position:absolute;left:" + (r.left ) + "px;top:" + (r.top - 20) + "px;color:#666;font-size:smaller'><?php echo _('ideal zone')?></div>");
	
	ctx.lineTo(r.left, r.top );
	ctx.fillStyle = 'rgba(77,167,77,0.3)' ;
	ctx.fill();
		
	});  // end of plot js
</script>
       
<?php


?>