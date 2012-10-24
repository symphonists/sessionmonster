<?php



	if( !defined( '__IN_SYMPHONY__' ) ) die('<h2>Error</h2><p>You cannot directly access this file</p>');



	Final Class eventSessionmonster_addgettosession extends Event
	{

		public static function about(){
			return array(
				'name' => 'Session Monster: Add GET variable to session',
				'author' => array('name' => 'Alistair Kearney',
					'website' => 'http://www.pointybeard.com',
					'email' => 'alistair@pointybeard.com'),
				'version' => '1.0',
				'release-date' => '2008-05-12',
			);
		}

		public function load(){
			if( is_array( $_GET ) && !empty($_GET) ) return $this->__trigger();

			return null;
		}

		public static function documentation(){
			return '
		
			<h3>Usage</h3>
			
			<p>Any GET parameters, i.e key & value pairs in the URL such as <code>?var1=hello</code>, will be added to the session, which is then available via the "Show Session Parameter" Data Source. XML after adding or removing variables look like so:</p>

			<pre class="XML"><code>&lt;session-monster>
	&lt;foo>one&lt;/foo>
	&lt;bar>
		&lt;baz>two&lt;/baz>
		&lt;item-0>three&lt;/item-0>
		&lt;item-2>four&lt;/item-2>
	&lt;/bar>
&lt;/session-monster></code></pre>

			<p>The corresponding URL for the above XML looks like <code>?foo=one&amp;bar[baz]=two&amp;bar[]=three&bar[2]=four</code>. Notice that to remove an item, you set it as empty.</p>
			';

		}

		protected function __trigger(){
			session_start();

			$exclude = array('symphony-page', 'Debug', 'debug', 'profile', 'XDEBUG_SESSION_START', 'fl-language', 'fl-region');

			foreach( $_GET as $key => $val ){
				if( !in_array( $key, $exclude ) ){
					$this->process( $_SESSION['sessionmonster'], $key, $val );
				}
			}

			if( is_array( $_SESSION['sessionmonster'] ) ){
				foreach( $_SESSION['sessionmonster'] as $key => $val ){
					$this->addParam('sessionmonster', $key, $val);
				}
			}

			return '';
		}

		private function process(&$result, $key, $val){

			// go deeper
			if( is_array( $val ) ){
				foreach( $val as $k => $v ){
					$this->process( $result[$key], $k, $v );
				}
			}

			// add
			else if( !empty($val) ){
				$result[$key] = $val;
			}

			// remove
			else if( is_array( $result ) ){
				if( isset($result[$key]) ){
					unset($result[$key]);
				}
			}
		}

		private function addParam($prefix, $key, $val){
			$pref = "$prefix-$key";

			if( is_array( $val ) ){
				foreach( $val as $k => $v ){
					$this->addParam( "$pref-arr", $k, $v );
				}
			}
			else
				Frontend::Page()->_param[$pref] = $val;
		}

	}

