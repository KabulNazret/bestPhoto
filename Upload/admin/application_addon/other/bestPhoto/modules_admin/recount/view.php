<?php
if(!defined('IN_ACP'))
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded 'admin.php'.";
	exit();
}


/**
 * This is admin module for application bestPhoto that provides method for recount
 * 
 * @package bestPhoto
 * @class admin_bestPhoto_recount_view
 * @author Anthony
 * @link http://brainfucklab.com
 * @version 0.1
 */
class admin_bestPhoto_recount_view extends ipsCommand {
	
	
	/**
	 * Class entry point
	 * @param ipsRegistry $registry
	 */
	public function doExecute(ipsRegistry $registry) {
		
		
		//saver
		if( $this->request['do'] == 'save' ) {
			
			//init gallery
			if ( ! $this->registry->isClassLoaded('gallery') ) {
				require_once( IPS_ROOT_PATH . '/applications_addon/ips/gallery/sources/classes/gallery.php' );
				$this->registry->setClass( 'gallery', new ipsGallery( $this->registry ) );
			}
			
			//delete last 10 rows
 			$this->DB->delete('gallery_best_photos', '', 'id DESC', array(20));
			
			//recount new statistic
			$start = (int)strtotime('previous Monday'); //strtotime('first day of');
			$end = (int)strtotime('next Monday'); //strtotime('last day of');
				
			//20 weeks
			for($i = 0; $i < 20; $i++) {
				if($i > 0) {
					$s = $start - ((60*60*24*7) * $i);
					$e = $end - ((60*60*24*7) * $i);
				} else {
					$s = $start;
					$e = $end;
				}
			
				
				ipsRegistry::DB()->allow_sub_select=1;
				$q = "	SELECT type_id, SUM(rep_rating) as rp
						FROM {$this->DB->obj['sql_tbl_prefix']}reputation_index
						WHERE app = 'gallery'
								AND type = 'id'
								AND rep_rating != -1
								AND rep_date BETWEEN " . $s . " AND " . $e . "
					 	GROUP BY type_id
						ORDER BY rp DESC
						LIMIT 1
						";
				$r = $this->DB->query($q);
				//got some data?
				while($repData = $this->DB->fetch($r)) {
					if(!empty($repData)) {
						ipsRegistry::DB()->allow_sub_select=1;
						$image = $this->registry->gallery
												->helper('image')
												->fetchImage( $repData['type_id'] );
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
						ipsRegistry::DB()->allow_sub_select=1;
						$this->DB->query($insert);
					}
				}
			}
				
			//redirect to recount index page
			$url      = $this->settings['base_url'];;
			$this->registry->output->global_message = $this->lang->words['bestPhoto_donemsg'];
			$this->registry->output->silentRedirectWithMessage($url);
		}
		
        
		//$html = $this->registry->output->loadTemplate( 'cp_skin_recount' );
		//$this->registry->output->html .= $html->recount();
		
		//instead using foreign template we just put html code below:	
		$html = "<div class='section_title'><h2>{$this->lang->words['bestPhoto_recount_title']}</h2></div>";
		$html .= "<div style='padding: 10px;'>
					{$this->lang->words['bestPhoto_recount_desc']}</div>";
		$html .= "<form id='adminform' name='adminform' method='post' action='{$this->settings['base_url']}t&do=save' enctype='multipart/form-data'>\n";
		$html .= "
		<div class='acp-actionbar'>
			<input type='submit' value='{$this->lang->words['bestPhoto_recount_button']}' class='realbutton' />
		</div>
		</form>
		";
        
        
       	$this->registry->output->html .= $html;
		$this->registry->getClass('output')->html_main .= $this->registry->getClass('output')
																			->global_template
																			->global_frame_wrapper();
		$this->registry->getClass('output')->sendOutput();
	}
}
