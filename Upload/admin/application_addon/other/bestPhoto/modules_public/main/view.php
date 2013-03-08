<?php

if ( !defined( 'IN_IPB' ) ) {
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}


/**
 * Show list photos of the weeks
 *
 * @package bestPhoto
 * @class public_bestPhoto_main_view
 * @author Anthony
 * @link http://brainfucklab.com
 * @version 0.1
 *
 */
class public_bestPhoto_main_view extends ipsCommand {
	
	
	/**
	 * Class entry point
	 * @param ipsRegistry $registry
	 */
	public function doExecute( ipsRegistry $registry ) {
		
		//init gallery
		if ( ! $this->registry->isClassLoaded('gallery') ) {
			require_once( IPS_ROOT_PATH . '/applications_addon/ips/gallery/sources/classes/gallery.php' );
			$this->registry->setClass( 'gallery', new ipsGallery( $this->registry ) );
		}
		
		/* Language */
		$this->lang->loadLanguageFile( array( 'public_main_bestPhoto' ), 'bestPhoto' );
		
				
		//set up application title and make the navigation link
		$this->registry->output->setTitle( IPSLib::getAppTitle('bestPhoto') );
		$this->registry->output->addNavigation( IPSLib::getAppTitle('bestPhoto'), 'app=bestPhoto', "false", 'app=bestPhoto' );
		
		//get photos list
		$this->DB->build( array( 	'select' 	=> '*', 
									'from'		=> 'gallery_best_photos', 
									'order' 	=> 'id DESC' 
								)
						);
		$qImages = $this->DB->execute();
		
		$images = array();
		while( $res = $this->DB->fetch($qImages) ) {
			$res['date_from'] = date('Y-m-d', $res['date_from']);
			$res['date_to'] = date('Y-m-d', $res['date_to']);
			$images[] = $res;
		}
		
		//got some photos?
		if(!empty($images)) {
			
			foreach($images as $k => $i) {
				//get photo data
				$image = $this->registry->gallery
											->helper('image')
											->fetchImage( $i['image_id'] );
				
				//get album data
				$parentAlbum = $this->registry->gallery
											->helper('albums')
											->fetchAlbumsById( $image['album_parent_id'] );
				
				$images[$k]['image'] = $image;
				$images[$k]['albumData'] = $parentAlbum;
			}
			
		}
		
		$images = array_reverse($images);
		$iCount = count($images);
		//show output
		$this->registry->output->addContent( 
										$this->registry->output
														->getTemplate('bestPhoto')
														->viewList( $images, $iCount ) 
											);
		$this->registry->output->sendOutput();
		
		
	}
}

