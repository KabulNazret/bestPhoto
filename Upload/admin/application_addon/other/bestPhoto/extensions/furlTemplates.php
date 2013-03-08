<?php

if ( !defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

$_SEOTEMPLATES = array( 'app=bestPhoto' => array( 'app'			=> 'bestPhoto',
												'allowRedirect' => 1,
												'out'           => array( '/app=bestPhoto/i', 'bestPhoto/' ),
												'in'            => array( 'regex'   => "#^/bestPhoto(/|$|\?)#i", 'matches' => array( array( 'app', 'bestPhoto' ) ) ) )
					  );
