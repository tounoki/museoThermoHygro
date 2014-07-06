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
/**
 * Configuration de base de votre application
 *
 * @package MuseoThermoHygro
 */

define ('HTTP_BASE','http://127.0.0.1/thermo') ; // without end /

define('VERSION', '0.1-RC3');

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Le nom de la base de données */
define('DB_NAME', 'thermo');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'password');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', '127.0.0.1');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Le type de collabtion de la base de données.
  * N'y touchez qui si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

define('SITE_NAME','MuseoThermoHygro') ;
define('MAIL_ADMIN','moi@chezmoi.org') ;
define('WEBMASTER','Moi Egalement') ;
define('CNIL_NUMBER','123456') ;

/**
 * Préfixe de base de données pour les tables.
 *
 * Il faut changer le script d'installation si vous changez ces valeurs
 */
$table_prefix  = 'mm_'; // not used
define ('TABLE_PREFIX', 'mm_') ; // not used

define ('TABLE_MEASURES','measures') ;
define ('TABLE_DEVICES','devices') ;
define ('TABLE_USERS','users') ;

/**
 * Langue de localisation, par défaut en Français.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier /locale.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans /locale, et réglez l'option ci-dessous à "fr_FR".
 */
define ('LANG', 'fr_FR') ;
define ('EXPLICIT_LANG', 'french') ;
define ('SHORTLANG', 'fr') ;
define ('COUNTRY', 'France') ;
define ('TIMEZONE','Europe/Paris') ;

/** Chemin absolu */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Suite de la config */

define ('DEBUG',false) ;

define ('FORMAT_DATE','%A %e %B %Y') ;
define ('FORMAT_TIME','%H:%M') ;

// theme
define ('THEME','default') ; // adress of images : HTTP_BASE."/content/themes/".THEME."/images"


