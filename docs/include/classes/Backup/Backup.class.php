<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$

require(AT_INCLUDE_PATH.'classes/zipfile.class.php');

define('NUMBER',	1);
define('TEXT',		2);

/**
* Backup
* Class for creating and managing course backups
* @access	public
* @author	Joel Kronenberg
* @package	Backup
*/
class Backup {

	// private
	// number of backups in the backup dir
	var $num_backups;

	// private
	// the current course id
	var $course_id;

	// private
	// where to store the backup
	var $backup_dir;

	// private
	// db handler
	var $db;

	// the backup zipfile Object
	var $zipfile;

	// the timestamp for the zip files
	var $timestamp;

	// constructor
	function Backup(&$db, $course_id = 0) {

		$this->db = $db;

		$this->setCourseID($course_id);
	}

	function setCourseID($course_id) {
		$this->course_id  = $course_id;
		$this->backup_dir = AT_BACKUP_DIR . $course_id . DIRECTORY_SEPARATOR;
	}


	function generateFileName($title, $timestamp) {
		$title = str_replace(' ',  '_', $title);
		$title = str_replace('%',  '',  $title);
		$title = str_replace('\'', '',  $title);
		$title = str_replace('"',  '',  $title);
		$title = str_replace('`',  '',  $title);

		$title .= '_' . date('d_M_y', $timestamp) . '.zip';

		return $title;
	}

	// private
	// quote $line so that it's safe to save as a CSV field
	function quoteCSV($line) {
		$line = str_replace('"', '""', $line);

		$line = str_replace("\n", '\n', $line);
		$line = str_replace("\r", '\r', $line);
		$line = str_replace("\x00", '\0', $line);

		return '"'.$line.'"';
	}
	
	// private
	// add this table to the backup
	function saveCSV($name, $sql, $fields) {
		$content = '';
		$num_fields = count($fields);

		$result = mysql_query($sql, $this->db);
		while ($row = mysql_fetch_assoc($result)) {
			for ($i=0; $i< $num_fields; $i++) {
				if ($fields[$i][1] == NUMBER) {
					$content .= $row[$fields[$i][0]] . ',';
				} else {
					$content .= $this->quoteCSV($row[$fields[$i][0]]) . ',';
				}
			}
			$content = substr($content, 0, strlen($content)-1);
			$content .= "\n";
		}
		@mysql_free_result($result); 

		// NOTE: probably want to store time() in a variable so all files get the same time stamp...

		$this->zipfile->add_file($content, $name, $this->timestamp);
	}

	// public
	// NOTE: should the create() deal with saving it to disk as well? or should it be general to just create it, and not actually
	// responsible for where to save it? (write a diff method to save it after)
	function create($description) {
		global $addslashes, $backup_tables;

		$this->timestamp = time();

		$this->zipfile =& new zipfile();
		if (is_dir(AT_CONTENT_DIR . $this->course_id)) {
			$this->zipfile->add_dir(AT_CONTENT_DIR . $this->course_id . DIRECTORY_SEPARATOR, 'content/');
		}

		$package_identifier = VERSION."\n\n\n".'Do not change the first line of this file it contains the ATutor version this backup was created with.';
		$this->zipfile->add_file($package_identifier, 'atutor_backup_version', $this->timestamp);

		// loop through all the tables/fields to save to the zip file:
		// ....
		foreach ($backup_tables as $name => $info) {
			$this->saveCSV($name, $info['sql'], $info['fields']);
		}

		// if no errors:

		$this->zipfile->close();

		$system_file_name = md5(time());

		$fp = fopen(AT_BACKUP_DIR . $this->course_id . DIRECTORY_SEPARATOR . $system_file_name . '.zip', 'wb+');
		fwrite($fp, $this->zipfile->get_file($backup_course_title));

		$row['description']      = $addslashes($description);
		$row['contents']         = ' some content in some format';
		$row['system_file_name'] = $system_file_name;

		$this->add($row);

		return TRUE;
	}

	// public
	function upload() {

	}

	// private
	// adds a backup to the database
	function add($row) {
		$file_size = $this->zipfile->get_size();

		// call getNumBackups() first
		$sql = "INSERT INTO ".TABLE_PREFIX."backups VALUES (0, $this->course_id, NOW(), '$row[description]', $file_size, '$row[system_file_name]', '$row[contents]')";

		mysql_query($sql, $this->db);
		//debug($sql);
	}

	// public
	// get number of backups
	function getNumAvailable() {
		// use $num_backups, if not set then do a COUNT(*) on the table
	}

	// public
	// get list of backups
	function getAvailableList($course_id) {
		$backup_list = array();

		$sql	= "SELECT *, UNIX_TIMESTAMP(date) AS date_timestamp FROM ".TABLE_PREFIX."backups WHERE course_id=$course_id ORDER BY date";
		$result = mysql_query($sql, $this->db);
		while ($row = mysql_fetch_assoc($result)) {
			$backup_list[] = $row;
		}

		$this->num_backups = count($backup_list);

		return $backup_list;
	}

	// public
	function fetch() { // or download()

	}
}

class RestoreBackup {

	
}

/* content.csv */
	$fields = array();
	$fields[0] = array('content_id',		NUMBER);
	$fields[1] = array('content_parent_id', NUMBER);
	$fields[2] = array('ordering',			NUMBER);
	$fields[3] = array('last_modified',		TEXT);
	$fields[4] = array('revision',			NUMBER);
	$fields[5] = array('formatting',		NUMBER);
	$fields[6] = array('release_date',		TEXT);
	$fields[7] = array('keywords',			TEXT);
	$fields[8] = array('content_path',		TEXT);
	$fields[9] = array('title',				TEXT);
	$fields[10] = array('text',				TEXT);

	$backup_tables['content.csv']['sql'] = 'SELECT * FROM '.TABLE_PREFIX.'content WHERE course_id='.$_SESSION['course_id'].' ORDER BY content_parent_id, ordering';
	$backup_tables['content.csv']['fields'] = $fields;

/* forums.csv */
	$fields = array();
	$fields[] = array('title',			TEXT);
	$fields[] = array('description',	TEXT);
	/* three fields added for v1.4 */
	$fields[] = array('num_topics',		NUMBER);
	$fields[] = array('num_posts',		NUMBER);
	$fields[] = array('last_post',		NUMBER);

	$backup_tables['forums.csv']['sql'] = 'SELECT * FROM '.TABLE_PREFIX.'forums WHERE course_id='.$_SESSION['course_id'].' ORDER BY forum_id ASC';
	$backup_tables['forums.csv']['fields'] = $fields;

/* related_content.csv */
	$fields[0] = array('content_id',			NUMBER);
	$fields[1] = array('related_content_id',	NUMBER);

	$backup_tables['related_content.csv']['sql'] = 'SELECT R.content_id, R.related_content_id 
													FROM '.TABLE_PREFIX.'related_content R, '.TABLE_PREFIX.'content C 
													WHERE C.course_id='.$_SESSION['course_id'].' AND R.content_id=C.content_id ORDER BY R.content_id ASC';
	$fields = array();
	$backup_tables['forums.csv']['fields'] = $fields;


/* glossary.csv */
	$fields = array();
	$fields[0] = array('word_id',			NUMBER);
	$fields[1] = array('word',				TEXT);
	$fields[2] = array('definition',		TEXT);
	$fields[3] = array('related_word_id',	NUMBER);

	$backup_tables['glossary.csv']['sql'] = 'SELECT * FROM '.TABLE_PREFIX.'glossary WHERE course_id='.$_SESSION['course_id'].' ORDER BY word_id ASC';
	$backup_tables['glossary.csv']['fields'] = $fields;

/* resource_categories.csv */
	$fields = array();
	$fields[0] = array('CatID',		NUMBER);
	$fields[1] = array('CatName',	TEXT);
	$fields[2] = array('CatParent', NUMBER);

	$backup_tables['resource_categories.csv']['sql'] = 'SELECT * FROM '.TABLE_PREFIX.'resource_categories WHERE course_id='.$_SESSION['course_id'].' ORDER BY CatID ASC';
	$backup_tables['resource_categories.csv']['fields'] = $fields;

/* resource_links.csv */
	$fields = array();
	$fields[0] = array('CatID',			NUMBER);
	$fields[1] = array('Url',			TEXT);
	$fields[2] = array('LinkName',		TEXT);
	$fields[3] = array('Description',	TEXT);
	$fields[4] = array('Approved',		NUMBER);
	$fields[5] = array('SubmitName',	TEXT);
	$fields[6] = array('SubmitEmail',	TEXT);
	$fields[7] = array('SubmitDate',	TEXT);
	$fields[8] = array('hits',			NUMBER);

	$backup_tables['resource_links.csv']['sql'] = 'SELECT L.* FROM '.TABLE_PREFIX.'resource_links L, '.TABLE_PREFIX.'resource_categories C 
													WHERE C.course_id='.$_SESSION['course_id'].' AND L.CatID=C.CatID 
													ORDER BY LinkID ASC';

	$backup_tables['resource_links.csv']['fields'] = $fields;

/* news.csv */
	$fields = array();
	$fields[0] = array('date',		TEXT);
	$fields[1] = array('formatting',NUMBER);
	$fields[2] = array('title',		TEXT);
	$fields[3] = array('body',		TEXT);

	$backup_tables['news.csv']['sql'] = 'SELECT * FROM '.TABLE_PREFIX.'news WHERE course_id='.$_SESSION['course_id'].' ORDER BY news_id ASC';
	$backup_tables['news.csv']['fields'] = $fields;
	
/* tests.csv */
	$fields = array();
	$fields[] = array('test_id',			NUMBER);
	$fields[] = array('title',				TEXT);
	$fields[] = array('format',				NUMBER);
	$fields[] = array('start_date',			TEXT);
	$fields[] = array('end_date',			TEXT);
	$fields[] = array('randomize_order',	NUMBER);
	$fields[] = array('num_questions',		NUMBER);
	$fields[] = array('instructions',		TEXT);

	/* four fields added for v1.4 */
	$fields[] = array('content_id',		NUMBER);
	$fields[] = array('automark',		NUMBER);
	$fields[] = array('random',			NUMBER);
	$fields[] = array('difficulty',		NUMBER);

	/* field added for v1.4.2 */
	$fields[] = array('num_takes',		NUMBER);
	$fields[] = array('anonymous',		NUMBER);

	$backup_tables['tests.csv']['sql'] = 'SELECT * FROM '.TABLE_PREFIX.'tests WHERE course_id='.$_SESSION['course_id'].' ORDER BY test_id ASC';
	$backup_tables['tests.csv']['fields'] = $fields;

/* tests_questions.csv */
	$fields = array();
	$fields[] = array('test_id',			NUMBER);
	$fields[] = array('ordering',			NUMBER);
	$fields[] = array('type',				NUMBER);
	$fields[] = array('weight',				NUMBER);
	$fields[] = array('required',			NUMBER);
	$fields[] = array('feedback',			TEXT);
	$fields[] = array('question',			TEXT);
	$fields[] = array('choice_0',			TEXT);
	$fields[] = array('choice_1',			TEXT);
	$fields[] = array('choice_2',			TEXT);
	$fields[] = array('choice_3',			TEXT);
	$fields[] = array('choice_4',			TEXT);
	$fields[] = array('choice_5',			TEXT);
	$fields[] = array('choice_6',			TEXT);
	$fields[] = array('choice_7',			TEXT);
	$fields[] = array('choice_8',			TEXT);
	$fields[] = array('choice_9',			TEXT);
	$fields[] = array('answer_0',			NUMBER);
	$fields[] = array('answer_1',			NUMBER);
	$fields[] = array('answer_2',			NUMBER);
	$fields[] = array('answer_3',			NUMBER);
	$fields[] = array('answer_4',			NUMBER);
	$fields[] = array('answer_5',			NUMBER);
	$fields[] = array('answer_6',			NUMBER);
	$fields[] = array('answer_7',			NUMBER);
	$fields[] = array('answer_8',			NUMBER);
	$fields[] = array('answer_9',			NUMBER);
	$fields[] = array('answer_size',		NUMBER);
	$fields[] = array('content_id',			NUMBER);	/* one field added for v1.4 */

	$backup_tables['tests_questions.csv']['sql'] = 'SELECT * FROM '.TABLE_PREFIX.'tests_questions WHERE course_id='.$_SESSION['course_id'].' ORDER BY test_id ASC';
	$backup_tables['tests_questions.csv']['fields'] = $fields;

/* polls.csv */
	$fields = array();
	$fields[0] = array('question',		TEXT);
	$fields[1] = array('created_date',	TEXT);
	$fields[2] = array('choice1',		TEXT);
	$fields[3] = array('choice2',		TEXT);
	$fields[4] = array('choice3',		TEXT);
	$fields[5] = array('choice4',		TEXT);
	$fields[6] = array('choice5',		TEXT);
	$fields[7] = array('choice6',		TEXT);
	$fields[8] = array('choice7',		TEXT);

	$backup_tables['polls.csv']['sql'] = 'SELECT * FROM '.TABLE_PREFIX.'polls WHERE course_id='.$_SESSION['course_id'];
	$backup_tables['polls.csv']['fields'] = $fields;

	unset($fields);
?>