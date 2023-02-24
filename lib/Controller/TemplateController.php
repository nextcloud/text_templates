<?php
/**
 * Nextcloud - TextTemplates
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <eneiluj@posteo.net>
 * @copyright Julien Veyssier 2023
 */

namespace OCA\TextTemplates\Controller;

use Exception;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;

use OCA\TextTemplates\Service\TemplateService;
use Throwable;

class TemplateController extends OCSController {

	private TemplateService $templateService;
	private ?string $userId;

	public function __construct(string          $appName,
								IRequest        $request,
								TemplateService   $templateService,
								?string         $userId) {
		parent::__construct($appName, $request);
		$this->templateService = $templateService;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 *
	 * @return DataResponse
	 */
	public function getUserTemplates() {
		try {
			$templates = $this->templateService->getTemplates($this->userId);
			return new DataResponse($templates);
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @return DataResponse
	 */
	public function getAdminTemplates() {
		try {
			$templates = $this->templateService->getTemplates();
			return new DataResponse($templates);
		} catch (Exception | Throwable $e) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
	}
}
