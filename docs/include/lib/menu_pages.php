<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$

if (!defined('AT_INCLUDE_PATH')) { exit; }

define('AT_NAV_PUBLIC', 1);
define('AT_NAV_START',  2);
define('AT_NAV_COURSE', 3);
define('AT_NAV_HOME',   4);
define('AT_NAV_ADMIN',  5);

/*
	5 sections: public, my_start_page, course, admin, home
*/

$_pages[AT_NAV_ADMIN]  = array('admin/index.php',  'admin/users.php',   'admin/courses.php');
$_pages[AT_NAV_PUBLIC] = array('registration.php', 'browse.php',        'login.php',             'password_reminder.php');
$_pages[AT_NAV_START]  = array('users/index.php',  'users/profile.php', 'users/preferences.php', 'users/inbox.php');
$_pages[AT_NAV_COURSE] = array('index.php');
$_pages[AT_NAV_HOME]   = array();

if ($_SESSION['course_id']) {
	$main_links = $home_links = $side_menu = array();

	if ($system_courses[$_SESSION['course_id']]['main_links']) {
			$main_links = explode('|', $system_courses[$_SESSION['course_id']]['main_links']);
			$_pages[AT_NAV_COURSE] = array_merge($_pages[AT_NAV_COURSE], $main_links);
	}

	if ($system_courses[$_SESSION['course_id']]['home_links']) {
		$home_links = explode('|', $system_courses[$_SESSION['course_id']]['home_links']);
		$_pages[AT_NAV_HOME] = array_merge($_pages[AT_NAV_HOME], $home_links);
	}

//	debug($_pages[AT_NAV_HOME]);

	if (authenticate(AT_PRIV_ADMIN, AT_PRIV_RETURN)) {
		$_pages[AT_NAV_COURSE][] = 'tools/index.php';
	}
}

/* admin pages */

$_pages['admin/index.php']['title']    = _AT('configuration');
$_pages['admin/index.php']['parent']   = AT_NAV_ADMIN;
$_pages['admin/index.php']['children'] = array('admin/admins/my_edit.php', 'admin/config_edit.php', 'admin/language.php', 'admin/themes/index.php', 'admin/error_logging.php');

	$_pages['admin/admins/my_edit.php']['title']    = _AT('edit_account');
	$_pages['admin/admins/my_edit.php']['parent']   = 'admin/index.php';

	$_pages['admin/config_edit.php']['title']    = _AT('system_preferences');
	$_pages['admin/config_edit.php']['parent']   = 'admin/index.php';

	$_pages['admin/fix_content.php']['title']    = _AT('fix_content_ordering');
	$_pages['admin/fix_content.php']['parent']   = 'admin/index.php';

	$_pages['admin/language.php']['title']    = _AT('languages');
	$_pages['admin/language.php']['parent']   = 'admin/index.php';

	$_pages['admin/themes/index.php']['title']    = _AT('themes');
	$_pages['admin/themes/index.php']['parent']   = 'admin/index.php';
	//$_pages['admin/themes/index.php']['children'] = array('admin/themes/delete.php');

	$_pages['admin/themes/delete.php']['title']    = _AT('delete');
	$_pages['admin/themes/delete.php']['parent']   = 'admin/themes/index.php';

	$_pages['admin/error_logging.php']['title']    = _AT('error_logging');
	$_pages['admin/error_logging.php']['parent']   = 'admin/index.php';

	$_pages['admin/error_logging_details.php']['title']    = _AT('viewing_profile_bugs');
	$_pages['admin/error_logging_details.php']['parent']   = 'admin/index.php';

$_pages['admin/users.php']['title']    = _AT('users');
$_pages['admin/users.php']['parent']   = AT_NAV_ADMIN;
$_pages['admin/users.php']['children'] = array('admin/create_user.php', 'admin/admin_email.php', 'admin/admins/index.php');

	$_pages['admin/admin_email.php']['title']    = _AT('admin_email');
	$_pages['admin/admin_email.php']['parent']   = 'admin/users.php';

	$_pages['admin/create_user.php']['title']    = _AT('create_user');
	$_pages['admin/create_user.php']['parent']   = 'admin/users.php';

	$_pages['admin/edit_user.php']['title']    = _AT('edit_user');
	$_pages['admin/edit_user.php']['parent']   = 'admin/users.php';

	$_pages['admin/profile.php']['title']    = _AT('profile');
	$_pages['admin/profile.php']['parent']   = 'admin/users.php';

	$_pages['admin/admin_delete.php']['title']    = _AT('delete_user');
	$_pages['admin/admin_delete.php']['parent']   = 'admin/users.php';

	$_pages['admin/admins/index.php']['title']    = _AT('administrators');
	$_pages['admin/admins/index.php']['parent']   = 'admin/users.php';
	$_pages['admin/admins/index.php']['children']   = array('admin/admins/create.php', 'admin/admins/log.php');

		$_pages['admin/admins/log.php']['title']    = _AT('admin_log');
		$_pages['admin/admins/log.php']['parent']   = 'admin/admins/index.php';
		$_pages['admin/admins/log.php']['children']   = array('admin/admins/reset_log.php');

			$_pages['admin/admins/reset_log.php']['title']    = _AT('reset_log');
			$_pages['admin/admins/reset_log.php']['parent']   = 'admin/admins/log.php';

			$_pages['admin/admins/detail_log.php']['title']    = _AT('details');
			$_pages['admin/admins/detail_log.php']['parent']   = 'admin/admins/log.php';

		$_pages['admin/admins/create.php']['title']    = _AT('create_admin');
		$_pages['admin/admins/create.php']['parent']   = 'admin/admins/index.php';

		$_pages['admin/admins/edit.php']['title']    = _AT('edit_admin');
		$_pages['admin/admins/edit.php']['parent']   = 'admin/admins/index.php';

		$_pages['admin/admins/delete.php']['title']    = _AT('delete_admin');
		$_pages['admin/admins/delete.php']['parent']   = 'admin/admins/index.php';


$_pages['admin/courses.php']['title']    = _AT('courses');
$_pages['admin/courses.php']['parent']   = AT_NAV_ADMIN;
$_pages['admin/courses.php']['children']   = array('admin/create_course.php', 'admin/backup/index.php', 'admin/forums.php', 'admin/course_categories.php');

	$_pages['admin/delete_course.php']['title']    = _AT('delete_course');
	$_pages['admin/delete_course.php']['parent']   = 'admin/courses.php';

	$_pages['admin/instructor_login.php']['title']    = _AT('view');
	$_pages['admin/instructor_login.php']['parent']   = 'admin/courses.php';

	$_pages['admin/edit_course.php']['title']    = _AT('course_properties');
	$_pages['admin/edit_course.php']['parent']   = 'admin/courses.php';

	$_pages['admin/create_course.php']['title']    = _AT('create_course');
	$_pages['admin/create_course.php']['parent']   = 'admin/courses.php';

	$_pages['admin/backup/index.php']['title']    = _AT('backups');
	$_pages['admin/backup/index.php']['parent']   = 'admin/courses.php';
	$_pages['admin/backup/index.php']['children'] = array('admin/backup/create.php');

		$_pages['admin/backup/create.php']['title']    = _AT('create_backup');
		$_pages['admin/backup/create.php']['parent']   = 'admin/backup/index.php';
	
		// this item is a bit iffy:
		$_pages['admin/backup/restore.php']['title']    = _AT('restore');
		$_pages['admin/backup/restore.php']['parent']   = 'admin/backup/index.php';

		$_pages['admin/backup/delete.php']['title']    = _AT('delete');
		$_pages['admin/backup/delete.php']['parent']   = 'admin/backup/index.php';

		$_pages['admin/backup/edit.php']['title']    = _AT('edit');
		$_pages['admin/backup/edit.php']['parent']   = 'admin/backup/index.php';


	$_pages['admin/forums.php']['title']    = _AT('forums');
	$_pages['admin/forums.php']['parent']   = 'admin/courses.php';
	$_pages['admin/forums.php']['children'] = array('admin/forum_add.php');

		$_pages['admin/forum_add.php']['title']    = _AT('create_forum');
		$_pages['admin/forum_add.php']['parent']   = 'admin/forums.php';

		$_pages['admin/forum_edit.php']['title']    = _AT('edit_forum');
		$_pages['admin/forum_edit.php']['parent']   = 'admin/forums.php';

		$_pages['admin/forum_delete.php']['title']    = _AT('delete_forum');
		$_pages['admin/forum_delete.php']['parent']   = 'admin/forums.php';

	$_pages['admin/course_categories.php']['title']    = _AT('cats_categories');
	$_pages['admin/course_categories.php']['parent']   = 'admin/courses.php';
	$_pages['admin/course_categories.php']['children'] = array('admin/create_category.php');

		$_pages['admin/create_category.php']['title']    = _AT('create_category');
		$_pages['admin/create_category.php']['parent']   = 'admin/course_categories.php';

		$_pages['admin/edit_category.php']['title']    = _AT('edit_category');
		$_pages['admin/edit_category.php']['parent']   = 'admin/course_categories.php';

		$_pages['admin/delete_category.php']['title']    = _AT('delete_category');
		$_pages['admin/delete_category.php']['parent']   = 'admin/course_categories.php';


/* public pages */
$_pages['registration.php']['title']    = _AT('register');
$_pages['registration.php']['parent']   = AT_NAV_PUBLIC;

$_pages['browse.php']['title']    = _AT('browse_courses');
$_pages['browse.php']['parent']   = AT_NAV_PUBLIC;

$_pages['login.php']['title']    = _AT('login');
$_pages['login.php']['parent']   = AT_NAV_PUBLIC;

$_pages['password_reminder.php']['title']    = _AT('password_reminder');
$_pages['password_reminder.php']['parent']   = AT_NAV_PUBLIC;

$_pages['logout.php']['title']    = _AT('logout');
$_pages['logout.php']['parent']   = AT_NAV_PUBLIC;

/* my start page pages */
$_pages['users/index.php']['title']    = _AT('my_courses');
$_pages['users/index.php']['parent']   = AT_NAV_START;
$_pages['users/index.php']['children'] = array('users/browse.php', 'users/create_course.php');
	
	$_pages['users/browse.php']['title']  = _AT('browse_courses');
	$_pages['users/browse.php']['parent'] = 'users/index.php';
	
	$_pages['users/create_course.php']['title']  = _AT('create_course');
	$_pages['users/create_course.php']['parent'] = 'users/index.php';

$_pages['users/profile.php']['title']    = _AT('profile');
$_pages['users/profile.php']['parent']   = AT_NAV_START;
	
$_pages['users/preferences.php']['title']  = _AT('preferences');
$_pages['users/preferences.php']['parent'] = AT_NAV_START;

$_pages['users/inbox.php']['title']    = _AT('inbox');
$_pages['users/inbox.php']['parent']   = AT_NAV_START;
$_pages['users/inbox.php']['children'] = array('users/send_message.php');

	$_pages['users/send_message.php']['title']  = _AT('send_message');
	$_pages['users/send_message.php']['parent'] = 'users/inbox.php';

/* course pages */
$_pages['index.php']['title']  = _AT('home');
$_pages['index.php']['parent'] = AT_NAV_COURSE;

$_pages['tools/index.php']['title']    = _AT('manage');
$_pages['tools/index.php']['parent']   = AT_NAV_COURSE;

	$_pages['tools/polls/index.php']['title']  = _AT('polls');
	$_pages['tools/polls/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/polls/index.php']['children'] = array('tools/polls/add.php');

		$_pages['tools/polls/add.php']['title']  = _AT('add_poll');
		$_pages['tools/polls/add.php']['parent'] = 'tools/polls/index.php';

		$_pages['tools/polls/edit.php']['title']  = _AT('edit_poll');
		$_pages['tools/polls/edit.php']['parent'] = 'tools/polls/index.php';

		$_pages['tools/polls/delete.php']['title']  = _AT('delete_poll');
		$_pages['tools/polls/delete.php']['parent'] = 'tools/polls/index.php';


	$_pages['tools/filemanager/index.php']['title']  = _AT('file_manager');
	$_pages['tools/filemanager/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/filemanager/index.php']['children'] = array('tools/filemanager/new.php');

		$_pages['tools/filemanager/new.php']['title']  = _AT('create_new_file');
		$_pages['tools/filemanager/new.php']['parent'] = 'tools/filemanager/index.php';

		$_pages['tools/filemanager/zip.php']['title']  = _AT('zip_file_manager');
		$_pages['tools/filemanager/zip.php']['parent'] = 'tools/filemanager/index.php';

		$_pages['tools/filemanager/rename.php']['title']  = _AT('rename');
		$_pages['tools/filemanager/rename.php']['parent'] = 'tools/filemanager/index.php';

		$_pages['tools/filemanager/move.php']['title']  = _AT('move');
		$_pages['tools/filemanager/move.php']['parent'] = 'tools/filemanager/index.php';

		$_pages['tools/filemanager/edit.php']['title']  = _AT('edit');
		$_pages['tools/filemanager/edit.php']['parent'] = 'tools/filemanager/index.php';

		$_pages['tools/filemanager/delete.php']['title']  = _AT('delete');
		$_pages['tools/filemanager/delete.php']['parent'] = 'tools/filemanager/index.php';

	$_pages['tools/course_stats.php']['title']  = _AT('statistics');
	$_pages['tools/course_stats.php']['parent'] = 'tools/index.php';

	$_pages['tools/course_properties.php']['title']    = _AT('properties');
	$_pages['tools/course_properties.php']['parent']   = 'tools/index.php';
	$_pages['tools/course_properties.php']['children'] = array('tools/delete_course.php');

		$_pages['tools/delete_course.php']['title']  = _AT('delete_course');
		$_pages['tools/delete_course.php']['parent'] = 'tools/course_properties.php';

	$_pages['sitemap.php']['title']  = _AT('sitemap');
	$_pages['sitemap.php']['parent'] = 'index.php';
	$_pages['sitemap.php']['img'] = 'images/home-site_map.gif';

	$_pages['tools/modules.php']['title']  = _AT('sections');
	$_pages['tools/modules.php']['parent'] = 'tools/index.php';
	$_pages['tools/modules.php']['children'] = array('tools/side_menu.php');

		$_pages['tools/side_menu.php']['title']  = _AT('side_menu');
		$_pages['tools/side_menu.php']['parent'] = 'tools/modules.php';

	$_pages['tools/course_email.php']['title']  = _AT('course_email');
	$_pages['tools/course_email.php']['parent'] = 'tools/index.php';

	$_pages['tools/content/index.php']['title']    = _AT('content');
	$_pages['tools/content/index.php']['parent']   = 'tools/index.php';
	$_pages['tools/content/index.php']['children'] = array('editor/add_content.php', 'tools/tracker/index.php', 'tools/ims/index.php', 'tools/tile/index.php');

		$_pages['editor/add_content.php']['title']    = _AT('add_content');
		$_pages['editor/add_content.php']['parent']   = 'tools/content/index.php';

		$_pages['editor/edit_content.php']['title']  = _AT('edit_content');
		$_pages['editor/edit_content.php']['parent'] = 'tools/content/index.php';


		$_pages['editor/delete_content.php']['title']    = _AT('delete_content');
		$_pages['editor/delete_content.php']['parent']   = 'tools/content/index.php';

		$_pages['tools/tracker/index.php']['title']  = _AT('content_usage');
		$_pages['tools/tracker/index.php']['parent'] = 'tools/content/index.php';
		$_pages['tools/tracker/index.php']['children'] = array('tools/tracker/student_usage.php', 'tools/tracker/reset.php');

			$_pages['tools/tracker/student_usage.php']['title']  = _AT('member_stats');
			$_pages['tools/tracker/student_usage.php']['parent'] = 'tools/tracker/index.php';

			//$_pages['tools/tracker/page_student_stats.php']['title']  = _AT('page_student_stats');
			//$_pages['tools/tracker/page_student_stats.php']['parent'] = 'tools/tracker/index.php';

			$_pages['tools/tracker/reset.php']['title']  = _AT('reset');
			$_pages['tools/tracker/reset.php']['parent'] = 'tools/tracker/index.php';

		$_pages['tools/ims/index.php']['title']    = _AT('content_packaging');
		$_pages['tools/ims/index.php']['parent']   = 'tools/content/index.php';

		$_pages['tools/tile/index.php']['title']  = _AT('tile_search');
		$_pages['tools/tile/index.php']['parent'] = 'tools/content/index.php';

	$_pages['tools/enrollment/index.php']['title']    = _AT('enrolment');
	$_pages['tools/enrollment/index.php']['parent']   = 'tools/index.php';
	$_pages['tools/enrollment/index.php']['children'] = array('tools/course_email.php', 'tools/enrollment/export_course_list.php', 'tools/enrollment/import_course_list.php', 'tools/enrollment/create_course_list.php', 'tools/enrollment/groups.php');

		$_pages['tools/course_email.php']['title']  = _AT('course_email');
		$_pages['tools/course_email.php']['parent'] = 'tools/enrollment/index.php';

		$_pages['tools/enrollment/export_course_list.php']['title']    = _AT('list_export_course_list');
		$_pages['tools/enrollment/export_course_list.php']['parent']   = 'tools/enrollment/index.php';

		$_pages['tools/enrollment/import_course_list.php']['title']    = _AT('list_import_course_list');
		$_pages['tools/enrollment/import_course_list.php']['parent']   = 'tools/enrollment/index.php';

		$_pages['tools/enrollment/create_course_list.php']['title']    = _AT('list_create_course_list');
		$_pages['tools/enrollment/create_course_list.php']['parent']   = 'tools/enrollment/index.php';

		$_pages['tools/enrollment/groups.php']['title']    = _AT('groups');
		$_pages['tools/enrollment/groups.php']['parent']   = 'tools/enrollment/index.php';
		$_pages['tools/enrollment/groups.php']['children']   = array('tools/enrollment/groups_manage.php');

			$_pages['tools/enrollment/groups_manage.php']['title']    = _AT('create_group');
			$_pages['tools/enrollment/groups_manage.php']['parent']   = 'tools/enrollment/groups.php';

		$_pages['tools/enrollment/privileges.php']['title']    = _AT('roles_privileges');
		$_pages['tools/enrollment/privileges.php']['parent']   = 'tools/enrollment/index.php';

	$_pages['tools/backup/index.php']['title']  = _AT('backups');
	$_pages['tools/backup/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/backup/index.php']['children'] = array('tools/backup/create.php', 'tools/backup/upload.php');

		$_pages['tools/backup/create.php']['title']  = _AT('create');
		$_pages['tools/backup/create.php']['parent'] = 'tools/backup/index.php';

		$_pages['tools/backup/upload.php']['title']  = _AT('upload');
		$_pages['tools/backup/upload.php']['parent'] = 'tools/backup/index.php';

		$_pages['tools/backup/restore.php']['title']  = _AT('restore');
		$_pages['tools/backup/restore.php']['parent'] = 'tools/backup/index.php';

		$_pages['tools/backup/edit.php']['title']  = _AT('edit');
		$_pages['tools/backup/edit.php']['parent'] = 'tools/backup/index.php';

		$_pages['tools/backup/delete.php']['title']  = _AT('delete');
		$_pages['tools/backup/delete.php']['parent'] = 'tools/backup/index.php';

	$_pages['tools/news/index.php']['title']  = _AT('announcements');
	$_pages['tools/news/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/news/index.php']['children'] = array('editor/add_news.php');

		$_pages['editor/add_news.php']['title']  = _AT('add_announcement');
		$_pages['editor/add_news.php']['parent'] = 'tools/news/index.php';

		$_pages['editor/edit_news.php']['title']  = _AT('edit_announcement');
		$_pages['editor/edit_news.php']['parent'] = 'tools/news/index.php';

		$_pages['editor/delete_news.php']['title']  = _AT('delete_announcement');
		$_pages['editor/delete_news.php']['parent'] = 'tools/news/index.php';

	$_pages['tools/glossary/index.php']['title']  = _AT('glossary');
	$_pages['tools/glossary/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/glossary/index.php']['children'] = array('tools/glossary/add.php');

		$_pages['tools/glossary/add.php']['title']  = _AT('add_glossary');
		$_pages['tools/glossary/add.php']['parent'] = 'tools/glossary/index.php';

		$_pages['tools/glossary/edit.php']['title']  = _AT('edit_glossary');
		$_pages['tools/glossary/edit.php']['parent'] = 'tools/glossary/index.php';

		$_pages['tools/glossary/delete.php']['title']  = _AT('delete_glossary');
		$_pages['tools/glossary/delete.php']['parent'] = 'tools/glossary/index.php';

	$_pages['tools/forums/index.php']['title']  = _AT('forums');
	$_pages['tools/forums/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/forums/index.php']['children'] = array('editor/add_forum.php');

		$_pages['editor/add_forum.php']['title']  = _AT('create_forum');
		$_pages['editor/add_forum.php']['parent'] = 'tools/forums/index.php';

		$_pages['editor/delete_forum.php']['title']  = _AT('delete_forum');
		$_pages['editor/delete_forum.php']['parent'] = 'tools/forums/index.php';

		$_pages['editor/edit_forum.php']['title']  = _AT('edit_forum');
		$_pages['editor/edit_forum.php']['parent'] = 'tools/forums/index.php';

	$_pages['tools/links/index.php']['title']  = _AT('links');
	$_pages['tools/links/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/links/index.php']['children'] = array('tools/links/add.php', 'tools/links/categories.php', 'tools/links/categories_create.php');

		$_pages['tools/links/add.php']['title']  = _AT('add_link');
		$_pages['tools/links/add.php']['parent'] = 'tools/links/index.php';

		$_pages['tools/links/edit.php']['title']  = _AT('edit_link');
		$_pages['tools/links/edit.php']['parent'] = 'tools/links/index.php';

		$_pages['tools/links/delete.php']['title']  = _AT('delete_link');
		$_pages['tools/links/delete.php']['parent'] = 'tools/links/index.php';

		$_pages['tools/links/categories.php']['title']  = _AT('categories');
		$_pages['tools/links/categories.php']['parent'] = 'tools/links/index.php';

		$_pages['tools/links/categories_create.php']['title']  = _AT('create_category');
		$_pages['tools/links/categories_create.php']['parent'] = 'tools/links/index.php';

		$_pages['tools/links/categories_edit.php']['title']  = _AT('edit_category');
		$_pages['tools/links/categories_edit.php']['parent'] = 'tools/links/categories.php';

		$_pages['tools/links/categories_delete.php']['title']  = _AT('delete_category');
		$_pages['tools/links/categories_delete.php']['parent'] = 'tools/links/categories.php';


	// tests

	$_pages['tools/tests/index.php']['title']  = _AT('tests');
	$_pages['tools/tests/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/tests/index.php']['children'] = array('tools/tests/create_test.php', 'tools/tests/question_db.php', 'tools/tests/question_cats.php');

	$_pages['tools/tests/create_test.php']['title']  = _AT('create_test');
	$_pages['tools/tests/create_test.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/question_db.php']['title']  = _AT('question_database');
	$_pages['tools/tests/question_db.php']['parent'] = 'tools/tests/index.php';

		$_pages['tools/tests/create_question_multi.php']['title']  = _AT('create_question_multi');
		$_pages['tools/tests/create_question_multi.php']['parent'] = 'tools/tests/question_db.php';

	$_pages['tools/tests/question_cats.php']['title']  = _AT('question_categories');
	$_pages['tools/tests/question_cats.php']['parent'] = 'tools/tests/index.php';
	$_pages['tools/tests/question_cats.php']['children'] = array('tools/tests/question_cats_manage.php');

	$_pages['tools/tests/question_cats_manage.php']['title']  = _AT('create_category');
	$_pages['tools/tests/question_cats_manage.php']['parent'] = 'tools/tests/question_cats.php';

	$_pages['tools/tests/question_cats_delete.php']['title']  = _AT('delete_category');
	$_pages['tools/tests/question_cats_delete.php']['parent'] = 'tools/tests/question_cats.php';

	$_pages['tools/tests/edit_test.php']['title']  = _AT('edit_test');
	$_pages['tools/tests/edit_test.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/preview.php']['title']  = _AT('preview');
	$_pages['tools/tests/preview.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/preview_question.php']['title']  = _AT('preview');
	$_pages['tools/tests/preview_question.php']['parent'] = 'tools/tests/question_db.php';

	$_pages['tools/tests/results.php']['title']  = _AT('submissions');
	$_pages['tools/tests/results.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/results_all_quest.php']['title']  = _AT('statistics');
	$_pages['tools/tests/results_all_quest.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/delete_test.php']['title']  = _AT('delete_test');
	$_pages['tools/tests/delete_test.php']['parent'] = 'tools/tests/index.php';

		// test questions
		$_pages['tools/tests/create_question_tf.php']['title']  = _AT('create_new_question');
		$_pages['tools/tests/create_question_tf.php']['parent'] = 'tools/tests/question_db.php';
		
		$_pages['tools/tests/create_question_multi.php']['title']  = _AT('create_new_question');
		$_pages['tools/tests/create_question_multi.php']['parent'] = 'tools/tests/question_db.php';

		$_pages['tools/tests/create_question_long.php']['title']  = _AT('create_new_question');
		$_pages['tools/tests/create_question_long.php']['parent'] = 'tools/tests/question_db.php';

		$_pages['tools/tests/create_question_likert.php']['title']  = _AT('create_new_question');
		$_pages['tools/tests/create_question_likert.php']['parent'] = 'tools/tests/question_db.php';

		$_pages['tools/tests/edit_question_tf.php']['title']  = _AT('edit_question');
		$_pages['tools/tests/edit_question_tf.php']['parent'] = 'tools/tests/question_db.php';
		
		$_pages['tools/tests/edit_question_multi.php']['title']  = _AT('edit_question');
		$_pages['tools/tests/edit_question_multi.php']['parent'] = 'tools/tests/question_db.php';

		$_pages['tools/tests/edit_question_long.php']['title']  = _AT('edit_question');
		$_pages['tools/tests/edit_question_long.php']['parent'] = 'tools/tests/question_db.php';

		$_pages['tools/tests/edit_question_likert.php']['title']  = _AT('edit_question');
		$_pages['tools/tests/edit_question_likert.php']['parent'] = 'tools/tests/question_db.php';

	$_pages['tools/take_test.php']['title']  = _AT('take_test');
	$_pages['tools/take_test.php']['parent'] = 'tools/my_tests.php';

	$_pages['tools/tests/view_results.php']['title']  = _AT('view_results');
	$_pages['tools/tests/view_results.php']['parent'] = 'tools/tests/results.php';


$_pages['forum/list.php']['title']  = _AT('forums');
$_pages['forum/list.php']['img'] = 'images/home-forums.gif';

$_pages['glossary/index.php']['title']  = _AT('glossary');
$_pages['glossary/index.php']['img'] = 'images/home-glossary.gif';

$_pages['links/index.php']['title']  = _AT('links');
$_pages['links/index.php']['children'] = array('links/add.php');
$_pages['links/index.php']['img'] = 'images/home-links.gif';

	$_pages['links/add.php']['title']  = _AT('suggest_link');
	$_pages['links/add.php']['parent'] = 'links/index.php';

$_pages['discussions/achat/index.php']['title'] = _AT('chat');
$_pages['discussions/achat/index.php']['img'] = 'images/home-chat.gif';

$_pages['tile.php']['title'] = _AT('tile_search');
$_pages['tile.php']['img'] = 'images/home-tile_search.gif';

$_pages['my_stats.php']['title'] = _AT('my_tracker');
$_pages['my_stats.php']['img'] = 'images/home-tracker.gif';

$_pages['tools/my_tests.php']['title'] = _AT('my_tests');
$_pages['tools/my_tests.php']['img'] = 'images/home-tests.gif';

$_pages['polls/index.php']['title'] = _AT('polls');
$_pages['polls/index.php']['img'] = 'images/home-polls.gif';

$_pages['acollab.php']['title'] = 'ACollab';
$_pages['acollab.php']['img'] = 'images/home-acollab.gif';

$_pages['export.php']['title'] = _AT('export_content');
$_pages['export.php']['img'] = 'images/home-export_content.gif';

$_pages['directory.php']['title'] = _AT('directory');
$_pages['directory.php']['img'] = 'images/home-directory.gif';

if (($_SESSION['course_id'] > 0) && isset($_modules)) {
	foreach ($_modules as $module) {
		if (in_array($module, $_pages[AT_NAV_COURSE])) {
			$_pages[$module]['parent'] = AT_NAV_COURSE;
		} else {
			$_pages[$module]['parent'] = 'index.php';
		}
	}
} else if ($_SESSION['course_id'] == -1) {
	if (!admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)) {
		$_pages[AT_NAV_ADMIN]  = array('admin/index.php');
		if (admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE)) {
			$_pages[AT_NAV_ADMIN][] = 'admin/users.php';
			$_pages['admin/users.php']['parent'] = AT_NAV_ADMIN;
		}
		if (admin_authenticate(AT_ADMIN_PRIV_COURSES, TRUE)) {
			$_pages[AT_NAV_ADMIN][] = 'admin/courses.php';
			$_pages['admin/courses.php']['children'] = array('admin/create_course.php');
			$_pages['admin/courses.php']['parent'] = AT_NAV_ADMIN;
		}

		if (admin_authenticate(AT_ADMIN_PRIV_BACKUPS, TRUE)) {
			$_pages[AT_NAV_ADMIN][] = 'admin/backup/index.php';
			$_pages['admin/backup/index.php']['parent'] = AT_NAV_ADMIN;
		}
		if (admin_authenticate(AT_ADMIN_PRIV_FORUMS, TRUE)) {
			$_pages[AT_NAV_ADMIN][] = 'admin/forums.php';
			$_pages['admin/forums.php']['parent'] = AT_NAV_ADMIN;
		}
		if (admin_authenticate(AT_ADMIN_PRIV_CATEGORIES, TRUE)) {
			$_pages[AT_NAV_ADMIN][] = 'admin/course_categories.php';
			$_pages['admin/course_categories.php']['parent'] = AT_NAV_ADMIN;
		}
		if (admin_authenticate(AT_ADMIN_PRIV_LANGUAGES, TRUE)) {
			$_pages[AT_NAV_ADMIN][] = 'admin/language.php';
			$_pages['admin/language.php']['parent'] = AT_NAV_ADMIN;
		}
		if (admin_authenticate(AT_ADMIN_PRIV_THEMES, TRUE)) {
			$_pages[AT_NAV_ADMIN][] = 'admin/themes/index.php';
			$_pages['admin/themes/index.php']['parent'] = AT_NAV_ADMIN;
		}
	}
}

/* global pages */
$_pages['about.php']['title']  = _AT('about_atutor');

$_pages['404.php']['title']  = _AT('404');

$_pages['help/index.php']['title']  = _AT('help');
$_pages['help/index.php']['children'] = array('help/accessibility.php', 'help/about_help.php', 'help/contact_admin.php');

	$_pages['help/accessibility.php']['title']  = _AT('accessibility');
	$_pages['help/accessibility.php']['parent'] = 'help/index.php';

	$_pages['help/about_help.php']['title']  = _AT('about_atutor_help');
	$_pages['help/about_help.php']['parent'] = 'help/index.php';

	$_pages['help/contact_admin.php']['title']  = _AT('contact_admin');
	$_pages['help/contact_admin.php']['parent'] = 'help/index.php';

$_pages['search.php']['title']      = _AT('search');


$current_page = substr($_SERVER['PHP_SELF'], strlen($_base_path));

function get_main_navigation($current_page) {
	global $_pages, $_base_path;

	$parent_page = $_pages[$current_page]['parent'];
	$_top_level_pages = array();

	if (isset($parent_page) && is_numeric($parent_page)) {
		foreach($_pages[$parent_page] as $page) {
			$_top_level_pages[] = array('url' => $_base_path . $page, 'title' => $_pages[$page]['title']);
		}
	} else if (isset($parent_page)) {
		return get_main_navigation($parent_page);
	}

	return $_top_level_pages;
}

function get_current_main_page($current_page) {
	global $_pages, $_base_path;

	$parent_page = $_pages[$current_page]['parent'];

	if (isset($parent_page) && is_numeric($parent_page)) {
		return $_base_path . $current_page;
	} else if (isset($parent_page)) {
		return get_current_main_page($parent_page);
	}
}

function get_sub_navigation($current_page) {
	global $_pages, $_base_path;

	if (isset($current_page) && is_numeric($current_page)) {
		// reached the top
		return array();
	} else if (isset($_pages[$current_page]['children'])) {
		$_sub_level_pages[] = array('url' => $_base_path . $current_page, 'title' => $_pages[$current_page]['title']);
		foreach ($_pages[$current_page]['children'] as $child) {
			$_sub_level_pages[] = array('url' => $_base_path . $child, 'title' => $_pages[$child]['title']);
		}
	} else if (isset($_pages[$current_page]['parent'])) {
		// no children

		$parent_page = $_pages[$current_page]['parent'];
		return get_sub_navigation($parent_page);
	}

	return $_sub_level_pages;
}

function get_current_sub_navigation_page($current_page) {
	global $_pages, $_base_path;

	$parent_page = $_pages[$current_page]['parent'];

	if (isset($parent_page) && is_numeric($parent_page)) {
		return $_base_path . $current_page;
	} else {
		return $_base_path . $current_page;
	}
}

function get_path($current_page) {
	global $_pages, $_base_path;

	$parent_page = $_pages[$current_page]['parent'];

	if (isset($parent_page) && is_numeric($parent_page)) {
		$path[] = array('url' => $_base_path . $current_page, 'title' => $_pages[$current_page]['title']);
		return $path;
	} else if (isset($parent_page)) {
		$path[] = array('url' => $_base_path . $current_page, 'title' => $_pages[$current_page]['title']);
		$path = array_merge($path, get_path($parent_page));
	} else {
		$path[] = array('url' => $_base_path . $current_page, 'title' => $_pages[$current_page]['title']);
	}
	
	return $path;
}

function get_home_navigation() {
	global $_pages, $_base_path;

	$home_links = array();
	foreach ($_pages[AT_NAV_HOME] as $child) {
		$home_links[] = array('url' => $_base_path . $child, 'title' => $_pages[$child]['title'], 'img' => $_base_path.$_pages[$child]['img']);
	}

	return $home_links;
}
?>