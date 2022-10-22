<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2022, Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\ReportURI\Listener;

use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Services\IAppConfig;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;

class CSPListener implements IEventListener {

    private IAppConfig $config;

    public function __construct(IAppConfig $config) {
        $this->config = $config;
    }

	public function handle(Event $event): void {
        if (!($event instanceof AddContentSecurityPolicyEvent)) {
            return;
        }

        $report_to_uri = $this->config->getAppValue('uri', '');
        if ($report_to_uri === '') {
            return;
        }

        $csp = new ContentSecurityPolicy();
        $csp->addReportTo($report_to_uri);
        $event->addPolicy($csp);
    }

}
