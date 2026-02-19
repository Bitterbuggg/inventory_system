<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login');
$routes->get('signup', 'AuthController::signupForm');
$routes->post('signup', 'AuthController::signup');
$routes->get('logout', 'AuthController::logout', ['filter' => 'auth']);

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
	$routes->get('dashboard', 'DashboardController::index');

	$routes->group('admin', ['filter' => 'role:Admin'], static function (RouteCollection $routes): void {
		$routes->get('overview', 'DashboardController::index');
	});

	$routes->group('employee', ['filter' => 'role:Employee'], static function (RouteCollection $routes): void {
		$routes->get('overview', 'DashboardController::index');
	});

	$routes->group('tech', ['filter' => 'role:IT Dev/Staff'], static function (RouteCollection $routes): void {
		$routes->get('overview', 'DashboardController::index');
	});

	$routes->group('api/workflow', ['filter' => 'role:Admin,Employee'], static function (RouteCollection $routes): void {
		$routes->post('purchase-requests', 'PurchaseWorkflowController::createPurchaseRequest');
		$routes->post('purchase-requests/(:num)/approve', 'PurchaseWorkflowController::approvePurchaseRequest/$1');
		$routes->post('purchase-orders', 'PurchaseWorkflowController::createPurchaseOrder');
		$routes->post('po-requests', 'PurchaseWorkflowController::createPoRequest');
		$routes->post('receivings/convert', 'PurchaseWorkflowController::convertReceiving');
		$routes->post('issuances', 'PurchaseWorkflowController::issueInventory');
	});
});
