<?php

	if(!defined('__IN_SYMPHONY__')) die('<h2>Error</h2><p>You cannot directly access this file</p>');

	Final Class eventSessionmonster_addgettosession extends Event {

		public static function about() {
					
			return array(
				'name' => 'Session Monster: Add GET variable to session',
				'author' => array('name' => 'Symphony Community',
					'website' => 'https://github.com/symphonists'
				),
				'version' => '1.1',
				'release-date' => '2013-03-24',
			);
		}
				
		public function load() {

			if(is_array($_SESSION[__SYM_COOKIE_PREFIX__ . '-sessionmonster'])) {
				foreach($_SESSION[__SYM_COOKIE_PREFIX__ . '-sessionmonster'] as $key => $val){
					if (!in_array($key, array('symphony-page', 'debug', 'profile'))) {
						Frontend::instance()->Page()->_param['sessionmonster-'.$key] = $val;
					}
				}
			}

			if(is_array($_GET) && !empty($_GET)) return $this->__trigger();
		}

		public static function documentation() {
			
			return '
		
			<h3>Usage</h3>
			
			<p>Any GET parameters, i.e key & value pairs in the URL such as <code>?var1=hello</code>, will be added to the session, which is then available via the page parameter pool. XML after adding or removing variables look like so:</p>
			
			<pre class="XML"><code>&lt;session-monster>
	&lt;item name="var1" action="added">one&lt;/item>
	&lt;item name="var2" action="removed"/>
&lt;/session-monster></code></pre>
			
			<p>The corresponding URL for the above XML looks like <code>?var1=one&amp;var2=</code>. Notice that to remove an item, you set it as empty.</p>
			
			';

		}
		
		protected function __trigger() {
			
			session_start();

			$xml = new XMLElement('session-monster');

			$count = 0;
			
			foreach($_GET as $key => $val) {
				if(!in_array($key, array('symphony-page', 'debug', 'profile'))) {

					$_SESSION[__SYM_COOKIE_PREFIX__ . '-sessionmonster'][$key] = $val;
					$xml->appendChild(
						new XMLElement('item', $val, array(
							'name' => $key,
							'action' => (strlen($val) > 0 ? 'added' : 'removed')
						))
					);

					$count++;
				}
			}

			return ($count == 0 ? NULL : $xml);
			
		}
	}