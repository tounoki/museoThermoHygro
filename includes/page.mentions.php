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

echo '<h2>'._('Mentions légales').'</h2>' ;

echo '<h3>'._('Mentions techniques').'</h3>' ;
echo '<br/>'._('La mise en oeuvre technique est assuré par').' '.WEBMASTER.'</p>' ;

echo '<h3>'._('Droits d\'auteurs').'</h3>' ;

echo '<p>'._('Une appli réalisée par Nicolas Poulain, http://tounoki.org').'</p>' ;

echo '<p>'._('Ce site utilise les éléments suivants et nous en remercions ici grandement leurs auteurs :').'</p><ul>' ;
echo '<li>'._('Le set d\'icônes FamFam http://www.famfamfam.com/lab/icons/silk').'</li>' ;
echo '<li>'._('La bibliothèque javascript JQuery et ses dérivés (Licence MIT)').'</li>' ;
echo '<li>'._('Le CSS design de Museomix - museomix.org').'</li>' ;
echo '<li>'._('Jquery Flot pour les tableaux').'</li>' ;
echo '<li>'._('Le tout bien codé en php/mysql et moins bien en javascript').'</li>' ;
echo '</ul>'
?>