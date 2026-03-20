@extends('layouts.app')
@section('title', 'Salary Calculator')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4><i class="bi bi-calculator-fill me-2 text-primary"></i>Salary Calculator</h4>
        <p>Calculate CTC breakdown, in-hand salary, tax, and deductions</p>
    </div>
    <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Payroll
    </a>
</div>

<div class="row g-4">
    <!-- Input Panel -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2 text-primary"></i>Salary Inputs</h6>
            </div>
            <div class="card-body">
                <!-- CTC -->
                <div class="mb-3">
                    <label class="form-label">Annual CTC (₹)</label>
                    <div class="position-relative">
                        <span class="position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-weight:600">₹</span>
                        <input type="number" id="ctc" class="form-control ps-4" placeholder="e.g. 1200000" value="1200000" oninput="calculate()">
                    </div>
                    <input type="range" id="ctcRange" class="form-range mt-2" min="100000" max="10000000" step="50000" value="1200000"
                        oninput="document.getElementById('ctc').value=this.value;calculate()" style="accent-color:var(--primary)">
                    <div class="d-flex justify-content-between" style="font-size:0.72rem;color:#94a3b8">
                        <span>₹1L</span><span>₹1Cr</span>
                    </div>
                </div>

                <hr>
                <p class="fw-semibold mb-2" style="font-size:0.85rem">Allowances (% of Basic)</p>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">HRA %</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="hraPercent" class="form-control" value="40" min="0" max="100" oninput="calculate()">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Basic %</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="basicPercent" class="form-control" value="50" min="30" max="70" oninput="calculate()">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Special Allowance</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" id="specialAllowance" class="form-control" value="0" min="0" oninput="calculate()">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Bonus (Annual)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" id="bonus" class="form-control" value="0" min="0" oninput="calculate()">
                        </div>
                    </div>
                </div>

                <hr>
                <p class="fw-semibold mb-2" style="font-size:0.85rem">Deductions</p>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">PF Employee %</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="pfPercent" class="form-control" value="12" min="0" max="12" oninput="calculate()">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Professional Tax (₹/mo)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" id="profTax" class="form-control" value="200" min="0" oninput="calculate()">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">ESI %</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="esiPercent" class="form-control" value="0.75" step="0.01" min="0" oninput="calculate()">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Other Deductions (₹/mo)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" id="otherDeductions" class="form-control" value="0" min="0" oninput="calculate()">
                        </div>
                    </div>
                </div>

                <hr>
                <div class="mb-3">
                    <label class="form-label">Tax Regime</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="taxRegime" id="newRegime" value="new" checked onchange="calculate()">
                            <label class="form-check-label" for="newRegime" style="font-size:0.85rem">New Regime</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="taxRegime" id="oldRegime" value="old" onchange="calculate()">
                            <label class="form-check-label" for="oldRegime" style="font-size:0.85rem">Old Regime</label>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary w-100" onclick="calculate()">
                    <i class="bi bi-calculator me-2"></i>Recalculate
                </button>
            </div>
        </div>
    </div>

    <!-- Results Panel -->
    <div class="col-lg-7">
        <!-- In-hand highlight -->
        <div class="card mb-3" style="background:linear-gradient(135deg,#6366f1,#a855f7);border:none">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col">
                        <div style="color:rgba(255,255,255,0.8);font-size:0.85rem;font-weight:500">Monthly In-Hand Salary</div>
                        <div id="inHandMonthly" style="color:#fff;font-size:2.4rem;font-weight:800;line-height:1.1">₹0</div>
                        <div style="color:rgba(255,255,255,0.7);font-size:0.8rem;margin-top:4px">After all deductions & tax</div>
                    </div>
                    <div class="col-auto text-end">
                        <div style="color:rgba(255,255,255,0.7);font-size:0.8rem">Annual In-Hand</div>
                        <div id="inHandAnnual" style="color:#fff;font-size:1.3rem;font-weight:700">₹0</div>
                        <div style="color:rgba(255,255,255,0.7);font-size:0.75rem;margin-top:8px">Annual CTC</div>
                        <div id="ctcDisplay" style="color:rgba(255,255,255,0.9);font-size:1rem;font-weight:600">₹0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown cards -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card p-3 text-center">
                    <div class="mb-1" style="font-size:1.4rem;color:#10b981"><i class="bi bi-cash"></i></div>
                    <div id="basicSalary" class="fw-bold" style="font-size:1.1rem;color:#10b981">₹0</div>
                    <div class="text-muted" style="font-size:0.75rem">Basic / Month</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card p-3 text-center">
                    <div class="mb-1" style="font-size:1.4rem;color:#6366f1"><i class="bi bi-house-fill"></i></div>
                    <div id="hraSalary" class="fw-bold" style="font-size:1.1rem;color:#6366f1">₹0</div>
                    <div class="text-muted" style="font-size:0.75rem">HRA / Month</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card p-3 text-center">
                    <div class="mb-1" style="font-size:1.4rem;color:#ef4444"><i class="bi bi-receipt"></i></div>
                    <div id="totalTax" class="fw-bold" style="font-size:1.1rem;color:#ef4444">₹0</div>
                    <div class="text-muted" style="font-size:0.75rem">Income Tax / Yr</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card p-3 text-center">
                    <div class="mb-1" style="font-size:1.4rem;color:#f59e0b"><i class="bi bi-piggy-bank-fill"></i></div>
                    <div id="pfAmount" class="fw-bold" style="font-size:1.1rem;color:#f59e0b">₹0</div>
                    <div class="text-muted" style="font-size:0.75rem">PF / Month</div>
                </div>
            </div>
        </div>

        <!-- Detailed breakdown table -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2 text-primary"></i>Monthly Salary Breakdown</h6>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" id="monthLabel">Monthly</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th class="text-end">Monthly (₹)</th>
                            <th class="text-end">Annual (₹)</th>
                        </tr>
                    </thead>
                    <tbody id="breakdownTable">
                        <tr><td colspan="3" class="text-center text-muted py-3">Enter CTC to see breakdown</td></tr>
                    </tbody>
                    <tfoot id="breakdownFoot"></tfoot>
                </table>
            </div>
        </div>

        <!-- Chart -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Salary Distribution</h6>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <canvas id="salaryChart" height="200"></canvas>
                    </div>
                    <div class="col-md-7">
                        <div id="chartLegend" class="d-flex flex-column gap-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let salaryChart = null;

function fmt(n) {
    return '₹' + Math.round(n).toLocaleString('en-IN');
}

function calcIncomeTax(taxableIncome, regime) {
    let tax = 0;
    if (regime === 'new') {
        // New regime FY 2024-25
        const slabs = [[300000,0],[400000,0.05],[300000,0.10],[300000,0.15],[300000,0.20],[Infinity,0.30]];
        let remaining = Math.max(0, taxableIncome - 300000);
        const limits = [400000,300000,300000,300000,300000];
        const rates  = [0.05,0.10,0.15,0.20,0.30];
        for (let i = 0; i < rates.length; i++) {
            if (remaining <= 0) break;
            const chunk = i < limits.length ? Math.min(remaining, limits[i]) : remaining;
            tax += chunk * rates[i];
            remaining -= chunk;
        }
        // Rebate u/s 87A — no tax if income <= 7L
        if (taxableIncome <= 700000) tax = 0;
    } else {
        // Old regime
        const slabs = [[250000,0],[250000,0.05],[500000,0.20],[Infinity,0.30]];
        let rem = taxableIncome;
        for (const [limit, rate] of slabs) {
            if (rem <= 0) break;
            const chunk = Math.min(rem, limit);
            tax += chunk * rate;
            rem -= chunk;
        }
        if (taxableIncome <= 500000) tax = 0;
    }
    // Surcharge + cess 4%
    return tax * 1.04;
}

function calculate() {
    const ctc = parseFloat(document.getElementById('ctc').value) || 0;
    document.getElementById('ctcRange').value = Math.min(ctc, 10000000);

    const basicPct   = parseFloat(document.getElementById('basicPercent').value) / 100 || 0.5;
    const hraPct     = parseFloat(document.getElementById('hraPercent').value) / 100 || 0.4;
    const pfPct      = parseFloat(document.getElementById('pfPercent').value) / 100 || 0.12;
    const esiPct     = parseFloat(document.getElementById('esiPercent').value) / 100 || 0.0075;
    const profTaxMo  = parseFloat(document.getElementById('profTax').value) || 0;
    const specialMo  = parseFloat(document.getElementById('specialAllowance').value) || 0;
    const bonusAnn   = parseFloat(document.getElementById('bonus').value) || 0;
    const otherMo    = parseFloat(document.getElementById('otherDeductions').value) || 0;
    const regime     = document.querySelector('input[name="taxRegime"]:checked').value;

    // Annual figures
    const basicAnn   = ctc * basicPct;
    const hraAnn     = basicAnn * hraPct;
    const pfAnn      = basicAnn * pfPct;
    const esiAnn     = (ctc <= 840000) ? basicAnn * esiPct : 0; // ESI only if basic <= 21k/mo
    const profTaxAnn = profTaxMo * 12;
    const specialAnn = specialMo * 12;
    const otherAnn   = otherMo * 12;

    // Employer PF (not deducted from employee but part of CTC)
    const employerPfAnn = basicAnn * 0.12;

    // Gross salary (what employee earns before deductions)
    const grossAnn = ctc - employerPfAnn;

    // Taxable income (simplified — standard deduction 50k for old, 75k for new)
    const stdDeduction = regime === 'new' ? 75000 : 50000;
    const taxableIncome = Math.max(0, grossAnn - pfAnn - stdDeduction);
    const incomeTaxAnn = calcIncomeTax(taxableIncome, regime);

    // Total deductions
    const totalDeductionsAnn = pfAnn + esiAnn + profTaxAnn + otherAnn + incomeTaxAnn;

    // In-hand
    const inHandAnn = grossAnn - totalDeductionsAnn + bonusAnn;
    const inHandMo  = inHandAnn / 12;

    // Monthly figures
    const basicMo = basicAnn / 12;
    const hraMo   = hraAnn / 12;
    const pfMo    = pfAnn / 12;
    const esiMo   = esiAnn / 12;
    const taxMo   = incomeTaxAnn / 12;

    // Update hero
    document.getElementById('inHandMonthly').textContent = fmt(inHandMo);
    document.getElementById('inHandAnnual').textContent  = fmt(inHandAnn);
    document.getElementById('ctcDisplay').textContent    = fmt(ctc);
    document.getElementById('basicSalary').textContent   = fmt(basicMo);
    document.getElementById('hraSalary').textContent     = fmt(hraMo);
    document.getElementById('totalTax').textContent      = fmt(incomeTaxAnn);
    document.getElementById('pfAmount').textContent      = fmt(pfMo);

    // Breakdown table
    const rows = [
        { label: 'Basic Salary',          mo: basicMo,    ann: basicAnn,    type: 'earning' },
        { label: 'HRA',                   mo: hraMo,      ann: hraAnn,      type: 'earning' },
        { label: 'Special Allowance',     mo: specialMo,  ann: specialAnn,  type: 'earning' },
        { label: 'Bonus',                 mo: bonusAnn/12,ann: bonusAnn,    type: 'earning' },
        { label: '— PF (Employee)',       mo: pfMo,       ann: pfAnn,       type: 'deduction' },
        { label: '— ESI',                 mo: esiMo,      ann: esiAnn,      type: 'deduction' },
        { label: '— Professional Tax',    mo: profTaxMo,  ann: profTaxAnn,  type: 'deduction' },
        { label: '— Income Tax (TDS)',    mo: taxMo,      ann: incomeTaxAnn,type: 'deduction' },
        { label: '— Other Deductions',    mo: otherMo,    ann: otherAnn,    type: 'deduction' },
    ];

    let html = '';
    rows.forEach(r => {
        if (r.ann === 0 && r.mo === 0) return;
        const cls = r.type === 'earning' ? 'text-success' : 'text-danger';
        const sign = r.type === 'deduction' ? '-' : '+';
        html += `<tr>
            <td style="font-size:0.85rem">${r.label}</td>
            <td class="text-end ${cls}" style="font-size:0.85rem">${sign}${Math.round(r.mo).toLocaleString('en-IN')}</td>
            <td class="text-end ${cls}" style="font-size:0.85rem">${sign}${Math.round(r.ann).toLocaleString('en-IN')}</td>
        </tr>`;
    });
    document.getElementById('breakdownTable').innerHTML = html;
    document.getElementById('breakdownFoot').innerHTML = `
        <tr style="background:rgba(99,102,241,0.06)">
            <td class="fw-bold" style="font-size:0.9rem">Net In-Hand</td>
            <td class="text-end fw-bold text-primary" style="font-size:0.9rem">${fmt(inHandMo)}</td>
            <td class="text-end fw-bold text-primary" style="font-size:0.9rem">${fmt(inHandAnn)}</td>
        </tr>`;

    // Chart
    const chartData = {
        labels: ['Basic', 'HRA', 'Special', 'PF', 'Tax', 'Other Ded.', 'Net In-Hand'],
        values: [basicAnn, hraAnn, specialAnn + bonusAnn, pfAnn + esiAnn, incomeTaxAnn, profTaxAnn + otherAnn, inHandAnn],
        colors: ['#10b981','#6366f1','#06b6d4','#f59e0b','#ef4444','#94a3b8','#a855f7'],
    };

    if (salaryChart) salaryChart.destroy();
    salaryChart = new Chart(document.getElementById('salaryChart'), {
        type: 'doughnut',
        data: {
            labels: chartData.labels,
            datasets: [{ data: chartData.values, backgroundColor: chartData.colors, borderWidth: 2, borderColor: '#fff' }]
        },
        options: {
            responsive: true, cutout: '65%',
            plugins: { legend: { display: false } }
        }
    });

    // Legend
    let legend = '';
    chartData.labels.forEach((l, i) => {
        const pct = ctc > 0 ? ((chartData.values[i] / ctc) * 100).toFixed(1) : 0;
        legend += `<div class="d-flex align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <div style="width:10px;height:10px;border-radius:3px;background:${chartData.colors[i]};flex-shrink:0"></div>
                <span style="font-size:0.8rem">${l}</span>
            </div>
            <div class="d-flex gap-3">
                <span style="font-size:0.8rem;font-weight:600">${fmt(chartData.values[i]/12)}/mo</span>
                <span class="text-muted" style="font-size:0.75rem;min-width:36px;text-align:right">${pct}%</span>
            </div>
        </div>`;
    });
    document.getElementById('chartLegend').innerHTML = legend;
}

// Run on load
calculate();
</script>
@endpush
