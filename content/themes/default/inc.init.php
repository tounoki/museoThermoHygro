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
/*
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head profile="http://gmpg.org/xfn/11">
*/
?>	
	
<!DOCTYPE html>
<html lang="fr-FR">
<head>
	<meta charset="UTF-8" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>MuseoThermoHygro</title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<link rel="shortcut icon" href="<?php echo HTTP_BASE ?>/content/themes/default/images/favicon.ico" type="image/x-icon">
	<link href="<?php echo HTTP_BASE ?>/content/themes/default/css/bootstrap.css" rel="stylesheet" />
	<!--link href="http://www.museomix.org/wp-content/themes/museomix-design-2/biblio/sst-style.css" rel="stylesheet" /-->
	<link href="<?php echo HTTP_BASE ?>/content/themes/default/css/bootstrap-responsive.css" rel="stylesheet" />
	<link href="<?php echo HTTP_BASE ?>/content/themes/default/css/style_museomix.css" rel="stylesheet" /> 
	<link href="<?php echo HTTP_BASE ?>/content/themes/default/css/style-annexe.css" rel="stylesheet" /> 


	<link href="<?php echo HTTP_BASE ?>/content/themes/default/style.css" rel="stylesheet" />
	<link href="<?php echo HTTP_BASE ?>/content/themes/default/js/assets/css/tablecloth.css" rel="stylesheet" /> </link>	
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://www.museomix.org/wp-content/themes/museomix-design-2/biblio/html5shiv.js"></script>
    <![endif]-->
	<script src="<?php echo HTTP_BASE ?>/content/themes/default/js/jquery.min.js"></script>
	<script type="text/javascript">
		$ServUrl = '<? echo HTTP_BASE ?>';
	</script>
	<script src="<?php echo HTTP_BASE ?>/content/themes/default/js/scripts.js"></script>
	
	<script src="<?php echo HTTP_BASE ?>/content/themes/default/js/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo HTTP_BASE ?>/content/themes/default/js/jquery-ui-1.9.2.custom/css/ui-lightness/jquery-ui-1.9.2.custom.min.css">
	
	<script src="<?php echo HTTP_BASE ?>/content/themes/default/js/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="<?php echo HTTP_BASE ?>/content/themes/default/js/flot/jquery.flot.time.js" type="text/javascript"></script>
	<script src="<?php echo HTTP_BASE ?>/content/themes/default/js/flot/jquery.flot.resize.js" type="text/javascript"></script>
	<script src="<?php echo HTTP_BASE ?>/content/themes/default/js/assets/js/jquery.tablecloth.js" type="text/javascript"></script>
	<script src="<?php echo HTTP_BASE ?>/content/themes/default/js/assets/js/jquery.tablesorter.min.js" type="text/javascript"></script>
	
	<!--[if IE]>
	<script type="text/javascript" src="<?php echo HTTP_BASE ?>/content/themes/default/js/flot/excanvas.js"></script>
	<![endif]--> 
	
<meta name='robots' content='noindex,nofollow' />
<!-- <script type='text/javascript' src='http://www.museomix.org/wp-includes/js/jquery/jquery.js?ver=1.8.3'></script>
-->
<script src="<?php echo HTTP_BASE ?>/content/themes/default/js/bootstrap.js"></script>

<meta name="generator" content="Bluefish 2.2.2" />

</head>

<body style="padding-top: 40px; background: #eee;" data-spy="scroll" data-target=".sidebar-nav">

	<!--div style="">

		<h1 class="bloc-titre">
		
			<a href="http://www.museomix.org" class="bouton-titre">Museomix</a>
			
		</h1>

	</div-->

	
<div class="navbar navbar-inverse navbar-fixed-top" style="">
  <div class="navbar-inner" style="background: #FFEC00; border-color: #d4d4d4;">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
    <div class="container nav">

		<!--<li class="" style="">-->

<a class="bouton-nav bouton-nav-accueil brand" href="#">
<img src="content/themes/default/images/museothermohygro.png" class="logoHeader"/></a>


    <div class="nav-collapse collapse">
	<ul class="nav">
	<li class=""><a href="<?php echo HTTP_BASE ?>" class="bouton-nav"><?  echo _('Accueil') ?></a></li>
	<li class=""><a href="page-deviceadd" class="bouton-nav"><?  echo _('Créer un enregistreur') ?></a></li>
	<li class=""><a href="page-outputform" class="bouton-nav"><?  echo _('Visualiser et exporter') ?></a></li>
	<li class=""><a href="page-mentions" class="bouton-nav"><?  echo _('Crédits') ?></a></li>
	<!-- <li class=""><a href="#" class="bouton-nav"><?  echo _('Page B') ?></a></li> -->
	
	<!--
	<? if ( $user->getRight(1) ) { ?>
	<li class=""><a href="page-myaccount" class="bouton-nav">My page</a></li>
	<li class=""><a href="page-logout" class="bouton-nav">Logout</a></li>
	<? } else { ?>
	<li class=""><a href="page-inscription" class="bouton-nav">I add me</a></li>
	<li class=""><a href="page-login" class="bouton-nav">Login</a></li>
	<? } ?>
	-->
	
    </ul>
	</div>
	</div>
  </div>
</div>

  <div class="container" style="margin: 0; width: auto;">
	
    <div class="row">

<div class="span2 hidden-phone hidden-tablet sidebar-nav" style="float: left; min-height: 1px;">
</div>

<div class="bloc-page span9">

	<div class="contenu-page">
	
			
		<div class="bloc-contenu" style="">
		
