<?php
/**
 * Nextcloud - TextTemplates
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier
 * @copyright Julien Veyssier 2023
 */

namespace OCA\TextTemplates\Service;

use OCP\Http\Client\IClient;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\L10N\IFactory;
use Psr\Log\LoggerInterface;
use OCP\Http\Client\IClientService;

class TemplateService {

	private IClient $client;

	public function __construct (string $appName,
								private LoggerInterface $logger,
								private IL10N $l10n,
								private IConfig $config,
								private IURLGenerator $urlGenerator,
								private IFactory $l10nFactory,
								IClientService $clientService) {
		$this->client = $clientService->newClient();
	}
}
