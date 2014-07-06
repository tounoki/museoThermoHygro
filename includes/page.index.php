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
	die(_('Please go out :(')) ;
?>
<h2><?php echo _('Liste des appareils') ?></h2>
<?php

$tab = getAllDevices() ;

echo "<table> <thead><tr> <td>".
			_('Code')."</td> <td>".
			_('Description')."</td> <td>".
			_('Premier jour'). "</td> <td>".
			_('Dernier jour'). "</td> <td>".
			_('Nombre de points'). "</td> <td>".
			_('Lien') . "</td><td>".
			_('Modifier') . "</td><td>".
			_('Reset')."</td>".
			"</tr></thead>
			<tbody>" ;
foreach ( $tab as $device ) {
	$device->printData('htmlTable') ;
	}

echo "</tbody></table>" ;

?>
<script>
$("table").tablecloth({
	theme: "default",
	bordered: true,
	condensed: true,
	striped: true,
	sortable: true,
	clean: true,
	cleanElements: "th td",
	customClass: "my-table"
});
</script>