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
    <title>Warehouse Panel User Manual - Desai Delivery</title>
    <style>
        @page {
            margin: 18mm 15mm 20mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: "DejaVu Sans", "Helvetica Neue", Arial, sans-serif;
            font-size: 11pt;
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
            padding-top: 80px;
            padding-bottom: 60px;
        }
        .cover-logo {
            max-width: 140px;
            margin-bottom: 25px;
        }
        .cover-title {
            font-size: 28pt;
            font-weight: bold;
            color: #101C4C;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .cover-subtitle {
            font-size: 15pt;
            color: #35429E;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .cover-badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 50px;
            border: 1px solid #bae6fd;
        }
        .cover-meta {
            margin-top: 100px;
            border-top: 2px solid #e2e8f0;
            padding-top: 25px;
            font-size: 10pt;
            color: #64748b;
        }
        .cover-meta table {
            width: 80%;
            margin: 0 auto;
            text-align: left;
        }
        .cover-meta td {
            padding: 5px 10px;
        }

        /* Headers & Typography */
        h1 {
            color: #101C4C;
            font-size: 18pt;
            border-bottom: 2px solid #35429E;
            padding-bottom: 6px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        h2 {
            color: #1e293b;
            font-size: 14pt;
            border-left: 4px solid #35429E;
            padding-left: 10px;
            margin-top: 20px;
            margin-bottom: 12px;
        }
        h3 {
            color: #334155;
            font-size: 12pt;
            margin-top: 15px;
            margin-bottom: 8px;
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
            margin-top: 12px;
            margin-bottom: 18px;
            font-size: 9.5pt;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }
        table.data-table th {
            background-color: #101C4C;
            color: #ffffff;
            font-weight: bold;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* Callouts / Info Boxes */
        .callout {
            border-left: 4px solid;
            padding: 12px 16px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 10pt;
        }
        .callout-info {
            background-color: #f0fdf4;
            border-color: #22c55e;
            color: #14532d;
        }
        .callout-warning {
            background-color: #fffbeb;
            border-color: #f59e0b;
            color: #78350f;
        }
        .callout-primary {
            background-color: #f0f9ff;
            border-color: #0284c7;
            color: #0c4a6e;
        }
        .callout-highlight {
            background-color: #e0f2fe;
            border-color: #0dcaf0;
            color: #082f49;
        }

        /* Step Circles */
        .step-container {
            margin-bottom: 14px;
        }
        .step-num {
            display: inline-block;
            background: #35429E;
            color: #ffffff;
            width: 22px;
            height: 22px;
            line-height: 22px;
            text-align: center;
            border-radius: 50%;
            font-weight: bold;
            font-size: 9.5pt;
            margin-right: 6px;
        }
        .step-title {
            font-weight: bold;
            color: #101C4C;
            font-size: 10.5pt;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8.5pt;
            font-weight: bold;
        }
        .badge-info { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
        .badge-cyan { background: #cffafe; color: #0891b2; border: 1px solid #a5f3fc; }
        .badge-warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* Table of Contents */
        .toc-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .toc-table td {
            padding: 7px 0;
            border-bottom: 1px dotted #cbd5e1;
        }
        .toc-table td.toc-title {
            font-weight: 600;
            color: #1e293b;
        }
        .toc-table td.toc-page {
            text-align: right;
            color: #64748b;
            font-weight: bold;
        }

        /* Footer styling */
        .footer-bar {
            position: fixed;
            bottom: -12mm;
            left: 0;
            right: 0;
            height: 10mm;
            border-top: 1px solid #cbd5e1;
            font-size: 8pt;
            color: #64748b;
            line-height: 10mm;
        }
        .footer-left { float: left; }
        .footer-right { float: right; }
    </style>
</head>
<body>

    <!-- Header / Footer Canvas Script for Page Numbers -->
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 9;
            $font = $fontMetrics->getFont("DejaVu Sans, Helvetica, sans-serif");
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = $pdf->get_width() - 15 * 2.83465 - $width;
            $y = $pdf->get_height() - 12 * 2.83465;
            $pdf->page_text($x, $y, $text, $font, $size, array(0.4, 0.45, 0.5));

            $footerText = "Desai Distribution Management System | Warehouse Panel Operational Manual";
            $pdf->page_text(15 * 2.83465, $y, $footerText, $font, 8, array(0.4, 0.45, 0.5));
        }
    </script>

    <!-- COVER PAGE -->
    <div class="cover">
        ' . ($logoBase64 ? '<img src="' . $logoBase64 . '" class="cover-logo" alt="Desai Logo"/>' : '') . '
        <div class="cover-title">Warehouse Panel</div>
        <div class="cover-subtitle">Complete Operational & User Manual</div>
        <div class="cover-badge">Standard Operating Procedure (SOP) &bull; Version 2.0</div>
        
        <p style="text-align: center; max-width: 500px; margin: 0 auto; color: #475569; font-size: 11pt;">
            A step-by-step comprehensive guide for Warehouse In-Charges, Supervisors, and Dispatch Managers 
            covering inventory management, driver product assignment, automatic carry-forward stock tracking, 
            physical returns reconciliation, and sales reporting.
        </p>

        <div class="cover-meta">
            <table>
                <tr>
                    <td style="font-weight: bold; width: 40%;">System Name:</td>
                    <td>Desai Distribution Management System (DMS)</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Module:</td>
                    <td>Warehouse & Depot Operations Portal</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Document Version:</td>
                    <td>2.0 (Updated November 2026)</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Target Audience:</td>
                    <td>Warehouse Managers, Depot Operators, Operations Team</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Status:</td>
                    <td>Official Approved Production Guide</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- TABLE OF CONTENTS -->
    <div class="page-break"></div>
    <h1>Table of Contents</h1>
    <table class="toc-table">
        <tr>
            <td class="toc-title">1. Introduction & Portal Overview</td>
            <td class="toc-page">Page 3</td>
        </tr>
        <tr>
            <td class="toc-title">2. Logging In & Navigation Structure</td>
            <td class="toc-page">Page 3</td>
        </tr>
        <tr>
            <td class="toc-title">3. Product & Catalog Management</td>
            <td class="toc-page">Page 4</td>
        </tr>
        <tr>
            <td class="toc-title">4. Warehouse Stock Management</td>
            <td class="toc-page">Page 4</td>
        </tr>
        <tr>
            <td class="toc-title">5. Driver Management & Product Assignment (Daily Dispatch)</td>
            <td class="toc-page">Page 5</td>
        </tr>
        <tr>
            <td class="toc-title">&nbsp;&nbsp;&bull; 5.1 Automatic Carry Forward Detection & Row-wise Loading</td>
            <td class="toc-page">Page 5</td>
        </tr>
        <tr>
            <td class="toc-title">&nbsp;&nbsp;&bull; 5.2 Stock-Filtered Product Selection & Real-Time Validation</td>
            <td class="toc-page">Page 6</td>
        </tr>
        <tr>
            <td class="toc-title">&nbsp;&nbsp;&bull; 5.3 Reviewing & Updating Past Assignments</td>
            <td class="toc-page">Page 6</td>
        </tr>
        <tr>
            <td class="toc-title">6. Daily Driver-wise Sales Report & Reconciliation</td>
            <td class="toc-page">Page 7</td>
        </tr>
        <tr>
            <td class="toc-title">&nbsp;&nbsp;&bull; 6.1 Top KPI Metrics & Summary Indicators</td>
            <td class="toc-page">Page 7</td>
        </tr>
        <tr>
            <td class="toc-title">&nbsp;&nbsp;&bull; 6.2 Table Structure & Multi-Channel Quantities</td>
            <td class="toc-page">Page 7</td>
        </tr>
        <tr>
            <td class="toc-title">&nbsp;&nbsp;&bull; 6.3 Product-wise Breakdown Inspection Modal</td>
            <td class="toc-page">Page 8</td>
        </tr>
        <tr>
            <td class="toc-title">7. Recording Physical Returns: Carry Forward vs. Return (Crucial)</td>
            <td class="toc-page">Page 8</td>
        </tr>
        <tr>
            <td class="toc-title">&nbsp;&nbsp;&bull; 7.1 Recording Step-by-Step</td>
            <td class="toc-page">Page 9</td>
        </tr>
        <tr>
            <td class="toc-title">&nbsp;&nbsp;&bull; 7.2 The Gold Standard Business Rules: Carry Forward vs. Return</td>
            <td class="toc-page">Page 9</td>
        </tr>
        <tr>
            <td class="toc-title">8. Product Daily Sales Report & Export</td>
            <td class="toc-page">Page 10</td>
        </tr>
        <tr>
            <td class="toc-title">9. Invoice Management Module</td>
            <td class="toc-page">Page 10</td>
        </tr>
        <tr>
            <td class="toc-title">10. Daily Operating Routine (SOP Checklist)</td>
            <td class="toc-page">Page 11</td>
        </tr>
        <tr>
            <td class="toc-title">11. FAQs & Troubleshooting</td>
            <td class="toc-page">Page 11</td>
        </tr>
    </table>

    <!-- SECTION 1 & 2 -->
    <div class="page-break"></div>
    <h1>1. Introduction & Portal Overview</h1>
    <p>
        The <strong>Desai Warehouse Panel</strong> is a mission-critical web interface engineered for distribution depots, 
        bottling warehouses, and supply hubs. It serves as the single source of truth for all inventory flowing between the central 
        depot and market delivery vans.
    </p>
    <div class="callout callout-primary">
        <strong>Primary Objectives:</strong>
        <ul style="margin: 5px 0 0 0; padding-left: 20px;">
            <li>Maintain accurate warehouse stock balances in dual units (Cases and Loose Bottles).</li>
            <li>Eliminate dispatch errors by preventing driver over-assignment beyond available depot inventory.</li>
            <li>Automate carry-forward stock rollover from previous-day unsold returns.</li>
            <li>Reconcile end-of-day driver cash collections, physical returns, and invoiced customer sales.</li>
        </ul>
    </div>

    <h1>2. Logging In & Navigation Structure</h1>
    <h2>2.1 Authentication & Security</h2>
    <p>
        Every warehouse operates under its unique warehouse admin credentials. To log in:
    </p>
    <div class="step-container">
        <span class="step-num">1</span> <span class="step-title">Access the Portal URL:</span> 
        Open your web browser (Chrome, Edge, or Firefox recommended) and navigate to the backend login URL.
    </div>
    <div class="step-container">
        <span class="step-num">2</span> <span class="step-title">Enter Credentials:</span> 
        Provide your registered Warehouse Email / Username and Password.
    </div>
    <div class="step-container">
        <span class="step-num">3</span> <span class="step-title">Verify Warehouse Context:</span> 
        Upon login, check the top right header to confirm your warehouse name is active.
    </div>

    <h2>2.2 Main Navigation Menu Structure</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 28%;">Sidebar Menu</th>
                <th style="width: 32%;">Sub-Menu / Route</th>
                <th>Functional Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Dashboard</strong></td>
                <td><code>/warehouse/dashboard</code></td>
                <td>High-level executive overview of active stock and daily sales KPIs.</td>
            </tr>
            <tr>
                <td><strong>Product Management</strong></td>
                <td><code>/warehouse/products</code></td>
                <td>Catalog view of authorized products, bottle packaging, and pricing.</td>
            </tr>
            <tr>
                <td><strong>Stock Management</strong></td>
                <td><code>/warehouse/stock</code></td>
                <td>Live warehouse inventory balance (Cases + Loose Bottles).</td>
            </tr>
            <tr>
                <td><strong>Driver Management</strong></td>
                <td>Product Assign<br><code>/warehouse/driver-product-create</code></td>
                <td>Assign dispatch stock to drivers with auto carry-forward loading.</td>
            </tr>
            <tr>
                <td></td>
                <td>Report List<br><code>/warehouse/driver-management-report</code></td>
                <td>Historical dispatch logs and editing previous assignments.</td>
            </tr>
            <tr>
                <td><strong>Driver Daily Sales</strong></td>
                <td><code>/warehouse/daily-sales-report</code></td>
                <td>Reconciliation dashboard, breakdown modal, and physical returns.</td>
            </tr>
            <tr>
                <td><strong>Product Daily Sales</strong></td>
                <td><code>/warehouse/product-daily-sales-report</code></td>
                <td>Product-by-product performance and one-click CSV export.</td>
            </tr>
            <tr>
                <td><strong>Invoice Management</strong></td>
                <td><code>/warehouse/invoice</code></td>
                <td>View customer sales invoices generated by drivers in the field.</td>
            </tr>
        </tbody>
    </table>

    <!-- SECTION 3 & 4 -->
    <div class="page-break"></div>
    <h1>3. Product & Catalog Management</h1>
    <p>
        The <strong>Product Management</strong> screen (<code>/warehouse/products</code>) displays the official catalog of FMCG beverage 
        products configured by central administration.
    </p>
    <h2>3.1 Understanding Product Packaging Units</h2>
    <p>All items in the Desai ecosystem operate on a dual-quantity unit system:</p>
    <ul>
        <li><strong>Cases (Box/Crate):</strong> The primary commercial container holding a fixed quantity of units (e.g. 24 bottles per case, 12 bottles per case, etc.).</li>
        <li><strong>Loose Bottles:</strong> Individual pieces or units not packed into a full case.</li>
    </ul>
    <div class="callout callout-info">
        <strong>Automatic Unit Normalization:</strong> The system automatically calculates total bottle units as:<br>
        <code>Total Units = (Cases &times; Bottles Per Case) + Loose Bottles</code><br>
        When cases or bottles are dispatched or returned, warehouse inventory automatically packs or unpacks cases accordingly.
    </div>

    <h1>4. Warehouse Stock Management</h1>
    <p>
        Located at <code>/warehouse/stock</code>, this module provides live, real-time insight into the products stored inside your physical warehouse.
    </p>
    <table class="data-table">
        <thead>
            <tr>
                <th>Column</th>
                <th>Meaning & Data Source</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Product Name</strong></td>
                <td>Brand, flavor, and pack size (e.g. FRUIZY MANGO 500ML MRP 35).</td>
            </tr>
            <tr>
                <td><strong>Available Stock</strong></td>
                <td>Current count of full Cases and loose Bottles currently on warehouse shelves.</td>
            </tr>
            <tr>
                <td><strong>Packaging Rule</strong></td>
                <td>Standard bottles per case count (e.g. 24 btls/case).</td>
            </tr>
            <tr>
                <td><strong>Stock Status</strong></td>
                <td><span class="badge badge-success">In Stock</span> (Available &gt; 0) or <span class="badge badge-danger">Out of Stock</span> (0 Available).</td>
            </tr>
        </tbody>
    </table>
    <div class="callout callout-warning">
        <strong>Stock Impact Rule:</strong><br>
        1. When stock is assigned to a driver, warehouse stock is <strong>deducted</strong>.<br>
        2. When a driver returns stock marked as <strong>Carry Forward</strong>, warehouse stock is <strong>replenished (added back)</strong>.<br>
        3. Stock marked as <strong>Return (Damage / Rejected)</strong> does <strong>not</strong> replenish inventory.
    </div>

    <!-- SECTION 5 -->
    <div class="page-break"></div>
    <h1>5. Driver Management & Product Assignment (Daily Dispatch)</h1>
    <p>
        The <strong>Product Assign</strong> screen (<code>/warehouse/driver-product-create</code>) is used by depot supervisors every morning 
        to load delivery vehicles.
    </p>

    <h2>5.1 Automatic Carry Forward Detection & Row-wise Loading</h2>
    <p>
        In daily operations, drivers often have unsold stock from their previous shift. The system features an 
        <strong>Intelligent Carry Forward Auto-Loader</strong>:
    </p>
    <div class="step-container">
        <span class="step-num">1</span> <span class="step-title">Select the Driver:</span>
        Choose the driver from the <strong>Driver</strong> dropdown.
    </div>
    <div class="step-container">
        <span class="step-num">2</span> <span class="step-title">Select Assign Date:</span>
        The date defaults to today. You can select tomorrow or any target dispatch date.
    </div>
    <div class="step-container">
        <span class="step-num">3</span> <span class="step-title">Instant Carry Forward Auto-Loading:</span>
        The system immediately scans previous return records for this driver. If unsold stock was marked as <em>Carry Forward</em>:
        <ul>
            <li>A prominent <strong>Carry Forward Banner</strong> appears, showing the previous return date.</li>
            <li>The product rows are <strong>automatically populated</strong> row-wise in the table.</li>
            <li>Each row is pre-selected with the product, exact Cases, and Loose Bottles.</li>
            <li>A special badge <span class="badge badge-cyan">Carry Forward Stock</span> identifies these items.</li>
        </ul>
    </div>

    <div class="callout callout-highlight">
        <strong>Supervisor Control:</strong> Auto-loaded carry forward rows are fully editable. You can adjust the cases/bottles, 
        or remove rows with the red trash button if plans change.
    </div>

    <h2>5.2 Stock-Filtered Product Selection & Real-Time Validation</h2>
    <p>
        To prevent dispatching items that are not in the warehouse, the <strong>Product Name</strong> dropdown applies strict 
        stock-based filtering:
    </p>
    <ul>
        <li><strong>Only Available Stock Shown:</strong> Products with 0 cases and 0 bottles are automatically excluded from the dropdown.</li>
        <li><strong>Stock Badge:</strong> When a product is selected, an instant badge shows available inventory (e.g. <code>Available: 23 Cases + 7 Bottles</code>).</li>
        <li><strong>Over-Assignment Prevention:</strong> If a supervisor types a quantity exceeding warehouse stock, a clear warning appears:
            <br><code style="color: #b91c1c;">Warning: Requested quantity exceeds currently available warehouse stock!</code>
        </li>
    </ul>

    <h2>5.3 Adding Extra Products & Submitting</h2>
    <div class="step-container">
        <span class="step-num">4</span> <span class="step-title">Add Additional Products:</span>
        Click the fixed green <strong>"+ Add Another Product"</strong> button at the bottom of the list to add new rows for fresh inventory.
    </div>
    <div class="step-container">
        <span class="step-num">5</span> <span class="step-title">Submit Assignment:</span>
        Click <strong>"Assign Products"</strong>. Warehouse inventory is updated instantly and the driver\'s route manifest is activated.
    </div>

    <!-- SECTION 6 -->
    <div class="page-break"></div>
    <h1>6. Daily Driver-wise Sales Report & Reconciliation</h1>
    <p>
        The <strong>Daily Driver-wise Sales Report</strong> (<code>/warehouse/daily-sales-report</code>) is the central reconciliation 
        hub where the warehouse reviews daily performance, tracks deliveries, and settles unsold driver stock.
    </p>

    <h2>6.1 Top KPI Summary Cards</h2>
    <p>At the top of the report, 5 summary metric cards provide an immediate snapshot of the warehouse\'s daily performance:</p>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">KPI Card</th>
                <th style="width: 20%;">Color / Accent</th>
                <th>Operational Meaning</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Overall Total Sales</strong></td>
                <td><span class="badge badge-success">Green (₹)</span></td>
                <td>Total monetary value of verified sales generated across all drivers for the selected date.</td>
            </tr>
            <tr>
                <td><strong>Total Assigned</strong></td>
                <td><span class="badge badge-info">Info Blue</span></td>
                <td>Total stock dispatched to drivers in the morning (Cases + Loose Bottles).</td>
            </tr>
            <tr>
                <td><strong>Carry Forward</strong></td>
                <td><span class="badge badge-cyan">Cyan Blue</span></td>
                <td>Total unsold stock brought back by drivers and added back into warehouse inventory.</td>
            </tr>
            <tr>
                <td><strong>Total Returned</strong></td>
                <td><span class="badge badge-warning">Amber Warning</span></td>
                <td>Total rejected, damaged, or physical returns that are NOT restocked into inventory.</td>
            </tr>
            <tr>
                <td><strong>Total Cases Sold</strong></td>
                <td><span class="badge badge-primary">Royal Blue</span></td>
                <td>Actual verified sold stock: <code>Sold = Assigned - (Carry Forward + Returned)</code>.</td>
            </tr>
        </tbody>
    </table>

    <h2>6.2 Main Summary Table Columns</h2>
    <p>The drivers table is arranged in a logical operational workflow order:</p>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Column Name</th>
                <th>Display Format</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><strong>#</strong></td>
                <td>Number</td>
                <td>Serial row index.</td>
            </tr>
            <tr>
                <td>2</td>
                <td><strong>Driver Name</strong></td>
                <td>Text + Badge</td>
                <td>Driver\'s name with Active / Inactive status indicator.</td>
            </tr>
            <tr>
                <td>3</td>
                <td><strong>Total Assigned</strong></td>
                <td><span class="badge badge-info">X Cases + Y Bottles</span></td>
                <td>Total quantity dispatched to the driver on this date.</td>
            </tr>
            <tr>
                <td>4</td>
                <td><strong>Carry Forward</strong></td>
                <td><span class="badge badge-cyan">X Cases + Y Bottles</span></td>
                <td>Stock returned to warehouse to carry over for next day.</td>
            </tr>
            <tr>
                <td>5</td>
                <td><strong>Total Returned</strong></td>
                <td><span class="badge badge-warning">X Cases + Y Bottles</span></td>
                <td>Physical return / damage recorded (Verified badge shown when confirmed).</td>
            </tr>
            <tr>
                <td>6</td>
                <td><strong>Total Sold</strong></td>
                <td><span class="badge badge-primary">X Cases + Y Bottles</span></td>
                <td>Net quantity delivered to retail stores / customers.</td>
            </tr>
            <tr>
                <td>7</td>
                <td><strong>Total Sales (₹)</strong></td>
                <td>Currency (₹)</td>
                <td>Net revenue collected or billed.</td>
            </tr>
            <tr>
                <td>8</td>
                <td><strong>Actions</strong></td>
                <td>Buttons</td>
                <td><strong>Breakdown:</strong> View product-level detail.<br><strong>Return:</strong> Record physical return.</td>
            </tr>
        </tbody>
    </table>

    <!-- SECTION 7 -->
    <div class="page-break"></div>
    <h1>7. Recording Physical Returns: Carry Forward vs. Return</h1>
    <div class="callout callout-warning">
        <strong>CRITICAL OPERATIONAL PROCEDURE:</strong> At the end of every shift, drivers bring back unsold stock, customer rejections, 
        or damaged bottles. The warehouse supervisor must record these in the <strong>Record Physical Return Modal</strong>.
    </div>

    <h2>7.1 Step-by-Step Return Recording Process</h2>
    <div class="step-container">
        <span class="step-num">1</span> <span class="step-title">Open Return Modal:</span>
        On the Daily Sales Report table, locate the driver\'s row and click the amber <strong>"Return"</strong> button.
    </div>
    <div class="step-container">
        <span class="step-num">2</span> <span class="step-title">Automatic Assigned Stock Loading:</span>
        The large modal opens. All products assigned to this driver on the selected date are <strong>automatically loaded</strong> 
        into the table with their assigned cases and bottles clearly displayed in the <em>Assigned Stock</em> column.
    </div>
    <div class="step-container">
        <span class="step-num">3</span> <span class="step-title">Enter Returned Quantities:</span>
        For each product the driver brought back, enter the returned <strong>Cases</strong> and <strong>Loose Bottles</strong>.
    </div>
    <div class="step-container">
        <span class="step-num">4</span> <span class="step-title">Choose Stock Treatment (Mutually Exclusive Checkboxes):</span>
        For each returned product row, select exactly ONE option:
        <ul>
            <li><strong>[ &check; ] Carry Forward:</strong> Select this if the stock is good, salable merchandise that should be added back into warehouse stock for tomorrow\'s route.</li>
            <li><strong>[ &check; ] Return:</strong> Select this if the stock is broken, spoiled, expired, or customer rejected that must NOT be put back into salable stock.</li>
        </ul>
    </div>
    <div class="step-container">
        <span class="step-num">5</span> <span class="step-title">Save & Verify:</span>
        Add any operational notes (e.g. "2 broken bottles in transit") and click <strong>"Save Return"</strong>.
    </div>

    <h2>7.2 The Gold Standard Business Rules</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Return Category</th>
                <th style="width: 35%;">Warehouse Inventory Impact</th>
                <th>Next-Day Dispatch Impact</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong style="color: #0284c7;">Carry Forward</strong><br><code>(is_carry_forward = 1)</code></td>
                <td><strong>Stock IS added back</strong> into warehouse inventory balance immediately.</td>
                <td><strong>Auto-populates row-wise</strong> on the Product Assign screen when assigning this driver next day.</td>
            </tr>
            <tr>
                <td><strong style="color: #b45309;">Return</strong><br><code>(is_return = 1)</code></td>
                <td><strong>Stock is NOT added back</strong> to salable inventory (quarantined/damaged).</td>
                <td>Does NOT pre-fill on next-day dispatch. Recorded as loss/return on sales reports.</td>
            </tr>
        </tbody>
    </table>

    <!-- SECTION 8 & 9 -->
    <div class="page-break"></div>
    <h1>8. Product Daily Sales Report & Export</h1>
    <p>
        The <strong>Product Daily Sales Report</strong> (<code>/warehouse/product-daily-sales-report</code>) provides an aggregated, 
        product-centric perspective across all delivery drivers for the warehouse.
    </p>
    <h2>8.1 Key Features</h2>
    <ul>
        <li><strong>Cross-Driver Aggregation:</strong> Displays total assigned, returned, and sold cases/bottles per catalog SKU across the whole depot.</li>
        <li><strong>View Mode Filter:</strong> Toggle between <em>"Active Products Only"</em> (products dispatched today) and <em>"All Catalog Products"</em>.</li>
        <li><strong>Driver Breakdown Modal:</strong> Click any product to see exactly which drivers took that product and how many each sold.</li>
        <li><strong>One-Click CSV Export:</strong> Click the <strong>"Export CSV"</strong> button to download a spreadsheet report for accounting or ERP upload.</li>
    </ul>

    <h1>9. Invoice Management Module</h1>
    <p>
        The <strong>Invoice Management</strong> module (<code>/warehouse/invoice</code>) allows warehouse staff to monitor real-time 
        customer billing generated by drivers using their mobile applications during delivery runs.
    </p>
    <table class="data-table">
        <thead>
            <tr>
                <th>Field</th>
                <th>Operational Information</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Invoice Number</strong></td>
                <td>Unique billing ID (e.g. <code>INV-2026-0042</code>).</td>
            </tr>
            <tr>
                <td><strong>Customer / Store</strong></td>
                <td>Retail outlet or customer name and address.</td>
            </tr>
            <tr>
                <td><strong>Delivered By</strong></td>
                <td>Name of the driver who fulfilled the order.</td>
            </tr>
            <tr>
                <td><strong>Invoice Amount</strong></td>
                <td>Total invoice value in INR (₹).</td>
            </tr>
            <tr>
                <td><strong>Payment Status</strong></td>
                <td>Cash on Delivery (COD), Online UPI, or Credit.</td>
            </tr>
            <tr>
                <td><strong>Actions</strong></td>
                <td>View detailed line-items, print duplicate invoice copy, or download PDF.</td>
            </tr>
        </tbody>
    </table>

    <!-- SECTION 10 & 11 -->
    <div class="page-break"></div>
    <h1>10. Daily Operating Routine (SOP Checklist)</h1>
    <p>Follow this standard operating routine daily to ensure 100% stock accuracy:</p>

    <div class="callout callout-primary">
        <h3 style="margin-top:0; color:#101C4C;">Morning Dispatch Routine (07:00 AM - 09:30 AM)</h3>
        <ol style="margin-bottom:0; padding-left:20px;">
            <li>Log into the Warehouse Panel and verify stock balances in <strong>Stock Management</strong>.</li>
            <li>Open <strong>Product Assign</strong> (<code>/warehouse/driver-product-create</code>).</li>
            <li>Select the driver. Review the automatically loaded <strong>Carry Forward Stock</strong> rows.</li>
            <li>Add extra products requested by the driver using <strong>"+ Add Another Product"</strong>.</li>
            <li>Physically load and verify the vehicle against the displayed quantities.</li>
            <li>Click <strong>"Assign Products"</strong> to seal the morning manifest.</li>
        </ol>
    </div>

    <div class="callout callout-warning">
        <h3 style="margin-top:0; color:#78350f;">Evening Settlement Routine (05:30 PM - 08:00 PM)</h3>
        <ol style="margin-bottom:0; padding-left:20px;">
            <li>When the driver returns, open <strong>Driver Daily Sales</strong> (<code>/warehouse/daily-sales-report</code>).</li>
            <li>Click the amber <strong>"Return"</strong> button on the driver\'s row.</li>
            <li>Count unsold stock physically brought back by the driver.</li>
            <li>Mark salable goods as <strong>Carry Forward</strong> (adds back to warehouse stock).</li>
            <li>Mark broken or rejected goods as <strong>Return</strong> (does not add to stock).</li>
            <li>Click <strong>"Save Return"</strong>. Verify that the <em>Total Sold</em> and <em>Total Sales (₹)</em> match driver cash/UPI collections.</li>
        </ol>
    </div>

    <h1>11. FAQs & Troubleshooting</h1>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40%;">Question / Issue</th>
                <th>Resolution / Solution</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Why is a product missing from the Product Name dropdown?</strong></td>
                <td>The dropdown only displays products with available stock in the warehouse. If a product has 0 cases and 0 bottles, inward stock first before assigning it to a driver.</td>
            </tr>
            <tr>
                <td><strong>What happens if I check Carry Forward?</strong></td>
                <td>The returned stock is instantly added back into warehouse inventory, and tomorrow when you select this driver, it will automatically populate row-wise.</td>
            </tr>
            <tr>
                <td><strong>Can I edit a driver\'s assignment after submitting?</strong></td>
                <td>Yes. Go to <strong>Driver Management &rarr; Report List</strong>, find the assignment record, and click <strong>Edit</strong>.</td>
            </tr>
            <tr>
                <td><strong>Can I assign products for tomorrow\'s date?</strong></td>
                <td>Yes. In the Assign Date input, choose tomorrow\'s date. The system will detect and load any carry forward stock up to that date.</td>
            </tr>
        </tbody>
    </table>

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
    $publicPath = public_path('manuals/Warehouse_Panel_User_Manual.pdf');
    $rootPath = base_path('Warehouse_Panel_User_Manual.pdf');

    $output = $pdf->output();
    file_put_contents($publicPath, $output);
    file_put_contents($rootPath, $output);

    echo "PDF generated successfully!" . PHP_EOL;
    echo "Saved to: " . $publicPath . " (" . strlen($output) . " bytes)" . PHP_EOL;
    echo "Saved to: " . $rootPath . PHP_EOL;
} catch (\Throwable $e) {
    echo "Error generating PDF: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
