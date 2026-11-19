<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Barryvdh\DomPDF\Facade\Pdf;

// Prepare base64 logo if exists
$logoPath = public_path('assets_front/icon/desai-delivery.png');
$logoBase64 = '';
if (file_exists($logoPath)) {
    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
}

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Manufacturer & Plant Operations User Manual - Desai Delivery</title>
    <style>
        @page {
            margin: 18mm 15mm 20mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: "DejaVu Sans", "Helvetica Neue", Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.5;
            color: #2d3748;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-before: always;
        }
        .no-break {
            page-break-inside: avoid;
        }

        /* Cover Page */
        .cover {
            text-align: center;
            padding-top: 60px;
            padding-bottom: 50px;
        }
        .cover-logo {
            max-width: 140px;
            margin-bottom: 25px;
        }
        .cover-title {
            font-size: 25pt;
            font-weight: bold;
            color: #101C4C;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .cover-subtitle {
            font-size: 13.5pt;
            color: #35429E;
            margin-bottom: 25px;
            font-weight: 600;
        }
        .cover-badge {
            display: inline-block;
            background: #f0fdf4;
            color: #166534;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 10.5pt;
            margin-bottom: 40px;
            border: 1px solid #bbf7d0;
        }
        .cover-desc {
            font-size: 11pt;
            color: #475569;
            max-width: 85%;
            margin: 0 auto 50px auto;
            line-height: 1.6;
        }
        .cover-meta {
            margin-top: 60px;
            border-top: 2px solid #e2e8f0;
            padding-top: 20px;
            font-size: 9.5pt;
            color: #64748b;
        }
        .cover-meta table {
            width: 85%;
            margin: 0 auto;
            text-align: left;
        }
        .cover-meta td {
            padding: 4px 8px;
        }

        /* Headers & Typography */
        h1 {
            color: #101C4C;
            font-size: 16pt;
            border-bottom: 2px solid #35429E;
            padding-bottom: 5px;
            margin-top: 22px;
            margin-bottom: 12px;
        }
        h2 {
            color: #35429E;
            font-size: 13pt;
            margin-top: 16px;
            margin-bottom: 8px;
            border-left: 4px solid #35429E;
            padding-left: 8px;
        }
        h3 {
            color: #1e293b;
            font-size: 11pt;
            margin-top: 12px;
            margin-bottom: 6px;
        }
        p {
            margin-top: 0;
            margin-bottom: 10px;
            text-align: justify;
        }

        /* Tables */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 9.5pt;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 9px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Badges & Pills */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            font-size: 8.5pt;
            font-weight: bold;
            border-radius: 4px;
        }
        .badge-primary { background: #35429E; color: #ffffff; }
        .badge-success { background: #10b981; color: #ffffff; }
        .badge-warning { background: #f59e0b; color: #111827; }
        .badge-danger  { background: #ef4444; color: #ffffff; }
        .badge-info    { background: #06b6d4; color: #ffffff; }
        .badge-neutral { background: #e2e8f0; color: #334155; }

        /* Callouts / Alert Boxes */
        .callout {
            padding: 10px 14px;
            margin-top: 12px;
            margin-bottom: 14px;
            border-left: 4px solid #35429E;
            background-color: #f8fafc;
            border-radius: 0 4px 4px 0;
            font-size: 9.5pt;
        }
        .callout-info {
            border-left-color: #0284c7;
            background-color: #f0f9ff;
            color: #0369a1;
        }
        .callout-success {
            border-left-color: #059669;
            background-color: #f0fdf4;
            color: #065f46;
        }
        .callout-warning {
            border-left-color: #d97706;
            background-color: #fffbeb;
            color: #92400e;
        }
        .callout-danger {
            border-left-color: #dc2626;
            background-color: #fef2f2;
            color: #991b1b;
        }

        /* Steps */
        .step-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 10px;
        }
        .step-num {
            display: inline-block;
            background: #166534;
            color: white;
            font-weight: bold;
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            border-radius: 50%;
            margin-right: 6px;
            font-size: 9pt;
        }

        code {
            background: #f1f5f9;
            color: #0f172a;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9pt;
            font-family: monospace;
        }
        .toc-list {
            list-style: none;
            padding-left: 0;
            margin-top: 15px;
        }
        .toc-list li {
            padding: 5px 0;
            border-bottom: 1px dotted #cbd5e1;
            font-size: 10pt;
        }
        .toc-list a {
            text-decoration: none;
            color: #1e293b;
        }
        .toc-page {
            float: right;
            color: #166534;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- COVER PAGE -->
    <div class="cover">
        ' . ($logoBase64 ? '<img src="' . $logoBase64 . '" class="cover-logo" alt="Desai Delivery Logo"/>' : '') . '
        <div class="cover-title">MANUFACTURER & PLANT PORTAL</div>
        <div class="cover-subtitle">Factory Inventory, Dual-Unit Production Tracking & Cross-Depot Pipeline Manual</div>
        <div>
            <span class="cover-badge">ENTERPRISE PLANT EDITION &bull; VERSION 2.0</span>
        </div>
        <div class="cover-desc">
            A comprehensive standard operating guide for Plant Incharges, Production Managers, Bottling Line Supervisors, and Supply Chain Planners. Covers Dual-Unit Factory Stock Management (Cases + Loose Bottles with Auto-Normalization), Batch Production Logging, 1-Click Quick Add, Physical Stock Adjustments with Difference Audit, Centralized Cross-Warehouse Pipeline Monitoring, and Movement Traceability.
        </div>

        <div class="cover-meta">
            <table>
                <tr>
                    <td style="width: 25%;"><strong>Document Reference:</strong></td>
                    <td style="width: 35%;">DDMS-SOP-MFG-2026-V2</td>
                    <td style="width: 20%;"><strong>Target Roles:</strong></td>
                    <td style="width: 20%;">Plant Incharge, Production Lead</td>
                </tr>
                <tr>
                    <td><strong>Software System:</strong></td>
                    <td>Desai Delivery & Distribution System</td>
                    <td><strong>Access Portal:</strong></td>
                    <td><code>/manufacture</code></td>
                </tr>
                <tr>
                    <td><strong>Last Revised:</strong></td>
                    <td>November 2026</td>
                    <td><strong>Classification:</strong></td>
                    <td>Confidential & Plant Operations</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- TABLE OF CONTENTS -->
    <h1>Table of Contents</h1>
    <ul class="toc-list">
        <li><strong>1. Plant Operations & Supply Chain Architecture</strong> <span class="toc-page">Page 3</span></li>
        <li><strong>2. Access Control, Security & Navigation</strong> <span class="toc-page">Page 4</span></li>
        <li><strong>3. Dual-Unit Inventory Management Engine (Cases & Bottles)</strong> <span class="toc-page">Page 5</span></li>
        <li><strong>4. Factory Stock Management & Dashboard Overview</strong> <span class="toc-page">Page 7</span></li>
        <li><strong>5. Recording Production Output: Multi-Product Batch Entry & Quick Add</strong> <span class="toc-page">Page 8</span></li>
        <li><strong>6. Physical Stock Adjustments & Difference Audit Logs</strong> <span class="toc-page">Page 10</span></li>
        <li><strong>7. Centralized Cross-Warehouse Pipeline Monitoring</strong> <span class="toc-page">Page 12</span></li>
        <li><strong>8. Stock Movement History & Traceability Audit</strong> <span class="toc-page">Page 14</span></li>
        <li><strong>9. Standard Operating Procedures (SOP) for Plant Incharges</strong> <span class="toc-page">Page 15</span></li>
        <li><strong>10. Plant FAQs & Operational Troubleshooting Matrix</strong> <span class="toc-page">Page 17</span></li>
    </ul>

    <div class="callout callout-info" style="margin-top: 30px;">
        <strong>Notice to Plant Managers:</strong> The Manufacturer Portal represents the entry point of physical goods into the DDMS supply chain. Accurate recording of production batches and close monitoring of regional depot pipeline levels ensures that distribution warehouses never encounter stockouts.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 1 -->
    <h1>1. Plant Operations & Supply Chain Architecture</h1>
    <p>
        In the <strong>Desai Delivery & Distribution Management System (DDMS)</strong>, the <strong>Manufacturer Portal</strong> functions as the central production control center. It connects manufacturing bottling lines, plant buffer warehouses, and regional distribution depots into one synchronized supply chain.
    </p>

    <h2>1.1 The Production-to-Distribution Flow</h2>
    <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:6px; padding:12px; margin-bottom:15px; font-size:9.5pt; line-height:1.7;">
        <strong style="color:#166534;">STEP 1: BOTTLING & PACKAGING LINES</strong><br/>
        Beverages are bottled, capped, labeled, and packed into standard cartons/cases with fixed Bottles Per Case (BPC).<br/>
        &nbsp;&nbsp;&darr;<br/>
        <strong style="color:#35429E;">STEP 2: PRODUCTION LOGGING (MANUFACTURER PORTAL)</strong><br/>
        Plant incharge records batch output via <em>Multi-Product Batch Entry</em> or the <em>1-Click Quick Add Modal</em> in Cases and Loose Bottles.<br/>
        &nbsp;&nbsp;&darr;<br/>
        <strong style="color:#0369a1;">STEP 3: FACTORY BUFFER STOCK HOLDING</strong><br/>
        Stock resides in the factory warehouse balance, ready for dispatch to regional depots.<br/>
        &nbsp;&nbsp;&darr;<br/>
        <strong style="color:#047857;">STEP 4: BULK DISPATCH TO REGIONAL WAREHOUSES</strong><br/>
        Consignments are loaded onto transfer trucks and dispatched to regional depots. Logged in <em>Stock Movement Logs</em>.<br/>
        &nbsp;&nbsp;&darr;<br/>
        <strong style="color:#b45309;">STEP 5: REAL-TIME PIPELINE MONITORING</strong><br/>
        Plant managers monitor live depot stock, in-transit consignments, and stock valuation across all regional warehouses to plan future production runs.
    </div>

    <h2>1.2 Core Modules in the Manufacturer Portal</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Module Name</th>
                <th style="width: 35%;">Primary Purpose</th>
                <th style="width: 40%;">Endpoint / Route</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Plant Dashboard</strong></td>
                <td>High-level KPIs: Total SKUs, Factory Stock, Dispatched Qty, Total Warehouse Stock.</td>
                <td><code>/manufacture/dashboard</code></td>
            </tr>
            <tr>
                <td><strong>Product Management</strong></td>
                <td>View active beverage SKUs, BPC specifications, and master case pricing.</td>
                <td><code>/manufacture/products</code></td>
            </tr>
            <tr>
                <td><strong>Factory Stock</strong></td>
                <td>Live factory inventory, low stock indicators, quick add, and stock adjustments.</td>
                <td><code>/manufacture/stock</code></td>
            </tr>
            <tr>
                <td><strong>Add Production Stock</strong></td>
                <td>Batch-wise multi-product production entry with date, batch ID, and shift notes.</td>
                <td><code>/manufacture/stock-create</code></td>
            </tr>
            <tr>
                <td><strong>Warehouse Stock View</strong></td>
                <td>Cross-depot pipeline visibility: Live stock, in-transit stock, and financial valuation.</td>
                <td><code>/manufacture/warehouse-stock</code></td>
            </tr>
            <tr>
                <td><strong>Stock Movement Logs</strong></td>
                <td>Audit ledger tracking all production, adjustments, and dispatches.</td>
                <td><code>/manufacture/stock-logs</code></td>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- CHAPTER 2 -->
    <h1>2. Access Control, Security & Navigation</h1>
    <p>
        The Manufacturer Portal is secured behind dedicated authentication middleware (<code>admin_logged_in</code>, <code>permission</code>, <code>warehouse_login</code>) mapped to Role ID <code>5</code> (Manufacturer).
    </p>

    <h2>2.1 Logging into the Plant Portal</h2>
    <div class="step-box">
        <span class="step-num">1</span> Navigate to <code>https://&lt;domain&gt;/manufacture/login</code> in your web browser.
    </div>
    <div class="step-box">
        <span class="step-num">2</span> Enter authorized <strong>Manufacturer Email Address</strong> and <strong>Password</strong>.
    </div>
    <div class="step-box">
        <span class="step-num">3</span> Upon authentication, the system lands on the <strong>Plant Dashboard</strong> (<code>/manufacture/dashboard</code>).
    </div>

    <h2>2.2 Plant Dashboard Key Metrics</h2>
    <p>
        The dashboard gives plant leadership immediate visibility over production and inventory:
    </p>
    <ul>
        <li><strong>Total Active SKUs:</strong> Count of manufactured beverage varieties active in the catalog.</li>
        <li><strong>Factory Cases & Loose Bottles:</strong> Live inventory physically held in the central factory warehouse.</li>
        <li><strong>Total Dispatched (Cases):</strong> Cumulative volume dispatched to regional distribution depots.</li>
        <li><strong>Total Regional Warehouse Stock:</strong> Aggregate stock sitting in all regional warehouses combined.</li>
    </ul>

    <h2>2.3 Security & Password Maintenance</h2>
    <p>
        Plant Supervisors can manage their credentials directly:
    </p>
    <ul>
        <li><strong>Profile Settings:</strong> Navigate to <code>/manufacture/manufacture-profile</code> to update plant incharge name and contact details.</li>
        <li><strong>Change Password:</strong> Submit current password and new password under the password tab. Always use complex credentials containing numbers and symbols.</li>
    </ul>

    <div class="callout callout-warning">
        <strong>Plant Terminal Security:</strong> Production floor workstations are often shared across shifts. Always log out at the end of each shift to prevent unauthorized or untraceable production adjustments.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 3 -->
    <h1>3. Dual-Unit Inventory Management Engine (Cases & Bottles)</h1>
    <p>
        In industrial beverage manufacturing and distribution, goods are packaged in bulk <strong>Cases</strong> (cartons/crates). However, during bottling, sample quality checks, breakage handling, and partial customer returns, individual loose <strong>Bottles</strong> must be tracked with equal precision.
    </p>

    <h2>3.1 The Dual-Unit Philosophy</h2>
    <p>
        To eliminate inventory rounding errors and lost units, DDMS tracks every inventory line item in two simultaneous units:
    </p>
    <ol>
        <li><strong>Cases:</strong> Sealed commercial cartons containing a fixed number of bottles.</li>
        <li><strong>Bottles:</strong> Individual loose units not sealed within a full case.</li>
    </ol>

    <h2>3.2 The Fundamental Conversion Formula</h2>
    <p>
        Every product in the master catalog defines a <strong>Per Case Quantity</strong> or <strong>Bottles Per Case (BPC)</strong> constant. The system computes total physical units using the equation:
    </p>
    <div class="callout callout-info" style="font-size: 11pt; text-align: center;">
        <strong>Total Physical Units = (Cases &times; BPC) + Loose Bottles</strong>
    </div>

    <h2>3.3 Automatic Stock Normalization Algorithm</h2>
    <p>
        Whenever production is logged or stock is adjusted, the controller automatically executes the normalization method:
    </p>
    <pre style="background: #f1f5f9; padding: 10px; border-radius: 4px; font-size: 8.5pt;">
if ($perCaseQty > 0 && $bottles >= $perCaseQty) {
    $cases += intdiv($bottles, $perCaseQty);
    $bottles = $bottles % $perCaseQty;
}
return [\'cases\' => $cases, \'bottles\' => $bottles];
    </pre>

    <h3>Normalization Example (Desai Soda 250ml &bull; BPC = 24):</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Action / Event</th>
                <th>Raw User Input</th>
                <th>Calculated Total Units</th>
                <th>Normalized System Output</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Shift 1 Production</td>
                <td>100 Cases + 10 Bottles</td>
                <td>(100 &times; 24) + 10 = 2,410 Bottles</td>
                <td><strong>100 Cases + 10 Bottles</strong></td>
            </tr>
            <tr>
                <td>Shift 2 Additional Output</td>
                <td>+ 0 Cases + 28 Bottles</td>
                <td>28 Bottles = 1 Case + 4 Bottles</td>
                <td><strong>101 Cases + 14 Bottles</strong></td>
            </tr>
            <tr>
                <td>Over-fill Input Test</td>
                <td>50 Cases + 48 Bottles</td>
                <td>48 Bottles / 24 = 2 Full Cases</td>
                <td><strong>52 Cases + 0 Bottles</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="callout callout-success">
        <strong>Operational Benefit:</strong> Plant workers do not need to mentally convert loose bottles into cases. You can enter the exact number of loose bottles counted at the end of the line, and the system automatically packs full cases while preserving remaining loose bottles.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 4 -->
    <h1>4. Factory Stock Management & Dashboard Overview</h1>
    <p>
        The <strong>Factory Stock</strong> interface (<code>/manufacture/stock</code>) is the operational hub for monitoring plant inventory and taking rapid stock actions.
    </p>

    <h2>4.1 Stock Datatable Columns</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Column</th>
                <th style="width: 20%;">Visual Display</th>
                <th>Description & Function</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Product Name</strong></td>
                <td>Bold text</td>
                <td>Trade name of the beverage SKU.</td>
            </tr>
            <tr>
                <td><strong>Bottles / Case</strong></td>
                <td>Text badge</td>
                <td>Master packaging constant (e.g. <em>24 Bottles/Case</em>).</td>
            </tr>
            <tr>
                <td><strong>Case Price (₹)</strong></td>
                <td>Currency (₹)</td>
                <td>Standard wholesale billing rate per case.</td>
            </tr>
            <tr>
                <td><strong>Factory Stock Quantity</strong></td>
                <td>Color-coded badge</td>
                <td>
                    <span class="badge badge-danger">0 Cases (Out of Stock)</span> when stock &le; 0.<br/>
                    <span class="badge badge-warning">X Cases + Y Bottles (Low)</span> when &le; 50 cases.<br/>
                    <span class="badge badge-success">X Cases + Y Bottles</span> when normal (&gt; 50 cases).
                </td>
            </tr>
            <tr>
                <td><strong>Total Dispatched</strong></td>
                <td>Info badge</td>
                <td>Cumulative cases transferred to regional warehouses.</td>
            </tr>
            <tr>
                <td><strong>Action Buttons</strong></td>
                <td>Button group</td>
                <td>
                    <span class="badge badge-primary">+ Add Stock</span> Quick production modal.<br/>
                    <span class="badge badge-warning">Edit Stock</span> Physical adjustment screen.<br/>
                    <span class="badge badge-neutral">History</span> Direct link to movement logs.
                </td>
            </tr>
        </tbody>
    </table>

    <h2>4.2 Stock Health Color Thresholds</h2>
    <p>
        The table visually guides supervisors toward SKUs requiring urgent production:
    </p>
    <ul>
        <li><strong style="color:#ef4444;">Red Badge (Out of Stock):</strong> Zero cases and zero bottles in factory inventory. Immediate production required.</li>
        <li><strong style="color:#f59e0b;">Yellow Badge (Low Stock &le; 50 Cases):</strong> Factory buffer stock has dropped below 50 cases. Production line scheduling advised.</li>
        <li><strong style="color:#10b981;">Green Badge (Healthy &gt; 50 Cases):</strong> Ample buffer stock available for regional warehouse dispatches.</li>
    </ul>

    <div class="page-break"></div>

    <!-- CHAPTER 5 -->
    <h1>5. Recording Production Output: Batch Entry & Quick Add</h1>
    <p>
        The DDMS offers two streamlined workflows for recording newly manufactured beverage stock into the factory ledger:
    </p>
    <ol>
        <li><strong>Multi-Product Batch Entry:</strong> For formal end-of-shift production runs covering multiple SKUs.</li>
        <li><strong>1-Click Quick Add Modal:</strong> For rapid, single-SKU entry directly from the stock overview table.</li>
    </ol>

    <h2>5.1 Method A: Multi-Product Batch Entry (<code>/manufacture/stock-create</code>)</h2>
    <p>
        Use this workflow when a packaging shift finishes multiple production runs and comprehensive batch traceability is required.
    </p>

    <div class="step-box">
        <span class="step-num">1</span> <strong>Open Batch Entry Form:</strong> In <code>/manufacture/stock</code>, click the blue <strong>"Add Production Stock"</strong> button.
    </div>
    <div class="step-box">
        <span class="step-num">2</span> <strong>Enter Batch Header:</strong>
        <ul style="margin-top: 5px; margin-bottom: 5px; padding-left: 20px;">
            <li><strong>Production Date:</strong> Select the date of manufacturing (defaults to today).</li>
            <li><strong>Batch / Lot No:</strong> Enter unique batch identifier (e.g. <code>BATCH-202611-L1</code>) or leave blank for auto-generation.</li>
            <li><strong>Shift / Production Note:</strong> Optional remarks (e.g. <em>Morning Shift Line A output - Supervisor Rajesh</em>).</li>
        </ul>
    </div>
    <div class="step-box">
        <span class="step-num">3</span> <strong>Add Product Line Items:</strong>
        <ul style="margin-top: 5px; margin-bottom: 5px; padding-left: 20px;">
            <li>Select <strong>Product</strong> from the dropdown.</li>
            <li>Enter <strong>Quantity (Cases)</strong> produced.</li>
            <li>Enter <strong>Loose Bottles</strong> produced (if any).</li>
            <li>Click <strong>"+ Add Another Product"</strong> to add more rows in the same batch.</li>
        </ul>
    </div>
    <div class="step-box">
        <span class="step-num">4</span> <strong>Submit Batch:</strong> Click <strong>"Save Production Stock"</strong>. The system normalizes bottles into cases, updates factory stock balances, and creates immutable audit log entries.
    </div>

    <h2>5.2 Method B: 1-Click Quick Add Modal</h2>
    <p>
        When you need to quickly record stock for a single product without navigating away from the table:
    </p>
    <div class="step-box">
        <span class="step-num">1</span> Locate the product row on the <strong>Factory Stock</strong> table.
    </div>
    <div class="step-box">
        <span class="step-num">2</span> Click the blue <strong>"Add Stock"</strong> button.
    </div>
    <div class="step-box">
        <span class="step-num">3</span> A popup modal opens displaying current stock and packaging specifications.
    </div>
    <div class="step-box">
        <span class="step-num">4</span> Enter <strong>Cases to Add</strong> and <strong>Bottles to Add</strong>, enter Batch No and Notes, then click <strong>"Add Stock"</strong>.
    </div>
    <div class="step-box">
        <span class="step-num">5</span> The modal submits via AJAX, updates the table row instantaneously with a green success notification, and refreshes the KPI cards.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 6 -->
    <h1>6. Physical Stock Adjustments & Difference Audit Logs</h1>
    <p>
        Discrepancies can occur between physical factory stock and system records due to bottle breakage, conveyor belt damage, laboratory quality testing, or end-of-month stocktakes. The <strong>Stock Adjustment</strong> module handles these corrections with full audit accountability.
    </p>

    <h2>6.1 Accessing Stock Adjustment (<code>/manufacture/stock-edit/{id}</code>)</h2>
    <p>
        In the Factory Stock table, click the yellow <strong>"Edit Stock"</strong> button on the target product row.
    </p>

    <h2>6.2 The Automated Difference Calculation Engine</h2>
    <p>
        Unlike production entry (which <em>adds</em> stock), the Adjustment module accepts the <strong>Actual Physical Count</strong> verified in the warehouse. The system then automatically calculates the net difference ($\Delta$) and records an audit log.
    </p>

    <div class="step-box">
        <span class="step-num">1</span> <strong>Inspect Current System Balance:</strong> The top card displays <em>Current Stock in System</em> (e.g. <code>120 Cases + 5 Bottles</code>).
    </div>
    <div class="step-box">
        <span class="step-num">2</span> <strong>Input Verified Physical Count:</strong> Enter the exact physical count on the warehouse floor:
        <ul style="margin-top: 5px; margin-bottom: 5px; padding-left: 20px;">
            <li><strong>Physical Cases:</strong> e.g. <code>118</code>.</li>
            <li><strong>Physical Loose Bottles:</strong> e.g. <code>0</code>.</li>
        </ul>
    </div>
    <div class="step-box">
        <span class="step-num">3</span> <strong>Mandatory Reason & Audit Note:</strong> Enter detailed explanation (e.g. <em>2 cases damaged during pallet handling at loading dock</em>).
    </div>
    <div class="step-box">
        <span class="step-num">4</span> <strong>Submit Adjustment:</strong> Click <strong>"Update Stock"</strong>.
    </div>

    <h2>6.3 System Audit Log Generation</h2>
    <p>
        Upon submission, the controller generates a structured audit note stored permanently in <code>manufacture_stock_logs</code>:
    </p>
    <div class="callout callout-warning">
        <code>[Stock adjusted from 120 Cases + 5 Bottles to 118 Cases (-2 Cases, -5 Bottles). Note: 2 cases damaged during pallet handling at loading dock]</code>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Adjustment Type</th>
                <th>Delta ($\Delta$)</th>
                <th>System Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Stock Reduction (Breakage / QC Rejection)</strong></td>
                <td>Negative (e.g. <code>-5 Cases</code>)</td>
                <td>Deducts units from factory balance; records reduction log with audit reason.</td>
            </tr>
            <tr>
                <td><strong>Stock Increase (Found Stock / Audit Surplus)</strong></td>
                <td>Positive (e.g. <code>+3 Cases</code>)</td>
                <td>Increases factory balance; records surplus log with audit reason.</td>
            </tr>
        </tbody>
    </table>

    <div class="callout callout-danger">
        <strong>Anti-Fraud Rule:</strong> Every stock adjustment is permanently linked to the logged-in supervisor\'s user account with date and timestamp. Once submitted, adjustment logs cannot be edited or deleted by anyone, ensuring complete audit integrity for corporate compliance.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 7 -->
    <h1>7. Centralized Cross-Warehouse Pipeline Monitoring</h1>
    <p>
        The <strong>Warehouse Stock View</strong> (<code>/manufacture/warehouse-stock</code>) is an executive supply-chain intelligence dashboard. It provides factory managers with 360-degree visibility over inventory sitting in all regional distribution depots across the state.
    </p>

    <h2>7.1 High-Level KPI Summary Cards</h2>
    <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:6px; padding:10px; margin-bottom:12px; font-size:9.5pt;">
        <table style="width:100%; text-align:center;">
            <tr>
                <td style="width:25%;"><strong>Active Depots</strong><br/><span style="font-size:14pt; color:#35429E; font-weight:bold;">All Regional</span></td>
                <td style="width:25%;"><strong>Total Depot Stock</strong><br/><span style="font-size:14pt; color:#166534; font-weight:bold;">Live Cases</span></td>
                <td style="width:25%;"><strong>In-Transit Pipeline</strong><br/><span style="font-size:14pt; color:#0284c7; font-weight:bold;">Pending Stock</span></td>
                <td style="width:25%;"><strong>Total Network Value</strong><br/><span style="font-size:14pt; color:#b45309; font-weight:bold;">₹ In Hand</span></td>
            </tr>
        </table>
    </div>

    <h2>7.2 Pipeline Table Attributes</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%;">Column</th>
                <th style="width: 25%;">Badge / Format</th>
                <th>Operational Meaning</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Warehouse Depot</strong></td>
                <td>Warehouse Name + ID</td>
                <td>Identifies which regional distribution hub holds the inventory.</td>
            </tr>
            <tr>
                <td><strong>Product SKU</strong></td>
                <td>Product Name + ID</td>
                <td>The specific beverage SKU being monitored.</td>
            </tr>
            <tr>
                <td><strong>Packaging / BPC</strong></td>
                <td>e.g. <em>24 Bottles/Case</em></td>
                <td>Carton multiplier specification.</td>
            </tr>
            <tr>
                <td><strong>Case Price</strong></td>
                <td>₹ Currency</td>
                <td>Master per-case wholesale value.</td>
            </tr>
            <tr>
                <td><strong>Current Depot Stock</strong></td>
                <td>
                    <span class="badge badge-success">X Cases + Y Bottles</span><br/>
                    <span class="badge badge-warning">Low Stock</span> (&le; 50)<br/>
                    <span class="badge badge-danger">Out of Stock</span> (&le; 0)
                </td>
                <td>Stock physically sitting in that warehouse depot\'s storage racks.</td>
            </tr>
            <tr>
                <td><strong>Pending / In-Transit</strong></td>
                <td><span class="badge badge-info">X Cases (In-Transit)</span></td>
                <td>Stock currently on delivery trucks or assigned to drivers but not yet finalized.</td>
            </tr>
            <tr>
                <td><strong>Total Pipeline</strong></td>
                <td>Bold text (Cases + Bottles)</td>
                <td><strong>Current Stock + Pending Stock.</strong> The total inventory actively within that depot\'s distribution network.</td>
            </tr>
            <tr>
                <td><strong>Stock Valuation (₹)</strong></td>
                <td>₹ Currency</td>
                <td>Financial valuation: <code>Current Stock Cases &times; Per Case Price</code>.</td>
            </tr>
        </tbody>
    </table>

    <h2>7.3 Multi-Parameter Filter Controls</h2>
    <p>
        Supervisors can filter the pipeline table to isolate operational bottlenecks:
    </p>
    <ul>
        <li><strong>Filter by Warehouse:</strong> View stock held in a specific depot (e.g. <em>North Depot, Central Hub</em>).</li>
        <li><strong>Filter by Product:</strong> Check the distribution spread of a specific SKU across all warehouses.</li>
        <li><strong>Filter by Stock Status:</strong>
            <ul>
                <li><code>In Stock (&gt; 50)</code>: Depots with healthy reserves.</li>
                <li><code>Low Stock (1 - 50)</code>: Depots needing an emergency replenishment truck.</li>
                <li><code>Out of Stock (&le; 0)</code>: Depots experiencing stockouts. Prioritize for next dispatch!</li>
                <li><code>Pending Stock</code>: Depots with consignments currently in transit.</li>
            </ul>
        </li>
    </ul>

    <div class="page-break"></div>

    <!-- CHAPTER 8 -->
    <h1>8. Stock Movement History & Traceability Audit</h1>
    <p>
        The <strong>Stock Movement Logs</strong> module (<code>/manufacture/stock-logs</code>) provides an immutable ledger of all physical movements into and out of the factory warehouse.
    </p>

    <h2>8.1 Transaction Types in Movement Logs</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Transaction Type</th>
                <th>Visual Badge</th>
                <th>Trigger Event & Flow</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Production</strong></td>
                <td><span class="badge badge-success">Production In</span></td>
                <td>Created when new stock is entered via Batch Entry or Quick Add modal. Increments factory stock balance.</td>
            </tr>
            <tr>
                <td><strong>Adjustment</strong></td>
                <td><span class="badge badge-warning">Adjustment</span></td>
                <td>Created when physical count is adjusted. Records delta difference and supervisor reason.</td>
            </tr>
            <tr>
                <td><strong>Dispatch</strong></td>
                <td><span class="badge badge-info">Dispatch Out</span></td>
                <td>Created when bulk stock is transferred and dispatched to a regional warehouse depot. Decrements factory stock balance.</td>
            </tr>
        </tbody>
    </table>

    <h2>8.2 Log Table Information Fields</h2>
    <ul>
        <li><strong>Date & Time:</strong> Exact timestamp when the inventory movement occurred.</li>
        <li><strong>Product SKU:</strong> Beverage trade name.</li>
        <li><strong>Batch / Lot Number:</strong> Production batch identifier for quality recall traceability.</li>
        <li><strong>Quantity (Cases & Bottles):</strong> Magnitude of the transaction.</li>
        <li><strong>Total Units:</strong> Normalized total individual bottles moved (<code>(Cases &times; BPC) + Bottles</code>).</li>
        <li><strong>Audit Notes:</strong> Shift comments, batch notes, or adjustment explanation.</li>
    </ul>

    <div class="callout callout-info">
        <strong>Batch Traceability for Quality Control:</strong> If a customer or depot reports a defect or taste issue with a specific batch, search the <strong>Batch Number</strong> in Stock Movement Logs. The system instantly reveals when that batch was bottled, the exact quantities produced, and which warehouse consignments received stock from that batch.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 9 -->
    <h1>9. Standard Operating Procedures (SOP) for Plant Incharges</h1>

    <div class="callout callout-info">
        <h3 style="margin-top:0; color:#0369a1;">Morning Production Planning (07:30 AM - 09:00 AM)</h3>
        <ol style="margin-bottom:0; padding-left:20px;">
            <li>Log into <code>/manufacture/dashboard</code>.</li>
            <li>Open <strong>Warehouse Stock View</strong> (<code>/manufacture/warehouse-stock</code>).</li>
            <li>Filter by <strong>Stock Status = Low Stock</strong> and <strong>Out of Stock</strong>.</li>
            <li>Identify which regional warehouses are running critical on specific beverage SKUs.</li>
            <li>Convene with bottling plant supervisors to finalize daily production batch schedules and bottling priorities.</li>
        </ol>
    </div>

    <div class="callout callout-warning">
        <h3 style="margin-top:0; color:#92400e;">Mid-Day Packaging & Dispatch Supervision (12:00 PM - 03:30 PM)</h3>
        <ol style="margin-bottom:0; padding-left:20px;">
            <li>Monitor packaging line output. If Shift 1 finishes a scheduled SKU run, log the batch immediately using <strong>Batch Entry</strong> (<code>/manufacture/stock-create</code>).</li>
            <li>Prepare warehouse stock transfer consignments. Inspect cartons for seal integrity, valid BPC counts, and pallet labeling.</li>
            <li>Ensure dispatch notes reflect accurate Case and Loose Bottle counts before delivery trucks leave the plant gate.</li>
        </ol>
    </div>

    <div class="callout callout-success">
        <h3 style="margin-top:0; color:#065f46;">Shift-End Stock Reconciliation (06:00 PM - 08:30 PM)</h3>
        <ol style="margin-bottom:0; padding-left:20px;">
            <li>Conduct end-of-shift count of finished goods sitting in the plant buffer bay.</li>
            <li>Record all remaining shift output via the <strong>1-Click Quick Add Modal</strong> or Batch Entry form.</li>
            <li>If any damaged bottles, breakage, or lab samples occurred during the shift, open <strong>Edit Stock</strong> (<code>/manufacture/stock-edit</code>) and record an adjustment with an explicit audit note.</li>
            <li>Open <strong>Stock Movement Logs</strong> (<code>/manufacture/stock-logs</code>). Verify that all daily production and dispatch entries balance out with physical floor inventory.</li>
        </ol>
    </div>

    <div class="callout callout-neutral" style="background:#f1f5f9; border-left:4px solid #64748b;">
        <h3 style="margin-top:0; color:#1e293b;">Weekly & Monthly Quality Audit Protocols</h3>
        <ul style="margin-bottom:0; padding-left:20px;">
            <li>Conduct a 100% physical wall-to-wall stocktake of the central factory warehouse on the last Saturday of each month.</li>
            <li>Reconcile cumulative production records with raw ingredient usage (syrup, CO2, preforms, caps).</li>
            <li>Archive and verify batch quality test certificates against recorded batch numbers in DDMS.</li>
        </ul>
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 10 -->
    <h1>10. Plant FAQs & Operational Troubleshooting Matrix</h1>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 38%;">Question / Problem</th>
                <th>Standard Operating Solution</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>What happens if a worker enters 30 loose bottles for a product with 24 BPC?</strong></td>
                <td>
                    The DDMS automatic normalization algorithm immediately converts 24 bottles into 1 full Case and retains 6 loose bottles. System will record: <code>+1 Case and +6 Bottles</code> without throwing an error.
                </td>
            </tr>
            <tr>
                <td><strong>How is Depot Stock Valuation (₹) calculated?</strong></td>
                <td>
                    Valuation is calculated dynamically as: <code>Depot Stock Cases &times; Master Per Case Price</code>. If a product\'s wholesale price is updated in Admin, the valuation reflects the new pricing automatically.
                </td>
            </tr>
            <tr>
                <td><strong>Can an entered batch number be changed after submitting?</strong></td>
                <td>
                    Batch numbers in movement logs are immutable for quality control compliance. If an incorrect batch number was entered, submit a corrective adjustment note documenting the correct lot ID.
                </td>
            </tr>
            <tr>
                <td><strong>Why does a regional warehouse show negative or zero stock when goods were dispatched?</strong></td>
                <td>
                    Dispatched goods remain in <strong>Pending / In-Transit Stock</strong> until the regional warehouse manager logs into <code>/warehouse/stock-create</code> and accepts the inward consignment. Once accepted, it moves into Live Depot Stock.
                </td>
            </tr>
            <tr>
                <td><strong>Can a product be permanently deleted from the Manufacturer Portal?</strong></td>
                <td>
                    No. Product creation and deletion is strictly reserved for the Super Admin (<code>/admin/products</code>). The Manufacturer Portal can only view active products and manage factory stock.
                </td>
            </tr>
            <tr>
                <td><strong>Who can see factory stock levels?</strong></td>
                <td>
                    Factory stock levels are visible to Plant Incharges in the Manufacturer Portal and to the Super Admin in the Admin Panel. Regional warehouses only see their own depot stock and driver dispatches.
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 9pt; color: #64748b; text-align: center;">
        Desai Delivery & Distribution Management System &bull; Manufacturer & Plant Operations User Manual &bull; Confidential & Internal Enterprise Documentation
    </div>

</body>
</html>
';

try {
    $pdf = Pdf::loadHTML($html);
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOptions([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'defaultFont' => 'DejaVu Sans'
    ]);

    // Save in public/manuals and root
    $publicPath = public_path('manuals/Manufacturer_Panel_User_Manual.pdf');
    $rootPath = base_path('Manufacturer_Panel_User_Manual.pdf');

    $output = $pdf->output();
    file_put_contents($publicPath, $output);
    file_put_contents($rootPath, $output);

    echo "Manufacturer PDF generated successfully!" . PHP_EOL;
    echo "Saved to: " . $publicPath . " (" . strlen($output) . " bytes)" . PHP_EOL;
    echo "Saved to: " . $rootPath . PHP_EOL;
} catch (\Throwable $e) {
    echo "Error generating PDF: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
