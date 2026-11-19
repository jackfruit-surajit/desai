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
    <title>Admin Control Panel User Manual - Desai Delivery</title>
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
            font-size: 26pt;
            font-weight: bold;
            color: #101C4C;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .cover-subtitle {
            font-size: 14pt;
            color: #35429E;
            margin-bottom: 25px;
            font-weight: 600;
        }
        .cover-badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 10.5pt;
            margin-bottom: 40px;
            border: 1px solid #bae6fd;
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
            background: #35429E;
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

        /* Code and UI elements */
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
            color: #35429E;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- COVER PAGE -->
    <div class="cover">
        ' . ($logoBase64 ? '<img src="' . $logoBase64 . '" class="cover-logo" alt="Desai Delivery Logo"/>' : '') . '
        <div class="cover-title">ADMIN CONTROL PANEL</div>
        <div class="cover-subtitle">Master Operations & Territory Administration User Manual</div>
        <div>
            <span class="cover-badge">ENTERPRISE SYSTEM EDITION &bull; VERSION 2.0</span>
        </div>
        <div class="cover-desc">
            A comprehensive, standard operating procedure (SOP) manual for Head Office Administrators, Operations Heads, and System Managers. Covers Master Product Catalog setup, 3-Tier Territory Management (State &rarr; Area &rarr; Route), Fleet & Staff Onboarding, Route Arrangement & Beat Sequencing, Real-Time Field Attendance & Tracking, and Full MIS Financial Reporting.
        </div>

        <div class="cover-meta">
            <table>
                <tr>
                    <td style="width: 25%;"><strong>Document Reference:</strong></td>
                    <td style="width: 35%;">DDMS-SOP-ADM-2026-V2</td>
                    <td style="width: 20%;"><strong>Target Roles:</strong></td>
                    <td style="width: 20%;">Super Admin, Operations Admin</td>
                </tr>
                <tr>
                    <td><strong>Software System:</strong></td>
                    <td>Desai Delivery & Distribution System</td>
                    <td><strong>Access Portal:</strong></td>
                    <td><code>/admin</code></td>
                </tr>
                <tr>
                    <td><strong>Last Revised:</strong></td>
                    <td>November 2026</td>
                    <td><strong>Classification:</strong></td>
                    <td>Confidential & Official</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- TABLE OF CONTENTS -->
    <h1>Table of Contents</h1>
    <ul class="toc-list">
        <li><strong>1. System Overview & Administrative Scope</strong> <span class="toc-page">Page 3</span></li>
        <li><strong>2. Access Control, Security & Session Management</strong> <span class="toc-page">Page 4</span></li>
        <li><strong>3. Master Product Catalog & Pricing Architecture</strong> <span class="toc-page">Page 5</span></li>
        <li><strong>4. Geographic & Territory Hierarchy Management</strong> <span class="toc-page">Page 7</span></li>
        <li><strong>5. Commercial Fleet & Vehicle Directory</strong> <span class="toc-page">Page 9</span></li>
        <li><strong>6. Staff & Personnel Management (Drivers, Executives, Warehouses)</strong> <span class="toc-page">Page 10</span></li>
        <li><strong>7. Operational Bindings: Road Arrangement & Warehouse Binding</strong> <span class="toc-page">Page 12</span></li>
        <li><strong>8. Customer & Retail Store Management</strong> <span class="toc-page">Page 14</span></li>
        <li><strong>9. Real-Time Field Attendance & GPS Route Tracking</strong> <span class="toc-page">Page 15</span></li>
        <li><strong>10. MIS Reporting & Financial Sales Invoice Suite</strong> <span class="toc-page">Page 17</span></li>
        <li><strong>11. Standard Operating Procedures (SOP) & Daily Checklists</strong> <span class="toc-page">Page 19</span></li>
        <li><strong>12. Administrative FAQs & Troubleshooting Matrix</strong> <span class="toc-page">Page 20</span></li>
    </ul>

    <div class="callout callout-info" style="margin-top: 30px;">
        <strong>Notice to System Administrators:</strong> The Admin Panel serves as the single source of truth for the entire supply chain. Configurations established here (such as Bottles Per Case, territory boundaries, driver route orders, and warehouse links) directly control mobile apps used by field drivers and sales representatives, as well as regional depot portals.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 1 -->
    <h1>1. System Overview & Administrative Scope</h1>
    <p>
        The <strong>Desai Delivery & Distribution Management System (DDMS)</strong> is an enterprise-grade platform connecting manufacturing plants, regional distribution warehouses, retail customer stores, and mobile field personnel (drivers and sales executives).
    </p>
    <p>
        The <strong>Admin Control Panel</strong> is situated at the top of the system hierarchy. While regional warehouses manage daily dispatches and physical inventory, and manufacturers record factory output, the Admin Panel governs master rules, master catalogs, financial ledgers, and cross-territorial operations.
    </p>

    <h2>1.1 Key Modules at a Glance</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Module Name</th>
                <th style="width: 35%;">Core Responsibilities</th>
                <th style="width: 40%;">Key URL & Endpoints</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Master Products</strong></td>
                <td>Catalog maintenance, pricing, Bottles-Per-Case (BPC), GST taxes, HSN codes.</td>
                <td><code>/admin/products</code><br/><code>/admin/product-create</code></td>
            </tr>
            <tr>
                <td><strong>Territory Management</strong></td>
                <td>3-Tier structure: States &rarr; Operational Areas &rarr; Delivery Roads / Beats.</td>
                <td><code>/admin/states</code><br/><code>/admin/areas</code><br/><code>/admin/roads</code></td>
            </tr>
            <tr>
                <td><strong>Vehicle Fleet</strong></td>
                <td>Delivery vans, mini-trucks, registration numbers, types, operational status.</td>
                <td><code>/admin/vehicles</code><br/><code>/admin/vehicle-add</code></td>
            </tr>
            <tr>
                <td><strong>Staff Management</strong></td>
                <td>Drivers, Sales Executives, Warehouse Incharges; licenses, profiles, credentials.</td>
                <td><code>/admin/drivers</code><br/><code>/admin/sales-executives</code><br/><code>/admin/warehouses</code></td>
            </tr>
            <tr>
                <td><strong>Routing & Arrangement</strong></td>
                <td>Assigning areas to drivers, sorting route order (Stop 1, Stop 2), warehouse binding.</td>
                <td><code>/admin/driver-assign-location/{id}</code><br/><code>/admin/driver-road-arrangement</code></td>
            </tr>
            <tr>
                <td><strong>Customers / Shops</strong></td>
                <td>Retail stores, trade names, road affiliations, contact phone, GSTIN numbers.</td>
                <td><code>/admin/customer-list</code><br/><code>/admin/customer-add</code></td>
            </tr>
            <tr>
                <td><strong>MIS & Audit Reports</strong></td>
                <td>Real-time punch-in attendance, monthly logs, route logs, sales invoices, delivery history.</td>
                <td><code>/admin/invoice-list</code><br/><code>/admin/driver-attendance</code><br/><code>/admin/shop-delivery-history</code></td>
            </tr>
        </tbody>
    </table>

    <h2>1.2 Architectural Data Flow</h2>
    <p>
        Master parameters configured in the Admin Panel cascade throughout the platform as illustrated below:
    </p>
    <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:6px; padding:12px; margin-bottom:15px; font-size:9pt; line-height:1.7;">
        <strong style="color:#101C4C;">ADMIN SETUP:</strong> Define Products (with BPC) + Create Areas & Roads + Onboard Drivers & Bind to Warehouses<br/>
        &nbsp;&nbsp;&darr;<br/>
        <strong style="color:#35429E;">MANUFACTURER PORTAL:</strong> Inwards Production &rarr; Dispatches Bulk Stock to Regional Warehouses<br/>
        &nbsp;&nbsp;&darr;<br/>
        <strong style="color:#0369a1;">WAREHOUSE PORTAL:</strong> Receives Factory Consignment &rarr; Assigns Stock to Drivers based on Admin Territory Routes<br/>
        &nbsp;&nbsp;&darr;<br/>
        <strong style="color:#047857;">FIELD EXECUTION (MOBILE):</strong> Driver clocks in (Selfie Attendance) &rarr; Delivers to Retailers along configured Road Sequence &rarr; Issues Printed Invoices<br/>
        &nbsp;&nbsp;&darr;<br/>
        <strong style="color:#b45309;">SETTLEMENT & AUDIT (ADMIN):</strong> Admin audits completed delivery history, driver daily sales invoices, and route completion logs.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 2 -->
    <h1>2. Access Control, Security & Session Management</h1>
    <p>
        The Admin Panel is protected by multi-tier authentication middleware. Only authenticated users with Role ID <code>1</code> (Super Admin) have unrestricted access to all endpoints.
    </p>

    <h2>2.1 Authentication Workflow</h2>
    <div class="step-box">
        <span class="step-num">1</span> <strong>Navigation:</strong> Open web browser and navigate to <code>https://&lt;domain&gt;/admin/login</code>.
    </div>
    <div class="step-box">
        <span class="step-num">2</span> <strong>Credentials:</strong> Enter authorized Admin Email Address and Secret Password.
    </div>
    <div class="step-box">
        <span class="step-num">3</span> <strong>Dashboard Entry:</strong> Upon successful verification, the system initiates an administrative session and redirects to <code>/admin/dashboard</code>.
    </div>

    <div class="callout callout-warning">
        <strong>Session Security Warning:</strong> Always log out using the <strong>Logout</strong> button in the top navigation profile dropdown before leaving an administrative workstation unattended. Sessions automatically timeout after inactivity to prevent unauthorized access.
    </div>

    <h2>2.2 Role Hierarchy Matrix</h2>
    <p>
        The DDMS security model uses granular roles across five operational tiers:
    </p>
    <table class="data-table">
        <thead>
            <tr>
                <th>Role ID</th>
                <th>Role Name</th>
                <th>Primary Workspace</th>
                <th>Permissions & Scope</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="badge badge-primary">1</span></td>
                <td><strong>Super Admin</strong></td>
                <td><code>/admin/*</code></td>
                <td>Full system control, catalog pricing, user creation, territory definitions, audit reports.</td>
            </tr>
            <tr>
                <td><span class="badge badge-info">2</span></td>
                <td><strong>Sales Executive</strong></td>
                <td>Mobile App / Web</td>
                <td>Field store visits, booking orders, collecting retailer requests across assigned areas.</td>
            </tr>
            <tr>
                <td><span class="badge badge-success">3</span></td>
                <td><strong>Driver</strong></td>
                <td>Mobile App / Web</td>
                <td>Punch-in attendance, route navigation, stock loading, customer deliveries, invoicing.</td>
            </tr>
            <tr>
                <td><span class="badge badge-warning">4</span></td>
                <td><strong>Warehouse Depot</strong></td>
                <td><code>/warehouse/*</code></td>
                <td>Regional inventory receiving, driver stock assignment, evening carry-forward & returns.</td>
            </tr>
            <tr>
                <td><span class="badge badge-neutral">5</span></td>
                <td><strong>Manufacturer</strong></td>
                <td><code>/manufacture/*</code></td>
                <td>Plant production logging, factory buffer stock, multi-warehouse stock monitoring.</td>
            </tr>
        </tbody>
    </table>

    <h2>2.3 Admin Profile & Password Update</h2>
    <p>
        Administrators can update their profile information and secure credentials at any time:
    </p>
    <ul>
        <li><strong>Profile Management:</strong> Navigate to <code>/admin/admin-profile</code> to update your contact name and email address.</li>
        <li><strong>Password Change:</strong> Submit old password and new password under <code>/admin/admin-change-password</code>. Passwords must be at least 8 characters with alphanumeric complexity.</li>
    </ul>

    <div class="page-break"></div>

    <!-- CHAPTER 3 -->
    <h1>3. Master Product Catalog & Pricing Architecture</h1>
    <p>
        The Master Product Catalog (<code>/admin/products</code>) is the central product registry. Every transaction in DDMS—factory production, depot inwards, driver assignment, mobile sales, and invoice printing—references the parameters established here.
    </p>

    <h2>3.1 Core Product Attributes</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Field Name</th>
                <th style="width: 20%;">Type</th>
                <th>Description & Operational Impact</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Product Name</strong></td>
                <td>Text (Unique)</td>
                <td>Full trade name of the beverage SKU (e.g., <em>Desai Soda 250ml</em>, <em>Club Soda 500ml</em>).</td>
            </tr>
            <tr>
                <td><strong>Per Case Quantity (BPC)</strong></td>
                <td>Integer</td>
                <td><strong>Bottles Per Case:</strong> The standard unit multiplier. For example, if BPC=24, 1 Case equals exactly 24 individual bottles. Crucial for dual-unit inventory calculations.</td>
            </tr>
            <tr>
                <td><strong>Per Case Price (₹)</strong></td>
                <td>Decimal / Currency</td>
                <td>Wholesale invoice billing rate per complete sealed case charged to retail stores.</td>
            </tr>
            <tr>
                <td><strong>Per Bottle Price (₹)</strong></td>
                <td>Decimal / Currency</td>
                <td>Loose bottle billing rate when retailers purchase partial or loose units.</td>
            </tr>
            <tr>
                <td><strong>CGST (%)</strong></td>
                <td>Decimal / Percentage</td>
                <td>Central Goods and Services Tax percentage (e.g., 6.00% or 9.00%).</td>
            </tr>
            <tr>
                <td><strong>SGST (%)</strong></td>
                <td>Decimal / Percentage</td>
                <td>State Goods and Services Tax percentage (e.g., 6.00% or 9.00%).</td>
            </tr>
            <tr>
                <td><strong>HSN Code</strong></td>
                <td>Alphanumeric</td>
                <td>Harmonized System of Nomenclature code printed on official GST tax invoices (e.g., <code>220210</code>).</td>
            </tr>
            <tr>
                <td><strong>Status Switch</strong></td>
                <td>Toggle (1/0)</td>
                <td>Active/Inactive flag. Inactive products are instantly hidden from driver assignment and mobile apps.</td>
            </tr>
        </tbody>
    </table>

    <h2>3.2 Step-by-Step: Adding a New Product</h2>
    <div class="step-box">
        <span class="step-num">1</span> <strong>Navigate to Product Management:</strong> Click <strong>Product Management</strong> in the sidebar (<code>/admin/products</code>).
    </div>
    <div class="step-box">
        <span class="step-num">2</span> <strong>Open Create Form:</strong> Click the blue <strong>"Add Product"</strong> button in the upper right.
    </div>
    <div class="step-box">
        <span class="step-num">3</span> <strong>Fill Specifications:</strong>
        <ul style="margin-top: 5px; margin-bottom: 5px; padding-left: 20px;">
            <li>Enter unique <em>Product Name</em>.</li>
            <li>Enter <em>Per Case Quantity</em> (number of bottles inside each carton).</li>
            <li>Enter <em>Per Case Price</em> and <em>Per Bottle Price</em> in INR.</li>
            <li>Input appropriate <em>CGST (%)</em> and <em>SGST (%)</em> rates.</li>
            <li>Provide official <em>HSN Code</em> for taxation compliance.</li>
            <li>Set Status to <strong>Active</strong>.</li>
        </ul>
    </div>
    <div class="step-box">
        <span class="step-num">4</span> <strong>Save & Verify:</strong> Click <strong>"Submit"</strong>. The product is immediately registered and made available to all warehouses and mobile apps.
    </div>

    <div class="callout callout-danger">
        <strong>Critical Note on BPC (Bottles Per Case):</strong> Never modify the BPC of an active product that already has live stock in warehouses or active driver assignments. If packaging configuration changes (e.g., switching from 24 bottles to 12 bottles per case), create a new product entry to preserve mathematical consistency in historical stock and invoice ledgers.
    </div>

    <h2>3.3 Editing & Managing Existing Products</h2>
    <p>
        In the Product Datatable (<code>/admin/products</code>), each row provides administrative controls:
    </p>
    <ul>
        <li><strong>Active/Inactive Toggle Switch:</strong> Click the switch to toggle product visibility. Changing to Inactive prevents warehouses from assigning the product without deleting historical records.</li>
        <li><strong>Edit Button:</strong> Modify prices, tax rates, or HSN codes. Changes take effect on future invoices immediately.</li>
        <li><strong>Delete Button:</strong> Safely deletes product records only if no foreign key constraints exist in sales or stock ledgers.</li>
    </ul>

    <div class="page-break"></div>

    <!-- CHAPTER 4 -->
    <h1>4. Geographic & Territory Hierarchy Management</h1>
    <p>
        The DDMS operates on a strict <strong>3-Tier Geographic Model</strong>. This structure ensures accurate customer shop registration, sales beat planning, and fuel-efficient driver routing.
    </p>

    <div style="text-align: center; margin: 15px 0;">
        <span class="badge badge-primary" style="font-size: 11pt; padding: 6px 14px;">TIER 1: STATE</span>
        &nbsp;&rarr;&nbsp;
        <span class="badge badge-info" style="font-size: 11pt; padding: 6px 14px;">TIER 2: AREA (CLUSTER)</span>
        &nbsp;&rarr;&nbsp;
        <span class="badge badge-success" style="font-size: 11pt; padding: 6px 14px;">TIER 3: ROAD / ROUTE (BEAT)</span>
    </div>

    <h2>4.1 Tier 1: State Management (<code>/admin/states</code>)</h2>
    <p>
        Represents regional jurisdictions (e.g., <em>West Bengal</em>, <em>Odisha</em>, <em>Bihar</em>).
    </p>
    <ul>
        <li>Navigate to <strong>State Management</strong> &rarr; Click <strong>"Add State"</strong>.</li>
        <li>Enter <em>State Name</em> and toggle <em>Status</em>.</li>
        <li>States establish the tax framework (CGST/SGST vs IGST) and partition operational branches.</li>
    </ul>

    <h2>4.2 Tier 2: Area Management (<code>/admin/areas</code>)</h2>
    <p>
        Areas represent municipal zones, sub-districts, or distribution clusters within a state (e.g., <em>Kolkata North</em>, <em>Howrah Urban</em>, <em>Barasat Central</em>).
    </p>
    <ul>
        <li>Navigate to <strong>Area Management</strong> &rarr; Click <strong>"Add Area"</strong>.</li>
        <li>Select the parent <strong>State</strong> from the dropdown.</li>
        <li>Enter the unique <strong>Area Name</strong>.</li>
        <li>Areas are assigned to Drivers and Sales Executives to define their operational jurisdiction.</li>
    </ul>

    <h2>4.3 Tier 3: Route / Road Management (<code>/admin/roads</code>)</h2>
    <p>
        Roads represent specific physical delivery beats, streets, markets, or commercial corridors where retail customer stores are located (e.g., <em>B.T. Road Beat</em>, <em>Gariahat Market Road</em>, <em>Station Bazaar Lane</em>).
    </p>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Field</th>
                <th style="width: 25%;">Mandatory?</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>State</strong></td>
                <td>Yes</td>
                <td>Parent state dropdown selection.</td>
            </tr>
            <tr>
                <td><strong>Area</strong></td>
                <td>Yes</td>
                <td>Dynamically populated area dropdown based on selected state.</td>
            </tr>
            <tr>
                <td><strong>Road / Route Name</strong></td>
                <td>Yes</td>
                <td>Name of the physical street or commercial market beat.</td>
            </tr>
            <tr>
                <td><strong>Full Address / Landmark</strong></td>
                <td>Optional</td>
                <td>Geographic context, starting landmarks, or pin code notes.</td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>Yes</td>
                <td>Active/Inactive switch.</td>
            </tr>
        </tbody>
    </table>

    <div class="callout callout-info">
        <strong>Importance for Sales & Delivery:</strong> When retail stores are registered, they are bound to a specific Road. When a driver is assigned an Area, all roads within that area are ordered into an optimal sequence, which guides the driver\'s mobile navigation step-by-step.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 5 -->
    <h1>5. Commercial Fleet & Vehicle Directory</h1>
    <p>
        The <strong>Vehicle Management</strong> module (<code>/admin/vehicles</code>) maintains the enterprise fleet directory of transport vehicles (vans, mini-trucks, three-wheelers) used for stock distribution.
    </p>

    <h2>5.1 Vehicle Information Matrix</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Attribute</th>
                <th style="width: 20%;">Format</th>
                <th>Operational Use</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Vehicle Number</strong></td>
                <td>String (e.g. WB-01-AB-1234)</td>
                <td>Unique commercial registration number displayed on trip sheets and daily dispatch registers.</td>
            </tr>
            <tr>
                <td><strong>Vehicle Type</strong></td>
                <td>Dropdown</td>
                <td>Category (e.g. <em>Tata Ace, Mahindra Bolero Maxi Truck, E-Rickshaw, Three-Wheeler</em>).</td>
            </tr>
            <tr>
                <td><strong>Load Capacity</strong></td>
                <td>Numeric (Cartons / KG)</td>
                <td>Maximum payload capacity to ensure drivers are not assigned stock exceeding vehicle weight limits.</td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>Active / Inactive</td>
                <td>Active allows vehicle assignment; Inactive flags vehicles under maintenance or out of service.</td>
            </tr>
        </tbody>
    </table>

    <h2>5.2 Onboarding a Vehicle (Step-by-Step)</h2>
    <div class="step-box">
        <span class="step-num">1</span> Navigate to <strong>Vehicle Management</strong> (<code>/admin/vehicles</code>) &rarr; Click <strong>"Add Vehicle"</strong>.
    </div>
    <div class="step-box">
        <span class="step-num">2</span> Enter the official <strong>Vehicle Registration Number</strong> without spaces or with standard hyphens.
    </div>
    <div class="step-box">
        <span class="step-num">3</span> Select <strong>Vehicle Type</strong> and input payload specifications.
    </div>
    <div class="step-box">
        <span class="step-num">4</span> Set status to <strong>Active</strong> and submit. The vehicle is now ready to be assigned to transport drivers.
    </div>

    <div class="callout callout-warning">
        <strong>Fleet Maintenance Rule:</strong> When a vehicle is sent for periodic maintenance or insurance renewal, toggle its status to <strong>Inactive</strong>. This prevents depot managers from allocating trips to an unroadworthy vehicle.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 6 -->
    <h1>6. Staff & Personnel Management</h1>
    <p>
        The <strong>Staff Management</strong> menu manages three field and operational roles: <strong>Drivers</strong>, <strong>Sales Executives</strong>, and <strong>Warehouse Depots</strong>.
    </p>

    <h2>6.1 Driver Management (<code>/admin/drivers</code>)</h2>
    <p>
        Drivers are responsible for transporting beverage goods from warehouses to customer retail shops, executing mobile invoicing, collecting payments, and returning unsold stock.
    </p>

    <h3>Driver Onboarding Requirements:</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Field</th>
                <th style="width: 25%;">Validation Rule</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Driver Name</strong></td>
                <td>Letters & Spaces only</td>
                <td>Full legal name of the transport driver.</td>
            </tr>
            <tr>
                <td><strong>Contact Phone</strong></td>
                <td>Exact 10 digits</td>
                <td>Primary mobile number used for login and customer contact.</td>
            </tr>
            <tr>
                <td><strong>Email & Password</strong></td>
                <td>Valid Email & Secret</td>
                <td>Authentication credentials for the driver\'s mobile application.</td>
            </tr>
            <tr>
                <td><strong>Transport Vehicle</strong></td>
                <td>Active Vehicle Dropdown</td>
                <td>The primary vehicle allocated to this driver.</td>
            </tr>
            <tr>
                <td><strong>Assigned Warehouse</strong></td>
                <td>Active Depot Dropdown</td>
                <td>The specific warehouse depot where the driver loads daily stock.</td>
            </tr>
            <tr>
                <td><strong>Driving Licence No</strong></td>
                <td>Alphanumeric (Max 100)</td>
                <td>Commercial driver licence identifier.</td>
            </tr>
            <tr>
                <td><strong>Licence Expiry Date</strong></td>
                <td>Valid Future Date</td>
                <td>Licence validity date for regulatory compliance.</td>
            </tr>
            <tr>
                <td><strong>Assigned Area</strong></td>
                <td>Area Dropdown</td>
                <td>Primary delivery area assigned to this driver.</td>
            </tr>
            <tr>
                <td><strong>Driver Photo</strong></td>
                <td>PNG/JPG (Max 5MB)</td>
                <td>Driver portrait photo for identification badges and customer invoices.</td>
            </tr>
        </tbody>
    </table>

    <h2>6.2 Sales Executive Management (<code>/admin/sales-executives</code>)</h2>
    <p>
        Sales Executives visit retail markets, collect pre-orders, register new store accounts, and gather market feedback.
    </p>
    <ul>
        <li><strong>Multi-Area Assignment:</strong> Unlike drivers who usually serve one primary zone per shift, a Sales Executive can be assigned up to <strong>5 Operational Areas</strong> simultaneously.</li>
        <li><strong>Field Visit History:</strong> Every physical store visit logged by an executive (with mandatory shop storefront photo) is audited under <code>/admin/sales-visit-history</code>.</li>
    </ul>

    <h2>6.3 Warehouse Depot Incharges (<code>/admin/warehouses</code>)</h2>
    <p>
        Represents regional physical warehouse depots (Role ID <code>4</code>). Creating a warehouse profile generates a secure login portal (<code>/warehouse/login</code>) where depot managers manage daily inward factory stock, driver assignment, carry forward, and returns.
    </p>

    <div class="page-break"></div>

    <!-- CHAPTER 7 -->
    <h1>7. Operational Bindings: Road Arrangement & Warehouse Binding</h1>
    <p>
        Creating a driver account is only the first step. For a driver to operate smoothly in the field, the Admin must establish two critical bindings: <strong>Warehouse Association</strong> and <strong>Road Sequence Arrangement</strong>.
    </p>

    <h2>7.1 Driver-to-Warehouse Binding (<code>/admin/driver-warehouse/{id}</code>)</h2>
    <p>
        Each driver must be bound to a specific regional warehouse. This ensures that:
    </p>
    <ol>
        <li>The driver appears exclusively in that warehouse\'s daily dispatch assignment screen (<code>/warehouse/driver-product-create</code>).</li>
        <li>Inventory assigned to the driver is strictly deducted from that specific warehouse\'s physical stock balance.</li>
        <li>Unsold goods and carry-forward stock return exclusively to that warehouse\'s evening reconciliation.</li>
    </ol>
    <div class="callout callout-info">
        <strong>Binding Shortcut:</strong> Warehouse binding can be set directly during Driver Creation or edited later in the unified Driver Edit screen (<code>/admin/driver-edit/{id}</code>).
    </div>

    <h2>7.2 Driver Location & Area Assignment (<code>/admin/driver-assign-location/{id}</code>)</h2>
    <p>
        Assigning an area to a driver links all customer retail shops in that territory to the driver\'s mobile delivery manifest. If a driver\'s beat is reallocated to another area, update this setting to instantly reflect the new store list on their mobile terminal.
    </p>

    <h2>7.3 Driver Road Arrangement & Beat Sequencing (SOP)</h2>
    <p>
        Within an assigned area, there may be 10 to 30 individual roads or market lanes. To maximize delivery speed and eliminate zig-zag travel, DDMS includes a <strong>Road Arrangement</strong> tool (<code>/admin/driver-road-arrangement</code>).
    </p>

    <div class="step-box">
        <span class="step-num">1</span> Navigate to <strong>Driver Management</strong> (<code>/admin/drivers</code>) &rarr; Click <strong>"Edit"</strong> on the target driver.
    </div>
    <div class="step-box">
        <span class="step-num">2</span> Scroll down to the <strong>Driver Routes & Sequence</strong> section.
    </div>
    <div class="step-box">
        <span class="step-num">3</span> The system displays all roads associated with the driver\'s assigned area alongside their current sequence numbers (<code>serial_no</code>).
    </div>
    <div class="step-box">
        <span class="step-num">4</span> Arrange the roads in optimal physical travel sequence:
        <ul style="margin-top: 5px; margin-bottom: 5px; padding-left: 20px;">
            <li><strong>Stop 1 (Serial 1):</strong> First road adjacent to the warehouse depot gate.</li>
            <li><strong>Stop 2 (Serial 2):</strong> Next connected market corridor.</li>
            <li><strong>Final Stop:</strong> Furthest route from the depot before heading back.</li>
        </ul>
    </div>
    <div class="step-box">
        <span class="step-num">5</span> Click <strong>"Save Road Arrangement"</strong>. The sequence is permanently stored in the <code>driver_route</code> table.
    </div>

    <div class="callout callout-success">
        <strong>Mobile App Sync:</strong> When the driver opens their mobile delivery app in the morning, customer shops are automatically grouped and listed in this precise road order. Drivers deliver sequentially without confusion.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 8 -->
    <h1>8. Customer & Retail Store Management</h1>
    <p>
        The <strong>Customers / Shops</strong> module (<code>/admin/customer-list</code>) is the central directory of all retail outlets, grocery stores, restaurants, and beverage retailers serviced by the enterprise.
    </p>

    <h2>8.1 Customer Directory Fields</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Field</th>
                <th style="width: 25%;">Type / Requirement</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Shop / Store Name</strong></td>
                <td>Mandatory Text</td>
                <td>Trade name displayed on the shop signboard (e.g., <em>M/S Ghosh Cold Drinks</em>).</td>
            </tr>
            <tr>
                <td><strong>Proprietor Name</strong></td>
                <td>Mandatory Text</td>
                <td>Full name of the shop owner or authorized store manager.</td>
            </tr>
            <tr>
                <td><strong>Contact Number</strong></td>
                <td>10-digit Phone</td>
                <td>Mobile number for delivery confirmation and digital invoice receipts.</td>
            </tr>
            <tr>
                <td><strong>State & Area</strong></td>
                <td>Dropdown selections</td>
                <td>Territorial boundaries determining which distribution network serves the shop.</td>
            </tr>
            <tr>
                <td><strong>Assigned Road / Beat</strong></td>
                <td>Dropdown selection</td>
                <td>The specific street or market beat where the physical shop is situated.</td>
            </tr>
            <tr>
                <td><strong>GSTIN Number</strong></td>
                <td>Optional (15 digits)</td>
                <td>Goods and Services Tax Identification Number printed on official B2B invoices.</td>
            </tr>
            <tr>
                <td><strong>Shop Address</strong></td>
                <td>Full Text</td>
                <td>Exact street address, building number, landmark, and postal PIN code.</td>
            </tr>
            <tr>
                <td><strong>Status Switch</strong></td>
                <td>Active / Inactive</td>
                <td>Active permits order booking; Inactive pauses deliveries (e.g., during shop renovations).</td>
            </tr>
        </tbody>
    </table>

    <h2>8.2 Registering a New Retailer (Step-by-Step)</h2>
    <div class="step-box">
        <span class="step-num">1</span> Navigate to <strong>Customers / Shops</strong> &rarr; Click <strong>"Add Customer"</strong> (<code>/admin/customer-add</code>).
    </div>
    <div class="step-box">
        <span class="step-num">2</span> Enter <em>Shop Name</em>, <em>Owner Name</em>, and 10-digit <em>Phone Number</em>.
    </div>
    <div class="step-box">
        <span class="step-num">3</span> Select <em>State</em> &rarr; <em>Area</em> &rarr; <em>Road</em>. (The Road dropdown dynamically filters based on the selected Area).
    </div>
    <div class="step-box">
        <span class="step-num">4</span> Input the shop\'s exact physical address and GSTIN number (if registered).
    </div>
    <div class="step-box">
        <span class="step-num">5</span> Click <strong>"Submit"</strong>. The retail shop is immediately registered and visible on the driver\'s mobile route manifest for that road.
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 9 -->
    <h1>9. Real-Time Field Attendance & GPS Route Tracking</h1>
    <p>
        The DDMS features a comprehensive field operations audit suite under <strong>MIS & Reports</strong>. Administrators can monitor driver movements, verify shift timings, and ensure complete beat coverage.
    </p>

    <h2>9.1 Driver Daily Attendance (<code>/admin/driver-attendance</code>)</h2>
    <p>
        Monitors daily shift activity across the transport fleet.
    </p>
    <table class="data-table">
        <thead>
            <tr>
                <th>Column Name</th>
                <th>Data Source</th>
                <th>Operational Interpretation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Driver Name</strong></td>
                <td>Driver Profile</td>
                <td>Identifies the field driver.</td>
            </tr>
            <tr>
                <td><strong>Verification Selfie</strong></td>
                <td>Mobile Camera Upload</td>
                <td>Real-time photo captured by the driver upon clock-in to prevent buddy punching.</td>
            </tr>
            <tr>
                <td><strong>Punch-In Time</strong></td>
                <td>GPS Timestamp</td>
                <td>Exact time the driver arrived at the warehouse and initiated their work shift.</td>
            </tr>
            <tr>
                <td><strong>Break Time</strong></td>
                <td><code>user_break_logs</code></td>
                <td>Cumulative duration (e.g. <em>0 hrs 45 mins</em>) computed automatically from start/end break taps.</td>
            </tr>
            <tr>
                <td><strong>Punch-Out Time</strong></td>
                <td>GPS Timestamp</td>
                <td>Time the driver completed evening reconciliation and closed their shift.</td>
            </tr>
        </tbody>
    </table>

    <h2>9.2 Driver Monthly Attendance Summary (<code>/admin/user-monthly-attendance/{id}</code>)</h2>
    <p>
        Presents a full 31-day calendar view for individual drivers:
    </p>
    <ul>
        <li><strong>Present Days Count:</strong> Total days with verified punch-in records.</li>
        <li><strong>Absent Days Count:</strong> Total working days elapsed without clock-in.</li>
        <li><strong>Break Hours Audit:</strong> Sum of break durations used for payroll and productivity assessments.</li>
    </ul>

    <h2>9.3 Driver Route Fulfillment Logs (<code>/admin/driver-route-logs</code>)</h2>
    <p>
        Captures physical road/beat completions in real time:
    </p>
    <ul>
        <li>Whenever a driver completes deliveries along a designated road, the mobile terminal sends a completion ping to <code>driver_attendance_route_logs</code>.</li>
        <li>Admin can filter logs by <strong>Date</strong> to verify whether scheduled beats (e.g., <em>Route A, Route B</em>) were executed or skipped.</li>
    </ul>

    <h2>9.4 Sales Executive Tracking</h2>
    <ul>
        <li><strong>Daily Attendance (<code>/admin/sales-executive-attendance</code>):</strong> Clock-in records for field sales staff.</li>
        <li><strong>Area Logs (<code>/admin/sales-executive-area-logs-list</code>):</strong> Beat-by-beat progression logs throughout the sales shift.</li>
    </ul>

    <div class="page-break"></div>

    <!-- CHAPTER 10 -->
    <h1>10. MIS Reporting & Financial Sales Invoice Suite</h1>
    <p>
        Financial and delivery auditing is managed through three dedicated ledgers under the <strong>MIS & Reports</strong> sidebar menu.
    </p>

    <h2>10.1 Daily Sales & Master Invoice Ledger (<code>/admin/invoice-list</code>)</h2>
    <p>
        The central sales billing repository. Every invoice issued by field drivers to retail stores is recorded here in real time.
    </p>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%;">Column</th>
                <th style="width: 25%;">Filter / Source</th>
                <th>Description & Function</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Date Range Filter</strong></td>
                <td>From Date & To Date</td>
                <td>Filter invoices across any fiscal period, month, or specific delivery day.</td>
            </tr>
            <tr>
                <td><strong>Invoice ID & No</strong></td>
                <td>Automated Unique ID</td>
                <td>Official sequential tax invoice number printed on customer billing receipts.</td>
            </tr>
            <tr>
                <td><strong>Driver Name</strong></td>
                <td>Assigned Driver</td>
                <td>Identifies which delivery personnel executed the transaction.</td>
            </tr>
            <tr>
                <td><strong>Created Date</strong></td>
                <td>Date & Time (dd/mm/YYYY)</td>
                <td>Exact timestamp when goods were handed over and bill was finalized.</td>
            </tr>
            <tr>
                <td><strong>Action (Print)</strong></td>
                <td>PDF Document Link</td>
                <td>Click the blue <strong>"Print"</strong> badge to open or download the official GST tax invoice PDF.</td>
            </tr>
        </tbody>
    </table>

    <h2>10.2 Shop Delivery History (<code>/admin/shop-delivery-history</code>)</h2>
    <p>
        Provides a store-by-store audit of all attempted and completed deliveries.
    </p>
    <ul>
        <li><strong>Store & Proprietor:</strong> Identifies the destination retail outlet.</li>
        <li><strong>Assigned Driver:</strong> Identifies who was responsible for the consignment.</li>
        <li><strong>Proof-of-Delivery Selfie / Photo:</strong> Displays photo proof captured at the retailer\'s counter.</li>
        <li><strong>Delivery Status Badges:</strong>
            <span class="badge badge-success">Delivered</span> (Goods successfully handed over),
            <span class="badge badge-danger">Failed</span> (Shop closed, owner unavailable, or rejected),
            <span class="badge badge-warning">Pending</span> (In transit).
        </li>
    </ul>

    <h2>10.3 Sales Visit History (<code>/admin/sales-visit-history</code>)</h2>
    <p>
        Monitors field market penetration by Sales Executives.
    </p>
    <ul>
        <li>Displays executive name, visited store, and exact visit timestamp.</li>
        <li>Audits mandatory storefront photos taken by executives to verify physical presence.</li>
        <li>Status displays <span class="badge badge-success">Visited</span> or <span class="badge badge-danger">Skipped</span> with executive remarks.</li>
    </ul>

    <div class="page-break"></div>

    <!-- CHAPTER 11 -->
    <h1>11. Standard Operating Procedures (SOP) & Daily Checklists</h1>

    <div class="callout callout-info">
        <h3 style="margin-top:0; color:#0369a1;">Morning Operational Routine (07:30 AM - 09:30 AM)</h3>
        <ol style="margin-bottom:0; padding-left:20px;">
            <li>Log into Admin Panel at <code>/admin/login</code>.</li>
            <li>Open <strong>Driver Attendance</strong> (<code>/admin/driver-attendance</code>). Filter by today\'s date. Verify that all scheduled drivers have clocked in with verified selfie photos.</li>
            <li>Verify vehicle availability under <strong>Vehicle Management</strong>. Ensure no assigned vehicles are flagged Inactive.</li>
            <li>Confirm that driver route arrangements (<code>/admin/driver-road-arrangement</code>) are active for the day\'s scheduled delivery beats.</li>
        </ol>
    </div>

    <div class="callout callout-warning">
        <h3 style="margin-top:0; color:#92400e;">Mid-Day Dispatch & Transit Monitoring (12:30 PM - 03:30 PM)</h3>
        <ol style="margin-bottom:0; padding-left:20px;">
            <li>Open <strong>Driver Route Logs</strong> (<code>/admin/driver-route-logs</code>) to monitor route progression. Identify drivers lagging behind their scheduled beat order.</li>
            <li>Review <strong>Shop Delivery History</strong> (<code>/admin/shop-delivery-history</code>). If any deliveries are flagged <span class="badge badge-danger">Failed</span>, review remarks (e.g. shop locked, customer cash shortage) and take corrective action.</li>
            <li>Monitor Sales Executive attendance and field visit progress under <code>/admin/sales-visit-history</code>.</li>
        </ol>
    </div>

    <div class="callout callout-success">
        <h3 style="margin-top:0; color:#065f46;">Evening Financial & Settlement Audit (06:00 PM - 08:30 PM)</h3>
        <ol style="margin-bottom:0; padding-left:20px;">
            <li>Open <strong>Daily Sales Invoices</strong> (<code>/admin/invoice-list</code>). Filter by today\'s date.</li>
            <li>Sum total invoice revenue and verify that physical collections reported by warehouse depot incharges match system figures.</li>
            <li>Review Driver Punch-Out times and cumulative break durations in Driver Attendance.</li>
            <li>Spot-check 3 to 5 printed tax invoices (via the Print PDF button) to confirm proper GST tax breakdown and customer details.</li>
        </ol>
    </div>

    <div class="callout callout-neutral" style="background:#f1f5f9; border-left:4px solid #64748b;">
        <h3 style="margin-top:0; color:#1e293b;">Periodic Master Data Maintenance (Weekly / Monthly)</h3>
        <ul style="margin-bottom:0; padding-left:20px;">
            <li>Audit commercial vehicle insurance and fitness certificate validity dates.</li>
            <li>Review driver driving licence expiry dates (flagged under Driver Management).</li>
            <li>Deactivate accounts of resigned staff members to revoke mobile system access.</li>
            <li>Review monthly attendance summaries for payroll approval.</li>
        </ul>
    </div>

    <div class="page-break"></div>

    <!-- CHAPTER 12 -->
    <h1>12. Administrative FAQs & Troubleshooting Matrix</h1>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 38%;">Scenario / Question</th>
                <th>Administrative Resolution & SOP</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>A driver cannot log into their mobile delivery application.</strong></td>
                <td>
                    1. Navigate to <code>/admin/drivers</code> and locate the driver record.<br/>
                    2. Verify that their Status switch is <strong>Active</strong> (checked).<br/>
                    3. Click <strong>Edit</strong>. Reset the driver password, verify phone number format (exact 10 digits), and save.
                </td>
            </tr>
            <tr>
                <td><strong>A driver cannot see any products or customer shops on their phone.</strong></td>
                <td>
                    1. Check <strong>Driver Warehouse Binding</strong>: Verify the driver is bound to an active warehouse.<br/>
                    2. Check <strong>Assigned Area & Road</strong>: Ensure the driver\'s area contains registered active shops.<br/>
                    3. Confirm that the warehouse depot has completed the daily morning stock assignment.
                </td>
            </tr>
            <tr>
                <td><strong>How do we update product selling prices without corrupting past invoices?</strong></td>
                <td>
                    Go to <code>/admin/products</code> &rarr; Edit Product &rarr; Update <em>Per Case Price</em> or <em>Per Bottle Price</em>. Past invoices retain the price stamped at the time of invoice creation, while all new orders immediately use the revised rates.
                </td>
            </tr>
            <tr>
                <td><strong>What happens if a driver is reassigned to a different warehouse?</strong></td>
                <td>
                    Open <code>/admin/driver-edit/{id}</code> and select the new warehouse from the <em>Assign Warehouse</em> dropdown. Ensure the driver has zero unreturned carry-forward stock from their previous depot before switching.
                </td>
            </tr>
            <tr>
                <td><strong>Should we delete staff accounts when an employee leaves?</strong></td>
                <td>
                    <strong>No. Never delete staff records</strong> as this breaks historical relationships in delivery and invoice tables. Always toggle their status switch to <strong>Inactive</strong>. This instantly blocks login while preserving full audit history.
                </td>
            </tr>
            <tr>
                <td><strong>A road is showing in the wrong area or missing completely.</strong></td>
                <td>
                    Navigate to <code>/admin/roads</code>. Check the parent <em>State</em> and <em>Area</em> linked to that road. If incorrect, click <strong>Edit</strong>, select the proper Area, and update.
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 9pt; color: #64748b; text-align: center;">
        Desai Delivery & Distribution Management System &bull; Admin Control Panel User Manual &bull; Confidential & Internal Enterprise Documentation
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
    $publicPath = public_path('manuals/Admin_Panel_User_Manual.pdf');
    $rootPath = base_path('Admin_Panel_User_Manual.pdf');

    $output = $pdf->output();
    file_put_contents($publicPath, $output);
    file_put_contents($rootPath, $output);

    echo "Admin PDF generated successfully!" . PHP_EOL;
    echo "Saved to: " . $publicPath . " (" . strlen($output) . " bytes)" . PHP_EOL;
    echo "Saved to: " . $rootPath . PHP_EOL;
} catch (\Throwable $e) {
    echo "Error generating PDF: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
