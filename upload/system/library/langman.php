<?php
class LangMan {
	protected   $_model,
				$_cache,
				$_dir_admin,
				$_dir_system,
				$_db_drivers = ['mpdo', 'mysqli'/* удалить */, 'mysql'];

	public  $menu = ['admin', 'system', 'db'],
			$menu_active = 'admin',
			$dir_active,
			$error_db_driver = false,
			$languages = [],
			$data = [];

	public function __construct(ControllerModuleLangMan $langman, $menu) {
		$this->_model = $langman->model_tool_langman;
		$this->_cache = $langman->langman_cache;

		$this->_dir_admin   = DIR_LANGUAGE;
		$this->_dir_system  = DIR_CATALOG . 'language/';

		if (!in_array(DB_DRIVER, $this->_db_drivers)) {
			$this->error_db_driver = true;
		} else {
			if (in_array($menu, $this->menu)) {
				$this->dir_active = ($menu == 'admin') ? $this->_dir_admin : $this->_dir_system;

				$this->menu_active = $menu;
			}

			$this->getLanguages();

			if ($this->menu_active == $this->menu[2]) {
				$this->getDataDB();
			}  else {
				$this->getDataFS();
			}
		}
	}

	protected function getLanguages() {
		if ($cache = $this->_cache->get('languages')) {
			$this->languages = $cache;
		} else {
			$languages = [];

			$dir_admin = new DirectoryIterator($this->_dir_admin);
			$dir_system = new DirectoryIterator($this->_dir_system);

			$languages_admin = [];

			foreach ($dir_admin as $language) {
				if ($language->isDir() && ! $language->isDot()) {
					$languages_admin[] = $language->getFilename();
				}
			}

			$languages_system = [];

			foreach ($dir_system as $language) {
				if ($language->isDir() && ! $language->isDot()) {
					$languages_system[] = $language->getFilename();
				}
			}

			$languages_fs = [];
			$languages_fs = array_unique(array_merge($languages_admin, $languages_system));

			if (count($diff = array_diff($languages_fs, $languages_admin))) {
				foreach ($diff as $dir) {
					if (! is_dir($this->_dir_admin . $dir)) {
						mkdir($this->_dir_admin . $dir, 0777, true);
					}
				}
			}

			if (count($diff = array_diff($languages_fs, $languages_system))) {
				foreach ($diff as $dir) {
					if (! is_dir($this->_dir_system . $dir)) {
						mkdir($this->_dir_system . $dir, 0777, true);
					}
				}
			}

			$langs = $this->_model->getLanguages();

			$languages_db = [];
			$id_db = 1;

			if ($langs) {
				foreach($langs as $lang) {
					if (isset($lang['directory']) && $lang['directory']) {
						$languages[] = $lang;
						$languages_db[] = $lang['directory'];

						if (array_search($lang['directory'], $languages_fs) === false) {
							$languages_fs[] = $lang['directory'];

							if (! is_dir($this->_dir_admin . $lang['directory'])) {
								$dir = $this->_dir_admin . end($languages_fs) . '/';
								mkdir($dir, 0777, true);
							}

							if (! is_dir($this->_dir_system . $lang['directory'])) {
								$dir = $this->_dir_system . end($languages_fs) . '/';
								mkdir($dir, 0777, true);
							}

							$this->_cache->delete();
						}

						if ($id_db <= $lang['id']) {
							$id_db = $lang['id'] + 1;
						}
					} else {
						$this->_model->deleteLanguage($lang['id']);
					}
				}
			}

			if (count($diff = array_diff($languages_fs, $languages_db))) {
				foreach ($diff as $lang) {
					$uc_lang = $this->utf8_ucfirst($lang);

					if ($this->_model->setLanguage($id_db, $uc_lang, $lang)) {
						$i = count($languages);
						$languages[$i] = [];

						$languages[$i]['id'] = $id_db;
						$languages[$i]['name'] = $uc_lang;
						$languages[$i]['directory'] = $lang;
						$languages[$i]['filename'] = $lang;


						$id_db++;
					}
				}
			}

			$this->languages = SplFixedArray::fromArray($languages);

			$this->_cache->set('languages', $this->languages);
		}
	}

	protected function getDataFS() {
		if ($cache = $this->_cache->get($this->menu_active)) {
			$this->data = $cache;
		}  else {
			$dir_active = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dir_active), RecursiveIteratorIterator::CHILD_FIRST);

			$data_temp = new stdClass;

			foreach ($dir_active as $fileInfo) {
				if (!$fileInfo->isFile()) {
						continue;
				} else {
					$file = $fileInfo->openFile();

					while(!$file->eof()){
						$line = $file->fgets();

						if(substr_count($line, '$')){
							$lang_dir    =   $dir_active->getSubIterator()->getSubPath();
							$depth      =   $dir_active->getDepth();

							$language   =   ($depth > 1)	? substr($lang_dir, 0, strpos($lang_dir, '\\'))		: $lang_dir;
							$dir        =   ($depth > 1)	? substr($lang_dir, strpos($lang_dir, '\\') + 1)	: 'index' ;
							$file_name  =   ($depth > 1)	? $fileInfo->getBasename('.php')					: 'index';

							$var        =   utf8_substr($line, utf8_strpos($line, "['") + 2, utf8_strpos($line, "']") - utf8_strpos($line, "['") - 2);
							$value      =   ltrim(utf8_substr($line, utf8_strpos($line, '='), utf8_strrpos($line, ';') - utf8_strpos($line, '=') - 1), "= \t'\"");

							$vg         =   utf8_substr($var, 0,  utf8_strpos($var, '_'));
							$vg         =   ($vg) ? $vg : $var;
							$var_group  =   "";
							switch($vg){
								case 'code' :;
								case 'direction' :;
								case 'date' :;
								case 'time' :;
								case 'decimal' :;
								case 'thousand' :
									$var_group = 'Locale'; break;
								default :
									$var_group = $this->utf8_ucfirst($vg);
							}

							if(!isset($data_temp->$dir)){
								$data_temp->$dir = new stdClass;
							}

							if(!isset($data_temp->$dir->$file_name)){
								$data_temp->$dir->$file_name = new stdClass;
							}

							if(!isset($data_temp->$dir->$file_name->$var_group)){
								$data_temp->$dir->$file_name->$var_group = new stdClass;
							}

							if (!isset($data_temp->$dir->$file_name->$var_group->$var)) {
								$obj = new stdClass;

								foreach($this->languages as $lang){
									$obj->{$lang['directory']} = '';
								}

								$obj->$language = (string)$value;

								$data_temp->$dir->$file_name->$var_group->$var = $obj;
							} else {
								$data_temp->$dir->$file_name->$var_group->$var->$language = (string)$value;
							}
						}
					}
				}
			}

			$data = [];

			foreach($data_temp as $dir => $files) {
				$directory = [];

				foreach($files as $file_name => $file) {
					$file->file_name = $file_name;
					$directory[] = $file;
				}

				usort($directory, [__CLASS__, 'cmpFiles']);
				array_unshift($directory, $dir);

				$directory = splFixedArray::fromArray($directory);

				if ($dir == 'index') {
					array_unshift($data, $directory);
				} else {
					$data[] = $directory;
				}
			}

			$this->data = splFixedArray::fromArray($data);

			$this->_cache->set($this->menu_active, $this->data);
		}
	}

	protected function getDataDB() {
		if ($cache = $this->_cache->get('db')) {
			$this->data = $cache;			
		} else {
			$tables = $this->_model->getTables();

			$data = new splFixedArray(count($tables));

			$i = 1;

			foreach($tables as $table) {
				if ($table['TABLE_NAME'] == 'language') {
					$data[0] = new stdClass;
					$data[0]->table_name = $table['TABLE_NAME'];
				} else {
					$data[$i] = new stdClass;
					$data[$i]->table_name = $table['TABLE_NAME'];
					$i++;
				}
			}

			foreach($data as $i => $table) {
				$result = $this->_model->getTablesData($table->table_name);

				if ($result) {
					foreach($result as $row) {
						$language_id = $row['language_id'];
						$j = 0;

						foreach($row as $column_name => $value) {
							if ($j == 0) {
								$id = $value;

								if (!isset($data[$i]->primary_column)) {
									$data[$i]->primary_column = $column_name;
								}

								if (!isset($data[$i]->{$data[$i]->primary_column})) {
									$data[$i]->{$data[$i]->primary_column} = [];
								}

								if (!isset($data[$i]->{$data[$i]->primary_column}[$value])) {
									$data[$i]->{$data[$i]->primary_column}[$id] = new stdClass;
								}
							} elseif ($i == 0) {
								foreach($this->languages as $language) {
									if ($language['id'] == $language_id) {
										$data[$i]->{$data[$i]->primary_column}[$id]->$column_name = $value;
									}
								}
							} elseif ($j != 1) {
								if (!isset($data[$i]->{$data[$i]->primary_column}[$id]->$column_name)) {
									$data[$i]->{$data[$i]->primary_column}[$id]->$column_name = new stdClass;
									
									foreach($this->languages as $language) {
										$data[$i]->{$data[$i]->primary_column}[$id]->$column_name->{$language['directory']} = '';
									}

								}

								if (!substr_count($column_name, '_id')) {
									foreach($this->languages as $language) {
										if ($language['id'] == $language_id) {
											$data[$i]->{$data[$i]->primary_column}[$id]->$column_name->{$language['directory']} = $value;
										}
									}
								} else {
									if ($value) {
										$val_id = $value;
									}

									foreach($this->languages as $language) {
										if ($language['id'] == $language_id) {
											$data[$i]->{$data[$i]->primary_column}[$id]->$column_name->{$language['directory']} = $val_id;
										}
									}
								}
							}

							$j++;	
						}
					}
				}
			
			}

			foreach($data as $i => $table) {
				if(isset($data[$i]->primary_column)) {
					$this->data[] = $data[$i];
				}
			}

			$this->data = splFixedArray::fromArray($this->data);

			$this->_cache->set('db', $this->data);
			
		}
	}

	protected function cmpFiles($obj1, $obj2) {
        $fn1 = strtolower($obj1->file_name);
        $fn2 = strtolower($obj2->file_name);
        if ($fn1 == $fn2) {
            return 0;
        }
        return ($fn1 > $fn2) ? +1 : -1;
    }

	public function getDBDrivers() {
		return implode(', ', $this->_db_drivers);
    }

	public function utf8_ucfirst($str) {
		$str = utf8_strtolower($str);

		$str = utf8_strtoupper(utf8_substr($str, 0, 1)) . utf8_substr($str, 1);

		return $str;
	}
}