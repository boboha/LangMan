<?php
class ModelToolLangMan extends Model {

	public function getLanguages() {
		$result = $this->db->query(	"SELECT `language_id` AS id, name, directory, filename
										FROM `language`"
		);

		return ($result->num_rows) ? $result->rows : false;
	}

	public function setLanguage($id, $uc_lang, $lang) {
		return $this->db->query(	"INSERT INTO `language` (`language_id`, `name`, `directory`, `filename`, `status`)
										VALUES ('$id', '$uc_lang', '$lang', '$lang', 0)");
	}

	public function deleteLanguage($id) {
		return $this->db->query(	"DELETE FROM `language`
										WHERE `language_id` = " . $id . "");
	}

	public function getTables() {
		/* Если Mysql */
		$result = $this->db->query(	"SELECT  `TABLE_NAME`
										FROM `INFORMATION_SCHEMA`.`COLUMNS`
										WHERE `TABLE_SCHEMA` = DATABASE()
											AND `TABLE_NAME` NOT IN ('order')
											AND `COLUMN_NAME` = 'language_id'");
		/* Если Mysql */

		return ($result->num_rows) ? $result->rows : false;
	}

	public function getTablesData($table_name) {
		$result = $this->db->query("SELECT * FROM $table_name");

		return ($result->num_rows) ? $result->rows : false;
	}
}