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
function closeHtml() { // not used
	$string = "\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t</div>\n\t\t<div id='footer'></div>\n\t </div> \n" ;
	$string .= "\n</body>\n</html>" ;
	return $string ;
}

function debug($var) {
	echo "<pre>" ;
	print_r($var) ;
	echo "</pre>" ;
}

function dataClean($data,$type="text") {
	// faire un case plutôt que l'enfilade de if :(
	switch ($type) {
	case "mail":
		if (!preg_match('/^[A-Z0-9._-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z.]{2,6}$/i',$data)) {
			$data = false ;
		}
		break ;
	case "text":
		// suppression des caractères pas catholiques
		$data = preg_replace('/[^[:alnum:]éèàçâêüô@-_.]/i','_',$data);
		//$data = preg_replace("[^([:alnum:]éèàçâê\.ô\-_\ )+]"," ",$data);
		// suppression des espaces répétés
		$data = preg_replace('/\s\s+/', ' ', $data) ;
		break ;
	case "alnum" :
		// suppression des caractères pas catholiques
		$data = preg_replace('/[^[:alnum:]-_.]/',"",$data);
		// suppression des espaces répétés
		$data = preg_replace('/\s/', '', $data) ;
		// miniscules
		$data = strtolower($data) ;
		break ;
	case "ALnum" :
		// suppression des caractères pas catholiques
		$data = preg_replace('/[^[:alnum:]A-Z-_.]/i',"",$data);
		// suppression des espaces répétés
		$data = preg_replace('/\s/', '', $data) ;
		// miniscules
		$data = strtolower($data) ;
		break ;
	case "sql" :
		$data = trim($data) ;
		//$data = str_replace(array("*","?"),array("%","_"),$data) ; // pb lors des insertions
		// vire les balises
		$data = strip_tags($data) ;
		// zappe le magic_quote déprécié
		if(get_magic_quotes_gpc()) {
			if(ini_get('magic_quotes_sybase'))
				$data = str_replace("''", "'", $data) ;
			else $data = stripslashes($data) ;
		}
		$data = mysql_real_escape_string($data) ;
		break ;
	case "undo_magic_quote" :
		// vire les balises
		$data = strip_tags($data) ;
		// zappe le magic_quote déprécié
		if(get_magic_quotes_gpc()) {
			if(ini_get('magic_quotes_sybase'))
				$data = str_replace("''", "'", $data) ;
			else $data = stripslashes($data) ;
		}
		break ;
	case "search" :
		// suppression des termes non déterminant
		$data = str_ireplace(" le "," ",$data) ;
		$data = str_ireplace(" et "," ",$data) ;
		$data = str_ireplace(" la "," ",$data) ;
		$data = str_ireplace(" les "," ",$data) ;
		$data = str_ireplace(" un "," ",$data) ;
		$data = str_ireplace(" une "," ",$data) ;
		$data = str_ireplace(" des "," ",$data) ;
		$data = str_ireplace(" ma "," ",$data) ;
		$data = str_ireplace(" ta "," ",$data) ;
		$data = str_ireplace(" sa "," ",$data) ;
		$data = str_ireplace(" sur "," ",$data) ;
		$data = str_ireplace(" à "," ",$data) ;
		$data = str_ireplace(" dans "," ",$data) ;
		$data = str_ireplace(" en "," ",$data) ;
		$data = str_ireplace(" d'"," ",$data) ;
		$data = str_ireplace(" l'"," ",$data) ;
		// suppression des caractères pas catholiques
		$data = preg_replace("/[^[:alnum:]éèàçâêüôù-_.@?%]/"," ",$data) ;
		// suppression des espaces répétés
		$data = preg_replace('/\s\s+/', ' ', $data) ;
		break ;
	case "int" :
		$data = preg_replace('/[^0-9]/','',$data);
		if ( $data == "" ) $data = 0 ;
		$data = (int) $data ;
		break ;
	case "float" : // because transtype (float) give , instead of . inside float // bug fixes with locale
		$data = str_replace(',','.',$data) ; // decimal separator must be the point and not the ,
		$data = preg_replace('/[^0-9]\./','',$data);
		$data = (float) $data ;
		if ( $data == "" ) $data = 0 ;
		break ;
	}
	return $data ;
}

function decline_image($src,$dimensions_cible,$dest) {
	//$dimensions_cible = array('1024x768','640x480','220x220','80x80','35x35') ;
	$test = true ; // valeur de test
	// nom final
	$size = GetImageSize($src) ;
	$image_cache = ImageCreateFromJpeg($src) ;
	
	foreach ( $dimensions_cible as $value ) {
		$size_dest = explode('x',$value) ;
		// print_r($size_dest) ;
		if ( $size[0] > $size_dest[0] || $size[1] > $size_dest[1] ) {
			// calcul de taille final
			if ( ($size[0]/$size[1]) >= ($size_dest[0]/$size_dest[1]) ) { // image horizontale
				$dest_w = $size_dest[0] ;
				$dest_h = round($size_dest[0]*$size[1]/$size[0]) ;
			}
			else { // image verticale
				$dest_h = $size_dest[1] ;
				$dest_w = round($size_dest[1]*$size[0]/$size[1]) ;
			}
			// echo "DEST: $dest_w x $dest_h ; $size[0] x $ize[1] \n" ;
			$new_image = ImageCreateTrueColor($dest_w,$dest_h) ;
			ImageCopyResampled($new_image,$image_cache,0,0,0,0,$dest_w,$dest_h,$size[0],$size[1]) ;
			ImageJpeg($new_image,$dest) ;
			ImageDestroy($new_image) ;
		}
		else copy($src,$dest) ;
		if ( !file_exists($dest) ) $test = false ;
	}
	ImageDestroy($image_cache) ;

	// si tout est ok retourne nom de l'image
	if ( $test ) return $dest ;
	else return false ;
}

function countUsers() {
	$sql = "SELECT COUNT(*) AS nb FROM ".TABLE_USERS ;
	foreach ( UPDO::getInstance()->query($sql) as $row ) {
		return $row['nb'] ;
	}
	return false ;
}

function IfNotNull($data) {
	if ( !empty($data) ) return $data ;
	else return NULL ;
}

/* manage cookies of research as a pile */
//$listCookies ;

function getSearchCookies() {
	$i = 0 ;
	$max = 5 ;
	while( isset($_COOKIE['search'.$i]) && $i <= $max ) {
		$list[] = $_COOKIE['search'.$i] ;
	}
	return $list ;
}

function addCookie($cookie) {
	$list = getSearchCookies() ;
	array_pop($list) ;
	array_unshift($list,$cookie) ;
}

function setSearchCookies($list) {
	$i = 0 ;
	foreach ($list as $value) {
		setcookie('search'.$i,$value) ;
		$i++ ;
	}
	return true ;
}


// from http://code.seebz.net/p/truncate/
function truncate($string, $max_length = 30, $replacement = '...', $trunc_at_space = false) {
	$max_length -= strlen($replacement);
	$string_length = strlen($string);
	
	if($string_length <= $max_length)
		return $string;
	
	if( $trunc_at_space && ($space_position = strrpos($string, ' ', $max_length-$string_length)) )
		$max_length = $space_position;
	
	return substr_replace($string, $replacement, $max_length);
}


?>