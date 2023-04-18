<?php
namespace OCA\TextTemplates\Settings;

use OCA\TextTemplates\Db\TemplateMapper;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\DB\Exception;
use OCP\Settings\ISettings;

use OCA\TextTemplates\AppInfo\Application;

class Admin implements ISettings {

	public function __construct(private TemplateMapper $templateMapper,
								private IInitialState $initialStateService,
								?string       $userId) {
	}

	/**
	 * @return TemplateResponse
	 * @throws Exception
	 */
	public function getForm(): TemplateResponse {
		$templates = $this->templateMapper->getTemplatesOfUser(null);

		$state = [
			'templates' => $templates,
		];
		$this->initialStateService->provideInitialState('admin-config', $state);
		return new TemplateResponse(Application::APP_ID, 'adminSettings');
	}

	public function getSection(): string {
		return 'server';
	}

	public function getPriority(): int {
		return 90;
	}
}
