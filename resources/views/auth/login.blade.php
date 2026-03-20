<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — HRMS Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh; margin: 0;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        /* Animated background blobs */
        body::before {
            content: ''; position: absolute; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(99,102,241,0.25) 0%, transparent 70%);
            top: -200px; right: -200px; border-radius: 50%; animation: blob 8s ease-in-out infinite;
        }
        body::after {
            content: ''; position: absolute; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(168,85,247,0.2) 0%, transparent 70%);
            bottom: -150px; left: -150px; border-radius: 50%; animation: blob 10s ease-in-out infinite reverse;
        }
        @keyframes blob { 0%,100% { transform: scale(1) translate(0,0); } 50% { transform: scale(1.1) translate(20px,-20px); } }
        .login-wrapper { position: relative; z-index: 1; width: 100%; max-width: 460px; padding: 16px; }
        .login-card {
            background: rgba(255,255,255,0.97);
            border-radius: 24px; border: none;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            padding: 36px 40px 32px; text-align: center; position: relative; overflow: hidden;
        }
        .login-header::before {
            content: ''; position: absolute; width: 200px; height: 200px;
            background: rgba(255,255,255,0.08); border-radius: 50%;
            top: -80px; right: -60px;
        }
        .login-header::after {
            content: ''; position: absolute; width: 150px; height: 150px;
            background: rgba(255,255,255,0.06); border-radius: 50%;
            bottom: -60px; left: -40px;
        }
        .logo-wrap {
            width: 68px; height: 68px; background: rgba(255,255,255,0.2);
            border-radius: 18px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-size: 2rem; color: #fff;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2); position: relative; z-index: 1;
            backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);
        }
        .login-header h3 { color: #fff; font-weight: 800; margin: 0 0 4px; font-size: 1.6rem; position: relative; z-index: 1; }
        .login-header p { color: rgba(255,255,255,0.8); margin: 0; font-size: 0.875rem; position: relative; z-index: 1; }
        .login-body { padding: 36px 40px; }
        .form-floating label { color: #64748b; font-size: 0.875rem; }
        .form-floating .form-control {
            border-radius: 12px; border: 1.5px solid #e2e8f0;
            padding: 14px 16px; height: auto; font-size: 0.9rem;
            transition: all 0.2s; background: #f8fafc;
        }
        .form-floating .form-control:focus {
            border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
            background: #fff;
        }
        .input-group-icon {
            position: relative;
        }
        .input-group-icon .form-control { padding-left: 44px; }
        .input-group-icon .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 1rem; z-index: 5; pointer-events: none;
        }
        .input-group-icon .toggle-pw {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 1rem; z-index: 5; cursor: pointer; background: none; border: none; padding: 0;
        }
        .input-group-icon .toggle-pw:hover { color: #6366f1; }
        .btn-signin {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border: none; border-radius: 12px; padding: 13px;
            font-weight: 700; font-size: 0.95rem; color: #fff;
            transition: all 0.2s; letter-spacing: 0.3px;
            box-shadow: 0 4px 16px rgba(99,102,241,0.4);
        }
        .btn-signin:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(99,102,241,0.5);
            color: #fff;
        }
        .btn-signin:active { transform: translateY(0); }
        .divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
        .divider span { color: #94a3b8; font-size: 0.78rem; white-space: nowrap; }
        .demo-box {
            background: linear-gradient(135deg, #f0f4ff, #faf5ff);
            border: 1px solid #e0e7ff; border-radius: 12px; padding: 14px 16px;
        }
        .demo-box .demo-title { font-size: 0.78rem; font-weight: 700; color: #6366f1; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; }
        .demo-cred {
            display: flex; align-items: center; justify-content: space-between;
            padding: 6px 10px; background: #fff; border-radius: 8px; margin-bottom: 4px;
            border: 1px solid #e0e7ff; cursor: pointer; transition: all 0.15s;
        }
        .demo-cred:last-child { margin-bottom: 0; }
        .demo-cred:hover { border-color: #6366f1; background: #f0f4ff; }
        .demo-cred .role { font-size: 0.75rem; font-weight: 600; color: #6366f1; }
        .demo-cred .email { font-size: 0.75rem; color: #64748b; }
        .demo-cred .copy-hint { font-size: 0.68rem; color: #94a3b8; }
        .form-check-input:checked { background-color: #6366f1; border-color: #6366f1; }
        .alert { border-radius: 10px; font-size: 0.875rem; }
        .features-strip {
            display: flex; gap: 16px; justify-content: center; margin-top: 24px;
        }
        .feature-chip {
            display: flex; align-items: center; gap: 5px;
            font-size: 0.72rem; color: rgba(255,255,255,0.6);
        }
        .feature-chip i { font-size: 0.8rem; color: rgba(255,255,255,0.5); }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-wrap"><i class="bi bi-people-fill"></i></div>
            <h3>HRMS Pro</h3>
            <p>Human Resource Management System</p>
        </div>
        <div class="login-body">
            @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:0.85rem;color:#374151">Email Address</label>
                    <div class="input-group-icon">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" id="emailInput"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="you@company.com" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:0.85rem;color:#374151">Password</label>
                    <div class="input-group-icon">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" id="passwordInput"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter your password" required>
                        <button type="button" class="toggle-pw" onclick="togglePassword()">
                            <i class="bi bi-eye" id="pwIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.85rem;color:#64748b">Remember me</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-signin w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to HRMS
                </button>
            </form>

            <div class="divider"><span>Quick Access — Demo Accounts</span></div>

            <div class="demo-box">
                <div class="demo-title"><i class="bi bi-lightning-fill me-1"></i>Click to fill credentials</div>
                <div class="demo-cred" onclick="fillCreds('admin@hrms.com','password')">
                    <div>
                        <div class="role"><i class="bi bi-shield-fill me-1"></i>Admin</div>
                        <div class="email">admin@hrms.com</div>
                    </div>
                    <div class="copy-hint"><i class="bi bi-cursor-fill"></i> Click</div>
                </div>
                <div class="demo-cred" onclick="fillCreds('hr@hrms.com','password')">
                    <div>
                        <div class="role"><i class="bi bi-person-badge-fill me-1"></i>HR Manager</div>
                        <div class="email">hr@hrms.com</div>
                    </div>
                    <div class="copy-hint"><i class="bi bi-cursor-fill"></i> Click</div>
                </div>
                <div class="demo-cred" onclick="fillCreds('arjun.sharma@company.com','password')">
                    <div>
                        <div class="role"><i class="bi bi-person-fill me-1"></i>Employee</div>
                        <div class="email">arjun.sharma@company.com</div>
                    </div>
                    <div class="copy-hint"><i class="bi bi-cursor-fill"></i> Click</div>
                </div>
            </div>
        </div>
    </div>

    <div class="features-strip">
        <div class="feature-chip"><i class="bi bi-shield-check"></i> Secure Login</div>
        <div class="feature-chip"><i class="bi bi-people"></i> 20+ Employees</div>
        <div class="feature-chip"><i class="bi bi-graph-up"></i> Live Analytics</div>
    </div>
</div>

<script>
function fillCreds(email, password) {
    document.getElementById('emailInput').value = email;
    document.getElementById('passwordInput').value = password;
    document.getElementById('loginForm').submit();
}
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('pwIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>
