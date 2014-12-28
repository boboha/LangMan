<?php
class LangManCache {
	protected	$_directory,
				$_session;

	public function __construct() {
		$this->_directory = DIR_CACHE . 'Langman/';
		$this->_session = $this->_directory . session_id();

		if (! is_dir($this->_directory)) {
			mkdir($this->_directory, 0777, true);
		}

		if (! $this->glob('session')) {
			$this->delete();
			
		}
	}

	public function get($key) {
		$file = $this->glob($key);

		return ($file) ? unserialize(bzdecompress(file_get_contents($file))) : false;
	}

  	public function set($key, $value) {
		$file = $this->_directory . $key . '.bz2';

		file_put_contents($file, bzcompress(serialize($value)));
  	}
	
  	public function delete($key = '') {
		if (!$key) {
			$files = $this->glob($key);

			if ($files) {
				foreach($files as $file) {
					unlink($file);
				}
			}

			$this->createSession();
		} elseif ($file = $this->glob($key)) {
    		unlink($file);
		}
  	}

	protected function glob($key = '') {
		if (!$key) {
			return glob($this->_directory . '*');

		} elseif ('session' == $key) {
			return file_exists($this->_session);

		} elseif (file_exists($this->_directory . $key . '.bz2')) {
			return $this->_directory . $key . '.bz2';

		} elseif (file_exists($this->_directory . $key . '.*')) {
			return $this->_directory . $key . '.*';
		}

		return false;
	}

	protected function createSession() {
		file_put_contents($this->_session, '');
	}
}