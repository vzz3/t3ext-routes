<?php
declare(strict_types = 1);

namespace LMS\Routes\Service;

/* * *************************************************************
 *
 *  Copyright notice
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

use LMS\Routes\Domain\Model\Route;
use LMS\Routes\Support\Extbase\Dispatcher;

/**
 * @author Sergey Borulko <borulkosergey@icloud.com>
 */
class RouteService
{
    use Router, Dispatcher;

    /**
     * Attempt to retrieve the corresponding <YAML Configuration> for the current request path
     *
     * @api
     *
     * @param  string $slug
     *
     * @return \LMS\Routes\Domain\Model\Route
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     * @throws \Symfony\Component\Routing\Exception\MethodNotAllowedException
     * @throws \Symfony\Component\Routing\Exception\NoConfigurationException
     */
    public function findRouteFor(string $slug): Route
    {
        $routeSettings = $this->getRouter()->match($slug);

        $this->notifyListenersBeforeHandling($routeSettings);

        return new Route($routeSettings);
    }

    /**
     * Other extensions could listen to Extbase Route Requests.
     * They could deny the current request if used is not permitted to.
     *
     * @param  array $route
     */
    private function notifyListenersBeforeHandling(array $route): void
    {
        $this->emit(__CLASS__, 'beforeHandling', ['route' => $route]);
    }
}