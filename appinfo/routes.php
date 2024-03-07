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

$requirements = [
	'apiVersion' => 'v1',
	//	'token' => '^[a-z0-9]{4,30}$',
];

return [
	'routes' => [
		['name' => 'config#setConfig', 'url' => '/config', 'verb' => 'PUT'],
		['name' => 'config#setAdminConfig', 'url' => '/admin-config', 'verb' => 'PUT'],
	],

	'ocs' => [
		['name' => 'template#getUserTemplates', 'url' => '/api/{apiVersion}/templates', 'verb' => 'GET', 'requirements' => $requirements],
		['name' => 'template#addUserTemplate', 'url' => '/api/{apiVersion}/templates', 'verb' => 'POST', 'requirements' => $requirements],
		['name' => 'template#editUserTemplate', 'url' => '/api/{apiVersion}/templates/{id}', 'verb' => 'PUT', 'requirements' => $requirements],
		['name' => 'template#deleteUserTemplate', 'url' => '/api/{apiVersion}/templates/{id}', 'verb' => 'DELETE', 'requirements' => $requirements],

		['name' => 'template#getAdminTemplates', 'url' => '/api/{apiVersion}/admin/templates', 'verb' => 'GET', 'requirements' => $requirements],
		['name' => 'template#addAdminTemplate', 'url' => '/api/{apiVersion}/admin/templates', 'verb' => 'POST', 'requirements' => $requirements],
		['name' => 'template#editAdminTemplate', 'url' => '/api/{apiVersion}/admin/templates/{id}', 'verb' => 'PUT', 'requirements' => $requirements],
		['name' => 'template#deleteAdminTemplate', 'url' => '/api/{apiVersion}/admin/templates/{id}', 'verb' => 'DELETE', 'requirements' => $requirements],
	],
];
