<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    protected $helpers = ['url', 'form', 'vite'];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    /**
     * Decode and validate JSON items from form submission
     * 
     * @param string $itemsJson JSON string containing array of items
     * @return array Validated items array
     * @throws InvalidArgumentException If JSON is malformed or invalid
     */
    protected function decodeItems(string $itemsJson): array
    {
        try {
            $items = json_decode($itemsJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \InvalidArgumentException('Invalid JSON items format: ' . $e->getMessage());
        }

        if (!is_array($items) || empty($items)) {
            throw new \InvalidArgumentException('Items JSON must be a non-empty array.');
        }

        return array_values(array_filter($items, static fn ($item): bool => is_array($item)));
    }
}
