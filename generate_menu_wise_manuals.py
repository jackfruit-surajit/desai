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
# Custom Canvas for Two-Pass Page Numbering & Corporate Headers
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
        self.setFont("Helvetica-Bold", 7)
        self.setFillColor(colors.HexColor("#64748B"))

        doc_title = getattr(self, 'doc_title', "Desai Delivery & Distribution Management System (DDMS)")

        # Header (Pages > 1)
        if self._pageNumber > 1:
            self.drawString(36, A4[1] - 25, doc_title.upper())
            self.drawRightString(A4[0] - 36, A4[1] - 25, "DETAILED MENU-BY-MENU OPERATIONS MANUAL")
            self.setStrokeColor(colors.HexColor("#CBD5E1"))
            self.setLineWidth(0.5)
            self.line(36, A4[1] - 30, A4[0] - 36, A4[1] - 30)

        # Footer (All pages)
        self.setStrokeColor(colors.HexColor("#CBD5E1"))
        self.setLineWidth(0.5)
        self.line(36, 32, A4[0] - 36, 32)
        self.setFont("Helvetica", 7)
        self.drawString(36, 22, "CONFIDENTIAL | Desai Delivery & Distribution Management System | RE infotech® (updates@re-infotech.com)")
        page_text = f"Page {self._pageNumber} of {page_count}"
        self.drawRightString(A4[0] - 36, 22, page_text)
        self.restoreState()

def create_canvas_factory(doc_title):
    def make_canvas(*args, **kwargs):
        c = NumberedCanvas(*args, **kwargs)
        c.doc_title = doc_title
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
    MUTED_TEXT = colors.HexColor("#64748B")
    BG_LIGHT = colors.HexColor("#F8FAFC")
    BORDER_LIGHT = colors.HexColor("#E2E8F0")

    styles.add(ParagraphStyle(
        name='doc_title',
        fontName='Helvetica-Bold',
        fontSize=20,
        leading=24,
        textColor=PRIMARY,
        spaceAfter=4
    ))
    styles.add(ParagraphStyle(
        name='doc_subtitle',
        fontName='Helvetica-Bold',
        fontSize=12,
        leading=16,
        textColor=SECONDARY,
        spaceAfter=10
    ))
    styles.add(ParagraphStyle(
        name='menu_h1',
        fontName='Helvetica-Bold',
        fontSize=13,
        leading=17,
        textColor=PRIMARY,
        spaceBefore=12,
        spaceAfter=4,
        keepWithNext=True
    ))
    styles.add(ParagraphStyle(
        name='menu_h2',
        fontName='Helvetica-Bold',
        fontSize=10,
        leading=14,
        textColor=SECONDARY,
        spaceBefore=8,
        spaceAfter=3,
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
        spaceAfter=4
    ))
    styles.add(ParagraphStyle(
        name='bullet',
        fontName='Helvetica',
        fontSize=8,
        leading=11,
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
        name='badge_pill',
        fontName='Helvetica-Bold',
        fontSize=6.5,
        leading=8.5,
        textColor=PRIMARY
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

# =============================================================================
# 1. ADMIN PANEL MENU-WISE MANUAL
# =============================================================================
def build_admin_manual(output_paths):
    st = get_pdf_styles("#101C4C", "#1E3A8A", "#EE1A28")
    story = []

    # Brand Header
    story.append(create_re_header(st))
    story.append(HRFlowable(width="100%", thickness=1, color=st['BORDER_LIGHT'], spaceBefore=2, spaceAfter=8))

    # Title Banner
    story.append(Paragraph("DESAI DELIVERY &amp; DISTRIBUTION MANAGEMENT SYSTEM", st['doc_title']))
    story.append(Paragraph("Admin Panel &mdash; Detailed Menu-Wise Operations Manual", st['doc_subtitle']))
    story.append(HRFlowable(width="100%", thickness=2, color=st['PRIMARY'], spaceBefore=2, spaceAfter=8))

    meta_table = Table([
        [Paragraph("<b>Target Workspace:</b>", st['table_cell_bold']), Paragraph("Super Admin Portal (/admin/*)", st['table_cell']),
         Paragraph("<b>Authorized Role:</b>", st['table_cell_bold']), Paragraph("Role ID 1 (Super Administrator)", st['table_cell'])],
        [Paragraph("<b>Total Menus Covered:</b>", st['table_cell_bold']), Paragraph("10 Primary Modules &amp; Submenus", st['table_cell']),
         Paragraph("<b>Classification:</b>", st['table_cell_bold']), Paragraph("Enterprise Operational Standard v3.0", st['table_cell'])],
    ], colWidths=[110, 160, 100, 153])
    meta_table.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F1F5F9")),
        ('BOX', (0,0), (-1,-1), 0.75, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(meta_table)
    story.append(Spacer(1, 10))

    # -------------------------------------------------------------
    # Menu 1: Dashboard
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 1: Dashboard (/admin/dashboard)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-gauge</code> | <b>Route:</b> <code>admin-dashboard</code>", st['body']))
    story.append(Paragraph(
        "<b>Functionality &amp; Purpose:</b> Central command intelligence dashboard providing executive oversight of all daily field logistics. Renders high-priority metric cards including: Total Monthly Invoiced Revenue, Total Assigned Driver Fleets, Active Field Sales Executives, and Total Operational Warehouses. Live charts illustrate regional sales distribution and real-time attendance status.",
        st['body']
    ))
    story.append(Paragraph("<b>Standard Actions:</b>", st['body_bold']))
    story.append(Paragraph("&bull; <b>Quick Statistics Cards:</b> Click on any card to instantly navigate to the filtered listing (e.g., clicking 'Active Drivers' routes to <code>/admin/drivers</code>).", st['bullet']))
    story.append(Paragraph("&bull; <b>Daily Pulse Check:</b> Review total invoiced sales generated today across all operational vans.", st['bullet']))

    # -------------------------------------------------------------
    # Menu 2: Staff Management (Dropdown)
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 2: Staff Management (Dropdown)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-user</code> | <b>Parent Route:</b> Staff Management Submenu", st['body']))
    story.append(Paragraph(
        "Comprehensive personnel provisioning engine controlling Role IDs 2 (Sales Executive), 3 (Van Driver), and 4 (Warehouse Manager). Provides cryptographic authentication, territory assignment, and fleet locking.",
        st['body']
    ))

    # Submenu 2.1: Drivers
    story.append(Paragraph("Submenu 2.1: Drivers (/admin/drivers)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>drivers</code> | <b>Create Route:</b> <code>driver-create</code><br/>"
        "<b>Description:</b> Manages all delivery van pilots who operate field mobile apps, sell goods from the mobile van, and issue retail tax invoices.",
        st['body']
    ))
    story.append(Paragraph("<b>Driver Creation Form Fields &amp; Rules:</b>", st['body_bold']))
    story.append(Paragraph("&bull; <b>Full Name:</b> Alpha characters only. Displayed on retail invoices and delivery receipts.", st['bullet']))
    story.append(Paragraph("&bull; <b>Email &amp; Password:</b> Login credentials for the Desai Mobile Delivery App.", st['bullet']))
    story.append(Paragraph("&bull; <b>Phone Number:</b> 10-digit numeric mobile number (used for field communications).", st['bullet']))
    story.append(Paragraph("&bull; <b>Driving Licence No:</b> Mandatory licence string (max 100 chars) for RTO compliance.", st['bullet']))
    story.append(Paragraph("&bull; <b>Assigned Vehicle:</b> Dropdown of active company vans. <i>Rule: A vehicle can only be active with one driver at a time.</i>", st['bullet']))
    story.append(Paragraph("&bull; <b>Assigned Warehouse:</b> Base depot where the driver loads daily stock in the morning and reconciles in the evening.", st['bullet']))
    story.append(Paragraph("&bull; <b>Assigned Operational Area:</b> Primary sales territory governing the routes the driver serves.", st['bullet']))
    story.append(Paragraph("&bull; <b>Driver Location Assignment (/admin/driver-assign-location/{id}):</b> Dedicated interface to assign specific road beats and arrange the sequential route order.", st['bullet']))

    # Submenu 2.2: Sales Executives
    story.append(Paragraph("Submenu 2.2: Sales Executives (/admin/sales-executives)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>sales-executives</code> | <b>Create Route:</b> <code>sales-executive-create</code><br/>"
        "<b>Description:</b> Controls field sales representatives who conduct retail shop visits, record orders, and audit market beat compliance. Allows multi-area assignment (up to 5 distinct market areas).",
        st['body']
    ))

    # Submenu 2.3: Warehouse Managers
    story.append(Paragraph("Submenu 2.3: Warehouse (/admin/warehouses)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouses</code> | <b>Create Route:</b> <code>warehouse-create</code><br/>"
        "<b>Description:</b> Manages depot administrator accounts. Each warehouse account controls its localized physical stock, morning van manifests, and evening cash/returns reconciliation.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 3: State Management
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 3: State Management (/admin/states)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-map-location-dot</code> | <b>Route:</b> <code>states</code> | <b>Create:</b> <code>state-add</code>", st['body']))
    story.append(Paragraph(
        "<b>Purpose:</b> Top tier of the 3-Tier Territorial Architecture (State &rarr; Area &rarr; Route).<br/>"
        "<b>Form Fields:</b> State Name (Unique text, e.g., 'West Bengal'), Status (Active/Inactive switch).<br/>"
        "<b>Controls:</b> Instant AJAX status toggle switch; Edit; Delete (cascade-protected if linked to active areas).",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 4: Area Management
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 4: Area Management (/admin/areas)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-list</code> | <b>Route:</b> <code>areas</code> | <b>Create:</b> <code>area-add</code>", st['body']))
    story.append(Paragraph(
        "<b>Purpose:</b> Second tier of the territory hierarchy representing commercial districts or municipal zones.<br/>"
        "<b>Form Fields:</b> State Selection (Dropdown populated from active States), Area Name (Unique string, e.g., 'Kolkata North'), Status Switch.<br/>"
        "<b>Operational Impact:</b> Governs driver warehouse allocation and sales executive route permissions.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 5: Route Management
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 5: Route Management (/admin/roads)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-route</code> | <b>Route:</b> <code>roads</code> | <b>Create:</b> <code>road-add</code>", st['body']))
    story.append(Paragraph(
        "<b>Purpose:</b> Third tier of territory mapping representing specific retail market beats, streets, and delivery circuits.<br/>"
        "<b>Form Fields:</b> Area Selection (Dropdown), Road/Beat Name (e.g., 'MG Road Commercial Hub'), Status (Active/Inactive).<br/>"
        "<b>Usage:</b> Each registered retail shop is strictly mapped to one Road/Beat for optimized van journey cycles.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 6: Customers / Shops
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 6: Customers / Shops (/admin/customer-list)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-users</code> | <b>Route:</b> <code>customer-list</code> | <b>Create:</b> <code>customer-add</code>", st['body']))
    story.append(Paragraph(
        "<b>Purpose:</b> Master retail merchant and outlet registry. Maintains shop profiles for mobile billing and route optimization.",
        st['body']
    ))
    story.append(Paragraph("<b>Shop Registration Form Fields:</b>", st['body_bold']))
    story.append(Paragraph("&bull; <b>Shop Name &amp; Owner Name:</b> Commercial trade name and proprietor identity.", st['bullet']))
    story.append(Paragraph("&bull; <b>Contact Mobile:</b> 10-digit number for SMS dispatch alerts and invoice receipts.", st['bullet']))
    story.append(Paragraph("&bull; <b>Road/Beat Mapping:</b> Dropdown connecting the shop to its geographical delivery road.", st['bullet']))
    story.append(Paragraph("&bull; <b>Physical Address &amp; Pin Code:</b> Complete physical location.", st['bullet']))
    story.append(Paragraph("&bull; <b>GSTIN &amp; PAN (Optional):</b> Commercial tax credentials for B2B GST tax invoices.", st['bullet']))
    story.append(Paragraph("&bull; <b>Shop Facade Photo:</b> Image upload allowing driver/sales executive visual verification during field visits.", st['bullet']))

    # -------------------------------------------------------------
    # Menu 7: Vehicle Management
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 7: Vehicle Management (/admin/vehicles)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-truck</code> | <b>Route:</b> <code>vehicles</code> | <b>Create:</b> <code>vehicle-add</code>", st['body']))
    story.append(Paragraph(
        "<b>Purpose:</b> Company delivery fleet registry. Vehicles registered here are bound to drivers and tracked in the Fleet Suite.",
        st['body']
    ))
    story.append(Paragraph("<b>Vehicle Registration Fields:</b>", st['body_bold']))
    story.append(Paragraph("&bull; <b>Vehicle Registration Number:</b> Official RTO plate (e.g., 'WB-02-AK-4589').", st['bullet']))
    story.append(Paragraph("&bull; <b>Vehicle Model &amp; Make:</b> Commercial model (e.g., 'Tata Ace Gold', 'Mahindra Bolero Maxi').", st['bullet']))
    story.append(Paragraph("&bull; <b>Load Capacity (Cases):</b> Physical volumetric payload limit in cases.", st['bullet']))
    story.append(Paragraph("&bull; <b>Status:</b> Active in fleet or Under Maintenance.", st['bullet']))

    # -------------------------------------------------------------
    # Menu 8: Fleet & Expenses (Dropdown)
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 8: Fleet & Expenses Reports (Dropdown)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-gas-pump</code> | <b>Authorization:</b> View-Only Audit Mode", st['body']))
    story.append(Paragraph(
        "Executive fleet monitoring suite providing complete transparency over operational expenditures entered by regional depots. Equipped with <b>Cascading Dynamic Filters</b>: selecting a Warehouse automatically re-populates the Driver dropdown with only drivers assigned to that warehouse, and selecting a Driver auto-selects their assigned Vehicle.",
        st['body']
    ))

    # Submenu 8.1: Fuel Reports
    story.append(Paragraph("Submenu 8.1: Fuel Reports (/admin/fleet/fuel-reports)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>admin-fleet-fuel-reports</code><br/>"
        "<b>Audited Columns:</b> Date, Warehouse, Driver Name, Vehicle Number, Previous Odometer, Current Odometer, Distance Covered (km), Fuel Liters, Fuel Rate (₹/L), Total Cost (₹), Calculated Mileage (km/L), Fuel Station Name, Bill Slip Preview Modal.",
        st['body']
    ))

    # Submenu 8.2: Daily Expense Reports
    story.append(Paragraph("Submenu 8.2: Daily Expense Reports (/admin/fleet/daily-expenses)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>admin-fleet-daily-expenses</code><br/>"
        "<b>Audited Columns:</b> Date, Warehouse, Driver Name, Vehicle Number, Category (Toll, Parking, Police/Challan, Van Cleaning, Food Allowance, Misc), Amount (₹), Driver Remarks, Expense Voucher Image Modal.",
        st['body']
    ))

    # Submenu 8.3: Repair & Service Reports
    story.append(Paragraph("Submenu 8.3: Repair & Service Reports (/admin/fleet/repair-reports)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>admin-fleet-repair-reports</code><br/>"
        "<b>Audited Columns:</b> Date, Warehouse, Driver Name, Vehicle Number, Workshop/Garage Name, Category (Engine/Mechanical, Electrical, Periodic Service, Body/Denting), Replaced Spare Parts, Total Bill Amount (₹), Garage Tax Invoice Modal.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 9: Product Management
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 9: Product Management (/admin/products)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-list</code> | <b>Route:</b> <code>products</code> | <b>Create:</b> <code>product-create</code>", st['body']))
    story.append(Paragraph(
        "<b>Purpose:</b> Master SKU catalog defining pricing, packaging multipliers, and GST tax computation parameters.",
        st['body']
    ))
    story.append(Paragraph("<b>Product Master Parameters:</b>", st['body_bold']))
    story.append(Paragraph("&bull; <b>Product Name:</b> Unique branded SKU name (e.g., 'Desai Soda 200ml Glass Bottle').", st['bullet']))
    story.append(Paragraph("&bull; <b>Per Case Quantity (BPC):</b> Bottles Per Case conversion factor (e.g., 24). Crucial constant for dual-unit math across all plant and warehouse portals.", st['bullet']))
    story.append(Paragraph("&bull; <b>Per Case Wholesale Price:</b> Base wholesale price for full case.", st['bullet']))
    story.append(Paragraph("&bull; <b>Per Bottle Retail Unit Price:</b> Loose bottle rate used for itemized billing.", st['bullet']))
    story.append(Paragraph("&bull; <b>CGST &amp; SGST Rate (%):</b> Statutory tax percentages for automated invoice generation.", st['bullet']))
    story.append(Paragraph("&bull; <b>HSN Code:</b> Harmonized System of Nomenclature code for B2B commercial invoices.", st['bullet']))

    # -------------------------------------------------------------
    # Menu 10: MIS & Reports (Dropdown)
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 10: MIS &amp; Reports (Dropdown)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-book</code> | <b>Parent Route:</b> MIS &amp; Analytics Submenu", st['body']))
    story.append(Paragraph(
        "Comprehensive surveillance and field audit reports compiled from mobile apps and depot reconciliations.",
        st['body']
    ))

    story.append(Paragraph("Submenu 10.1: Daily Sales (/admin/invoice-list)", st['menu_h2']))
    story.append(Paragraph("Master audit table of all mobile invoices issued by delivery drivers. Filterable by Date Range. Provides one-click <b>Print Invoice</b> action generating standardized 3-copy commercial tax invoices.", st['body']))

    story.append(Paragraph("Submenu 10.2: Driver Attendance (/admin/driver-attendance)", st['menu_h2']))
    story.append(Paragraph("Facial selfie verification with embedded GPS Coordinates (Latitude, Longitude). Shows In-Time, Out-Time, Total Shift Hours, and Cumulative Break Minutes.", st['body']))

    story.append(Paragraph("Submenu 10.3: Driver Route Logs (/admin/driver-route-logs)", st['menu_h2']))
    story.append(Paragraph("Monitors route beat execution against assigned schedules. Tracks shops visited vs shops skipped per journey cycle.", st['body']))

    story.append(Paragraph("Submenu 10.4: Sales Executive Attendance (/admin/sales-executive-attendance)", st['menu_h2']))
    story.append(Paragraph("Tracks field sales representatives' mobile selfie check-ins, geo-coordinates, and workday duration.", st['body']))

    story.append(Paragraph("Submenu 10.5: Sales Executive Area Logs (/admin/sales-executive-area-logs-list)", st['menu_h2']))
    story.append(Paragraph("Timestamped geographical breadcrumbs showing sequential market area coverage by sales executives.", st['body']))

    story.append(Paragraph("Submenu 10.6: Shop Delivery History (/admin/shop-delivery-history)", st['menu_h2']))
    story.append(Paragraph("Proof-of-delivery audit ledger: records Delivery Date, Driver Name, Customer/Shop, and Status badge (Delivered / Failed / Pending) with physical shop delivery selfie.", st['body']))

    story.append(Paragraph("Submenu 10.7: Sales Visit History (/admin/sales-visit-history)", st['menu_h2']))
    story.append(Paragraph("Retail beat visit verification: captures executive shop visits, captured shop photo, visit timestamp, status (Visited / Skipped), and merchant feedback notes.", st['body']))

    # Build Document
    canvas_factory = create_canvas_factory("DDMS Admin Operations Manual &mdash; Menu-Wise Standard v3.0")
    for path in output_paths:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        doc = SimpleDocTemplate(path, pagesize=A4, leftMargin=36, rightMargin=36, topMargin=40, bottomMargin=40)
        doc.build(list(story), canvasmaker=canvas_factory)
        print(f"Successfully generated Admin Manual: {path}")

# =============================================================================
# 2. MANUFACTURER PANEL MENU-WISE MANUAL
# =============================================================================
def build_manufacturer_manual(output_paths):
    st = get_pdf_styles("#064E3B", "#059669", "#10B981")
    story = []

    # Brand Header
    story.append(create_re_header(st))
    story.append(HRFlowable(width="100%", thickness=1, color=st['BORDER_LIGHT'], spaceBefore=2, spaceAfter=8))

    story.append(Paragraph("DESAI DELIVERY &amp; DISTRIBUTION MANAGEMENT SYSTEM", st['doc_title']))
    story.append(Paragraph("Manufacturer Panel &mdash; Detailed Menu-Wise Operations Manual", st['doc_subtitle']))
    story.append(HRFlowable(width="100%", thickness=2, color=st['PRIMARY'], spaceBefore=2, spaceAfter=8))

    meta_table = Table([
        [Paragraph("<b>Target Workspace:</b>", st['table_cell_bold']), Paragraph("Plant &amp; Manufacturing Portal (/manufacture/*)", st['table_cell']),
         Paragraph("<b>Authorized Role:</b>", st['table_cell_bold']), Paragraph("Role ID 5 (Plant Head / Factory Manager)", st['table_cell'])],
        [Paragraph("<b>Total Menus Covered:</b>", st['table_cell_bold']), Paragraph("3 Primary Functional Modules", st['table_cell']),
         Paragraph("<b>Key Mathematical Engine:</b>", st['table_cell_bold']), Paragraph("Dual-Unit Inventory Normalization Algorithm", st['table_cell'])],
    ], colWidths=[110, 160, 100, 153])
    meta_table.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#ECFDF5")),
        ('BOX', (0,0), (-1,-1), 0.75, colors.HexColor("#A7F3D0")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#D1FAE5")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(meta_table)
    story.append(Spacer(1, 10))

    # Core Mathematical Box
    story.append(create_callout(
        st,
        "<b>CRITICAL INVENTORY CONVERSION FORMULA:</b><br/>"
        "All stock in Desai DMS is handled in Dual-Units: <b>Cases</b> and <b>Loose Bottles</b>. "
        "Whenever loose bottles reach or exceed the Bottles Per Case (BPC) multiplier, the system automatically normalizes:<br/>"
        "&bull; <b>Additional Cases:</b> <code>floor(Loose Bottles / BPC)</code><br/>"
        "&bull; <b>Remaining Loose Bottles:</b> <code>Loose Bottles % BPC</code>",
        bg_hex="#F0FDF4", border_hex="#86EFAC"
    ))
    story.append(Spacer(1, 8))

    # -------------------------------------------------------------
    # Menu 1: Dashboard
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 1: Dashboard (/manufacture/dashboard)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-gauge</code> | <b>Route:</b> <code>manufacture-dashboard</code>", st['body']))
    story.append(Paragraph(
        "<b>Functionality &amp; Purpose:</b> High-level plant intelligence overview showing production volumes and supply-chain pipeline health. Displays 4 primary analytical widgets:<br/>"
        "1. <b>Total Factory Stock:</b> Real-time warehouse cases &amp; loose bottles physically present at the bottling plant.<br/>"
        "2. <b>Total Products:</b> Count of active beverage SKUs currently configured for manufacturing.<br/>"
        "3. <b>Total Dispatched Stock:</b> Cumulative cases transferred out of the plant to regional distribution depots.<br/>"
        "4. <b>Total Depot Stock:</b> Aggregated inventory residing across all regional warehouses, highlighting replenishment needs.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 2: Product Management
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 2: Product Management (/manufacture/products)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-list</code> | <b>Route:</b> <code>manufacture-products</code>", st['body']))
    story.append(Paragraph(
        "<b>Functionality &amp; Purpose:</b> Technical SKU reference portal for bottling plant operators. Displays official commercial specifications for all Desai beverages:<br/>"
        "&bull; <b>SKU Identity:</b> Product Name and Brand classification.<br/>"
        "&bull; <b>Packaging Standard (BPC):</b> Strict conversion benchmark (e.g., 24 bottles/case or 12 bottles/case).<br/>"
        "&bull; <b>Commercial Valuation:</b> Per Case Wholesale Price and Per Bottle MRP.<br/>"
        "&bull; <b>Production Status:</b> Active for bottling vs Discontinued.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 3: Stock Management (Dropdown)
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 3: Stock Management (Dropdown)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-boxes-stacked</code> | <b>Parent Route:</b> Stock Suite Submenu", st['body']))
    story.append(Paragraph(
        "The core manufacturing control suite managing physical batch production, stock variance audits, cross-depot supply pipelines, and audit trails.",
        st['body']
    ))

    # Submenu 3.1: Factory Stock
    story.append(Paragraph("Submenu 3.1: Factory Stock (/manufacture/stock)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>manufacture-stock</code> | <b>Create:</b> <code>manufacture-stock-create</code><br/>"
        "<b>Overview:</b> Master inventory table of all finished goods stored inside the plant warehouse. Features color-coded inventory health badges (Green: >50 Cases, Yellow: &le;50 Cases Low Stock, Red: 0 Cases Out of Stock).",
        st['body']
    ))
    story.append(Paragraph("<b>Available Actions &amp; Workflows:</b>", st['body_bold']))
    story.append(Paragraph(
        "&bull; <b>1-Click Quick Add Modal:</b> Click <b>'Add Stock'</b> button on any product row. Enter Cases and Loose Bottles from the latest bottling shift. The modal automatically displays the normalized total and instantly increments plant stock.",
        st['bullet']
    ))
    story.append(Paragraph(
        "&bull; <b>Multi-SKU Shift Batch Production (/manufacture/stock-create):</b> For full production runs across multiple beverage lines. Select multiple products, input Cases and Loose Bottles for each, specify Batch/Lot Number, Manufacturing Date, Expiry Date, and Shift Supervisor Notes. Saves all line items atomically in a single database transaction.",
        st['bullet']
    ))
    story.append(Paragraph(
        "&bull; <b>Physical Stock Audit &amp; Adjustment (/manufacture/stock-edit/{id}):</b> Used during physical inventory counting. Displays <i>Current System Stock</i> and allows entering <i>Actual Physical Counted Stock</i>. The system calculates the exact difference (&Delta;) and logs an adjustment record.",
        st['bullet']
    ))

    # Submenu 3.2: Warehouse Stock View
    story.append(Paragraph("Submenu 3.2: Warehouse Stock View (/manufacture/warehouse-stock)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>manufacture-warehouse-stock</code><br/>"
        "<b>Purpose:</b> Supply-chain pipeline surveillance. Enables plant managers to inspect real-time stock levels at each regional distribution warehouse (e.g., Kolkata Depot, Siliguri Depot, Howrah Depot). Highlights depots experiencing stockouts or low inventory, allowing the plant to plan and dispatch replenishment trucks proactively.",
        st['body']
    ))

    # Submenu 3.3: Stock Movement Logs
    story.append(Paragraph("Submenu 3.3: Stock Movement Logs (/manufacture/stock-logs)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>manufacture-stock-logs</code><br/>"
        "<b>Purpose:</b> Immutable chronological audit trail of all factory stock transactions. Filterable by Product, Movement Type, and Date Range.<br/>"
        "<b>Logged Columns:</b> Timestamp, Product Name, Transaction Type (<code>add</code> for production, <code>edit</code> for physical audit adjustment, <code>dispatch</code> for warehouse transfer), Previous Stock, Adjustment Quantity (&plusmn;&Delta;), New Resulting Stock, Batch/Lot Number, Reference Note, and Performed By.",
        st['body']
    ))

    # Build Document
    canvas_factory = create_canvas_factory("DDMS Manufacturer Operations Manual &mdash; Menu-Wise Standard v3.0")
    for path in output_paths:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        doc = SimpleDocTemplate(path, pagesize=A4, leftMargin=36, rightMargin=36, topMargin=40, bottomMargin=40)
        doc.build(list(story), canvasmaker=canvas_factory)
        print(f"Successfully generated Manufacturer Manual: {path}")

# =============================================================================
# 3. WAREHOUSE PANEL MENU-WISE MANUAL
# =============================================================================
def build_warehouse_manual(output_paths):
    st = get_pdf_styles("#101C4C", "#D97706", "#F59E0B")
    story = []

    # Brand Header
    story.append(create_re_header(st))
    story.append(HRFlowable(width="100%", thickness=1, color=st['BORDER_LIGHT'], spaceBefore=2, spaceAfter=8))

    story.append(Paragraph("DESAI DELIVERY &amp; DISTRIBUTION MANAGEMENT SYSTEM", st['doc_title']))
    story.append(Paragraph("Warehouse Panel &mdash; Detailed Menu-Wise Operations Manual", st['doc_subtitle']))
    story.append(HRFlowable(width="100%", thickness=2, color=st['PRIMARY'], spaceBefore=2, spaceAfter=8))

    meta_table = Table([
        [Paragraph("<b>Target Workspace:</b>", st['table_cell_bold']), Paragraph("Regional Depot Portal (/warehouse/*)", st['table_cell']),
         Paragraph("<b>Authorized Role:</b>", st['table_cell_bold']), Paragraph("Role ID 4 (Warehouse / Depot Manager)", st['table_cell'])],
        [Paragraph("<b>Total Menus Covered:</b>", st['table_cell_bold']), Paragraph("8 Core Operational Modules &amp; Submenus", st['table_cell']),
         Paragraph("<b>Key Logic Engine:</b>", st['table_cell_bold']), Paragraph("Carry-Forward Differential Van Allocation &amp; Reconciliation", st['table_cell'])],
    ], colWidths=[110, 160, 100, 153])
    meta_table.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#FFFBEB")),
        ('BOX', (0,0), (-1,-1), 0.75, colors.HexColor("#FDE68A")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#FEF3C7")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(meta_table)
    story.append(Spacer(1, 10))

    # -------------------------------------------------------------
    # Menu 1: Dashboard
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 1: Dashboard (/warehouse/dashboard)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-gauge</code> | <b>Route:</b> <code>warehouse-dashboard</code>", st['body']))
    story.append(Paragraph(
        "<b>Functionality &amp; Purpose:</b> Real-time regional depot command center. Displays live KPI summaries for the current operational shift:<br/>"
        "&bull; <b>Depot Stock Valuation:</b> Total inventory stored on warehouse floor in dual units (Cases &amp; Bottles).<br/>"
        "&bull; <b>Active Field Vans:</b> Total mobile delivery vans dispatched on morning sales routes.<br/>"
        "&bull; <b>Today's Field Invoicing:</b> Cumulative value of invoices generated by drivers in the field.<br/>"
        "&bull; <b>Pending Reconciliations:</b> Count of delivery vans yet to complete evening return settlement.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 2: Product Management
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 2: Product Management (/warehouse/products)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-list</code> | <b>Route:</b> <code>warehouse-products</code>", st['body']))
    story.append(Paragraph(
        "<b>Functionality &amp; Purpose:</b> Depot price list and packaging directory. Displays all active commercial SKUs with their Case Wholesale Price, Unit Bottle Price, Bottles Per Case multiplier, and applicable GST rates. Used by depot supervisors to verify pricing applied to retail dispatches.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 3: Stock Management
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 3: Stock Management (/warehouse/stock)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-house</code> | <b>Route:</b> <code>warehouse-stock</code>", st['body']))
    story.append(Paragraph(
        "<b>Functionality &amp; Purpose:</b> Real-time depot physical stock table. Shows current stock of every beverage SKU in formatted Dual-Units (<code>X Cases + Y Bottles</code>).<br/>"
        "<b>Visual Indicators:</b> Red badge indicates 0 Cases (Stockout); Yellow indicates &le;50 Cases (Reorder Buffer); Green indicates healthy stock buffer (>50 Cases). Stock levels automatically adjust when morning manifests are dispatched or evening physical returns are accepted.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 4: Driver Management (Dropdown)
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 4: Driver Management (Dropdown)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-truck</code> | <b>Parent Route:</b> Van Dispatch Operations", st['body']))
    story.append(Paragraph(
        "Manages morning delivery van loading manifests, gate passes, and carry-forward allocations.",
        st['body']
    ))

    # Submenu 4.1: Product Assign
    story.append(Paragraph("Submenu 4.1: Product Assign (/warehouse/driver-product-create)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>driver-product-create</code><br/>"
        "<b>Purpose:</b> Morning loading manifest generation for field vans.<br/>"
        "<b>CRITICAL BUSINESS LOGIC &mdash; CARRY-FORWARD DIFFERENTIAL DEDUCTION:</b><br/>"
        "1. When a driver is selected, the system automatically checks if they have unsold bottles from yesterday (Carry Forward stock).<br/>"
        "2. If the driver already has 10 Cases carried forward and the target manifest is 25 Cases, the warehouse supervisor inputs 25 Cases.<br/>"
        "3. <b>The system automatically deducts only the net difference (15 Cases) from warehouse stock.</b> The 10 carried-forward cases are NOT deducted again, eliminating duplicate stock consumption.",
        st['body']
    ))

    # Submenu 4.2: Report List
    story.append(Paragraph("Submenu 4.2: Report List (/warehouse/driver-management-report)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>driver-management-report</code><br/>"
        "<b>Purpose:</b> Historical manifest directory. Displays past van loading manifests with Driver Name, Dispatch Date, Total Cases Loaded, and Manifest Status. Provides action buttons to <b>View Manifest Breakdown</b>, <b>Edit Allocation</b>, and <b>Print PDF Gate Pass</b> for security checkpoint clearance.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 5: Driver Daily Sales
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 5: Driver Daily Sales (/warehouse/daily-sales-report)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-chart-line</code> | <b>Route:</b> <code>warehouse-daily-sales-report</code>", st['body']))
    story.append(Paragraph(
        "<b>Functionality &amp; Purpose:</b> Evening financial and inventory settlement desk. Reconciles field sales, physical stock returning in the van, and cash/UPI receipts.",
        st['body']
    ))
    story.append(Paragraph("<b>Settlement Columns &amp; Metrics:</b>", st['body_bold']))
    story.append(Paragraph("&bull; <b>Assigned Bottles:</b> Total goods the driver carried into the field (Morning Load + Carry Forward).", st['bullet']))
    story.append(Paragraph("&bull; <b>Sold Bottles &amp; Cases:</b> Itemized quantity successfully invoiced to retail merchants.", st['bullet']))
    story.append(Paragraph("&bull; <b>Cash Collected vs UPI Payments:</b> Breakdown of revenue collected in hand vs digital UPI payments.", st['bullet']))
    story.append(Paragraph("&bull; <b>Total Invoices Count:</b> Number of retail shop bills generated during the shift.", st['bullet']))
    story.append(Paragraph(
        "&bull; <b>Evening Return Modal (Driver Return Store):</b> Opens the physical audit modal. The supervisor enters unsold goods and categorizes them into:<br/>"
        "&nbsp;&nbsp;&nbsp;&nbsp;1. <b>Carry Forward:</b> Intact, undamaged bottles that remain locked in the van for tomorrow's shift.<br/>"
        "&nbsp;&nbsp;&nbsp;&nbsp;2. <b>Physical Return / Scrap:</b> Broken, damaged, or expired bottles removed from the van and returned to depot scrap storage.",
        st['bullet']
    ))

    # -------------------------------------------------------------
    # Menu 6: Product Daily Sales
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 6: Product Daily Sales (/warehouse/product-daily-sales-report)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-boxes-stacked</code> | <b>Route:</b> <code>warehouse-product-daily-sales-report</code>", st['body']))
    story.append(Paragraph(
        "<b>Functionality &amp; Purpose:</b> Product-centric aggregated daily sales ledger. Consolidates sales volume across all delivery vans operating under this depot.<br/>"
        "<b>Features:</b><br/>"
        "&bull; <b>View Modes:</b> Toggle between 'Active Products' (only products with movement today) and 'All Products' (entire catalog).<br/>"
        "&bull; <b>Report Columns:</b> SKU Name, Assigned Bottles, Sold Bottles, Carry Forward Bottles, Scrapped Bottles, and Current Warehouse Available Stock.<br/>"
        "&bull; <b>Driver Breakdown Modal:</b> Click on any product row to see an itemized breakdown of which driver loaded, sold, or carried forward that specific SKU.<br/>"
        "&bull; <b>CSV Export:</b> One-click export button downloading full daily sales spreadsheet for depot bookkeeping.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 7: Invoice Management
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 7: Invoice Management (/warehouse/invoice)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-book</code> | <b>Route:</b> <code>warehouse-invoice</code> | <b>Create:</b> <code>warehouse-invoice-create</code>", st['body']))
    story.append(Paragraph(
        "<b>Functionality &amp; Purpose:</b> Direct depot B2B sales and field invoice repository.<br/>"
        "<b>Direct Invoice Creation (/warehouse/invoice-create):</b> Used when commercial merchants purchase goods directly from the warehouse loading dock. Enter Shop Name, Contact, GSTIN, PAN, and select line items with Cases/Bottles. Automatically computes GST and generates downloadable official commercial tax PDF.",
        st['body']
    ))

    # -------------------------------------------------------------
    # Menu 8: Fleet & Expenses (Dropdown)
    # -------------------------------------------------------------
    story.append(Paragraph("Menu 8: Fleet &amp; Expenses Operations (Dropdown)", st['menu_h1']))
    story.append(Paragraph("<b>Icon:</b> <code>fa-gas-pump</code> | <b>Parent Route:</b> Depot Fleet Management", st['body']))
    story.append(Paragraph(
        "Depot vehicle management suite for logging running costs, fuel refills, and repairs. Features <b>Driver-to-Vehicle Auto-Lock</b>: selecting a Driver automatically selects and locks their assigned vehicle.",
        st['body']
    ))

    # Submenu 8.1: Fuel Logs
    story.append(Paragraph("Submenu 8.1: Fuel Logs (/warehouse/vehicle/fuel-logs)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouse-vehicle-fuel-logs</code> | <b>Create:</b> <code>warehouse-vehicle-fuel-log-create</code><br/>"
        "<b>Input Fields &amp; Auto-Behaviors:</b><br/>"
        "&bull; <b>Driver Selection:</b> Dropdown of warehouse drivers. Selecting a driver auto-populates their vehicle.<br/>"
        "&bull; <b>Previous Odometer:</b> Automatically fetched from the vehicle's last recorded log.<br/>"
        "&bull; <b>Current Odometer:</b> Must be greater than Previous Odometer (validates distance covered).<br/>"
        "&bull; <b>Fuel Liters &amp; Rate:</b> Volume in liters and price/liter. Automatically calculates Total Cost.<br/>"
        "&bull; <b>Petrol Pump Name &amp; Bill Slip:</b> Station name and photographic receipt upload for audit verification.",
        st['body']
    ))

    # Submenu 8.2: Daily Expenses
    story.append(Paragraph("Submenu 8.2: Daily Expenses (/warehouse/vehicle/daily-expenses)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouse-vehicle-daily-expenses</code> | <b>Create:</b> <code>warehouse-vehicle-daily-expense-create</code><br/>"
        "<b>Input Fields &amp; Categories:</b><br/>"
        "&bull; <b>Driver Selection:</b> Auto-locks assigned delivery van.<br/>"
        "&bull; <b>Expense Date:</b> Defaults to current date.<br/>"
        "&bull; <b>Expense Category:</b> Toll Tax, Parking Fee, Police/Challan, Van Washing/Cleaning, Driver Food Allowance, Miscellaneous En-Route.<br/>"
        "&bull; <b>Amount (₹) &amp; Voucher:</b> Expense sum and photo upload of payment receipt or toll ticket.",
        st['body']
    ))

    # Submenu 8.3: Repair Costs
    story.append(Paragraph("Submenu 8.3: Repair Costs (/warehouse/vehicle/repair-logs)", st['menu_h2']))
    story.append(Paragraph(
        "<b>Route:</b> <code>warehouse-vehicle-repair-logs</code> | <b>Create:</b> <code>warehouse-vehicle-repair-log-create</code><br/>"
        "<b>Input Fields:</b> Driver, Auto-locked Vehicle, Workshop/Garage Name, Category (Scheduled 10k Maintenance, Engine/Transmission, Electrical/Battery, Tyre/Brake, Body Repair/Painting), Replaced Spare Parts List, Total Bill Amount (₹), and Garage Invoice copy upload.",
        st['body']
    ))

    # Build Document
    canvas_factory = create_canvas_factory("DDMS Warehouse Operations Manual &mdash; Menu-Wise Standard v3.0")
    for path in output_paths:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        doc = SimpleDocTemplate(path, pagesize=A4, leftMargin=36, rightMargin=36, topMargin=40, bottomMargin=40)
        doc.build(list(story), canvasmaker=canvas_factory)
        print(f"Successfully generated Warehouse Manual: {path}")

# =============================================================================
# Main Orchestrator
# =============================================================================
if __name__ == '__main__':
    base_public = r"c:\laragon\www\desai\public\manuals"
    base_brain = r"C:\Users\user\.gemini\antigravity\brain\b4d8eca2-e457-42bc-9477-a5d18f6bdfd7"

    print("--- 1. Building Admin Menu-Wise Manual ---")
    admin_public = os.path.join(base_public, "Admin_Menu_Wise_User_Manual.pdf")
    admin_brain = os.path.join(base_brain, "Admin_Menu_Wise_User_Manual.pdf")
    build_admin_manual([admin_public])
    shutil.copyfile(admin_public, admin_brain)

    print("--- 2. Building Manufacturer Menu-Wise Manual ---")
    mfg_public = os.path.join(base_public, "Manufacturer_Menu_Wise_User_Manual.pdf")
    mfg_brain = os.path.join(base_brain, "Manufacturer_Menu_Wise_User_Manual.pdf")
    build_manufacturer_manual([mfg_public])
    shutil.copyfile(mfg_public, mfg_brain)

    print("--- 3. Building Warehouse Menu-Wise Manual ---")
    wh_public = os.path.join(base_public, "Warehouse_Menu_Wise_User_Manual.pdf")
    wh_brain = os.path.join(base_brain, "Warehouse_Menu_Wise_User_Manual.pdf")
    build_warehouse_manual([wh_public])
    shutil.copyfile(wh_public, wh_brain)

    print("All 3 Menu-Wise manuals compiled and synchronized successfully!")
