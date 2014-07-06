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

$devices = getAllDevices() ;
$selectDevice = NULL ;
foreach ( $devices as $device ) {
	$selectDevice .= "<option value={$device->getID()}>{$device->getID()} - {$device->getData('device_name')}</option>\n" ;
	}
?>

<h2><?php echo ("Visualiser et exporter")?></h2>
<h3><?php echo ("Visualiser")?></h3>

	<form name="visuForm" method="GET" action="page-device">
		<fieldset>
		<legend><?php echo _('Paramètres de visualisation') ;?></legend>
		
		<label for="ID"><?php echo _("Enregistreur")?></label>
		<select name="ID">
			<?php echo $selectDevice ; ?>			
		</select>
		<br />
		<label for="dateStart">Date inférieure</label>
		<input class="formDate" name="dateStart" type="text" size="30" value=""/>
		<br />
		<label for="dateStop">Date supérieure</label>
		<input class="formDate" name="dateStop" type="text" size="30" value=""/>
		
		<br />
		<label for="type"><?php echo _("Type de visualisation")?></label>
		<select name="type">
			<option value="table">Tableau</option>
			<option selected value="analyse">Analyse complète</option>			
		</select>	
		<br/>
		<input type="submit" name="send" value="<?php echo _('Valider')?>" />
		</fieldset>
	</form>

<h3><?php echo ("Exporter")?></h3>

	<form name="exportForm" method="GET" action="script-output">
		<fieldset>
		<legend><?php echo _("Paramètres d'export") ;?></legend>
		
		<label for="ID"><?php echo _("Enregistreur")?></label>
		<select name="ID">
			<?php echo $selectDevice ; ?>
		</select>
		<br />
		<label for="dateStart">Date inférieure</label>
		<input class="formDate" name="dateStart" type="text" size="30" value=""/>
		<br />
		<label for="dateStop">Date supérieure</label>
		<input class="formDate" name="dateStop" type="text" size="30" value=""/>
		<br />
		<label for="type"><?php echo _("Format d'export")?></label>
		<select name="type">
			<option selected value="csv">CSV</option>
			<option value="xml">XML</option>
			<option value="json">json</option>
		</select>	
		<br/>
		<input type="submit" name="send" value="<?php echo _('Valider')?>" />
		</fieldset>
	</form>

<script type="text/javascript" >
$(function() {
	$.datepicker.setDefaults( $.datepicker.regional[ "<?php echo SHORTLANG ?>" ] );
	$( ".formDate" ).datepicker({
			firstDay: 1,
   		dateFormat: 'yy-mm-dd'
			});
});
</script>

