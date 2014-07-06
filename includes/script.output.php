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

// type : csv/xml/json
$type 		= ( !empty($_GET['type']) ) ? dataClean($_GET['type'],"alnum") : 'csv' ;

$dateStart	= ( !empty($_GET['dateStart']) ) ? new DateTime($_GET['dateStart']) : new DateTime( date('Y-m-d',time() - ( 60 * 60 * 24 * 60 ) ) ) ;
$dateStop	= ( !empty($_GET['dateStop']) ) ? new DateTime( $_GET['dateStop'] ) : new DateTime( date('Y-m-d',time() + ( 60*60*24 ) ) ) ;

// TO DO
// if ( $dateStart > $dateStop )

$d = new device( $id ) ;
$d->loadDataFromID() ;

// chart 1
$measures = $d->getMeasures($dateStart->format('Y-m-d'),$dateStop->format('Y-m-d')) ;

switch( $type ) {
	case 'csv':
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename='._('releve').'-'.time().".csv" );
		$fp = fopen('php://output', 'w');
		foreach( $measures as $m ) {
			fputcsv($fp, array( $m->getData('dateAndTime') , $m->getData('temperature') , $m->getData('hygrometry')) );
			}
		fclose($fp) ;
		break ;
	case 'xml':
		header( 'Content-Type: text/xml' );
		header( 'Content-Disposition: attachment;filename='._('releve').'-'.time().".xml" );
		echo '<?xml version="1.0" encoding="utf-8"?>'."\n" ;
		echo "<device>\n" ;
		echo "\t<id>".$d->getID()."</id>\n" ;
		echo "\t<name>".$d->getData('device_name')."</name>\n" ;
		echo "\t<description>".$d->getData('device_description')."</description>\n" ;
		echo "\t<dateStart>".$dateStart->format('Y-m-d')."</dateStart>\n" ;
		echo "\t<dateStop>".$dateStop->format('Y-m-d')."</dateStop>\n" ;
		// records
		echo "\t<records>"."\n" ;
		foreach( $measures as $m ) {
			echo "\t\t<record>\n" ;
			echo "\t\t\t<data name=\"dateAndTime\">".$m->getData('dateAndTime')."</data>\n" ;
			echo "\t\t\t<data name=\"temperature\">".$m->getData('temperature')."</data>\n" ;
			echo "\t\t\t<data name=\"hygrometry\">".$m->getData('hygrometry')."</data>\n" ;
			echo "\t\t</record>\n" ;
			}
		echo "\t</records>"."\n" ;
		echo "</device>\n" ;
		break ;
	case 'json':
		header( 'Content-Type: application/json' );
		header( 'Content-Disposition: attachment;filename='._('releve').'-'.time().".js" );
		foreach( $measures as $m ) {
			$out[] = array( 
							'dateAndTime' => $m->getData('dateAndTime') ,
							'temperature' => floatval ( $m->getData('temperature') ) ,
							'hygrometry' => floatval ( $m->getData('hygrometry') ) 
						) ;
			}
		echo json_encode($out);
		break ;
	default:
		// nothing happened
		echo '' ;
	}

?>