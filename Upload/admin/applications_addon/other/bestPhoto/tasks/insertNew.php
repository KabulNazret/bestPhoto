<?php

if ( ! defined( 'IN_IPB' ) ) {
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

/**
 * This is the task that inserting new image every monday
 * 
 * @package bestPhoto
 * @class task_item
 * @author Anthony
 * @link http://brainfucklab.com
 * @version 0.1
 */
class task_item {
	
	/**
	 * Object that stores the parent task manager class
	 * @var		object
	 */
	protected $class;
	
	/**
	 * Array that stores the task data
	 * @var		array
	 */
	protected $task		= array();
	
	/**
	 * Registry Object Shortcuts
	 * @var		object
	 */
	protected $registry;
	protected $DB;
	protected $settings;
	protected $lang;
	
	/**
	 * Class anrty point
	 *
	 * @param object $registry
	 * @param object $class
	 * @param array $task
	 * @return	void
	 */
	public function __construct( ipsRegistry $registry, $class, $task ) {

		$this->registry	= $registry;
		$this->DB		= $this->registry->DB();
		$this->settings	=& $this->registry->fetchSettings();
		$this->lang		= $this->registry->getClass('class_localization');
		
		$this->class	= $class;
		$this->task		= $task;


		//$this->lang->loadLanguageFile( array( 'admin_gallery' ), 'gallery' );
	}
	
	/**
	 * Run this task
	 * @return void
	 */
	public function runTask() {
		
		
		//init gallery
		if ( ! $this->registry->isClassLoaded('gallery') ) {
			require_once( IPS_ROOT_PATH . '/applications_addon/ips/gallery/sources/classes/gallery.php' );
			$this->registry->setClass( 'gallery', new ipsGallery( $this->registry ) );
		}
		
		
		
		//get the photo
		ipsRegistry::DB()->allow_sub_select=1;
		
		$start = (int)strtotime('previous Monday'); //strtotime('first day of');
		$end = (int)strtotime('next Monday'); //strtotime('last day of');
		
		$q = "	SELECT type_id, SUM(rep_rating) as rp
				FROM {$this->DB->obj['sql_tbl_prefix']}reputation_index
				WHERE app = 'gallery'
						AND type = 'id'
						AND rep_rating != -1
						AND rep_date BETWEEN " . $start . " AND " . $end . "
				GROUP BY type_id
				ORDER BY rp DESC
				LIMIT 1
			";
		$r = $this->DB->query($q);
		$repData = $this->DB->fetch($r);
		
		if(!empty($repData)) {
			ipsRegistry::DB()->allow_sub_select=1;
			$image = $this->registry->gallery
									->helper('image')
									->fetchImage( $repData['type_id'] );
			if(!empty($image)) {
				//insert new data
				$insert = 'INSERT INTO
							'.$this->DB->obj['sql_tbl_prefix'].'gallery_best_photos
							(image_id, member_id, rating, date_from, date_to)
							VALUES
							(
							' . $repData['type_id'] . ',
							' . $image['member_id'] . ',
							' . $repData['rp'] . ',
							' . $s . ',
							' . $e . '
							)';
				$this->DB->query($insert);
			}
		}
		
		
		
		//unlock the task
		$this->class->unlockTask( $this->task );
	} 
}