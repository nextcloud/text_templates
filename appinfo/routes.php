<?php
/**
 * Nextcloud - TextTemplates
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 * @copyright Julien Veyssier 2023
 */

return [
	'routes' => [
		['name' => 'config#setConfig', 'url' => '/config', 'verb' => 'PUT'],
		['name' => 'config#setAdminConfig', 'url' => '/admin-config', 'verb' => 'PUT'],

		['name' => 'template#getUserTemplates', 'url' => '/templates', 'verb' => 'GET'],
		['name' => 'template#addUserTemplate', 'url' => '/templates', 'verb' => 'POST'],
		['name' => 'template#editUserTemplate', 'url' => '/templates/{id}', 'verb' => 'PUT'],
		['name' => 'template#deleteUserTemplate', 'url' => '/templates/{id}', 'verb' => 'DELETE'],

		['name' => 'template#getAdminTemplates', 'url' => '/admin/templates', 'verb' => 'GET'],
		['name' => 'template#addAdminTemplate', 'url' => '/admin/templates', 'verb' => 'POST'],
		['name' => 'template#editAdminTemplate', 'url' => '/admin/templates/{id}', 'verb' => 'PUT'],
		['name' => 'template#deleteAdminTemplate', 'url' => '/admin/templates/{id}', 'verb' => 'DELETE'],
	],
];
