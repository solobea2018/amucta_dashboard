<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Dashboard
{
    public function index()
    {
        Authentication::require_roles(['admin','hro','pro']);

        $db = Database::get_instance();

        $totalEmployees  = $db->select("SELECT COUNT(*) t FROM employee")[0]['t'] ?? 0;
        $activeEmployees = $db->select("SELECT COUNT(*) t FROM employee WHERE active=1")[0]['t'] ?? 0;

        $today = date('Y-m-d');

        $content = <<<HTML
<div class="dashboard">

    <div class="amucta-grid">
        <div class="card">
            <div class="stat-label">Total Employees</div>
            <div class="stat-number">$totalEmployees</div>
        </div>

        <div class="card">
            <div class="stat-label">Active Employees</div>
            <div class="stat-number">$activeEmployees</div>
        </div>

        <div class="card">
            <div class="stat-label">Present Today</div>
            <div class="stat-number">74</div>
        </div>

        <div class="stat-item">
            <div class="stat-label">Open Jobs</div>
            <div class="stat-number">1</div>
        </div>
    </div>

    <div class="dashboard-actions">
        <a href="/employee/list" class="btn btn-primary">Employees</a>       
        <a href="/#" class="btn btn-primary"><i class="fa fa-file-alt"></i> Policies</a>
        <a href="/#" class="btn btn-complete"><i class="fa fa-exclamation-triangle"></i> Incidents</a>       
    </div>

    <div class="card">
        <h3>System Overview</h3>
        <p>
            Centralized view of employees, attendance, recruitment,
            payroll, and compliance activities.
        </p>
    </div>

</div>
HTML;

        MainLayout::render($content, null, "Dashboard");
    }
}