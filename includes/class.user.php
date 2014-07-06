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
class user extends database {
	static protected $LIST_DATA = array('user_email','user_pass','display_name','user_url','user_registered','user_activation_key','user_status','user_twitteraccount','user_presentation','user_startpart','user_participation','user_image','user_image_template','user_lang') ;
	static protected $table = TABLE_USERS ;

	protected $participations ; // array of data - cache
	
	/*function printData($level,$before,$after) {

	}*/

	function check() {
		$login = $this->getData('user_email') ;
		$pass = $this->getData('user_pass') ;
		if ( empty($login) && empty($pass) ) return false ;
		// connexion
		$sql = "SELECT COUNT(*) AS nb FROM ".STATIC::$table." WHERE user_email LIKE '$login' AND user_pass LIKE '$pass'" ;
		//$count = UPDO::getInstance()->query( $sql ) ;
		$result = UPDO::getInstance()->query( $sql ) ;
		foreach ( $result as $ligne ) {
			$count = $ligne['nb'] ;
		}
		if ( $count == 1 ) {
			// faire chargement des données
			$sql = "SELECT * FROM ".STATIC::$table." WHERE user_email LIKE '$login' AND user_pass LIKE '$pass' LIMIT 1" ;
			$results = UPDO::getInstance()->query( $sql ) ;
			foreach ( $results as $result ) {
				$this->ID = $result['ID'] ;
				$this->setData($result) ;
			}
			$_SESSION['login'] = true ;
			$_SESSION['user_ID'] = $this->ID ;
			return true ;
		}
		elseif ( $count == 0 ) {
			$_SESSION['login'] = false ;
			return false ;
		}
		else {
			$_SESSION['login'] = false ;
			return false ;
		}
	}

	// retourne le bit correspondant au droit recherché
	// $right = (int) position
	// 1: true/be able to
	// 0: false/don't be able to
	/*
	1- active account
	2- can read information about account/user
	3- be able to modify configuration / admin level
	4- be able to modify directions/routes/searches
	5- be able to modify account and cars informations
	*/
	function getRight($right=0) {
		if ( substr($this->getData('user_status'),-$right,1) == 1 )
			return true ;
		else
			return false ;
	}

	function getName() {
		return $this->getData('display_name') ;
	}

	function getPermalink() {
		$permalink = HTTP_BASE.'/museomixer-'.$this->ID ;
		return $permalink ;
	}

	function printData($level=0,$before=NULL,$after=NULL) {
		$string = $before ;
		if ( $level == 0 ) { // global data / my account / private
			$string .= "<div class='leftphotobox'>".$this->getImage(300,"MUSEOMIX")."" ;
			$string .= "<p><strong>"._('Nom')." : ".$this->getName()."</strong></p></div>" ;
			$string .= "<p>"._('Présentation')." : ".$this->getData('user_presentation')."</p>" ;
			$string .= "<p>". $this->getData('user_startpart') ." ". nl2br($this->getData('user_participation')) ."</p>" ;
			$string .= "<p>"._('Site personnel')." : <a href=\"{$this->getData('user_url')}\" target=\"_blank\">".$this->getData('user_url')."</a></p>" ;
			$string .= "<p>"._('Compte twitter')." : <a href=\"http://twitter.com/{$this->getData('user_twitteraccount')}\" target=\"_blank\">".$this->getData('user_twitteraccount')."</a></p>" ;
			$string .= "<p>"._('Courriel')." : ".$this->getData('user_email')."</p>" ;
			$string .= "<p>"._('Public permalink')." : <a href='{$this->getPermalink()}'>{$this->getPermalink()}</a></p>" ;
			$string .= "<p>"._('Poster')." <a href='page-userposter?ID={$this->getID()}'>{Link and share}</a></p>" ;
		}
		if ( $level == 1 ) { // floating box
			$string .= '<div class="userbox">' ;
			$string .= "<a href=\"museomixer-".$this->getID()."\" >" ;
			$string .= $this->getImage(230,"pastille")."</a><br />" ;
			$string .= $this->getData('user_startpart')." ". nl2br($this->getData('user_participation'))."<br />" ;
			$string .= "<span style='float:right'><em>{$this->getName()}</em>&nbsp; &nbsp; &nbsp; &nbsp;<small><a href=\"museomixer-".$this->getID()."\" >"._("+ de détails")."</a></small></span>" ;
			$string .= '</div>' ;
		}
		if ( $level == 2 ) { // simple bausson template
			$string .= "<div style='width:100%;padding-top:50px;padding-bottom:50px;'>" ;
			$string .= "<p align='center'>" ;
			$string .= $this->getImage(640,"bausson2", "style='border:1px solid black'") ;
			$string .= "</p>" ;
			$string .= "</div>" ;
		}
		if ( $level == 3 ) { // list slider 
			$string .= "<li>" ;
			$string .= $this->getImage(640,"bausson2", "class=\"fade\"" ) ;
			$string .= "</li>" ;
		}
		if ( $level == 4 ) { // page user
			$string .= "<div class='leftphotobox'>".$this->getImage(300,"MUSEOMIX")."</div>" ;
			
			$string .= "<blockquote>".$this->getData('user_startpart')." ". nl2br($this->getData('user_participation'))."</blockquote>" ;
			
			$string .= "<p class='signature'>".$this->getName()."</p>" ;			
			
			if ( $this->getData('user_presentation') )
				$string .= "<p style='clear:both'>".$this->getData('user_presentation')."</p>" ;
			
			$string .= "<p>" ;
			if ( $this->getData('user_url') )
				$string .= _('website')." <a href=\"{$this->getData('user_url')}\" target=\"_blank\">".$this->getData('user_url')."</a><br/>" ;
			if ( $this->getData('user_twitteraccount') )			
				$string .= _('twitter')." <a href=\"http://twitter.com/{$this->getData('user_twitteraccount')}\" target=\"_blank\">".$this->getData('user_twitteraccount')."</a><br/>" ;
			$string .= _('permalink')." <a href='{$this->getPermalink()}'>{$this->getPermalink()}</a><br/>" ;
			$string .= _('poster')." <a href='page-userposter?ID={$this->getID()}'>{Link and share}</a></p>" ;
		}
		if ( $level == 5 ) { // floating box with random size - don't work - TODO
			$randWidth = rand(100,240) ;
			$string .= '<div class="userbox2" style="width:'.$randWidth.'px">' ;
			$string .= $this->getImage($randWidth-10,"pastille")."<br />" ;
			$string .= $this->getData('user_startpart')." ". nl2br($this->getData('user_participation'))."<br />" ;
			$string .= "<span style='float:right'><em>{$this->getName()}</em>&nbsp; &nbsp; &nbsp; &nbsp;<small><a href=\"museomixer-".$this->getID()."\" >"._("+ de détails")."</a></small></span>" ;
			$string .= '</div>' ;
		}
		if ( $level == 6 ) { // remote floating box
			$string .= '<div id="museomixer-'.$this->getID().'" class="remoteuserbox">' ;
			$string .= "<p style='text-align:center;margin-top:5px'><a target=\"_blank\" href=\"".HTTP_BASE."/museomixer-".$this->getID()."\" >" ;
			$string .= $this->getImage(230,"remote")."</a></p>" ;
			$buffer = $this->getData('user_startpart')." ". nl2br($this->getData('user_participation')) ;
			$string .= "<p style='margin:0 5px 0 5px'>".truncate($buffer,200)."<br/>" ;
			$string .= "<em><a target=\"_blank\" title=\"museomix profile of {$this->getName()}\" href=\"".HTTP_BASE."/museomixer-".$this->getID()."\" >{$this->getName()}</a></em>
							<br/>
							<small>
								&nbsp; &nbsp; &nbsp; &nbsp;
								<a href=\"".HTTP_BASE."/page-users\" target=\"_blank\" title=\"View museomixers\">".
									_("MuseoThermoHygro")."
								</a>
							</small></p>" ;
			$string .= '</div>' ;
		}
		
		$string .= $after ;
		echo $string ;
	}

	function getImage($width,$modele,$add=null) {
		// petit bug dans la prise en compte de width - erreur de conception - à revoir		
		
		
		$arrSize = null ;
		$htmlSize = null ;
		$imgLoc = null ;
		
		if ( $this->getData('user_image') == "" ) {
			$imgSrc = HTTP_BASE."/content/uploads/no-image.png" ;
			$img = "<img src=\"$imgSrc\" $add width=$width alt=\"".$this->getName()."\" />" ;
			return $img ;
		}
		
		$lucky = rand(0,100) ; // used for random regeneration of thumbnail 
		switch( $modele ) {
			case "remote" :
				// small thumb with pastille of museomix
				if ( $lucky > 95 || !file_exists( ABSPATH."/content/uploads/cache/$modele-".$this->getData('user_image') ) ) {
					// if no cache, do it
					$src = ABSPATH."/content/uploads/".$this->getData('user_image') ;
					$dest = ABSPATH."/content/uploads/cache/$modele-".$this->getData('user_image') ;
					$dimensions_cible = array( "240x160" ) ;
					decline_image($src,$dimensions_cible,$dest) ;
					// ajout de la pastille	museomix en 30x30px
					$lucky2 = rand(0,100) ;
					if ( $lucky2 >= 50 )					
						$src_im = imagecreatefrompng( ABSPATH."/content/uploads/pastille30jaune.png" ) ;
					else					
						$src_im = imagecreatefrompng( ABSPATH."/content/uploads/pastille30noire.png" ) ;
					
					$dest_im = imagecreatefromjpeg( ABSPATH."/content/uploads/cache/$modele-".$this->getData('user_image') ) ;
					imagecopy($dest_im,$src_im ,0,0,0,0,30,30) ;
					imagejpeg($dest_im,$dest,85) ;
					ImageDestroy($src_im) ;
					ImageDestroy($dest_im) ;
				}
				$imgSrc = HTTP_BASE."/content/uploads/cache/$modele-".$this->getData('user_image') ;
				$imgLoc = ABSPATH."content/uploads/cache/$modele-".$this->getData('user_image') ;
				break ;
			case "pastille" :
				// small thumb with pastille of museomix
				if ( $lucky > 95 || !file_exists( ABSPATH."/content/uploads/cache/pastille-".$this->getData('user_image') ) ) {
					// if no cache, do it
					$src = ABSPATH."/content/uploads/".$this->getData('user_image') ;
					$dest = ABSPATH."/content/uploads/cache/pastille-".$this->getData('user_image') ;
					$dimensions_cible = array( "240x600" ) ;
					decline_image($src,$dimensions_cible,$dest) ;
					// ajout de la pastille	museomix en 30x30px
					$lucky2 = rand(0,100) ;
					if ( $lucky2 >= 50 )					
						$src_im = imagecreatefrompng( ABSPATH."/content/uploads/pastille30jaune.png" ) ;
					else					
						$src_im = imagecreatefrompng( ABSPATH."/content/uploads/pastille30noire.png" ) ;
					
					$dest_im = imagecreatefromjpeg( ABSPATH."/content/uploads/cache/pastille-".$this->getData('user_image') ) ;
					imagecopy($dest_im,$src_im ,0,0,0,0,30,30) ;
					imagejpeg($dest_im,$dest,85) ;
					ImageDestroy($src_im) ;
					ImageDestroy($dest_im) ;
				}
				$imgSrc = HTTP_BASE."/content/uploads/cache/pastille-".$this->getData('user_image') ;
				$imgLoc = ABSPATH."content/uploads/cache/$modele-".$this->getData('user_image') ;
				break ;
			case "bausson" : // not used
				// small thumb with pastille of museomix
				if ( !file_exists( ABSPATH."/content/uploads/cache/bausson-".$this->getData('user_image') ) ) {
					// if no cache, do it
					$src = ABSPATH."/content/uploads/".$this->getData('user_image') ;
					$dest = ABSPATH."/content/uploads/cache/bausson-".$this->getData('user_image') ;
					$dimensions_cible = array( "640x1200" ) ;
					decline_image($src,$dimensions_cible,$dest) ;
					
					// work with template
					// 1. get dimensions
					$size = getimagesize( ABSPATH."/content/uploads/cache/bausson-".$this->getData('user_image') ) ;
					$final = imagecreatetruecolor($size[0],$size[1]+60) ;
					
					$base = imagecreatefromjpeg( ABSPATH."/content/uploads/cache/bausson-".$this->getData('user_image') ) ; 
					
					$pastille = imagecreatefrompng( ABSPATH."/content/uploads/pastille60.png" ) ;
					
					$color = imagecolorallocate($final,255,255,255) ;
					$black = imagecolorallocate($final,0,0,0) ;
					imagefilledrectangle($final,0,0,$size[0],$size[1]+60,$color) ;
					imagecopy($final,$base,0,0,0,0,$size[0],$size[1]) ;
					imagecopy($final,$pastille,20,$size[1]-20,0,0,60,60) ;
					//imagecop
					$font_file = './arial.ttf' ;
					imagefttext ($final,14,0,80,$size[1]+30,$black,$font_file,"Museomix") ;
					imagefttext ($final,14,0,$size[0]-200,$size[1]+30,$black,$font_file,"People make museums") ;
					
					imagejpeg($final,$dest,85) ;
					
					ImageDestroy($base) ;
					ImageDestroy($pastille) ;
					ImageDestroy($final) ;
					
					$width = $size[0] ;
				}
				$imgSrc = HTTP_BASE."/content/uploads/cache/bausson-".$this->getData('user_image') ;
				$imgLoc = ABSPATH."content/uploads/cache/$modele-".$this->getData('user_image') ;
				break ;
			case "bausson2" :
				// not created if in cache
				if ( 1==1 || $lucky > 80 || !file_exists( ABSPATH."/content/uploads/cache/bausson2-".$this->getData('user_image') ) ) {
					// if no cache, do it
					$src = ABSPATH."/content/uploads/".$this->getData('user_image') ;
					$dest = ABSPATH."/content/uploads/cache/bausson2-".$this->getData('user_image') ;
					$dimensions_cible = array( "640x1200" ) ;
					decline_image($src,$dimensions_cible,$dest) ;
					
					// work with template
					// 1. get dimensions
					$size = getimagesize( ABSPATH."/content/uploads/cache/bausson2-".$this->getData('user_image') ) ;
					$final = imagecreatetruecolor($size[0],$size[1]+60) ;
					
					
					// tests for text size
					$font_file = './arial.ttf' ;
					$grey = imagecolorallocate($final,80,80,80) ;
					
					// test 1
					$text = wordwrap( $this->getData('user_startpart')." ".$this->getData('user_participation'), 25, "\n" ) ;				
					$testTxtPos = imagefttext($final,14,0,8,0,$grey,$font_file, $text ) ;
					if ( $testTxtPos[1] < $size[1]*2/3 ) {$txtPos=$testTxtPos;$fontsize=14;$wordwrap=25;$maxHeight=$txtPos[1];} // mode 1
					else {
						// test 2
						$text = wordwrap( $this->getData('user_startpart')." ".$this->getData('user_participation'), 25, "\n" ) ;
						$testTxtPos = imagefttext($final,11,0,8,0,$grey,$font_file, $text ) ;
						if ( $testTxtPos[1] < $size[1]*2/3 ) {$txtPos=$testTxtPos;$fontsize=11;$wordwrap=25;$maxHeight=$txtPos[1];} // mode 2
						else {
							// test 3
							$text = wordwrap( $this->getData('user_startpart')." ".$this->getData('user_participation'), 35, "\n" ) ;
							$testTxtPos = imagefttext($final,9,0,8,0,$grey,$font_file, $text ) ;
							if ( $testTxtPos[1] < $size[1]*2/3 ) {$txtPos=$testTxtPos;$fontsize=9;$wordwrap=35;$maxHeight=$txtPos[1];} // mode 3
							else { // mode default
								$txtPos=$testTxtPos;$fontsize=9;$wordwrap=35;$maxHeight=$txtPos[1];
							} 
						}
					}
					// end of tests
					
					$base = imagecreatefromjpeg( ABSPATH."/content/uploads/cache/bausson2-".$this->getData('user_image') ) ; 
					$pastille = imagecreatefrompng( ABSPATH."/content/uploads/pastille60.png" ) ;
					
					
					$color = imagecolorallocate($final,255,255,255) ;
					$black = imagecolorallocate($final,0,0,0) ;
					imagefilledrectangle($final,0,0,$size[0],$size[1]+60,$color) ;
					imagecopy($final,$base,0,0,0,0,$size[0],$size[1]) ;
					imagecopy($final,$pastille,20,$size[1]-20,0,0,60,60) ;
					//imagecop
					$font_file = './arial.ttf' ;
					imagefttext ($final,14,0,80,$size[1]+30,$black,$font_file,"Museomix") ;
					imagefttext ($final,14,0,$size[0]-220,$size[1]+30,$black,$font_file,"People make museums") ;

					
					// add presentation					
					$grey = imagecolorallocate($final,80,80,80) ;
					$red = imagecolorallocate($final,255,80,255) ;
					//imagefilledrectangle($final,0,0,$size[0],$size[1]+60,$color) ;
					$text = wordwrap( $this->getData('user_startpart')." ".$this->getData('user_participation'),$wordwrap, "\n" ) ;
					$y_position = rand (30,$size[1]-$maxHeight-20) ; //$size[1]-60
					$txtPos = imagefttext($final,$fontsize,0,8,$y_position,$grey,$font_file, $text ) ;//$grey
					
					// add background text
					$bgcolor = imagecolorallocatealpha ($final,250,250,250,40) ;
					//imagefilledrectangle($final,0,$txtPos[3]-5,$txtPos[6]+5,$txtPos[7]+5,$bgcolor) ;
					imagefilledrectangle($final,0,$txtPos[7]-8,$txtPos[2]+8,$txtPos[3]+8,$bgcolor) ;
					
					// on remet sur calque sup
					$txtPos = imagefttext($final,$fontsize,0,8,$y_position,$grey,$font_file, $text ) ;
					
					
					//imagefilledrectangl
					imagejpeg($final,$dest,82) ;
					
					ImageDestroy($base) ;
					ImageDestroy($pastille) ;
					ImageDestroy($final) ;
					
					$width = $size[0] ;
				}
				$imgSrc = HTTP_BASE."/content/uploads/cache/$modele-".$this->getData('user_image') ;
				$imgLoc = ABSPATH."content/uploads/cache/$modele-".$this->getData('user_image') ;
				break ;
			case "MUSEOMIX" :
				// small thumb with museomix font
				if ( $lucky > 60 || !file_exists( ABSPATH."/content/uploads/cache/$modele-".$this->getData('user_image') ) ) {
					// if no cache, do it
					$src = ABSPATH."/content/uploads/".$this->getData('user_image') ;
					$dest = ABSPATH."/content/uploads/cache/$modele-".$this->getData('user_image') ;
					$dimensions_cible = array( "300x1200" ) ;
					decline_image($src,$dimensions_cible,$dest) ;
					
					// work with template
					// 1. get dimensions
					$size = getimagesize( ABSPATH."/content/uploads/cache/$modele-".$this->getData('user_image') ) ;
					$final = imagecreatetruecolor($size[0],$size[1]) ;
					
					$base = imagecreatefromjpeg( ABSPATH."/content/uploads/cache/$modele-".$this->getData('user_image') ) ; 
					
					$bleu = imagecolorallocate($final,18,194,239) ;
					$jaune = imagecolorallocate($final,248,238,77) ;
					$temp = rand(0,100) ;
					
					imagecopy($final,$base,0,0,0,0,$size[0],$size[1]) ;

					// add mention
					$font_file = './museomix.ttf' ;
					$color = ( rand(0,100) >= 50 ) ? $bleu : $jaune ;
					imagefttext ($final,14,0,5,$size[1]-6,$color,$font_file,"MUSEOMIX 2013") ;
					
					imagejpeg($final,$dest,85) ;
					
					ImageDestroy($base) ;
					ImageDestroy($final) ;
					
					$width = $size[0] ;
				}
				$imgSrc = HTTP_BASE."/content/uploads/cache/$modele-".$this->getData('user_image') ;
				$imgLoc = ABSPATH."content/uploads/cache/$modele-".$this->getData('user_image') ;
				break ;
			default :
				$imgSrc = HTTP_BASE."/content/uploads/".$this->getData('user_image') ;
				$imgLoc = ABSPATH."content/uploads/".$this->getData('user_image') ;
		}		
		
		$arrSize = getimagesize($imgLoc) ;
		$htmlSize = $arrSize[3] ;
		
		$img = "<img src=\"$imgSrc\" $htmlSize $add alt=\"".$this->getName()."\" />" ;
		return $img ;
	}

	// delete cached images for an user 
	function imageCacheUnlink() {
		if ( $this->getData('user_image') == "" ) return true ;
		
		$fileSrc = $this->getData('user_image') ;
		$dir = ABSPATH."content/uploads/cache/" ;
		if ( $handle = opendir($dir) ) {
			while (false !== ($file = readdir($handle))) {
				if ( strpos($file,$fileSrc) != false)
					unlink("$dir"."$file") ;
			}
		}
		else
			return false ;
		return true ;
	}

	function getMyParticipations() {
		if ( empty($this->ID) ) return false ;
		if ( empty($this->events) ) {
			$query = "SELECT * FROM ".TABLE_PARTICIPATIONS." WHERE user_id = ".$this->ID ;
			$results = UPDO::getInstance()->query( $query ) ;
			$i = 0 ;
			foreach ( $results as $result ) {
				$this->participations[$i] = new participation($result['ID']) ;
				$this->participations[$i]->setData($result) ;
				$i++ ;
			}
			return $this->participations ;
		}
		else
			return $this->participations ;
	}

	function getAdminBar($before=NULL,$after=NULL) {
		//$del = "<a href=\"page-userdelete?ID={$this->getID()}\">"._('Désactiver')."</a>" ;
		$mod = "<a href=\"page-usermodify?ID={$this->getID()}\">"._('Modifier')."</a>" ;
		$password = "<a href=\"page-usernewpassword\">"._('Changer mon mot de passe')."</a>" ;
		$mypicture = "<a href=\"page-mypicture\">"._('Charger ma photo')."</a>" ;
		
		$buffer = $before.$mod." | ".$password." | ".$mypicture.$after ;
		return $buffer ;
	}



	// objetc must be an object with an user_id column
	// be able with route, vehicule
	function isOwner(&$object) {
		$user_id = $object->getData('user_id') ;
		if ( empty($user_id) ) $object->loadDataFromID() ;
		$user_id = $object->getData('user_id') ;
		if ( $this->getID() === $user_id )
			return true ;
		else
			return false ;
	}

} // end of class

function getAllUsers() {
	$list = NULL ;
	$sql = "SELECT * FROM ".TABLE_USERS." WHERE user_status NOT LIKE \"00000\" ORDER BY ID DESC" ;
	$query = UPDO::getInstance()->prepare($sql) ;
	$query->execute() ;
	$i = 0 ;
	while( $ligne = $query->fetch(PDO::FETCH_ASSOC) ) {
		$list[$i] = new user($ligne['ID']) ;
		$list[$i]->setData($ligne) ;
		$i++ ;
	}
	return $list ;
}

function getAllUsersWithMedia() {
	$list = NULL ;
	$sql = "SELECT * FROM ".TABLE_USERS." WHERE user_image NOT LIKE \"\" AND user_status NOT LIKE \"00000\" ORDER BY ID DESC" ;
	$query = UPDO::getInstance()->prepare($sql) ;
	$query->execute() ;
	$i = 0 ;
	while( $ligne = $query->fetch(PDO::FETCH_ASSOC) ) {
		$list[$i] = new user($ligne['ID']) ;
		$list[$i]->setData($ligne) ;
		$i++ ;
	}
	return $list ;
}
?>