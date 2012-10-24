<?php

	Final Class datasourceSessionmonster_showsessionparam Extends DataSource
	{

		function about(){
			return array(
				'name' => 'Session Monster: Show Session Parameters',
				'author' => array(
					'name' => 'Symphony Team'
				),
				'version' => '1.0',
				'release-date' => '2012-10-23'
			);
		}


		public function execute(){
			session_start();

			if( !is_array( $_SESSION['sessionmonster'] ) || empty($_SESSION['sessionmonster']) ) return null;

			$result = new XMLElement('session-monster');

			foreach( $_SESSION['sessionmonster'] as $key => $val ){
				$this->process( $result, $key, $val );
			}

			return $result;
		}

		private function process(XMLElement &$result, $key, $val){
			$elem = new XMLElement((is_int( $key ) ? "item-" : '').$key);

			// go deeper
			if( is_array( $val ) ){
				foreach( $val as $k => $v ){
					$this->process( $elem, $k, $v );
				}
			}

			// add
			else{
				$elem->setValue( General::sanitize( $val ) );
			}

			$result->appendChild( $elem );
		}
	}
