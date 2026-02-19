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

	$routes->group('workflow', ['filter' => 'role:Admin,Employee'], static function (RouteCollection $routes): void {
		$routes->get('/', 'WorkflowController::index');
		$routes->get('purchase-request', 'WorkflowController::purchaseRequestForm');
		$routes->post('purchase-request', 'WorkflowController::createPurchaseRequest');
		$routes->get('purchase-order', 'WorkflowController::purchaseOrderForm');
		$routes->post('purchase-order', 'WorkflowController::createPurchaseOrder');
		$routes->get('receiving-convert', 'WorkflowController::receivingForm');
		$routes->post('receiving-convert', 'WorkflowController::convertReceiving');
		$routes->get('issuance', 'WorkflowController::issuanceForm');
		$routes->post('issuance', 'WorkflowController::createIssuance');
	});

	$routes->group('purchase-requests', ['filter' => 'role:Admin,Employee'], static function (RouteCollection $routes): void {
		$routes->get('/', 'PurchaseRequestController::index');
		$routes->get('create', 'PurchaseRequestController::create');
		$routes->post('/', 'PurchaseRequestController::store');
		$routes->get('(:num)', 'PurchaseRequestController::show/$1');
		$routes->get('(:num)/edit', 'PurchaseRequestController::edit/$1');
		$routes->post('(:num)', 'PurchaseRequestController::update/$1');
		$routes->post('(:num)/delete', 'PurchaseRequestController::delete/$1');
	});

	$routes->group('purchase-orders', ['filter' => 'role:Admin,Employee'], static function (RouteCollection $routes): void {
		$routes->get('/', 'PurchaseOrderController::index');
		$routes->get('create', 'PurchaseOrderController::create');
		$routes->post('/', 'PurchaseOrderController::store');
		$routes->get('(:num)', 'PurchaseOrderController::show/$1');
		$routes->get('(:num)/edit', 'PurchaseOrderController::edit/$1');
		$routes->post('(:num)', 'PurchaseOrderController::update/$1');
		$routes->post('(:num)/delete', 'PurchaseOrderController::delete/$1');
	});

	$routes->group('inventory', ['filter' => 'role:Admin,Employee'], static function (RouteCollection $routes): void {
		$routes->get('/', 'InventoryController::index');
		$routes->post('/', 'InventoryController::index');
	});
});
