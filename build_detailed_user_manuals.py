import os
import sys
import shutil
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, HRFlowable, Image
)
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.pdfgen import canvas

# =============================================================================
# Custom Two-Pass Canvas for Dynamic Page Numbering & Running Corporate Headers
# =============================================================================
class NumberedCanvas(canvas.Canvas):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_decorations(num_pages)
            super().showPage()
        super().save()

    def draw_page_decorations(self, page_count):
        self.saveState()
        self.setFont("Helvetica-Bold", 7.5)
        self.setFillColor(colors.HexColor("#475569"))

        doc_title = getattr(self, 'doc_title', "Desai Delivery & Distribution Management System (DDMS)")
        role_label = getattr(self, 'role_label', "OFFICIAL OPERATIONAL USER MANUAL")

        # Running Header (Pages > 1)
        if self._pageNumber > 1:
            self.drawString(36, A4[1] - 25, doc_title.upper())
            self.drawRightString(A4[0] - 36, A4[1] - 25, role_label.upper())
            self.setStrokeColor(colors.HexColor("#CBD5E1"))
            self.setLineWidth(0.5)
            self.line(36, A4[1] - 30, A4[0] - 36, A4[1] - 30)

        # Running Footer (All Pages)
        self.setStrokeColor(colors.HexColor("#CBD5E1"))
        self.setLineWidth(0.5)
        self.line(36, 32, A4[0] - 36, 32)
        self.setFont("Helvetica", 7.5)
        self.drawString(36, 22, "CONFIDENTIAL | Desai Delivery & Distribution Management System | RE infotech® (updates@re-infotech.com)")
        page_text = f"Page {self._pageNumber} of {page_count}"
        self.drawRightString(A4[0] - 36, 22, page_text)
        self.restoreState()

def create_canvas_factory(doc_title, role_label):
    def make_canvas(*args, **kwargs):
        c = NumberedCanvas(*args, **kwargs)
        c.doc_title = doc_title
        c.role_label = role_label
        return c
    return make_canvas

# =============================================================================
# Style Palette Generator
# =============================================================================
def get_pdf_styles(theme_primary, theme_secondary, theme_accent):
    styles = getSampleStyleSheet()

    PRIMARY = colors.HexColor(theme_primary)
    SECONDARY = colors.HexColor(theme_secondary)
    ACCENT = colors.HexColor(theme_accent)
    DARK_TEXT = colors.HexColor("#0F172A")
    BODY_TEXT = colors.HexColor("#334155")
    BG_LIGHT = colors.HexColor("#F8FAFC")
    BORDER_LIGHT = colors.HexColor("#CBD5E1")

    styles.add(ParagraphStyle(
        name='doc_title',
        fontName='Helvetica-Bold',
        fontSize=18,
        leading=22,
        textColor=PRIMARY,
        spaceAfter=3
    ))
    styles.add(ParagraphStyle(
        name='doc_subtitle',
        fontName='Helvetica-Bold',
        fontSize=11,
        leading=15,
        textColor=SECONDARY,
        spaceAfter=8
    ))
    styles.add(ParagraphStyle(
        name='menu_h1',
        fontName='Helvetica-Bold',
        fontSize=12,
        leading=16,
        textColor=PRIMARY,
        spaceBefore=11,
        spaceAfter=3,
        keepWithNext=True
    ))
    styles.add(ParagraphStyle(
        name='menu_h2',
        fontName='Helvetica-Bold',
        fontSize=9.5,
        leading=13,
        textColor=SECONDARY,
        spaceBefore=7,
        spaceAfter=2,
        keepWithNext=True
    ))
    styles.add(ParagraphStyle(
        name='body',
        fontName='Helvetica',
        fontSize=8,
        leading=11.5,
        textColor=BODY_TEXT,
        spaceAfter=4
    ))
    styles.add(ParagraphStyle(
        name='body_bold',
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=11.5,
        textColor=DARK_TEXT,
        spaceAfter=3
    ))
    styles.add(ParagraphStyle(
        name='bullet',
        fontName='Helvetica',
        fontSize=8,
        leading=11.5,
        textColor=BODY_TEXT,
        leftIndent=12,
        firstLineIndent=-8,
        spaceAfter=2
    ))
    styles.add(ParagraphStyle(
        name='table_header',
        fontName='Helvetica-Bold',
        fontSize=7.5,
        leading=9.5,
        textColor=colors.white,
        alignment=0
    ))
    styles.add(ParagraphStyle(
        name='table_cell',
        fontName='Helvetica',
        fontSize=7,
        leading=9.5,
        textColor=DARK_TEXT,
        alignment=0
    ))
    styles.add(ParagraphStyle(
        name='table_cell_bold',
        fontName='Helvetica-Bold',
        fontSize=7,
        leading=9.5,
        textColor=DARK_TEXT,
        alignment=0
    ))
    styles.add(ParagraphStyle(
        name='callout_text',
        fontName='Helvetica',
        fontSize=7.5,
        leading=10.5,
        textColor=DARK_TEXT
    ))

    return {
        'PRIMARY': PRIMARY,
        'SECONDARY': SECONDARY,
        'ACCENT': ACCENT,
        'BG_LIGHT': BG_LIGHT,
        'BORDER_LIGHT': BORDER_LIGHT,
        'doc_title': styles['doc_title'],
        'doc_subtitle': styles['doc_subtitle'],
        'menu_h1': styles['menu_h1'],
        'menu_h2': styles['menu_h2'],
        'body': styles['body'],
        'body_bold': styles['body_bold'],
        'bullet': styles['bullet'],
        'table_header': styles['table_header'],
        'table_cell': styles['table_cell'],
        'table_cell_bold': styles['table_cell_bold'],
        'callout_text': styles['callout_text']
    }

def create_re_header(st):
    logo_path = r"c:\laragon\www\desai\public\manuals\re_infotech_logo.jpg"
    contact_p = Paragraph(
        '<div align="right">'
        '<font color="#101C4C" size="9.5"><b>RE INFOTECH &reg;</b></font><br/>'
        '<font color="#4A5568" size="7">'
        '<b>Email:</b> <a href="mailto:updates@re-infotech.com"><font color="#1E3A8A">updates@re-infotech.com</font></a> &nbsp;|&nbsp; '
        '<b>Mobile:</b> +918981365361<br/>'
        '<b>Official Website:</b> <a href="https://re-infotech.com/"><font color="#1E3A8A"><u>https://re-infotech.com/</u></font></a>'
        '</font>'
        '</div>',
        st['body']
    )
    t = Table([[Image(logo_path, width=140, height=32.5), contact_p]], colWidths=[160, 363])
    t.setStyle(TableStyle([
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('LEFTPADDING', (0,0), (-1,-1), 0),
        ('RIGHTPADDING', (0,0), (-1,-1), 0),
        ('TOPPADDING', (0,0), (-1,-1), 0),
        ('BOTTOMPADDING', (0,0), (-1,-1), 2),
    ]))
    return t

def create_callout(st, text, bg_hex="#EFF6FF", border_hex="#93C5FD"):
    t = Table([[Paragraph(text, st['callout_text'])]], colWidths=[523])
    t.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor(bg_hex)),
        ('BOX', (0,0), (-1,-1), 0.75, colors.HexColor(border_hex)),
        ('LEFTPADDING', (0,0), (-1,-1), 8),
        ('RIGHTPADDING', (0,0), (-1,-1), 8),
        ('TOPPADDING', (0,0), (-1,-1), 5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 5),
    ]))
    return t

# =============================================================================
# 1. ADMIN PANEL DETAILED MANUAL BUILDER
# =============================================================================
def build_admin_manual(output_paths):
    st = get_pdf_styles("#101C4C", "#1E3A8A", "#EE1A28")
    story = []

    # Brand Header
    story.append(create_re_header(st))
    story.append(HRFlowable(width="100%", thickness=1, color=st['BORDER_LIGHT'], spaceBefore=2, spaceAfter=8))

    # Title Banner
    story.append(Paragraph("DESAI DELIVERY &amp; DISTRIBUTION MANAGEMENT SYSTEM", st['doc_title']))
    story.append(Paragraph("Super Admin Control Panel &mdash; Detailed Operational Manual (v3.0)", st['doc_subtitle']))
    story.append(HRFlowable(width="100%", thickness=2, color=st['PRIMARY'], spaceBefore=2, spaceAfter=8))

    meta_table = Table([
        [Paragraph("<b>Target Workspace:</b>", st['table_cell_bold']), Paragraph("Super Admin Portal (/admin/*)", st['table_cell']),
         Paragraph("<b>Authorized Role:</b>", st['table_cell_bold']), Paragraph("Role ID 1 (Super Administrator)", st['table_cell'])],
        [Paragraph("<b>Modules Documented:</b>", st['table_cell_bold']), Paragraph("All 10 Core Menus &amp; Submenus", st['table_cell']),
         Paragraph("<b>Technical Partner:</b>", st['table_cell_bold']), Paragraph("RE infotech&reg; (https://re-infotech.com/)", st['table_cell'])],
    ], colWidths=[110, 160, 100, 153])
    meta_table.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F1F5F9")),
        ('BOX', (0,0), (-1,-1), 0.75, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(meta_table)
    story.append(Spacer(1, 8))

    # Callout Box
    story.append(create_callout(
        st,
        "<b>SYSTEM GOVERNANCE OVERVIEW:</b><br/>"
        "The Super Admin workspace governs all master definitions (Catalogs, Packaging Constants, 3-Tier Geography, "
        "Staff Provisioning, Fleet Assets) and monitors company-wide operational performance. "
        "Fleet &amp; Expenses reports in this portal operate in <b>View-Only Audit Mode</b>, equipped with dynamic cascading "
        "filters: selecting a Warehouse automatically narrows the Driver list, and selecting a Driver locks their assigned Vehicle.",
        bg_hex="#EFF6FF", border_hex="#93C5FD"
    ))
    story.append(Spacer(1, 8))

    # Menu 1: Dashboard
    story.append(Paragraph("1. Dashboard (/admin/dashboard)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>admin-dashboard</code> | <b>Navigation Icon:</b> <code>fa-gauge</code><br/>"
        "<b>Operational Purpose:</b> Real-time executive command center. Features live summary cards for Total Monthly Sales Revenue, "
        "Total Active Delivery Vans, Field Sales Executives, and Regional Warehouses. Includes interactive graphical charts visualizing "
        "daily delivery completion rates, sales trends by area, and punch-in attendance percentages. Clicking any metric card routes "
        "directly to the corresponding filtered management table.",
        st['body']
    ))

    # Menu 2: Staff Management
    story.append(Paragraph("2. Staff Management Suite (Dropdown Menu)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Parent Icon:</b> <code>fa-user</code> | Controls role-based authentication, credentials, and field assignments for staff.",
        st['body']
    ))
    story.append(Paragraph("<b>2.1 Drivers (/admin/drivers):</b>", st['menu_h2']))
    story.append(Paragraph(
        "&bull; <b>Route:</b> <code>drivers</code> | <b>Create:</b> <code>driver-create</code> | <b>Edit:</b> <code>driver-edit/{id}</code><br/>"
        "&bull; <b>Form Fields:</b> Full Name, Email, Password, 10-digit Phone, Commercial Driving Licence No, Assigned Vehicle (auto-locks van asset), Assigned Base Warehouse, Assigned Operational Area, Profile Photo.<br/>"
        "&bull; <b>Route Arrangement (/admin/driver-assign-location/{id}):</b> Dedicated drag-and-drop beat ordering interface allowing admins to configure the exact sequence of roads a driver visits during daily delivery rounds.<br/>"
        "&bull; <b>Warehouse Reallocation (/admin/driver-warehouse/{id}):</b> Transfers a driver to another regional depot when operational reassignments occur.<br/>"
        "&bull; <b>Live Status Switch:</b> Instant toggle to enable or deactivate driver mobile app login.",
        st['body']
    ))
    story.append(Paragraph("<b>2.2 Sales Executives (/admin/sales-executives):</b>", st['menu_h2']))
    story.append(Paragraph(
        "&bull; <b>Route:</b> <code>sales-executives</code> | <b>Create:</b> <code>sales-executive-create</code><br/>"
        "&bull; <b>Form Fields:</b> Name, Email, Password, Mobile, Photo, and Area Multi-Select (supports assigning up to 5 commercial market areas).<br/>"
        "&bull; <b>Territory Assignment (/admin/sales-executive-assign-location/{id}):</b> Configures market areas where the executive conducts shop visits.",
        st['body']
    ))
    story.append(Paragraph("<b>2.3 Warehouses (/admin/warehouses):</b>", st['menu_h2']))
    story.append(Paragraph(
        "&bull; <b>Route:</b> <code>warehouses</code> | <b>Create:</b> <code>warehouse-create</code><br/>"
        "&bull; <b>Form Fields:</b> Depot Name, Email, Password, Contact Phone, Physical Address, Depot Manager Photo.",
        st['body']
    ))

    # Menu 3, 4, 5: 3-Tier Geography
    story.append(Paragraph("3. Three-Tier Territorial Management Architecture", st['menu_h1']))
    story.append(Paragraph(
        "Desai DMS implements a hierarchical 3-tier geography that ensures exact GPS routing and structured territory boundaries:",
        st['body']
    ))
    story.append(Paragraph(
        "&bull; <b>Tier 1 &mdash; State Management (/admin/states):</b> Creates provincial boundaries (e.g. 'West Bengal'). Features unique name validation and AJAX status switch toggle.<br/>"
        "&bull; <b>Tier 2 &mdash; Area Management (/admin/areas):</b> Commercial districts and municipal hubs (e.g. 'Kolkata North', 'Hooghly Hub'). Links to parent State.<br/>"
        "&bull; <b>Tier 3 &mdash; Route/Road Management (/admin/roads):</b> Retail market beats and streets (e.g. 'MG Road Commercial Beat'). Links to parent Area. Retail shops are mapped to roads, and driver journey cycles follow these beats.",
        st['body']
    ))

    # Menu 6: Customers / Shops
    story.append(Paragraph("4. Customers &amp; Retail Shops Management (/admin/customer-list)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>customer-list</code> | <b>Create:</b> <code>customer-add</code> | <b>Icon:</b> <code>fa-users</code><br/>"
        "<b>Fields:</b> Shop Name, Proprietor Name, Contact Number, Physical Address, Pincode, Linked Road/Beat (Dropdown), GSTIN, PAN, and Shop Facade Photo.<br/>"
        "<b>Operational Usage:</b> Delivery drivers select customers from their daily route, generate instant tax invoices, and record proof-of-delivery photos.",
        st['body']
    ))

    # Menu 7: Vehicle Management
    story.append(Paragraph("5. Vehicle Fleet Management (/admin/vehicles)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>vehicles</code> | <b>Create:</b> <code>vehicle-add</code> | <b>Icon:</b> <code>fa-truck</code><br/>"
        "<b>Fields:</b> Vehicle Registration Number (RTO plate), Make &amp; Model (e.g. 'Tata Ace Gold'), Fuel Type (Diesel, Petrol, CNG), Engine Number, Chassis Number, GPS IMEI, Payload Case Capacity, PUC &amp; Pollution expiry, and Maintenance Status.",
        st['body']
    ))

    # Menu 8: Fleet & Expenses Reports
    story.append(Paragraph("6. Fleet &amp; Expenses Central Audit Suite (View-Only Mode)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Parent Icon:</b> <code>fa-gas-pump</code> | Provides executive oversight of field expenses recorded by regional depots.<br/>"
        "<b>Cascading Dynamic Filters:</b> Selecting a <i>Warehouse</i> automatically filters the <i>Driver</i> dropdown to show only drivers attached to that warehouse, and selecting a <i>Driver</i> auto-locks their assigned <i>Vehicle</i>.",
        st['body']
    ))
    story.append(Paragraph(
        "&bull; <b>Fuel Reports (/admin/fleet/fuel-reports):</b> Audits fuel consumption. Displays Date, Warehouse, Driver, Vehicle Number, Previous Odometer, Current Odometer, Distance Covered (km), Fuel Liters, Fuel Rate (₹/L), Total Amount (₹), Mileage (km/L), Fuel Station Name, and Petrol Slip Image Preview modal.<br/>"
        "&bull; <b>Daily Expense Reports (/admin/fleet/daily-expenses):</b> Audits en-route costs. Displays Date, Warehouse, Driver, Vehicle, Expense Category (Toll, Parking, Police/Challan, Van Cleaning, Driver Allowance, Misc), Amount (₹), Notes, and Voucher Receipt Modal.<br/>"
        "&bull; <b>Repair &amp; Service Reports (/admin/fleet/repair-reports):</b> Audits workshop maintenance. Displays Date, Warehouse, Driver, Vehicle, Garage Name, Maintenance Category (Scheduled Service, Engine, Electrical, Tyre/Brake, Body Repair), Replaced Spare Parts, Total Bill (₹), and Garage Tax Invoice Modal.",
        st['body']
    ))

    # Menu 9: Product Management
    story.append(Paragraph("7. Master Product Catalog &amp; Dual-Unit Constants (/admin/products)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>products</code> | <b>Create:</b> <code>product-create</code> | <b>Icon:</b> <code>fa-list</code><br/>"
        "<b>Parameters:</b> Product Name, Bottles Per Case (BPC multiplier &mdash; critical constant for dual-unit math), Wholesale Case Price, Retail Single Bottle Price, CGST %, SGST %, HSN Code, and Active Status.<br/>"
        "<b>Statutory Tax Engine:</b> Automatically computes itemized CGST, SGST, IGST, sub-totals, and grand totals across all mobile and depot billing.",
        st['body']
    ))

    # Menu 10: MIS & Reports
    story.append(Paragraph("8. MIS &amp; Field Surveillance Reports Suite", st['menu_h1']))
    story.append(Paragraph(
        "<b>Parent Icon:</b> <code>fa-book</code> | Central audit repository capturing mobile and depot transactions:<br/>"
        "&bull; <b>Daily Sales (/admin/invoice-list):</b> Master ledger of all field invoices. Filterable by date range. Includes one-click <b>Print Invoice</b> generating standardized 3-copy commercial tax invoices (Original, Duplicate, Triplicate).<br/>"
        "&bull; <b>Driver Attendance (/admin/driver-attendance):</b> Verifies punch-ins. Shows driver facial selfie, GPS Geolocation (Latitude &amp; Longitude coordinates), In-Time, Out-Time, Total Duty Hours, and Break Duration.<br/>"
        "&bull; <b>Driver Route Logs (/admin/driver-route-logs):</b> Evaluates daily route execution against assigned beats.<br/>"
        "&bull; <b>Sales Executive Attendance &amp; Area Logs:</b> Tracks executive selfie check-ins, geo-coordinates, and market area visits.<br/>"
        "&bull; <b>Shop Delivery History (/admin/shop-delivery-history):</b> Audits delivered goods, driver name, delivery status badge (Delivered / Failed / Pending), and physical delivery selfie.<br/>"
        "&bull; <b>Sales Visit History (/admin/sales-visit-history):</b> Records retail shop visits, shop front photo, visit status (Visited / Skipped), and executive field remarks.",
        st['body']
    ))

    # Build Document
    canvas_factory = create_canvas_factory("Desai DMS &mdash; Super Admin Operations Manual", "ROLE: SUPER ADMINISTRATOR")
    for path in output_paths:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        doc = SimpleDocTemplate(path, pagesize=A4, leftMargin=36, rightMargin=36, topMargin=40, bottomMargin=40)
        doc.build(list(story), canvasmaker=canvas_factory)
        print(f"Built Admin Manual: {path}")

# =============================================================================
# 2. MANUFACTURER PANEL DETAILED MANUAL BUILDER
# =============================================================================
def build_manufacturer_manual(output_paths):
    st = get_pdf_styles("#064E3B", "#059669", "#10B981")
    story = []

    # Brand Header
    story.append(create_re_header(st))
    story.append(HRFlowable(width="100%", thickness=1, color=st['BORDER_LIGHT'], spaceBefore=2, spaceAfter=8))

    # Title Banner
    story.append(Paragraph("DESAI DELIVERY &amp; DISTRIBUTION MANAGEMENT SYSTEM", st['doc_title']))
    story.append(Paragraph("Manufacturer Panel &mdash; Plant Operations Manual (v3.0)", st['doc_subtitle']))
    story.append(HRFlowable(width="100%", thickness=2, color=st['PRIMARY'], spaceBefore=2, spaceAfter=8))

    meta_table = Table([
        [Paragraph("<b>Target Workspace:</b>", st['table_cell_bold']), Paragraph("Plant Portal (/manufacture/*)", st['table_cell']),
         Paragraph("<b>Authorized Role:</b>", st['table_cell_bold']), Paragraph("Role ID 5 (Plant Head / Factory Manager)", st['table_cell'])],
        [Paragraph("<b>Core Modules:</b>", st['table_cell_bold']), Paragraph("Dual-Unit Math, Batch Logging, Audit Logs, Pipeline", st['table_cell']),
         Paragraph("<b>Technical Partner:</b>", st['table_cell_bold']), Paragraph("RE infotech&reg; (https://re-infotech.com/)", st['table_cell'])],
    ], colWidths=[110, 160, 100, 153])
    meta_table.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#ECFDF5")),
        ('BOX', (0,0), (-1,-1), 0.75, colors.HexColor("#A7F3D0")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#D1FAE5")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(meta_table)
    story.append(Spacer(1, 8))

    # Mathematical Formula Callout Box
    story.append(create_callout(
        st,
        "<b>MATHEMATICAL ALGORITHM &mdash; DUAL-UNIT INVENTORY NORMALIZATION:</b><br/>"
        "All stock in Desai DMS is handled in Dual-Units: <b>Cases</b> and <b>Loose Bottles</b>. "
        "Whenever loose bottles reach or exceed the Bottles Per Case (BPC) multiplier, the system automatically converts them into full cases:<br/>"
        "&bull; <b>Additional Cases:</b> <code>floor(Loose Bottles / BPC)</code><br/>"
        "&bull; <b>Remaining Loose Bottles:</b> <code>Loose Bottles % BPC</code><br/>"
        "<i>Worked Example:</i> If BPC = 24 and the shift produces 10 Cases and 58 Loose Bottles: <code>floor(58 / 24) = 2 Cases</code>, "
        "<code>58 % 24 = 10 Bottles</code>. Resulting stock = <b>12 Cases + 10 Loose Bottles</b>.",
        bg_hex="#F0FDF4", border_hex="#86EFAC"
    ))
    story.append(Spacer(1, 8))

    # Menu 1: Dashboard
    story.append(Paragraph("1. Plant Command Dashboard (/manufacture/dashboard)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>manufacture-dashboard</code> | <b>Icon:</b> <code>fa-gauge</code><br/>"
        "<b>Operational Purpose:</b> Real-time production intelligence dashboard displaying 4 primary analytical widgets:<br/>"
        "1. <b>Total Factory Stock:</b> Live volume of finished goods physically stored in the bottling plant warehouse in dual units.<br/>"
        "2. <b>Total Active Products:</b> Count of beverage SKUs currently configured for manufacturing.<br/>"
        "3. <b>Total Dispatched Stock:</b> Cumulative cases transferred from the plant to regional distribution depots.<br/>"
        "4. <b>Total Cross-Depot Stock:</b> Total inventory residing across all regional warehouses, enabling proactive production scheduling.",
        st['body']
    ))

    # Menu 2: Product Management
    story.append(Paragraph("2. Technical Product Catalog Reference (/manufacture/products)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>manufacture-products</code> | <b>Icon:</b> <code>fa-list</code><br/>"
        "<b>Purpose:</b> Plant technical specification directory. Displays SKU Name, Brand, Packaging Multiplier (BPC), "
        "Wholesale Case Price, Unit Bottle Price, and Manufacturing Status (Active vs Discontinued). Plant operators refer to this "
        "table to ensure packaging compliance during bottling shift runs.",
        st['body']
    ))

    # Menu 3: Stock Management
    story.append(Paragraph("3. Factory Stock Management Suite (Dropdown Menu)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Parent Icon:</b> <code>fa-boxes-stacked</code> | The operational core of plant production, physical audits, and dispatch tracking.",
        st['body']
    ))
    story.append(Paragraph("<b>3.1 Factory Stock Overview (/manufacture/stock):</b>", st['menu_h2']))
    story.append(Paragraph(
        "Displays real-time plant inventory in formatted Dual-Units (<code>X Cases + Y Bottles</code>) with visual threshold badges:<br/>"
        "&bull; <font color='#16A34A'><b>Green Badge:</b></font> Healthy Inventory (&gt;50 Cases).<br/>"
        "&bull; <font color='#CA8A04'><b>Yellow Badge:</b></font> Low Stock Warning (&le;50 Cases).<br/>"
        "&bull; <font color='#DC2626'><b>Red Badge:</b></font> Out of Stock (0 Cases).<br/>"
        "<b>Action Workflows:</b><br/>"
        "&bull; <b>1-Click Quick Add Modal:</b> Click 'Add Stock' on any product row. Enter Cases and Loose Bottles from the latest bottling shift. The modal calculates the normalized total and increments plant stock instantly.<br/>"
        "&bull; <b>Multi-SKU Shift Batch Production (/manufacture/stock-create):</b> Used for comprehensive production runs across multiple beverage lines. Select multiple products, enter Cases and Bottles for each, specify Batch/Lot Number, Manufacturing Date, Expiry Date, and Shift Supervisor Notes. Saves all items atomically in a single database transaction.<br/>"
        "&bull; <b>Physical Stock Audit &amp; Adjustment (/manufacture/stock-edit/{id}):</b> Physical verification interface. Displays current system inventory, accepts actual counted inventory, calculates the exact variance (&Delta;), and logs an adjustment reason.",
        st['body']
    ))

    story.append(Paragraph("<b>3.2 Warehouse Stock Pipeline View (/manufacture/warehouse-stock):</b>", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>manufacture-warehouse-stock</code><br/>"
        "Enables plant managers to inspect real-time stock levels at each regional distribution warehouse (Kolkata Depot, Siliguri Depot, Howrah Depot). "
        "Highlights depots experiencing low inventory or stockouts, allowing the plant to schedule and dispatch replenishment truckloads proactively.",
        st['body']
    ))

    story.append(Paragraph("<b>3.3 Immutable Stock Movement Logs (/manufacture/stock-logs):</b>", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>manufacture-stock-logs</code><br/>"
        "Chronological audit trail of all factory stock transactions. Filterable by Product, Movement Type, and Date Range.<br/>"
        "<b>Columns Logged:</b> Date &amp; Timestamp, Product Name, Transaction Type (<code>add</code> for production, <code>edit</code> for physical audit adjustment, <code>dispatch</code> for depot transfer), Previous Stock, Adjustment Quantity (&plusmn;&Delta;), Resulting New Stock, Batch/Lot Number, Reference Note, and Performed By.",
        st['body']
    ))

    # Build Document
    canvas_factory = create_canvas_factory("Desai DMS &mdash; Manufacturer Operations Manual", "ROLE: PLANT HEAD / MANUFACTURER")
    for path in output_paths:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        doc = SimpleDocTemplate(path, pagesize=A4, leftMargin=36, rightMargin=36, topMargin=40, bottomMargin=40)
        doc.build(list(story), canvasmaker=canvas_factory)
        print(f"Built Manufacturer Manual: {path}")

# =============================================================================
# 3. WAREHOUSE PANEL DETAILED MANUAL BUILDER
# =============================================================================
def build_warehouse_manual(output_paths):
    st = get_pdf_styles("#101C4C", "#D97706", "#F59E0B")
    story = []

    # Brand Header
    story.append(create_re_header(st))
    story.append(HRFlowable(width="100%", thickness=1, color=st['BORDER_LIGHT'], spaceBefore=2, spaceAfter=8))

    # Title Banner
    story.append(Paragraph("DESAI DELIVERY &amp; DISTRIBUTION MANAGEMENT SYSTEM", st['doc_title']))
    story.append(Paragraph("Warehouse Panel &mdash; Depot Operations Manual (v3.0)", st['doc_subtitle']))
    story.append(HRFlowable(width="100%", thickness=2, color=st['PRIMARY'], spaceBefore=2, spaceAfter=8))

    meta_table = Table([
        [Paragraph("<b>Target Workspace:</b>", st['table_cell_bold']), Paragraph("Regional Depot Portal (/warehouse/*)", st['table_cell']),
         Paragraph("<b>Authorized Role:</b>", st['table_cell_bold']), Paragraph("Role ID 4 (Warehouse / Depot Manager)", st['table_cell'])],
        [Paragraph("<b>Modules Documented:</b>", st['table_cell_bold']), Paragraph("All 8 Menus &amp; Vehicle Fleet Suite", st['table_cell']),
         Paragraph("<b>Technical Partner:</b>", st['table_cell_bold']), Paragraph("RE infotech&reg; (https://re-infotech.com/)", st['table_cell'])],
    ], colWidths=[110, 160, 100, 153])
    meta_table.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#FFFBEB")),
        ('BOX', (0,0), (-1,-1), 0.75, colors.HexColor("#FDE68A")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#FEF3C7")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(meta_table)
    story.append(Spacer(1, 8))

    # Differential Stock Formula Callout Box
    story.append(create_callout(
        st,
        "<b>CRITICAL DEPOT LOGISTIC LOGIC &mdash; CARRY-FORWARD DIFFERENTIAL DEDUCTION:</b><br/>"
        "When allocating daily goods to delivery vans, the system prevents double-deduction of unsold stock:<br/>"
        "1. When a driver is selected in <code>/warehouse/driver-product-create</code>, the system queries unsold carry-forward stock via AJAX.<br/>"
        "2. If a driver has 10 Cases carried forward and the supervisor inputs a target load of 25 Cases:<br/>"
        "&nbsp;&nbsp;&nbsp;&nbsp;<b>Stock Deducted from Warehouse = Target Load (25) &minus; Carry Forward (10) = 15 Cases.</b><br/>"
        "3. The 10 carried-forward cases remain on the van without consuming warehouse stock. Duplicate deduction is eliminated.",
        bg_hex="#FFFBEB", border_hex="#FDE68A"
    ))
    story.append(Spacer(1, 8))

    # Menu 1: Dashboard
    story.append(Paragraph("1. Regional Depot Command Dashboard (/warehouse/dashboard)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouse-dashboard</code> | <b>Icon:</b> <code>fa-gauge</code><br/>"
        "<b>Operational Purpose:</b> Real-time depot command center detailing live metrics for the active operational shift:<br/>"
        "&bull; <b>Current Depot Stock:</b> Total cases and loose bottles physically present in the warehouse.<br/>"
        "&bull; <b>Active Dispatched Vans:</b> Count of mobile delivery vans dispatched on morning sales routes.<br/>"
        "&bull; <b>Today's Field Invoicing:</b> Cumulative value of invoices issued by drivers in the field.<br/>"
        "&bull; <b>Pending Reconciliations:</b> Count of returning vans yet to complete evening return settlement.",
        st['body']
    ))

    # Menu 2: Product Management
    story.append(Paragraph("2. Depot Pricing &amp; Product Directory (/warehouse/products)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouse-products</code> | <b>Icon:</b> <code>fa-list</code><br/>"
        "<b>Purpose:</b> Master pricing directory for depot dispatches. Displays wholesale case price, single bottle retail rate, "
        "BPC multiplier, and applicable GST rates. Depot supervisors reference this to verify prices applied to van sales.",
        st['body']
    ))

    # Menu 3: Stock Management
    story.append(Paragraph("3. Depot Physical Stock Management (/warehouse/stock)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouse-stock</code> | <b>Icon:</b> <code>fa-house</code><br/>"
        "<b>Purpose:</b> Live depot stock ledger displaying inventory in Dual-Units (<code>X Cases + Y Bottles</code>). "
        "Color-coded badges alert operators to stock health: Green (&gt;50 Cases), Yellow (&le;50 Cases Reorder Buffer), Red (0 Cases Stockout). "
        "Stock levels automatically decrement when morning van manifests are approved and increment when factory transfers arrive.",
        st['body']
    ))

    # Menu 4: Driver Management
    story.append(Paragraph("4. Van Dispatch &amp; Manifest Engine (Dropdown Menu)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Parent Icon:</b> <code>fa-truck</code> | Manages morning loading manifests, security gate passes, and carry-forward calculations.",
        st['body']
    ))
    story.append(Paragraph("<b>4.1 Product Assign (/warehouse/driver-product-create):</b>", st['menu_h2']))
    story.append(Paragraph(
        "&bull; <b>Driver Selection:</b> Strictly filtered to drivers assigned to this specific warehouse via <code>driver_warehouse</code>.<br/>"
        "&bull; <b>Date Selection:</b> Defaults to today's date.<br/>"
        "&bull; <b>Automatic Carry-Forward Retrieval:</b> System queries yesterday's unsold goods and displays them per SKU.<br/>"
        "&bull; <b>Target Loading Input:</b> Supervisor inputs target load; system automatically deducts only the net difference from warehouse stock.",
        st['body']
    ))
    story.append(Paragraph("<b>4.2 Report List (/warehouse/driver-management-report):</b>", st['menu_h2']))
    story.append(Paragraph(
        "Historical manifest directory. Displays past van loading manifests with Driver Name, Dispatch Date, Loaded Cases, and Status. "
        "Provides action buttons to <b>View Manifest Line Items</b>, <b>Edit Manifest</b>, and <b>Print PDF Dispatch Gate Pass</b> for gate security clearance.",
        st['body']
    ))

    # Menu 5: Driver Daily Sales
    story.append(Paragraph("5. Evening Settlement &amp; Driver Reconciliation (/warehouse/daily-sales-report)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouse-daily-sales-report</code> | <b>Icon:</b> <code>fa-chart-line</code><br/>"
        "<b>Operational Purpose:</b> Evening financial and inventory settlement desk. Reconciles field sales, physical stock returning in the van, and cash/UPI receipts.<br/>"
        "<b>Reconciliation Columns:</b> Assigned Bottles, Sold Bottles, Cash Collected, UPI Digital Payments, and Total Invoices Generated.<br/>"
        "<b>Evening Return Modal (Driver Return Store):</b> Physical audit modal where supervisor categorizes remaining bottles into:<br/>"
        "&nbsp;&nbsp;&nbsp;&nbsp;1. <b>Carry Forward:</b> Intact, undamaged bottles remaining locked in the van for tomorrow's shift.<br/>"
        "&nbsp;&nbsp;&nbsp;&nbsp;2. <b>Physical Return / Scrap:</b> Broken, damaged, or spoiled bottles removed from the van and returned to depot scrap storage.",
        st['body']
    ))

    # Menu 6: Product Daily Sales
    story.append(Paragraph("6. Consolidated Product Sales Intelligence (/warehouse/product-daily-sales-report)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouse-product-daily-sales-report</code> | <b>Icon:</b> <code>fa-boxes-stacked</code><br/>"
        "Product-centric consolidated daily sales ledger. Summarizes sales volume across all delivery vans operating under this depot.<br/>"
        "<b>Features:</b> Active/All view mode toggle, SKU-wise Assigned, Sold, Carry Forward, and Scrapped quantities, Current Available Depot Stock, "
        "Driver Breakdown popup modal, and One-click CSV Export button.",
        st['body']
    ))

    # Menu 7: Invoice Management
    story.append(Paragraph("7. Direct Commercial Invoicing (/warehouse/invoice)", st['menu_h1']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouse-invoice</code> | <b>Create:</b> <code>warehouse-invoice-create</code> | <b>Icon:</b> <code>fa-book</code><br/>"
        "Used for direct dockside B2B sales to commercial retailers. Enter Shop Name, Contact, GSTIN, PAN, and select product line items with quantities. "
        "Automatically computes GST and generates downloadable official commercial tax PDF invoices.",
        st['body']
    ))

    # Menu 8: Fleet & Expenses
    story.append(Paragraph("8. Depot Vehicle Fleet &amp; Running Expense Suite", st['menu_h1']))
    story.append(Paragraph(
        "<b>Parent Icon:</b> <code>fa-gas-pump</code> | Full fleet management suite for logging running costs, fuel refills, and repairs.<br/>"
        "<b>Driver-to-Vehicle Auto-Locking:</b> Selecting a Driver automatically binds and locks their assigned delivery van asset.",
        st['body']
    ))
    story.append(Paragraph(
        "&bull; <b>Fuel Logs (/warehouse/vehicle/fuel-logs):</b> Add (<code>create</code>), Edit, Delete. Select Driver ➔ Vehicle auto-locks. Previous Odometer auto-fetches. Enter Current Odometer, Fuel Liters, Fuel Rate (₹/L), Total Cost (auto-calculated), Fuel Station Name, and Receipt Slip Photo Upload.<br/>"
        "&bull; <b>Daily Expenses (/warehouse/vehicle/daily-expenses):</b> Add, Edit, Delete. Select Driver ➔ Vehicle auto-locks. Enter Expense Date, Category (Toll Tax, Parking Fee, Police/Challan, Van Cleaning, Driver Allowance, Misc), Amount (₹), Remarks, and Expense Voucher Photo Upload.<br/>"
        "&bull; <b>Repair Costs (/warehouse/vehicle/repair-logs):</b> Add, Edit, Delete. Select Driver ➔ Vehicle auto-locks. Enter Repair Date, Workshop/Garage Name, Category (Scheduled Service, Engine/Transmission, Electrical, Tyre/Brake, Body Repair), Replaced Parts List, Total Bill Amount (₹), and Garage Tax Invoice Upload.",
        st['body']
    ))

    # Build Document
    canvas_factory = create_canvas_factory("Desai DMS &mdash; Warehouse Operations Manual", "ROLE: WAREHOUSE / DEPOT MANAGER")
    for path in output_paths:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        doc = SimpleDocTemplate(path, pagesize=A4, leftMargin=36, rightMargin=36, topMargin=40, bottomMargin=40)
        doc.build(list(story), canvasmaker=canvas_factory)
        print(f"Built Warehouse Manual: {path}")

# =============================================================================
# Main Orchestrator
# =============================================================================
if __name__ == '__main__':
    base_public = r"c:\laragon\www\desai\public\manuals"
    base_brain = r"C:\Users\user\.gemini\antigravity\brain\b4d8eca2-e457-42bc-9477-a5d18f6bdfd7"

    print("--- 1. Compiling Admin User Manual ---")
    admin_public = os.path.join(base_public, "Admin_User_Manual.pdf")
    admin_brain = os.path.join(base_brain, "Admin_User_Manual.pdf")
    build_admin_manual([admin_public])
    shutil.copyfile(admin_public, admin_brain)

    print("--- 2. Compiling Manufacturer User Manual ---")
    mfg_public = os.path.join(base_public, "Manufacturer_User_Manual.pdf")
    mfg_brain = os.path.join(base_brain, "Manufacturer_User_Manual.pdf")
    build_manufacturer_manual([mfg_public])
    shutil.copyfile(mfg_public, mfg_brain)

    print("--- 3. Compiling Warehouse User Manual ---")
    wh_public = os.path.join(base_public, "Warehouse_User_Manual.pdf")
    wh_brain = os.path.join(base_brain, "Warehouse_User_Manual.pdf")
    build_warehouse_manual([wh_public])
    shutil.copyfile(wh_public, wh_brain)

    print("=== All 3 Detailed User Manuals Successfully Compiled and Synchronized! ===")
