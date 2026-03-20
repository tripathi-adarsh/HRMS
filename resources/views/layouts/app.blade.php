<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HRMS Pro') }} - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --sidebar-bg: linear-gradient(180deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            --sidebar-text: #c4b5fd;
            --topbar-height: 64px;
        }
        [data-theme="dark"] {
            --bs-body-bg: #0f172a;
            --bs-body-color: #e2e8f0;
            --bs-card-bg: #1e293b;
            --bs-border-color: #334155;
            --bs-secondary-color: #94a3b8;
            --bs-tertiary-color: #64748b;
            --bs-heading-color: #f1f5f9;
            --bs-link-color: #818cf8;
            --bs-code-color: #a5b4fc;
            --bs-table-color: #e2e8f0;
            --bs-table-bg: transparent;
            --bs-table-border-color: #1e293b;
            --bs-table-striped-bg: rgba(255,255,255,0.03);
            --bs-table-hover-bg: #0f172a;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; min-height: 100vh; }
        [data-theme="dark"] body { background: #0f172a; color: #e2e8f0; }
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh;
            width: var(--sidebar-width); background: var(--sidebar-bg);
            z-index: 1000; overflow-y: auto; transition: transform 0.3s;
            scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.1) transparent;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
        .sidebar-brand {
            padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; gap: 12px;
            position: sticky; top: 0; background: rgba(15,12,41,0.95); backdrop-filter: blur(8px); z-index: 1;
        }
        .sidebar-brand .logo { width: 38px; height: 38px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(99,102,241,0.4); }
        .sidebar-brand h5 { color: #fff; margin: 0; font-weight: 700; font-size: 1.05rem; }
        .sidebar-brand small { color: var(--sidebar-text); font-size: 0.68rem; opacity: 0.7; }
        .sidebar-nav { padding: 12px 0 24px; }
        .nav-section { padding: 14px 24px 4px; font-size: 0.6rem; font-weight: 700;
            color: rgba(196,181,253,0.4); text-transform: uppercase; letter-spacing: 1.5px; }
        .sidebar .nav-link {
            display: flex; align-items: center; gap: 10px; padding: 9px 20px 9px 24px;
            color: rgba(196,181,253,0.75); transition: all 0.2s;
            font-size: 0.845rem; font-weight: 500; border-left: 3px solid transparent;
            margin: 1px 8px; border-radius: 8px;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08); color: #fff;
            border-left-color: rgba(255,255,255,0.3);
        }
        .sidebar .nav-link.active {
            background: linear-gradient(90deg, rgba(99,102,241,0.35), rgba(168,85,247,0.15));
            color: #fff; border-left-color: #6366f1;
            box-shadow: inset 0 0 0 1px rgba(99,102,241,0.2);
        }
        .sidebar .nav-link i { font-size: 1rem; width: 18px; flex-shrink: 0; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .topbar {
            height: var(--topbar-height); background: #fff; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; padding: 0 24px; gap: 12px;
            position: sticky; top: 0; z-index: 100;
        }
        [data-theme="dark"] .topbar { background: #1e293b; border-color: #334155; }
        .topbar-search { flex: 1; max-width: 380px; position: relative; }
        .topbar-search input {
            border: 1px solid #e2e8f0; border-radius: 10px; padding: 8px 16px 8px 38px;
            background: #f8fafc; width: 100%; font-size: 0.85rem; outline: none;
        }
        .topbar-search input:focus { border-color: var(--primary); }
        .topbar-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        [data-theme="dark"] .topbar-search input { background: #0f172a; border-color: #334155; color: #e2e8f0; }
        .topbar-actions { display: flex; align-items: center; gap: 6px; margin-left: auto; }
        .topbar-btn {
            width: 38px; height: 38px; border-radius: 10px; border: 1px solid #e2e8f0;
            background: transparent; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #64748b; position: relative; transition: all 0.15s;
        }
        .topbar-btn:hover { background: #f1f5f9; color: #1e293b; }
        [data-theme="dark"] .topbar-btn { border-color: #334155; color: #94a3b8; }
        [data-theme="dark"] .topbar-btn:hover { background: #0f172a; color: #e2e8f0; }
        .badge-dot { position: absolute; top: 5px; right: 5px; width: 8px; height: 8px;
            background: #ef4444; border-radius: 50%; border: 2px solid #fff; }
        [data-theme="dark"] .badge-dot { border-color: #1e293b; }
        .avatar { width: 36px; height: 36px; border-radius: 10px; background: var(--primary);
            display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 0.9rem; }
        .topbar-divider { width: 1px; height: 28px; background: #e2e8f0; margin: 0 4px; }
        [data-theme="dark"] .topbar-divider { background: #334155; }
        .page-content { padding: 24px; }
        .page-header { margin-bottom: 24px; }
        .page-header h4 { font-weight: 700; color: #1e293b; margin: 0; font-size: 1.35rem; }
        [data-theme="dark"] .page-header h4 { color: #f1f5f9; }
        .page-header p { color: #64748b; margin: 4px 0 0; font-size: 0.875rem; }
        .card { border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        [data-theme="dark"] .card { background: #1e293b; border-color: #334155; }
        .card-header { background: transparent; border-bottom: 1px solid #e2e8f0; padding: 14px 20px; }
        [data-theme="dark"] .card-header { border-color: #334155; }
        .card-footer { background: transparent; border-top: 1px solid #e2e8f0; padding: 12px 20px; }
        [data-theme="dark"] .card-footer { border-color: #334155; }
        .stat-card { border-radius: 16px; padding: 22px; color: #fff; position: relative; overflow: hidden; border: none; }
        .stat-card::before { content: ''; position: absolute; right: -30px; top: -30px;
            width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.08); }
        .stat-card .stat-icon { width: 46px; height: 46px; border-radius: 12px;
            background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .stat-card h3 { font-size: 1.9rem; font-weight: 800; margin: 10px 0 2px; }
        .stat-card p { margin: 0; opacity: 0.88; font-size: 0.85rem; font-weight: 500; }
        .table { font-size: 0.875rem; }
        .table th { font-weight: 600; color: #64748b; border-bottom: 2px solid #e2e8f0; padding: 11px 16px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table td { padding: 12px 16px; vertical-align: middle; border-color: #f1f5f9; }
        .table-hover tbody tr:hover { background: #f8fafc; }
        [data-theme="dark"] .table { color: #e2e8f0; }
        [data-theme="dark"] .table th { color: #94a3b8; border-color: #334155; }
        [data-theme="dark"] .table td { border-color: #1e293b; }
        [data-theme="dark"] .table-hover tbody tr:hover { background: #0f172a; }
        .badge { font-weight: 500; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; }
        /* Dark mode — force text colors that Bootstrap CSS vars don't cover */
        [data-theme="dark"] .badge.bg-light { background: #334155 !important; color: #e2e8f0 !important; }
        [data-theme="dark"] .bg-success-subtle { background: rgba(16,185,129,0.15) !important; }
        [data-theme="dark"] .text-success { color: #34d399 !important; }
        [data-theme="dark"] .border-success-subtle { border-color: rgba(16,185,129,0.3) !important; }
        [data-theme="dark"] .bg-danger-subtle { background: rgba(239,68,68,0.15) !important; }
        [data-theme="dark"] .text-danger { color: #f87171 !important; }
        [data-theme="dark"] .border-danger-subtle { border-color: rgba(239,68,68,0.3) !important; }
        [data-theme="dark"] .bg-warning-subtle { background: rgba(245,158,11,0.15) !important; }
        [data-theme="dark"] .text-warning { color: #fbbf24 !important; }
        [data-theme="dark"] .border-warning-subtle { border-color: rgba(245,158,11,0.3) !important; }
        [data-theme="dark"] .bg-primary-subtle { background: rgba(99,102,241,0.15) !important; }
        [data-theme="dark"] .text-primary { color: #818cf8 !important; }
        [data-theme="dark"] .border-primary-subtle { border-color: rgba(99,102,241,0.3) !important; }
        [data-theme="dark"] .bg-info-subtle { background: rgba(6,182,212,0.15) !important; }
        [data-theme="dark"] .text-info { color: #22d3ee !important; }
        [data-theme="dark"] .border-info-subtle { border-color: rgba(6,182,212,0.3) !important; }
        [data-theme="dark"] .bg-secondary-subtle { background: rgba(100,116,139,0.2) !important; }
        [data-theme="dark"] .text-secondary { color: #94a3b8 !important; }
        [data-theme="dark"] .text-muted { color: #64748b !important; }
        [data-theme="dark"] .text-dark { color: #e2e8f0 !important; }
        [data-theme="dark"] .text-body { color: #e2e8f0 !important; }
        [data-theme="dark"] code { background: #334155 !important; color: #a5b4fc !important; padding: 2px 6px; border-radius: 4px; }
        [data-theme="dark"] small, [data-theme="dark"] .small { color: #94a3b8; }
        [data-theme="dark"] .fw-semibold, [data-theme="dark"] .fw-bold { color: #e2e8f0; }
        [data-theme="dark"] .card p, [data-theme="dark"] .card div, [data-theme="dark"] .card span { color: inherit; }
        [data-theme="dark"] .border { border-color: #334155 !important; }
        [data-theme="dark"] .border-light { border-color: #334155 !important; }
        [data-theme="dark"] .bg-light { background: #1e293b !important; }
        [data-theme="dark"] hr { border-color: #334155; }
        .topbar-username { color: #1e293b; }
        [data-theme="dark"] .topbar-username { color: #f1f5f9; }
        /* Buttons */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-sm { padding: 5px 12px; font-size: 0.8rem; border-radius: 8px; }
        /* Forms */
        .form-control, .form-select { border-radius: 10px; border: 1px solid #e2e8f0; padding: 9px 14px; font-size: 0.875rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .form-label { font-weight: 500; font-size: 0.875rem; color: #374151; margin-bottom: 6px; }
        [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background: #0f172a; border-color: #334155; color: #e2e8f0; }
        [data-theme="dark"] .form-label { color: #94a3b8; }
        [data-theme="dark"] .form-control::placeholder { color: #475569; }
        /* Alerts */
        .alert { border-radius: 12px; border: none; font-size: 0.875rem; }
        /* Dropdowns */
        .dropdown-menu { border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.1); padding: 8px; }
        [data-theme="dark"] .dropdown-menu { background: #1e293b; border-color: #334155; }
        .dropdown-item { border-radius: 8px; font-size: 0.875rem; padding: 8px 12px; }
        [data-theme="dark"] .dropdown-item { color: #e2e8f0; }
        [data-theme="dark"] .dropdown-item:hover { background: #0f172a; }
        /* Notifications */
        .notif-item { padding: 10px 12px; border-radius: 8px; cursor: pointer; transition: background 0.15s; }
        .notif-item:hover { background: #f8fafc; }
        [data-theme="dark"] .notif-item:hover { background: #0f172a; }
        .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--primary); flex-shrink: 0; margin-top: 5px; }
        /* Pagination */
        .hrms-pagination { gap: 3px; margin: 0; flex-wrap: wrap; }
        .hrms-pagination .page-link {
            border-radius: 8px !important;
            border: 1px solid #e2e8f0;
            color: #374151;
            padding: 5px 11px;
            font-size: 0.82rem;
            font-weight: 500;
            line-height: 1.5;
            transition: all 0.15s;
            background: #fff;
            min-width: 34px;
            text-align: center;
        }
        .hrms-pagination .page-link:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
            text-decoration: none;
        }
        .hrms-pagination .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 2px 8px rgba(99,102,241,0.4);
        }
        .hrms-pagination .page-item.disabled .page-link {
            background: #f8fafc;
            color: #94a3b8;
            border-color: #e2e8f0;
            cursor: not-allowed;
        }
        [data-theme="dark"] .hrms-pagination .page-link {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }
        [data-theme="dark"] .hrms-pagination .page-link:hover {
            background: #334155;
            border-color: #475569;
            color: #f1f5f9;
        }
        [data-theme="dark"] .hrms-pagination .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        [data-theme="dark"] .hrms-pagination .page-item.disabled .page-link {
            background: #0f172a;
            color: #475569;
            border-color: #1e293b;
        }
        /* Sidebar overlay */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; }
        .sidebar-overlay.show { display: block; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); box-shadow: 0 0 40px rgba(0,0,0,0.3); }
            .main-content { margin-left: 0; }
        }
        @media (max-width: 576px) { .page-content { padding: 16px; } .stat-card h3 { font-size: 1.5rem; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="logo"><i class="bi bi-people-fill text-white fs-5"></i></div>
            <div><h5>HRMS Pro</h5><small>Human Resource System</small></div>
        </div>
        <div class="sidebar-nav">
            @if(auth()->user()->role === 'employee')
            <div class="nav-section">Main</div>
            <a href="{{ route('ess.portal') }}" class="nav-link {{ request()->routeIs('ess.*') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <div class="nav-section">Attendance</div>
            <a href="{{ route('my.attendance') }}" class="nav-link {{ request()->routeIs('my.attendance') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i> My Attendance
            </a>
            <div class="nav-section">Leave</div>
            <a href="{{ route('my.leaves') }}" class="nav-link {{ request()->routeIs('my.leaves') ? 'active' : '' }}">
                <i class="bi bi-calendar-x-fill"></i> My Leaves
            </a>
            <a href="{{ route('leaves.create') }}" class="nav-link {{ request()->routeIs('leaves.create') ? 'active' : '' }}">
                <i class="bi bi-calendar-plus-fill"></i> Apply Leave
            </a>
            <div class="nav-section">Finance</div>
            <a href="{{ route('salary.calculator') }}" class="nav-link {{ request()->routeIs('salary.calculator') ? 'active' : '' }}">
                <i class="bi bi-calculator-fill"></i> Salary Calculator
            </a>
            <div class="nav-section">Performance</div>
            <a href="{{ route('my.performance') }}" class="nav-link {{ request()->routeIs('my.performance') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> My Performance
            </a>
            @else
            <div class="nav-section">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <div class="nav-section">Organization</div>
            <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Employees
            </a>
            <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Departments
            </a>
            <a href="{{ route('designations.index') }}" class="nav-link {{ request()->routeIs('designations.*') ? 'active' : '' }}">
                <i class="bi bi-briefcase-fill"></i> Designations
            </a>
            <div class="nav-section">Time & Attendance</div>
            <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i> Attendance
            </a>
            <div class="nav-section">Leave</div>
            <a href="{{ route('leaves.index') }}" class="nav-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-x-fill"></i> Leave Management
            </a>
            <div class="nav-section">Finance</div>
            <a href="{{ route('payroll.index') }}" class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Payroll
            </a>
            <a href="{{ route('salary.calculator') }}" class="nav-link {{ request()->routeIs('salary.calculator') ? 'active' : '' }}">
                <i class="bi bi-calculator-fill"></i> Salary Calculator
            </a>
            <div class="nav-section">Performance</div>
            <a href="{{ route('performance.index') }}" class="nav-link {{ request()->routeIs('performance.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Performance
            </a>
            <div class="nav-section">Reports</div>
            <a href="{{ route('reports.attendance') }}" class="nav-link {{ request()->routeIs('reports.attendance') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Attendance Report
            </a>
            <a href="{{ route('reports.leave') }}" class="nav-link {{ request()->routeIs('reports.leave') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Leave Report
            </a>
            <a href="{{ route('reports.payroll') }}" class="nav-link {{ request()->routeIs('reports.payroll') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-spreadsheet"></i> Payroll Report
            </a>
            @endif
        </div>
    </nav>
    <div class="main-content">
        <div class="topbar">
            <button class="topbar-btn d-lg-none" onclick="openSidebar()" style="border:none;background:transparent">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="topbar-search d-none d-md-block">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search employees, leaves, payroll...">
            </div>
            <div class="topbar-actions">
                <button class="topbar-btn" onclick="toggleTheme()" title="Toggle dark mode">
                    <i class="bi bi-moon-fill" id="themeIcon"></i>
                </button>
                <div class="topbar-divider d-none d-sm-block"></div>
                <div class="dropdown">
                    <button class="topbar-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-bell-fill"></i>
                        <span class="badge-dot"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-2" style="width:320px;max-height:420px;overflow-y:auto">
                        <div class="d-flex align-items-center justify-content-between px-2 py-1 mb-1">
                            <span class="fw-bold" style="font-size:0.9rem">Notifications</span>
                            <span class="badge bg-primary">3 new</span>
                        </div>
                        <div class="notif-item d-flex gap-2">
                            <div class="notif-dot mt-1"></div>
                            <div>
                                <div style="font-size:0.82rem;font-weight:500">Leave request approved</div>
                                <div class="text-muted" style="font-size:0.75rem">Arjun Sharma's leave was approved</div>
                                <div class="text-muted" style="font-size:0.72rem">2 hours ago</div>
                            </div>
                        </div>
                        <div class="notif-item d-flex gap-2">
                            <div class="notif-dot mt-1"></div>
                            <div>
                                <div style="font-size:0.82rem;font-weight:500">Payroll generated</div>
                                <div class="text-muted" style="font-size:0.75rem">March payroll has been generated</div>
                                <div class="text-muted" style="font-size:0.72rem">5 hours ago</div>
                            </div>
                        </div>
                        <div class="notif-item d-flex gap-2">
                            <div class="notif-dot mt-1"></div>
                            <div>
                                <div style="font-size:0.82rem;font-weight:500">New employee joined</div>
                                <div class="text-muted" style="font-size:0.75rem">Sunita Yadav joined Marketing</div>
                                <div class="text-muted" style="font-size:0.72rem">Yesterday</div>
                            </div>
                        </div>
                        <hr class="my-1">
                        <a href="#" class="dropdown-item text-center text-primary" style="font-size:0.8rem">View all notifications</a>
                    </div>
                </div>
                <div class="topbar-divider d-none d-sm-block"></div>
                <div class="dropdown">
                    <button class="btn p-0 border-0 d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        <div class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <div class="d-none d-md-block text-start">
                            <div style="font-size:0.82rem;font-weight:600;line-height:1.2" class="topbar-username">{{ auth()->user()->name }}</div>
                            <div style="font-size:0.72rem;color:#64748b">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                        <i class="bi bi-chevron-down d-none d-md-block" style="font-size:0.7rem;color:#94a3b8"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mt-1" style="min-width:200px">
                        <li class="px-3 py-2">
                            <div style="font-size:0.85rem;font-weight:600">{{ auth()->user()->name }}</div>
                            <div style="font-size:0.75rem;color:#64748b">{{ auth()->user()->email }}</div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2 text-muted"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2 text-muted"></i>Settings</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-circle-fill text-danger"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li style="font-size:0.875rem">{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.getAttribute('data-theme') === 'dark';
            html.setAttribute('data-theme', isDark ? 'light' : 'dark');
            document.getElementById('themeIcon').className = isDark ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
        }
        function openSidebar() {
            document.getElementById('sidebar').classList.add('show');
            document.getElementById('sidebarOverlay').classList.add('show');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        if (savedTheme === 'dark') document.getElementById('themeIcon').className = 'bi bi-sun-fill';
    </script>
    @stack('scripts')
</body>
</html>
