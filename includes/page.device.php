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
if ( !empty($_GET['ID']) ) $ID = $_GET['ID'] ;
$id = dataClean($ID,"int") ;

// type : analyse/table
$type 		= ( !empty($_GET['type']) ) ? dataClean($_GET['type'],"alnum") : 'analyse' ;

$dateStart	= ( !empty($_GET['dateStart']) ) ? new DateTime($_GET['dateStart']) : new DateTime( date('Y-m-d',time() - ( 60 * 60 * 24 * 60 ) ) ) ;
$dateStop	= ( !empty($_GET['dateStop']) ) ? new DateTime( $_GET['dateStop'] ) : new DateTime( date('Y-m-d',time() + ( 60 * 60 * 25 ) ) ) ;

// TO DO
// if ( $dateStart > $dateStop )



$d = new device( $id ) ;
$d->loadDataFromID() ;

// for chart 1 or table
$measures = $d->getMeasures($dateStart->format('Y-m-d'),$dateStop->format('Y-m-d')) ;

switch( $type ) {
	case "analyse" :
		$extreme = $d->getExtremeMeasures($dateStart->format('Y-m-d'),$dateStop->format('Y-m-d')) ;
		// chart 2
		$delta = $d->getDeltaPerDay($dateStart->format('Y-m-d'),$dateStop->format('Y-m-d')) ;
		
		foreach ( $delta as $statOfDay ) {
			$deltaT[] = $statOfDay['deltaT'] ;
			$deltaH[] = $statOfDay['deltaH'] ;
			}
		
		?>
		<h3><?php echo _('Infos') ?></h3>
		<?php
		echo "<p><strong>".$d->getData('device_name')."</strong> (ID: ".$d->getID().")<br/>".$d->getData('device_description')."</p>" ;
		printf('<p>Ensemble des mesures comprises entre le %s et le %s.</p>',$dateStart->format('l d F Y'),$dateStop->format('l d F Y')) ;
		?>
		<h3><?php echo _('Mesures au fil du temps') ?></h3>
		<div id="plotareaA"></div>
		
		<h3><?php echo _('Extrêmes et moyennes') ?></h3>
		<?php
		echo "<table class='beautifulTab' >" ;
		echo "<thead><tr><td>"._('Champs')."</td><td>"._('minimum')."</td><td>"._('maximum')."</td><td>"._('moyenne')."</td></tr></thead>" ;
		echo "<tr><td>"._('Température')."</td><td>".$extreme['minT']."</td><td>".$extreme['maxT']."</td><td>".$extreme['moyT']."</td></tr>" ;
		echo "<tr><td>"._('Hygrométrie')."</td><td>".$extreme['minH']."</td><td>".$extreme['maxH']."</td><td>".$extreme['moyH']."</td></tr>" ;
		echo "</table>" ;
		?>
		
		<h3><?php echo _('Ecarts journalier') ?></h3>
		<div id="plotareaB"></div>
		
		<h3><?php echo _('Statistiques sur les écarts journaliers') ?></h3>
		<?php
		echo "<table class='beautifulTab' >" ;
		echo "<thead><tr><td>"._('Champs')."</td><td>"._('écart journalier minimum')."</td><td>"._('écart journalier maximum')."</td><td>"._('moyenne des écarts journaliers')."</td></tr></thead>" ;
		echo "<tr><td>"._('Température')."</td><td>".min( $deltaT )."</td><td>".max( $deltaT )."</td><td>". round( array_sum( $deltaT )/count( $deltaT ) , 2 )."</td></tr>" ;
		echo "<tr><td>"._('Hygrométrie')."</td><td>".min( $deltaH )."</td><td>".max( $deltaH )."</td><td>". round( array_sum( $deltaH )/count( $deltaH ) , 2 )."</td></tr>" ;
		echo "</table>" ;
		?>
		
		<h3><?php echo _('Répartition des couples de mesures') ?></h3>
		<div id="plotareaC"></div>
		
		<?php
		$p = $d->goodMeasures($dateStart->format('Y-m-d'),$dateStop->format('Y-m-d'),18,22,50,60) ;
		if ( $p < 60 )
			$only = _('seulement') ;
		else
			$only = NULL ; 
		printf ("Il y a <u>%s</u> <strong>%.1f%%</strong> des points qui sont dans la zone idéale.", $only , $p );
		?>
		
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
					label: "<?php  echo _('température') ?>",
					data: [
						<?php
						foreach ( $measures as $m ) {
							$date = new DateTime( $m->getData('dateAndTime') );
							$dt = $date->format('U')*1000 ;
							echo "[".$dt.",".$m->getData('temperature')."],\n" ;
						}
						?>
					]
				},
				{
				label: "<?php  echo _('hygrométrie') ?>",
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
					label: "<?php  echo _('température') ?>",
					data:  [
						<?php
						foreach ( $delta as $day => $statOfDay ) {
							$date = new DateTime( $day );
							$dayJS = $date->format('U')*1000 ;
							echo "[".$dayJS.",".$statOfDay['deltaT']."],\n" ;
						}
						?>
					]
				},
				{ // data for %HR
					label: "<?php  echo _('hygrométrie') ?>",
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
					label: "<?php  echo _('répartition des mesures') ?>",
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
			
			ctx.lineTo(r.left, r.top ) ;
			ctx.fillStyle = 'rgba(77,167,77,0.3)' ;
			ctx.fill() ;
			
			});  // end of plot js
		</script>
<?php
		break ;
	case 'table' :
		echo "<table class='beautifulTab' >" ;
		echo "<thead><tr><td>"._('Date et heure')."</td><td>"._('Température')."</td><td>"._('Hygrométrie')."</td></tr></thead>" ;
		foreach ( $measures as $measure ) {
			$measure->printData("htmlTable") ;			
			}		
		echo "</table>" ;
		break ;
	default:
		// nothing happened
		echo "" ;
	}
?>
<script type="text/javascript" >
$(function() {
	// make tab beautiful
	$("table.beautifulTab").tablecloth({
		theme: "default",
		bordered: true,
		condensed: true,
		striped: true,
		sortable: true,
		clean: true,
		cleanElements: "th td",
		customClass: "my-table"
		});	
});  // end of plot js
</script>