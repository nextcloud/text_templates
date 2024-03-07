<?php

namespace OCA\TextTemplates\Settings;

use OCA\TextTemplates\AppInfo\Application;
use OCA\TextTemplates\Db\TemplateMapper;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\DB\Exception;

use OCP\Settings\ISettings;

class Personal implements ISettings {

	public function __construct(
		private TemplateMapper $templateMapper,
		private IInitialState $initialStateService,
		private ?string       $userId,
	) {
	}

	/**
	 * @return TemplateResponse
	 * @throws Exception
	 */
	public function getForm(): TemplateResponse {
		$templates = $this->templateMapper->getTemplatesOfUser($this->userId);
		$adminTemplates = $this->templateMapper->getTemplatesOfUser(null);

		$userConfig = [
			'templates' => array_merge($templates, $adminTemplates),
		];
		$this->initialStateService->provideInitialState('user-config', $userConfig);
		return new TemplateResponse(Application::APP_ID, 'personalSettings');
	}

	public function getSection(): string {
		return 'additional';
	}

	public function getPriority(): int {
		return 90;
	}
}
