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

// test accès droit utilisateur

?>

<h2><?php echo ("Visualiser")?></h2>

	<form name="deviceForm" method="GET" action="page-device">
		<fieldset>
		<legend><?php echo _('Paramètres') ;?></legend>
		
		<label for="ID"><?php echo _("Nom de l'enregistreur")?></label>
		<select name="ID">
			<option value="1">1-Name</option>
			<option value="2">2-Name</option>			
		</select>		
			
		<input name="device_name" type="text" size="30" value="<?php echo $d->getData('device_name') ?>"/>
		<br /><br />
		
		<label for="device_description"><?php echo _('Commentaire/description')?></label>
		<textarea name="device_description" ><?php 
		if ( $d->getData('device_description') ) 
			echo $d->getData('device_description') ;
		//else 
			//echo _('car/pour/parce que/because/to...') ;?></textarea>
		<br /><br />
		
		<input type="submit" name="send" value="<?php echo _('Enregistrer')?>" />
		</fieldset>
	</form>

<h2><?php echo ("Exporter")?></h2>

	// error messages
	if ( $valid == false ) {
		echo '<p class="error">'.implode('<br/>',$error).'</p>' ;
	}
	// print the form
	$d->loadDataFromID() ;
	?>
	<form name="deviceForm" method="POST" action="page-deviceadd">
		<fieldset>
		<legend><?php echo _('Enregistreur')." : ".$d->getData('device_name');?></legend>
		
		<label for="device_name"><?php echo _("Nom de l'enregistreur")?></label>
		<input name="device_name" type="text" size="30" value="<?php echo $d->getData('device_name') ?>"/>
		<br /><br />
		
		<label for="device_description"><?php echo _('Commentaire/description')?></label>
		<textarea name="device_description" ><?php 
		if ( $d->getData('device_description') ) 
			echo $d->getData('device_description') ;
		//else 
			//echo _('car/pour/parce que/because/to...') ;?></textarea>
		<br /><br />
		
		<input type="submit" name="send" value="<?php echo _('Enregistrer')?>" />
		</fieldset>
	</form>


<h2><?php echo ("Ajout d'un enregistreur")?></h2>

<?php

$d = new device() ;

// tests values
$valid = true ;
if ( !empty($_POST['send']) ) {

	$d->setData($_POST) ;
	/*
	if (!preg_match('/^[A-Z0-9._-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z.]{2,6}$/i',$d_temp->getData('user_email') ) ) {
		$error[] = _('Adresse de courriel invalide') ;
		$valid = false ;
	}
	if ( empty($_POST['user_email']) ) {
		$error[] = _('') ;
		$valid = false ;
	}
	if ( preg_match('/^[0-9]{4}$/',$_POST['']) == 0 ) {
		$error[] = _('') ;
		$valid = false ;
	}*/
}

if ( !empty($_POST['send']) && $valid === true ) {
	// save the data form
	if ( $d->save() ) {
		echo '<p class="success">'._('Vos modifications ont bien été enregistrées.').'</p>' ;
		echo "<p>L'ID de votre enregistreur est : ".$d->getID()."</p>" ;
	}
	else {
		echo '<p class="error">'._('Echec.').'</p>' ;
	}
}
else {
	// error messages
	if ( $valid == false ) {
		echo '<p class="error">'.implode('<br/>',$error).'</p>' ;
	}
	// print the form
	$d->loadDataFromID() ;
	?>
	<form name="deviceForm" method="POST" action="page-deviceadd">
		<fieldset>
		<legend><?php echo _('Enregistreur')." : ".$d->getData('device_name');?></legend>
		
		<label for="device_name"><?php echo _("Nom de l'enregistreur")?></label>
		<input name="device_name" type="text" size="30" value="<?php echo $d->getData('device_name') ?>"/>
		<br /><br />
		
		<label for="device_description"><?php echo _('Commentaire/description')?></label>
		<textarea name="device_description" ><?php 
		if ( $d->getData('device_description') ) 
			echo $d->getData('device_description') ;
		//else 
			//echo _('car/pour/parce que/because/to...') ;?></textarea>
		<br /><br />
		
		<input type="submit" name="send" value="<?php echo _('Enregistrer')?>" />
		</fieldset>
	</form>
	<?php
}


?>