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

namespace OCA\TextTemplates\Controller;

use Exception;
use OCA\TextTemplates\Db\TemplateMapper;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use Throwable;

class TemplateController extends OCSController {

	public function __construct(
		string $appName,
		IRequest $request,
		private TemplateMapper $templateMapper,
		private ?string $userId,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param bool $includeAdmin
	 * @return DataResponse
	 */
	public function getUserTemplates(bool $includeAdmin = true): DataResponse {
		try {
			$userTemplates = $this->templateMapper->getTemplatesOfUser($this->userId);
			$adminTemplates = $includeAdmin
				? $this->templateMapper->getTemplatesOfUser(null)
				: [];
			return new DataResponse(array_merge($userTemplates, $adminTemplates));
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param string $name
	 * @param string $content
	 * @return DataResponse
	 */
	public function addUserTemplate(string $name, string $content): DataResponse {
		try {
			$template = $this->templateMapper->createTemplate($this->userId, $name, $content);
			return new DataResponse($template);
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param int $id
	 * @param string|null $name
	 * @param string|null $content
	 * @return DataResponse
	 */
	public function editUserTemplate(int $id, ?string $name = null, ?string $content = null): DataResponse {
		try {
			$template = $this->templateMapper->updateTemplate($id, $this->userId, $name, $content);
			return new DataResponse($template);
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param int $id
	 * @return DataResponse
	 */
	public function deleteUserTemplate(int $id): DataResponse {
		try {
			$template = $this->templateMapper->deleteTemplate($id, $this->userId);
			return new DataResponse($template);
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @return DataResponse
	 */
	public function getAdminTemplates() {
		try {
			$templates = $this->templateMapper->getTemplatesOfUser(null);
			return new DataResponse($templates);
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @param string $name
	 * @param string $content
	 * @return DataResponse
	 */
	public function addAdminTemplate(string $name, string $content): DataResponse {
		try {
			$template = $this->templateMapper->createTemplate(null, $name, $content);
			return new DataResponse($template);
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @param int $id
	 * @param string|null $name
	 * @param string|null $content
	 * @return DataResponse
	 */
	public function editAdminTemplate(int $id, ?string $name = null, ?string $content = null): DataResponse {
		try {
			$template = $this->templateMapper->updateTemplate($id, null, $name, $content);
			return new DataResponse($template);
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @param int $id
	 * @return DataResponse
	 */
	public function deleteAdminTemplate(int $id): DataResponse {
		try {
			$template = $this->templateMapper->deleteTemplate($id, null);
			return new DataResponse($template);
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}
}
