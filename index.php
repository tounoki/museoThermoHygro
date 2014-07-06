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

// fichier de configuration
require('./config.php') ;

include('./includes/class.UPDO.php') ;
include('./includes/inc.functions.php') ;
include('./includes/class.database.php') ;
include('./includes/class.user.php') ;

session_start() ;
if ( !isset($_SESSION['login']) ) $_SESSION['login'] = false ;
if ( $_SESSION['login'] === true && !empty($_SESSION['user_ID']) ) {
	$user = new user($_SESSION['user_ID']) ;
	$user->loadDataFromID() ;
}
else $user = new user() ;

// i18n
//setlocale(LC_ALL, LANG.'.UTF-8' ) ;
date_default_timezone_set( TIMEZONE );
setlocale(LC_ALL, array( LANG.'.UTF-8', LANG.'@euro', LANG, EXPLICIT_LANG ));
setlocale(LC_NUMERIC,'en_US.utf8') ; // for use . in float instead of ,
// not really fine. Must just change decimal_point and not the next separator
bindtextdomain("front", "./locale") ; // path for translations
textdomain("front") ;

ob_start() ; // buffer for output, flush in themes/default/inc.sidebar.php

include('./content/themes/'.THEME.'/inc.init.php') ;

$page = ( !empty($_GET['page']) ) ? dataClean($_GET['page'],'ALnum') : NULL ;
// sécuriser caractères exotiques dans GET
if ( !empty($page) && file_exists('includes/page.'.$page.'.php') ) {
	if ( file_exists('./content/themes/'.THEME.'/page.'.$page.'.php') )
		require('./content/themes/'.THEME.'/page.'.$page.'.php') ; // if present in theme, get theme's file
	else
		require('includes/page.'.$page.'.php') ; // else load normal file
}
else
	require('includes/page.index.php') ;

include('./content/themes/'.THEME.'/inc.sidebar.php') ;

?>