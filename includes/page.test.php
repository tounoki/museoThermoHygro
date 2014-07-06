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

// cette page permet de simuler l'enregistrement de données

// id du th à simuler
$id = 1 ;
// sauvegarder les données
$s = false ; // true or false

// ########################################################################

$data[0] = new measure() ;

$data[0]->setData( array('dateAndTime'=>'2014-06-30 10:30:00','temperature'=>17.2,'hygrometry'=>44.6,'device_id'=>1 ) ) ;
$data[0]->printData() ;

$t = 18 ;
$h = 50 ;

for ($i = 1 ; $i <= 1200 ; $i++ ) {
	$time = new DateTime( $data[$i-1]->getData('dateAndTime') );
	$time->modify('+900 sec') ;
	//echo $date->format('Y-m-d H:i:s');
	
	$data[$i] = new measure() ;
	$t = floatval( $t + rand(-2,2)/10 ) ;
	$h = floatval( $h + rand(-5,5)/10 ) ;
	$data[$i]->setData( array(
							'dateAndTime'=>$time->format('Y-m-d H:i:s') ,
							'temperature'=>$t,
							'hygrometry'=>$h,
							'device_id'=>$id ) 
						) ;
	$data[$i]->printData() ;
	if ( $s )
		$data[$i]->save() ;
	echo "<hr/>" ;
	}


?>