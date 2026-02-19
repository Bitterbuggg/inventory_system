<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $user = session('auth_user');

        return view('dashboard/index', [
            'user' => $user,
            'roleSections' => [
                'Admin' => [
                    'User Administration',
                    'Approval Oversight',
                    'Supplier Governance',
                ],
                'Employee' => [
                    'Purchase Request',
                    'Purchase Order & PO Request',
                    'Receiving and Issuance',
                ],
                'IT Dev/Staff' => [
                    'System Health',
                    'Audit and Monitoring',
                    'Data Maintenance',
                ],
            ],
        ]);
    }
}
