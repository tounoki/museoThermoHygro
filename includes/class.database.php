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
 * abstract class, manage data and records
 * @abstract
 **/
abstract class database {
	/**
	 * ID
	 * @var int
	 */
	protected $ID ; // ID of record
	/**
	 * date of creation for record
	 * @var date
	 */
	protected $date_created ;
	/**
	 * date of last modification for record
	 * @var date
	 */
	protected $date_modified ;
	/**
	 * specific data of each record, $data[name_of_column] = value
	 * @var array
	 */
	protected $data ;
	/**
	 * list of columns of each table
	 * @var array
	 */
	static protected $LIST_DATA = array() ;
	/**
	 * name of considered table - STATIC
	 * @var string
	 */
	static protected $table = "" ;
	/**
	 * array of prepared query for PDO
	 * @var array
	 */
	private $query ; // array of query

	/**
	 * constructor - the constructor prepare the common querys too
	 * @param int
	 * @return bool
	 */
	function __construct($ID=NULL) {
		$this->ID = $ID ;
		foreach( STATIC::$LIST_DATA as $value) {
			if ( 1==1 ) $this->data[$value] = NULL ;
		}
		// prepare query for loadDataFromID
		$this->query['loadDataFromID'] = UPDO::getInstance()->prepare("SELECT * FROM ".STATIC::$table." WHERE ID = :ID") ;
		// prepare query for update
		foreach ( STATIC::$LIST_DATA as $value ) {
			$temp[] = "$value = :$value" ;
		}
		// ajouter date_modified
		$list = implode(',',$temp) ;
		$sql = "UPDATE ".STATIC::$table."
			SET $list WHERE ID = :ID" ;
		$this->query['update'] = UPDO::getInstance()->prepare($sql) ;
		return true ;
	}

	/**
	 * ressource could be a SQL ressource or a POST or GET array
	 * @param array
	 * @return bool
	 */
	public function setData($ressource) {
		if ( empty($ressource) ) return false ;
		foreach( $ressource as $key => $value ) {
			if ( !empty($key) && in_array($key,STATIC::$LIST_DATA) ) {
				$this->data[$key] = trim($value) ;
			}
		}
		return true ;
	}
	/**
	 * return the ID of the record, if it is already save
	 * @return int
	 */
	function getID() {
		return $this->ID ;
	}
	/**
	 * return a specific value of a column - the return value type depend of the choosed column
	 * @param string
	 * @return mixed
	 */
	function getData($field) {
		if ( empty($field) ) return false ;
		return $this->data[$field] ;
	}
	/**
	 * return a all value of a record
	 * @return array
	 */
	function getAllData() {
		return $this->data ;
	}
	/**
	 * load the data stored in database if ID is given
	 * @return bool
	 */
	function loadDataFromID() {
		if ( empty($this->ID) ) return false ;
		// "SELECT * FROM ".STATIC::$table." WHERE ID = :ID" ; // prepared in constructor
		$this->query['loadDataFromID']->execute(array(':ID'=>$this->ID)) ;
		while( $ligne = $this->query['loadDataFromID']->fetch(PDO::FETCH_ASSOC) ) {
			/*
			foreach ($ligne as $key => $value ) {
				if ( in_array($key,STATIC::$LIST_DATA) ) {
					$this->data[$key] = $value ;
				}
			}*/
			$this->setData($ligne) ;
		}
		return true ;
	}

	/**
	 * print directly all the data - often use for debugging or preparing script
	 * @return bool, ever true
	 */
	function printData($level=0,$before=NULL,$after=NULL) {
		echo $before ;
		echo "ID : ".$this->ID."<br/>" ;
		foreach( $this->data as $key => $value ) {
			echo "<strong>".$key." :</strong> ".$value."<br/>" ;
		}
		echo "$after\n" ;
		return true ;
	}

	/**
	 * save the datas a new record
	 * @return bool, true if success
	 */
	function add() {
		if ( !empty($this->data) && count($this->data) > 0 ) {
			//print_r($this->data) ;
			// STATIC::$LIST_DATA
			$list = implode(",",array_keys($this->data)) ;
			$list_prep = ':'.implode(",:",array_keys($this->data)) ;

			// ajout date_created automatic
			$sql = "INSERT
				INTO ".STATIC::$table."(".implode(',',STATIC::$LIST_DATA).")
				VALUES (".$list_prep.")" ;
			//echo $sql ;
			$query = UPDO::getInstance()->prepare($sql) ;
			if ( $query->execute($this->data) ) {
				$this->ID = UPDO::getInstance()->lastInsertId() ;
				return true ;
			}
			else return false ;
		}
		else return false ;
	}

	/**
	 * update a record if it already exists
	 * @return bool true if success
	 */
	function update() {
		if ( !empty($this->data) && count($this->data) > 0 ) {
			// query is prepared in constructor
			$this->query['update']->bindValue(':ID', $this->ID) ;
			foreach ( $this->data as $key => $val ) {
				if ( !empty($key) && in_array($key,STATIC::$LIST_DATA) )
					$this->query['update']->bindValue(':'.$key,$val) ;
			}
			if ( $this->query['update']->execute() ) {
				return true ;
			}
			else return false ;
		}
		else return false ;
	}

	function save() {
		if ( empty($this->ID) ) return $this->add() ;
		else return $this->update() ;
	}

	/**
	 * delete record in database
	 * @return bool, true if success
	 */
	function delete() {
		if ( empty( $this->ID ) ) return false ;
		// prepare the querys
		$sql[0] = "DELETE FROM ".STATIC::$table." WHERE ID = {$this->getID()}" ;
		// execute the querys
		$count[0] = UPDO::getInstance()->exec($sql[0]) ;
		if ( $count[0] > 0 )
			return true ;
		else return false ;
	}

	/**
	 * surcharge of serialize a record for private data
	 * @return string
	 */
	function serialize() {
		$arr['ID'] = $this->getID() ;
		$arr['data'] = $this->getAllData() ;
		return serialize($arr) ;
	}
	/**
	 * surcharge of unserialize for record
	 * @param string the unserialised data
	 * @return bool
	 */
	function unserialize($string) {
		$buffer = unserialize($string) ;
		$this->ID = ( empty($buffer['ID']) ) ? NULL : $buffer['ID'] ;
		$this->setData($buffer['data']) ;
		return true ;
	}

	/**
	 * destructor
	 */
	function __destruct() {
		$this->ID = 		NULL ;
		$this->date_created = 	NULL ;
		$this->date_modified = 	NULL ;
		$this->data = 		NULL ;
	}

}


class measure extends database {
	static protected $LIST_DATA = array('dateAndTime','temperature','hygrometry','device_id') ;
	static protected $table = TABLE_MEASURES ;
	
	/**
	 * print directly all the data - often use for debugging or preparing script
	 * @return bool, ever true
	 */
	function printData($level=0,$before=NULL,$after=NULL) {
		echo $before ;
		switch ($level) {
		case "htmlTable":
			echo "<tr><td>".
					$this->getData('dateAndTime')."</td><td>".
					$this->getData('temperature')."</td><td>".
					$this->getData('hygrometry')."</td></tr>\n" ;
			break;
		case 1:
			echo "i égal 1";
			break;
		case 2:
			echo "i égal 2";
			break;
		}
		
		echo "$after\n" ;
		return true ;
	}
	}

class device extends database {
	static protected $LIST_DATA = array('device_name','device_description') ;
	static protected $table = TABLE_DEVICES ;
	
	//Return percentage of measures that are in the good zone
	function goodMeasures($dateStart,$dateStop,$Tinf,$Tsup,$Hinf,$Hsup) {
		if ( $this->getID() == NULL ) return false ;
		
		$dateStop = $dateStop." 23:59" ;		
		
		$list = NULL ;
		$sql = "SELECT COUNT(ID) AS nb
					FROM measures 
					WHERE
						device_id = ".$this->getID().
						" AND dateAndTime >= '". $dateStart."' 
						AND dateAndTime <= '". $dateStop."' 
						AND temperature > $Tinf
						AND temperature < $Tsup
						AND hygrometry < $Hsup
						AND hygrometry > $Hinf" ;

		$query = UPDO::getInstance()->prepare($sql) ;
		$query->execute() ;
		$i = 0 ;
		$ligne = $query->fetch(PDO::FETCH_ASSOC) ;
		$goodM = $ligne['nb'] ;
		
		$list = NULL ;
		$sql = "SELECT COUNT(ID) AS nb
					FROM measures 
					WHERE
						dateAndTime >= '". $dateStart."' 
						AND dateAndTime <= '". $dateStop."' 
						AND device_id = ".$this->getID() ;
		$query = UPDO::getInstance()->prepare($sql) ;
		$query->execute() ;
		$i = 0 ;
		$ligne = $query->fetch(PDO::FETCH_ASSOC) ;
		$nb = $ligne['nb'] ;	
		$percent = round( $goodM * 100 / $nb , 1 ) ;
		return $percent ;
		}	
	
	function resetDevice() {
		if ( $this->getID() == NULL ) return false ;
		
		$sql = "DELETE
					FROM measures 
					WHERE
						device_id = ".$this->getID() ;
		$query = UPDO::getInstance()->prepare($sql) ;
		if ( $query->execute() ) return true ;
		else return false ;
	}	
	
	function getDates() {
		if ( $this->getID() == NULL ) return false ;
		
		$list = NULL ;
		$sql = "SELECT 
						MIN( DATE_FORMAT(dateAndTime,'%Y-%m-%d') ) AS firstDay, 
						MAX( DATE_FORMAT(dateAndTime,'%Y-%m-%d') ) AS lastDay
					FROM measures 
					WHERE
						device_id = ".$this->getID() ;

		$query = UPDO::getInstance()->prepare($sql) ;
		$query->execute() ;
		$i = 0 ;
		while( $ligne = $query->fetch(PDO::FETCH_ASSOC) ) {
			$data['firstDay'] = $ligne['firstDay'] ;
			$data['lastDay'] = $ligne['lastDay'] ;
		}
		return $data ;
	}
	
	function countMeasures() {
		if ( $this->getID() == NULL ) return false ;
		
		$list = NULL ;
		$sql = "SELECT COUNT(ID) AS nb
					FROM measures 
					WHERE
						device_id = ".$this->getID() ;

		$query = UPDO::getInstance()->prepare($sql) ;
		$query->execute() ;
		$i = 0 ;
		while( $ligne = $query->fetch(PDO::FETCH_ASSOC) ) {
			$nb = $ligne['nb'] ;
		}
		return $nb ;
	}
	
	function getMeasures($dateStart=NULL,$dateStop=NULL) {
		if ( $this->getID() == NULL ) return false ;
		
		$dateStop = $dateStop." 23:59" ;
		
		$list = NULL ;
		$sql = "SELECT * FROM ".TABLE_MEASURES.
					" WHERE device_id = ".$this->getID().
					" AND dateAndTime >= '". $dateStart."'".
					" AND dateAndTime <= '". $dateStop."'".
					" ORDER BY dateAndTime ASC" ;
		$query = UPDO::getInstance()->prepare($sql) ;
		$query->execute() ;
		$i = 0 ;
		while( $ligne = $query->fetch(PDO::FETCH_ASSOC) ) {
			$list[$i] = new measure($ligne['ID']) ;
			$list[$i]->setData($ligne) ;
			$i++ ;
		}
		return $list ;
	}
	
	function getExtremeMeasures($dateStart=NULL,$dateStop=NULL) {
		if ( $this->getID() == NULL ) return false ;
		
		$dateStop = $dateStop." 23:59" ;		
		
		$list = NULL ;
		$sql = "SELECT MAX(temperature) as maxT, 
							MIN(temperature) as minT, 
							MAX(hygrometry) as maxH,
							MIN(hygrometry) as minH,
							ROUND( AVG(temperature), 2 ) as moyT,
							ROUND( AVG(hygrometry), 2 ) as moyH
					FROM ".TABLE_MEASURES.
					" WHERE device_id = ".$this->getID().
					" AND dateAndTime > '". $dateStart."'".
					" AND dateAndTime < '". $dateStop."' LIMIT 1" ;
		$query = UPDO::getInstance()->prepare($sql) ;
		$query->execute() ;
		return $query->fetch(PDO::FETCH_ASSOC) ;
	}	
	
	function getMaxMinPerDay($dateStart=NULL,$dateStop=NULL) {
		if ( $this->getID() == NULL ) return false ;
		
		$dateStop = $dateStop." 23:59" ;		
		
		$list = NULL ;
		$sql = "SELECT DATE_FORMAT(dateAndTime,'%Y-%m-%d') AS day, 
						MAX(temperature) AS maxT, 
						MIN(temperature) AS minT, 
						MAX(hygrometry) AS maxH, 
						MIN(hygrometry) AS minH 
					FROM measures 
					WHERE
						device_id = ".$this->getID().
						" AND dateAndTime >= '". $dateStart."'".
						" AND dateAndTime <= '". $dateStop."'".
						"GROUP BY day ORDER BY day ASC" ;

		$query = UPDO::getInstance()->prepare($sql) ;
		$query->execute() ;
		$i = 0 ;
		while( $ligne = $query->fetch(PDO::FETCH_ASSOC) ) {
			$list[$i] = $ligne ;
			$i++ ;
		}
		return $list ;
	}
	
	function getDeltaPerDay($dateStart=NULL,$dateStop=NULL) {
		if ( $this->getID() == NULL ) return false ;

		$buffer = $this->getMaxMinPerDay($dateStart,$dateStop) ;
		$statsDay = NULL ;
		foreach ( $buffer as $day ) {
				$statsDay[ $day['day'] ]['deltaT'] = $day['maxT'] - $day['minT'] ;
				$statsDay[ $day['day'] ]['deltaH'] = $day['maxH'] - $day['minH'] ;
			}
		return $statsDay ;
		}
	
	/**
	 * print directly all the data - often use for debugging or preparing script
	 * @return bool, ever true
	 */
	function printTable($dateStart,$dateStop) {
		echo "<table>\n" ;
		$measures = $this->getMeasures($dateStart,$dateStop) ;
		foreach ( $measures as $m ) {
			$m->printData('htmlTable') ;			
			}
		echo "</table>\n" ;
		return true ;
	}
	
	function printData($level=0,$before=NULL,$after=NULL) {
		echo $before ;
		switch ($level) {
		case "htmlTable":
			$dates = $this->getDates() ;
			echo "<tr><td>".
					$this->getData('device_name')."</td><td>".
					$this->getData('device_description')."</td><td>".
					$dates['firstDay']."</td><td>".
					$dates['lastDay']."</td><td>".
					$this->countMeasures()."</td><td>".
					"<a href='device-{$this->getID()}'>O</a>"."</td>".
					"<td><a href='page-devicemodify?ID={$this->getID()}'>O</a>"."</td>".
					"<td><a href='page-reset?ID={$this->getID()}'>O</a>"."</td>".
					"</tr>\n" ;
			break;
		case 1:
			echo "i égal 1";
			break;
		case 2:
			echo "i égal 2";
			break;
		}
		
		echo "$after\n" ;
		return true ;
	}
	
	}

function getAllDevices() {
	$sql = "SELECT *
				FROM devices 
				ORDER BY device_name ASC" ;
	$query = UPDO::getInstance()->prepare($sql) ;
	$query->execute() ;
	$i = 0 ;
	while( $ligne = $query->fetch(PDO::FETCH_ASSOC) ) {
		$list[$i] = new device( $ligne['ID'] ) ;
		$list[$i]->setData( $ligne ) ;
		$i++ ;
	}
	return $list ;
	}

/**
 * class for manage
 **/
 /*
class participation extends database {
	static protected $LIST_DATA = array('event_id','user_id') ;
	static protected $table = TABLE_PARTICIPATIONS ;
	
	function printData($level=0,$before=NULL,$after=NULL) {
		if ( $this->getData('event_id') == 1 )
			echo "<img style='margin-right:5px;' src=\"content/uploads/museo2011.png\" >" ;
		if ( $this->getData('event_id') == 2 )
			echo "<img style='margin-right:5px;' src=\"content/uploads/museo2012.png\" >" ;
		if ( $this->getData('event_id') == 3 )
			echo "<img style='margin-right:5px;' src=\"content/uploads/museo2013.png\" >" ;
		return true ;
	}	
	
}

/**
 * class for manage
 **/
 /*
class event extends database {
	static protected $LIST_DATA = array('event_name','event_year','event_localisation','event_comment') ;
	static protected $table = TABLE_PARTICIPATIONS ;
}
function getAllEvents() {
	$list = NULL ;
	$sql = "SELECT * FROM ".TABLE_EVENTS." ORDER BY ID ASC" ;
	$query = UPDO::getInstance()->prepare($sql) ;
	$query->execute() ;
	$i = 0 ;
	while( $ligne = $query->fetch(PDO::FETCH_ASSOC) ) {
		$list[$i] = new event($ligne['ID']) ;
		$list[$i]->setData($ligne) ;
		$i++ ;
	}
	return $list ;
}
*/
?>