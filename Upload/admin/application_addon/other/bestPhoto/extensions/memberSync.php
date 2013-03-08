<?php

if ( ! defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

class forumsMemberSync
{
	/**
	 * Registry reference
	 *
	 * @var		object
	 */
	public $registry;

	/**
	 * CONSTRUCTOR
	 *
	 * @return	@e void
	 */
	public function __construct()
	{
		$this->registry = ipsRegistry::instance();
	}


	/**
	 * This method is called after a member account has been removed
	 *
	 * @param	string	$ids	SQL IN() clause
	 * @return	@e void
	 */
	//public function onDelete( $mids )
	//{
		/* Delete awards - note, we can't do this via internally, since we no longer have the member data */
	//	ipsRegistry::DB()->delete( 'jlogica_awards_awarded', "user_id" . $mids );
	//}

	/**
	 * This method is called after a member's account has been merged into another member's account
	 *
	 * @param	array	$member		Member account being kept
	 * @param	array	$member2	Member account being removed
	 * @return	@e void
	 */
	//public function onMerge( $member, $member2 )
	//{
	//	$this->DB->update( 'jlogica_awards_awarded', array( 'user_id' => $member['member_id'] ), 'user_id=' . $member2['member_id'] );
	//}

}
