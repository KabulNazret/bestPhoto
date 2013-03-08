<?php
if ( !defined( 'IN_IPB' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

$_PERM_CONFIG = array( 'Recount' );


class bestPhotoPermMappingRecount
{
        /**
         * Mapping of keys to columns
         *
         * @access      protected
         * @var         array
         */
        protected $mapping = array(
                                                                'view'          => 'perm_view',
                                                        );

        /**
         * Mapping of keys to names
         *
         * @access      protected
         * @var         array
         */
        protected $perm_names = array(
                                                                'view'          => 'View recount',
                                                        );

        /**
         * Mapping of keys to background colors for the form
         *
         * @access      protected
         * @var         array
         */
        protected $perm_colors = array(
                                                                'view'          => '#fff0f2',
                                                        );

        /**
         * Method to pull the key/column mapping
         *
         * @access      public
         * @return      array
         */
        public function getMapping()
        {
                return $this->mapping;
        }

        /**
         * Method to pull the key/name mapping
         *
         * @access      public
         * @return      array
         */
        public function getPermNames()
        {
                return $this->perm_names;
        }

        /**
         * Method to pull the key/color mapping
         *
         * @access      public
         * @return      array
         */
        public function getPermColors()
        {
                return $this->perm_colors;
        }

        /**
         * Retrieve the items that support permission mapping
         *
         * @access      public
         * @return      array
         */
        public function getPermItems()
        {
			$return = array();

			ipsRegistry::DB()->build( array( 'select'   => 'p.*',
											 'from'     => array( 'permission_index' => 'p' ),
											 'where'    => "app='bestPhoto' AND perm_type='recount'",
									)      );
			ipsRegistry::DB()->execute();

			$return[ 1 ] = array(	'title'     => 'View recount',
											'perm_view' => '',
							 );
			while ( $r = ipsRegistry::DB()->fetch() )
			{
				$return[ 1 ] = array(	'title'     => 'View recount',
												'perm_view' => $r['perm_view'],
									 );
			}

			return $return;
        }
}
