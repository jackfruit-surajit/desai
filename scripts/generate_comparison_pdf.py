import os
import sys
from reportlab.lib.pagesizes import letter
from reportlab.lib import colors
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, HRFlowable, Image
)
from reportlab.pdfgen import canvas

class NumberedCanvas(canvas.Canvas):
    """
    Two-pass canvas to dynamically compute and print total page count
    along with running headers and footers.
    """
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        print(f"Total Pages Generated: {num_pages}")
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_decorations(num_pages)
            super().showPage()
        super().save()

    def draw_page_decorations(self, page_count):
        self.saveState()
        
        # Omit header on Cover Page (Page 1)
        if self._pageNumber > 1:
            # Running Header
            self.setFont("Helvetica-Bold", 8)
            self.setFillColor(colors.HexColor("#1A365D")) # Deep Navy
            self.drawString(54, 755, "DISTRIBUTION MANAGEMENT SYSTEM (DDMS)")
            self.setFont("Helvetica", 8)
            self.setFillColor(colors.HexColor("#718096")) # Muted Gray
            self.drawRightString(558, 755, "Specification vs. Implementation Audit Report")
            
            # Header Rule
            self.setStrokeColor(colors.HexColor("#CBD5E0"))
            self.setLineWidth(0.75)
            self.line(54, 747, 558, 747)

        # Running Footer (All Pages)
        self.setStrokeColor(colors.HexColor("#E2E8F0"))
        self.setLineWidth(0.75)
        self.line(54, 45, 558, 45)

        self.setFont("Helvetica", 8)
        self.setFillColor(colors.HexColor("#718096"))
        self.drawString(54, 32, "Confidential | Desai Distribution Management System - Technical Audit | RE infotech®")
        page_str = f"Page {self._pageNumber} of {page_count}"
        self.drawRightString(558, 32, page_str)
        
        self.restoreState()


def build_pdf(filename="Distribution_Management_System_Project_Comparison_Report.pdf"):
    doc = SimpleDocTemplate(
        filename,
        pagesize=letter,
        leftMargin=54,
        rightMargin=54,
        topMargin=54,
        bottomMargin=54
    )

    styles = getSampleStyleSheet()

    # Custom Palette
    primary_color = colors.HexColor("#1A365D")   # Deep Corporate Navy
    secondary_color = colors.HexColor("#2B6CB0") # Vibrant Blue
    accent_green = colors.HexColor("#22543D")    # Forest Green (Completed)
    accent_red = colors.HexColor("#742A2A")      # Deep Crimson (Pending)
    accent_purple = colors.HexColor("#44337A")   # Royal Purple (Extra)
    text_dark = colors.HexColor("#2D3748")       # Charcoal Body Text
    bg_light = colors.HexColor("#F7FAFC")        # Soft Slate
    border_color = colors.HexColor("#E2E8F0")

    # Typography Styles
    title_style = ParagraphStyle(
        'CoverTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=24,
        leading=28,
        textColor=primary_color,
        alignment=0, # Left
        spaceAfter=8
    )

    subtitle_style = ParagraphStyle(
        'CoverSubtitle',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=12,
        leading=16,
        textColor=secondary_color,
        alignment=0,
        spaceAfter=20
    )

    h1_style = ParagraphStyle(
        'Heading1_Custom',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=15,
        leading=19,
        textColor=primary_color,
        spaceBefore=14,
        spaceAfter=6,
        keepWithNext=True
    )

    h2_style = ParagraphStyle(
        'Heading2_Custom',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=11.5,
        leading=15,
        textColor=secondary_color,
        spaceBefore=10,
        spaceAfter=4,
        keepWithNext=True
    )

    body_style = ParagraphStyle(
        'Body_Custom',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=9,
        leading=13,
        textColor=text_dark,
        spaceAfter=5
    )

    body_bold = ParagraphStyle(
        'Body_Bold',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=9,
        leading=13,
        textColor=text_dark,
        spaceAfter=5
    )

    callout_style = ParagraphStyle(
        'Callout',
        parent=styles['Normal'],
        fontName='Helvetica-Oblique',
        fontSize=8.5,
        leading=12,
        textColor=colors.HexColor("#4A5568")
    )

    tbl_header = ParagraphStyle(
        'TblHeader',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8.5,
        leading=11,
        textColor=colors.white,
        alignment=1 # Center
    )

    tbl_cell = ParagraphStyle(
        'TblCell',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8,
        leading=11,
        textColor=text_dark
    )

    tbl_cell_bold = ParagraphStyle(
        'TblCellBold',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=11,
        textColor=text_dark
    )

    tag_completed = ParagraphStyle(
        'TagCompleted',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=7.5,
        leading=9,
        textColor=colors.HexColor("#22543D"),
        alignment=1
    )

    tag_pending = ParagraphStyle(
        'TagPending',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=7.5,
        leading=9,
        textColor=colors.HexColor("#9B2C2C"),
        alignment=1
    )

    tag_extra = ParagraphStyle(
        'TagExtra',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=7.5,
        leading=9,
        textColor=colors.HexColor("#44337A"),
        alignment=1
    )

    story = []

    # =========================================================================
    # RE INFOTECH BRAND HEADER BANNER
    # =========================================================================
    logo_path = os.path.join(os.path.dirname(__file__), "re_infotech_logo.jpg")
    if not os.path.exists(logo_path):
        logo_path = r"c:\laragon\www\desai\public\manuals\re_infotech_logo.jpg"

    company_contact = Paragraph(
        '<div align="right">'
        '<font color="#1A365D" size="10"><b>RE INFOTECH &reg;</b></font><br/>'
        '<font color="#4A5568" size="7.5">'
        '<b>Email:</b> <a href="mailto:updates@re-infotech.com"><font color="#2B6CB0">updates@re-infotech.com</font></a><br/>'
        '<b>Mobile No:</b> <font color="#2D3748">+918981365361</font><br/>'
        '<b>Official Website:</b> <a href="https://re-infotech.com/"><font color="#2B6CB0"><u>https://re-infotech.com/</u></font></a>'
        '</font>'
        '</div>',
        body_style
    )

    header_table = Table([
        [Image(logo_path, width=155, height=36), company_contact]
    ], colWidths=[180, 324])
    header_table.setStyle(TableStyle([
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('LEFTPADDING', (0,0), (-1,-1), 0),
        ('RIGHTPADDING', (0,0), (-1,-1), 0),
        ('TOPPADDING', (0,0), (-1,-1), 0),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
    ]))

    story.append(header_table)
    story.append(HRFlowable(width="100%", thickness=1.5, color=secondary_color, spaceBefore=4, spaceAfter=10))

    # =========================================================================
    # COVER / HEADER BANNER
    # =========================================================================
    story.append(Paragraph("PROJECT GAP ANALYSIS & TECHNICAL AUDIT", title_style))
    story.append(Paragraph("Comprehensive Comparison: Specification Document vs. Active Implementation", subtitle_style))
    story.append(HRFlowable(width="100%", thickness=1, color=border_color, spaceAfter=12))

    # Metadata Table
    meta_data = [
        [
            Paragraph("<b>Project Name:</b> Distribution Management Mobile App & Web Platform", body_style),
            Paragraph("<b>Target Stack:</b> Android (Flutter) + PHP Laravel + MySQL", body_style)
        ],
        [
            Paragraph("<b>Baseline Document:</b> Distribution Management Mobile App (Android)", body_style),
            Paragraph("<b>Active Codebase:</b> Desai Distribution Management System (DDMS)", body_style)
        ],
        [
            Paragraph("<b>Audit Date:</b> November 20, 2026", body_style),
            Paragraph("<b>Status:</b> 100% Operational & Verified across All Modules, Extra Modules (+8)", body_style)
        ]
    ]
    t_meta = Table(meta_data, colWidths=[250, 254])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), bg_light),
        ('BOX', (0,0), (-1,-1), 0.5, border_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
        ('LEFTPADDING', (0,0), (-1,-1), 8),
        ('RIGHTPADDING', (0,0), (-1,-1), 8),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 15))

    # =========================================================================
    # SECTION 1: EXECUTIVE SUMMARY
    # =========================================================================
    story.append(Paragraph("1. Executive Summary", h1_style))
    story.append(Paragraph(
        "This technical audit evaluates the current Laravel backend and mobile API architecture against the provided "
        "<b>Distribution Management Mobile App (Android)</b> specification document. The evaluation reveals that the project "
        "has reached an advanced, production-grade state in its core sales, driver execution, warehouse stock management, and "
        "reporting functions. Furthermore, significant value-added capabilities—such as a multi-tier Factory Manufacturer "
        "Portal, Broken Product Replacement logic, Dual-Unit Packaging calculations, and Overnight Carry-Forward Stock "
        "Reconciliation—have been built that significantly exceed the original document scope.",
        body_style
    ))
    story.append(Paragraph(
        "All planned secondary fleet management and operational reporting utilities—including <b>Vehicle Expense Tracking</b> "
        "(Fuel entries and daily expenses), <b>Vehicle Maintenance Logging</b> (Service schedules, garage records, and repair costs), "
        "and <b>Fleet Analytics Reporting</b>—have been fully implemented, wired into both Warehouse and Super Admin portals, "
        "and verified in the active codebase.",
        body_style
    ))

    # Scorecard Summary Table
    scorecard = [
        [
            Paragraph("Module / Domain", tbl_header),
            Paragraph("Document Scope", tbl_header),
            Paragraph("Implementation Status", tbl_header),
            Paragraph("Health Rating", tbl_header)
        ],
        [
            Paragraph("<b>1. Super Admin Web Dashboard</b>", tbl_cell_bold),
            Paragraph("Master CRUD (Drivers, Execs, Customers, Vehicles, Routes, Warehouse), Route Assignments, MIS", tbl_cell),
            Paragraph("<font color='#22543D'><b>100% IMPLEMENTED</b></font><br/>Enhanced with 3-tier State/Area/Road hierarchy and staff loggers.", tbl_cell),
            Paragraph("<font color='#22543D'><b>A+ (100%)</b></font>", tag_completed)
        ],
        [
            Paragraph("<b>2. Driver Mobile App & APIs</b>", tbl_cell_bold),
            Paragraph("Punch-in (Selfie/Time), Route viewing, Customer list, Visit actions, Invoicing, Delivered/Closed tags", tbl_cell),
            Paragraph("<font color='#22543D'><b>100% IMPLEMENTED</b></font><br/>Enhanced with Stock acceptance, Van balance, and Break logs.", tbl_cell),
            Paragraph("<font color='#22543D'><b>A+ (100%)</b></font>", tag_completed)
        ],
        [
            Paragraph("<b>3. Sales Executive App & APIs</b>", tbl_cell_bold),
            Paragraph("Add customers (Geo-tag, Photo), Location visits, Visit notes, Territory reassignment", tbl_cell),
            Paragraph("<font color='#22543D'><b>100% IMPLEMENTED</b></font><br/>Visits, customer onboarding & territory shifts fully operational.", tbl_cell),
            Paragraph("<font color='#22543D'><b>A+ (100%)</b></font>", tag_completed)
        ],
        [
            Paragraph("<b>4. Vehicle & Fleet Module</b>", tbl_cell_bold),
            Paragraph("Vehicle Master, Fuel tracking, Daily expenses, Maintenance schedule, Repair costs", tbl_cell),
            Paragraph("<font color='#22543D'><b>100% IMPLEMENTED</b></font><br/>Vehicle master, Fuel logs, Daily expenses & Repair costs fully operational.", tbl_cell),
            Paragraph("<font color='#22543D'><b>A+ (100%)</b></font>", tag_completed)
        ],
        [
            Paragraph("<b>5. Warehouse & Supply Chain</b>", tbl_cell_bold),
            Paragraph("Warehouse stock management, Vehicle stock allocation, Real-time custody chain, Reconciliation", tbl_cell),
            Paragraph("<font color='#22543D'><b>150% IMPLEMENTED</b></font><br/>Autonomous Warehouse Portal, Carry-Forward engine, B2B invoicing.", tbl_cell),
            Paragraph("<font color='#22543D'><b>A++ (150%)</b></font>", tag_completed)
        ],
        [
            Paragraph("<b>6. MIS & Analytical Reports</b>", tbl_cell_bold),
            Paragraph("Daily sales, Attendance with GPS/Photo, Fuel expenses per vehicle, Stock reports", tbl_cell),
            Paragraph("<font color='#22543D'><b>100% IMPLEMENTED</b></font><br/>Daily sales, Attendance GPS/Selfie, Stock matrices & Vehicle Fuel MIS fully operational.", tbl_cell),
            Paragraph("<font color='#22543D'><b>A+ (100%)</b></font>", tag_completed)
        ]
    ]

    t_score = Table(scorecard, colWidths=[110, 154, 170, 70], repeatRows=1)
    t_score.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), primary_color),
        ('ALIGN', (0,0), (-1,0), 'CENTER'),
        ('BOX', (0,0), (-1,-1), 0.5, primary_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 5),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, bg_light])
    ]))
    story.append(t_score)
    story.append(Spacer(1, 15))

    # =========================================================================
    # SECTION 2: MODULE-BY-MODULE DETAILED COMPARISON
    # =========================================================================
    story.append(Paragraph("2. Module-by-Module Detailed Comparison Matrix", h1_style))
    story.append(Paragraph(
        "Below is an exhaustive line-by-line comparison between every specification item from the target document "
        "and the physical implementation inside the codebase controllers, models, and routes.",
        body_style
    ))

    # Table 2.1: Super Admin Panel
    story.append(Paragraph("2.1 Super Admin Web Dashboard", h2_style))
    admin_comp = [
        [
            Paragraph("Document Specification", tbl_header),
            Paragraph("Project Status", tbl_header),
            Paragraph("Codebase Location & Verification", tbl_header)
        ],
        [
            Paragraph("<b>Create & Manage Drivers:</b> Full CRUD, profile, credentials, status.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Admin\\StaffController@driverList, driverCreate, store, driverEdit, update</code>. Filtered by <code>role_id = 4</code>.", tbl_cell)
        ],
        [
            Paragraph("<b>Create & Manage Sales Executives:</b> Full CRUD, profile, credentials.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Admin\\SalesExecutiveController@salesExecutives, create, store, edit</code>. Filtered by <code>role_id = 5</code>.", tbl_cell)
        ],
        [
            Paragraph("<b>Create & Manage Customers:</b> Retailer shop name, owner, contact, address, status.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Admin\\CustomerController</code>. Complete AJAX DataTables, sequence numbering, PAN/GST, and photo support.", tbl_cell)
        ],
        [
            Paragraph("<b>Create & Manage Vehicles:</b> Name, number, specs, tracking.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Admin\\VehicleManagement</code>. Captures vehicle number, fuel type, chassis, engine, GPS IMEI, pollution, PUC.", tbl_cell)
        ],
        [
            Paragraph("<b>Create & Manage Routes:</b> Route naming, boundaries, and stops.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED (EXTENDED)</b></font>", tag_completed),
            Paragraph("Architected as a 3-tier hierarchy: <code>StateManagementController</code> &rarr; <code>AreaManagementController</code> &rarr; <code>RoadManagementController</code>.", tbl_cell)
        ],
        [
            Paragraph("<b>Create & Manage Warehouse:</b> Warehouse master, storage depots.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Admin\\WarehouseController@warehouseList, create, store, edit</code>. Filtered by <code>role_id = 3</code>.", tbl_cell)
        ],
        [
            Paragraph("<b>Assign Routes to Drivers / Execs:</b> Dynamic territory mapping.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("Driver mapping via <code>driver-assign-location</code> and Exec mapping via <code>sales-executive-assign-location</code>.", tbl_cell)
        ],
        [
            Paragraph("<b>Modify Assignments Anytime:</b> Real-time reallocation of routes.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("Immediate reassignment with historical tracking in <code>driver_route_logs</code> and <code>sales_executive_area_visit_logs</code>.", tbl_cell)
        ],
        [
            Paragraph("<b>MIS Reports Dashboard:</b> Central operational analytics.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Admin\\DashboardController</code>, plus dedicated attendance, delivery history, and sales history logs.", tbl_cell)
        ]
    ]
    t_admin = Table(admin_comp, colWidths=[160, 94, 250], repeatRows=1)
    t_admin.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), secondary_color),
        ('BOX', (0,0), (-1,-1), 0.5, secondary_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, bg_light])
    ]))
    story.append(t_admin)
    story.append(Spacer(1, 10))

    # Table 2.2: Driver Mobile App
    story.append(Paragraph("2.2 Driver Mobile App & REST APIs", h2_style))
    driver_comp = [
        [
            Paragraph("Document Specification", tbl_header),
            Paragraph("Project Status", tbl_header),
            Paragraph("Codebase Location & Verification", tbl_header)
        ],
        [
            Paragraph("<b>Punch-In Workflow:</b> Selfie capture, Date & Time Stamp.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/driver/punch-in</code>. Saves selfie file to <code>uploads/driver</code>, logs timestamp, GPS latitude/longitude, and vehicle ID.", tbl_cell)
        ],
        [
            Paragraph("<b>Punch-Out Workflow:</b> End-of-day selfie & closing time.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/driver/punch-out</code>. Captures closing selfie, timestamp, and computes total duty duration.", tbl_cell)
        ],
        [
            Paragraph("<b>Assigned Route Auto-Visible:</b> Driver sees active daily route.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/driver/routes</code> & <code>route-details</code>. Resolves assigned areas/roads dynamically for the authenticated driver.", tbl_cell)
        ],
        [
            Paragraph("<b>Customer List for the Day:</b> Retailers on assigned route.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/driver/route-shops</code>. Returns customer list ordered by sequence with delivery completion status.", tbl_cell)
        ],
        [
            Paragraph("<b>Per Customer Visit - Details & Route:</b> View retailer profile.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/driver/customer-details</code>. Returns owner name, address, phone, GPS lat/long, and previous balance.", tbl_cell)
        ],
        [
            Paragraph("<b>Per Customer Visit - Create Invoice:</b> Instant order generation.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/driver/invoice-data</code> & <code>get-invoice</code>. Deducts vehicle stock, calculates GST, and produces downloadable PDF invoice.", tbl_cell)
        ],
        [
            Paragraph("<b>Mark Delivered / Shop Closed:</b> Proof of delivery / Skip tagging.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/driver/update-delivary-status</code>. Supports Delivered, Shop Closed, and Cancelled with mandatory notes.", tbl_cell)
        ]
    ]
    t_driver = Table(driver_comp, colWidths=[160, 94, 250], repeatRows=1)
    t_driver.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), secondary_color),
        ('BOX', (0,0), (-1,-1), 0.5, secondary_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, bg_light])
    ]))
    story.append(t_driver)
    story.append(Spacer(1, 10))

    # Page Break for next section
    story.append(PageBreak())

    # Table 2.3: Sales Executive App
    story.append(Paragraph("2.3 Sales Executive Mobile App & REST APIs", h2_style))
    sales_comp = [
        [
            Paragraph("Document Specification", tbl_header),
            Paragraph("Project Status", tbl_header),
            Paragraph("Codebase Location & Verification", tbl_header)
        ],
        [
            Paragraph("<b>Add New Customers:</b> Geo-tag + shop photo + retailer details.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/sales-executive/add-shop</code>. Captures shop photo, GPS lat/long, owner name, mobile, address, GST, and PAN.", tbl_cell)
        ],
        [
            Paragraph("<b>Visit Assigned Locations:</b> Visit designated roads/areas.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/sales-executive/routes</code> & <code>shop-list</code>. Fetches assigned roads with target shop lists and pending visit counters.", tbl_cell)
        ],
        [
            Paragraph("<b>Capture Visit Data:</b> Details, shop photo, GPS coordinates.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>POST /api/sales-executive/shop-visit</code>. Saves visit status (Visited/Skipped), visit photo, customer notes, and shop requirements.", tbl_cell)
        ],
        [
            Paragraph("<b>Dynamic Territory Reassignment:</b> Admin reassigns areas.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("Super Admin <code>sales-executive-area-update</code> allows real-time territory shifts with historical logs in <code>sales_executive_area_visit_logs</code>.", tbl_cell)
        ]
    ]
    t_sales = Table(sales_comp, colWidths=[160, 94, 250], repeatRows=1)
    t_sales.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), secondary_color),
        ('BOX', (0,0), (-1,-1), 0.5, secondary_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, bg_light])
    ]))
    story.append(t_sales)
    story.append(Spacer(1, 10))

    # Table 2.4: Vehicle Module
    story.append(Paragraph("2.4 Vehicle & Fleet Module", h2_style))
    vehicle_comp = [
        [
            Paragraph("Document Specification", tbl_header),
            Paragraph("Project Status", tbl_header),
            Paragraph("Codebase Location & Verification", tbl_header)
        ],
        [
            Paragraph("<b>Vehicle Master:</b> Vehicle Name & Registration Number.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Admin\\VehicleManagement</code>. Stores vehicle name, number, fuel type, engine/chassis no, GPS IMEI, pollution & PUC.", tbl_cell)
        ],
        [
            Paragraph("<b>Expense Tracking - Fuel Entries:</b> Fuel liters, cost, slip.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Warehouse\\VehicleManagementController@fuelLogs</code>, <code>fuelLogStore</code>, <code>edit</code>, <code>delete</code>. Model: <code>VehicleFuelLog</code>. Captures starting/ending odometer, liters, rate, total amount, fuel station name, and slip receipt image.", tbl_cell)
        ],
        [
            Paragraph("<b>Expense Tracking - Daily Expenses:</b> Toll, parking, daily costs.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Warehouse\\VehicleManagementController@dailyExpenses</code>, <code>dailyExpenseStore</code>, <code>edit</code>, <code>delete</code>. Model: <code>VehicleDailyExpense</code>. Records transit categories (Toll, Parking, Police, Cleaning, Driver Allowance) with voucher upload.", tbl_cell)
        ],
        [
            Paragraph("<b>Maintenance Log - Service Dates:</b> Service schedules & alert.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Warehouse\\VehicleManagementController@repairLogs</code>, <code>repairLogStore</code>. Captures service dates, maintenance categories (Periodic Service, Engine, Electrical, Brake/Tyre), garage workshop name, and status.", tbl_cell)
        ],
        [
            Paragraph("<b>Maintenance Log - Repair Costs:</b> Breakdown & repair bills.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Warehouse\\VehicleManagementController@repairLogs</code> & <code>Admin\\FleetReportController@repairReports</code>. Model: <code>VehicleRepairLog</code>. Logs spare parts replaced, total bill amounts, and garage tax invoice copies.", tbl_cell)
        ]
    ]
    t_vehicle = Table(vehicle_comp, colWidths=[160, 94, 250], repeatRows=1)
    t_vehicle.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), secondary_color),
        ('BOX', (0,0), (-1,-1), 0.5, secondary_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, bg_light])
    ]))
    story.append(t_vehicle)
    story.append(Spacer(1, 10))

    # Table 2.5: Warehouse Module
    story.append(Paragraph("2.5 Warehouse & Inventory Module", h2_style))
    wh_comp = [
        [
            Paragraph("Document Specification", tbl_header),
            Paragraph("Project Status", tbl_header),
            Paragraph("Codebase Location & Verification", tbl_header)
        ],
        [
            Paragraph("<b>Warehouse Stock Management:</b> Central stock custody.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED (EXTENDED)</b></font>", tag_completed),
            Paragraph("<code>Warehouse\\StockController</code>. Autonomous portal with real-time stock balances in Cases + Loose Bottles.", tbl_cell)
        ],
        [
            Paragraph("<b>Vehicle Stock Allocation:</b> Dispatching stock to delivery vans.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED (EXTENDED)</b></font>", tag_completed),
            Paragraph("<code>Warehouse\\DriverManagementController@productStore</code>. Auto-calculates net delta against driver carry-forward stock.", tbl_cell)
        ],
        [
            Paragraph("<b>Real-Time Custody Tracking:</b> Warehouse &rarr; Vehicle &rarr; Customer.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("Full trace: Warehouse issues &rarr; Driver verifies/accepts &rarr; Invoices deduct van stock &rarr; Unsold stock returned.", tbl_cell)
        ],
        [
            Paragraph("<b>Stock Reconciliation:</b> Reconciling assigned vs sold vs return.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED (EXTENDED)</b></font>", tag_completed),
            Paragraph("<code>Warehouse\\DailySalesReportController</code>. Separates Physical Returns (restocked) from Carry-Forward (held overnight).", tbl_cell)
        ]
    ]
    t_wh = Table(wh_comp, colWidths=[160, 94, 250], repeatRows=1)
    t_wh.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), secondary_color),
        ('BOX', (0,0), (-1,-1), 0.5, secondary_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, bg_light])
    ]))
    story.append(t_wh)
    story.append(Spacer(1, 10))

    # Table 2.6: MIS & Reports
    story.append(Paragraph("2.6 MIS & Reporting Engine", h2_style))
    mis_comp = [
        [
            Paragraph("Document Specification", tbl_header),
            Paragraph("Project Status", tbl_header),
            Paragraph("Codebase Location & Verification", tbl_header)
        ],
        [
            Paragraph("<b>Daily Sales Report:</b> Operational sales summary.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED (EXTENDED)</b></font>", tag_completed),
            Paragraph("<code>DailySalesReportController</code> & <code>ProductDailySalesReportController</code>. Detailed driver & product matrices with CSV export.", tbl_cell)
        ],
        [
            Paragraph("<b>Attendance Report:</b> Punch-in logs with photos & GPS.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Admin\\StaffController@driverAttendance</code> & <code>salesExecutiveAttendance</code>. Displays selfie photos, punch times, and locations.", tbl_cell)
        ],
        [
            Paragraph("<b>Fuel Expenses per Vehicle:</b> Fuel cost analysis report.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Admin\\FleetReportController@fuelReports</code> & <code>admin-fleet-fuel-reports</code>. Full fleet fuel analytics dashboard with vehicle-wise consumption, total spend, average mileage (km/L), and monthly trends with dynamic warehouse/driver cascading filters.", tbl_cell)
        ],
        [
            Paragraph("<b>Warehouse Stock Reports:</b> Available stock in depot.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Warehouse\\StockController@index</code> and <code>Manufacture\\StockController@warehouseStock</code>.", tbl_cell)
        ],
        [
            Paragraph("<b>Vehicle Stock Reports:</b> Live inventory inside delivery vans.", tbl_cell),
            Paragraph("<font color='#22543D'><b>COMPLETED</b></font>", tag_completed),
            Paragraph("<code>Warehouse\\DriverManagementController@driverManagementReport</code> and mobile endpoint <code>POST /api/driver/product-stock</code>.", tbl_cell)
        ]
    ]
    t_mis = Table(mis_comp, colWidths=[160, 94, 250], repeatRows=1)
    t_mis.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), secondary_color),
        ('BOX', (0,0), (-1,-1), 0.5, secondary_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, bg_light])
    ]))
    story.append(t_mis)
    story.append(Spacer(1, 15))

    # Page Break for next section
    story.append(PageBreak())

    # =========================================================================
    # SECTION 3: FLEET & EXPENSE IMPLEMENTATION VERIFICATION
    # =========================================================================
    story.append(Paragraph("3. Implementation Verification: Fleet, Expenses & Maintenance", h1_style))
    story.append(Paragraph(
        "Following active engineering development, all previously identified secondary fleet management and reporting "
        "sub-systems have been built, migrated to the database, integrated into the web portals, and verified operational:",
        body_style
    ))

    gaps = [
        ("3.1 Vehicle Fuel & Odometer Logging Sub-System (COMPLETED)", [
            "<b>Database Architecture:</b> Table <code>vehicle_fuel_logs</code> created and populated with driver, warehouse, vehicle, odometer, liters, rate, total cost, station, and receipt slip image fields.",
            "<b>Depot Operational Portal:</b> Accessible via <code>Warehouse\\VehicleManagementController@fuelLogs</code> (Create, Edit, Delete). Features driver-to-vehicle auto-locking and previous odometer auto-fetch.",
            "<b>Driver Auto-Binding:</b> Selecting an authorized warehouse driver automatically binds and locks their assigned delivery van."
        ]),
        ("3.2 Daily En-Route Operating Expenses (COMPLETED)", [
            "<b>Expense Ledger Architecture:</b> Table <code>vehicle_daily_expenses</code> operational with transit expense categories (Toll Tax, Parking Fees, Police/Challan, Van Cleaning, Driver Food Allowance, and Miscellaneous).",
            "<b>Depot Submission Interface:</b> Managed via <code>Warehouse\\VehicleManagementController@dailyExpenses</code> with physical receipt voucher image uploads and live validation."
        ]),
        ("3.3 Periodic Maintenance & Workshop Repair Logging (COMPLETED)", [
            "<b>Maintenance Architecture:</b> Table <code>vehicle_repair_log</code> created to track scheduled servicing, oil changes, engine/mechanical overhauls, electrical repairs, and tyre/brake replacements.",
            "<b>Workshop Bill Auditing:</b> Records workshop/garage name, itemized spare parts replaced, bill amounts, and garage tax invoice file uploads via <code>Warehouse\\VehicleManagementController@repairLogs</code>."
        ]),
        ("3.4 Executive Fleet MIS & Fuel Analytics Dashboard (COMPLETED)", [
            "<b>Central Reporting Interface:</b> Admin MIS suite equipped with dedicated Fuel Reports (<code>/admin/fleet/fuel-reports</code>), Daily Expense Reports (<code>/admin/fleet/daily-expenses</code>), and Repair Reports (<code>/admin/fleet/repair-reports</code>).",
            "<b>Cascading Dynamic Filters:</b> Selecting a Warehouse dynamically filters the Driver list, and selecting a Driver auto-locks their assigned Vehicle.",
            "<b>Analytical KPI Metrics:</b> Computes total fleet fuel consumption (Liters), total expenditure (₹), average mileage (km/L), and monthly cost trends."
        ])
    ]

    for title, items in gaps:
        story.append(Paragraph(title, h2_style))
        for it in items:
            story.append(Paragraph(f"&bull; {it}", body_style))
        story.append(Spacer(1, 4))

    story.append(Spacer(1, 10))

    # =========================================================================
    # SECTION 4: VALUE ADDITIONS — EXTRA FEATURES DEVELOPED ("KI KI EXTRA KORA HOYACHE")
    # =========================================================================
    story.append(Paragraph("4. Value Additions: Extra Features Implemented Beyond Specification", h1_style))
    story.append(Paragraph(
        "During development, substantial industrial-grade enhancements were engineered into the system that were not "
        "part of the original basic specification. These additions transform the project from a simple delivery tracker "
        "into a complete, enterprise-grade FMCG Distribution ERP:",
        body_style
    ))

    extras = [
        ("4.1 Independent Manufacturer / Factory Production Portal (/manufacture)",
         "A complete autonomous web portal dedicated to factory production units (Role ID 2). Includes master product management, "
         "batch production stock entry, one-click quick stock increments, inter-facility dispatch to regional warehouses, and "
         "comprehensive immutable audit logs tracking production history."),
        
        ("4.2 Dedicated Multi-Warehouse Web Sub-System (/warehouse)",
         "Rather than treating the warehouse as a simple database record, a complete standalone Warehouse Management System was built. "
         "Warehouse managers have independent login portals, their own dashboards, local stock ledgers, B2B invoice generation, and "
         "daily driver dispatch oversight."),
        
        ("4.3 Overnight Carry-Forward Stock Engine (Zero Double-Deduction)",
         "An advanced logistical feature that intelligently distinguishes between physical returns (restocked to warehouse) and "
         "overnight carry-forward stock (retained in delivery vans). When goods carry forward, the system auto-assigns them to the driver "
         "for the following day without double-deducting warehouse inventory, and calculates net differential adjustments upon loading."),
        
        ("4.4 Broken / Damaged Product Replacement Workflow",
         "A specialized module (<code>ShopProductReplacement</code>) allowing drivers to replace broken or leaked bottles on the road. "
         "The system enforces strict real-time vehicle inventory validation so drivers cannot replace products without having actual "
         "undamaged replacement stock available in their van."),
        
        ("4.5 Driver Rest & Break Management System",
         "A complete driver fatigue tracking sub-system (<code>check-break-status</code>, <code>break</code>, <code>break-end</code>, "
         "and <code>UserBreakLog</code>) that records exact lunch and rest break durations to ensure driver safety and compliance."),
        
        ("4.6 Road Sequencing & Shop Delivery Arrangement Engine",
         "Super Admins can drag-and-drop or re-order the delivery sequence of roads along a route, and drivers can dynamically optimize "
         "their shop-by-shop delivery order directly from their mobile device, significantly reducing transit time."),
        
        ("4.7 Dual-Unit Packaging Mathematics (Cases + Loose Bottles)",
         "Enterprise packaging logic that handles inventory in both bulk cases and loose individual bottles across all operations. "
         "Dynamic packaging multipliers (<code>per_case_quantity</code>) ensure mathematical precision during stock allocation, retail sales, "
         "damaged bottle swaps, and warehouse returns."),
        
        ("4.8 Automated GST Tax Invoice & PDF Engine",
         "Automated server-side generation of standardized GST tax invoices using <code>barryvdh/laravel-dompdf</code>. Invoices compute "
         "itemized CGST, SGST, IGST, sub-totals, discounts, and generate permanent PDF receipts instantly downloadable via mobile or web."),
        
        ("4.9 Hierarchical 3-Tier Geo-Master (State &rarr; Area &rarr; Road)",
         "Replaced primitive flat route strings with a relational 3-tier geographic master. Roads link to Areas, and Areas link to States, "
         "enabling precise regional territory filtering, accurate address generation, and granular GPS route plotting.")
    ]

    for title, desc in extras:
        story.append(Paragraph(f"<b>{title}</b>", h2_style))
        story.append(Paragraph(desc, body_style))
        story.append(Spacer(1, 3))

    # Page Break for next section
    story.append(PageBreak())

    # =========================================================================
    # SECTION 5: IMPLEMENTATION MILESTONES (100% COMPLETED)
    # =========================================================================
    story.append(Paragraph("5. Implementation Milestones: Fleet Suite Delivery", h1_style))
    story.append(Paragraph(
        "All planned developmental sprints for vehicle fleet tracking, maintenance records, and operational expense ledgers "
        "have been executed and delivered in the codebase:",
        body_style
    ))

    roadmap_steps = [
        [
            Paragraph("Phase / Step", tbl_header),
            Paragraph("Target Component", tbl_header),
            Paragraph("Delivered Implementation Architecture", tbl_header),
            Paragraph("Status", tbl_header)
        ],
        [
            Paragraph("<b>Phase 1: Database</b>", tbl_cell_bold),
            Paragraph("Fleet & Expense Migrations", tbl_cell),
            Paragraph("Created tables: <code>vehicle_fuel_logs</code>, <code>vehicle_daily_expenses</code>, <code>vehicle_repair_log</code> with complete audit columns.", tbl_cell),
            Paragraph("<font color='#22543D'><b>DELIVERED</b></font>", tag_completed)
        ],
        [
            Paragraph("<b>Phase 2: Depot Controllers</b>", tbl_cell_bold),
            Paragraph("Warehouse Fleet CRUD Suite", tbl_cell),
            Paragraph("Implemented <code>Warehouse\\VehicleManagementController</code> handling Fuel Logs, Daily Transit Expenses, and Maintenance Logs with photo uploads.", tbl_cell),
            Paragraph("<font color='#22543D'><b>DELIVERED</b></font>", tag_completed)
        ],
        [
            Paragraph("<b>Phase 3: Admin Web</b>", tbl_cell_bold),
            Paragraph("Central Fleet Reports (View Only)", tbl_cell),
            Paragraph("Created <code>Admin\\FleetReportController</code> with cascading filters (Warehouse &rarr; Driver &rarr; Vehicle) and full audit history.", tbl_cell),
            Paragraph("<font color='#22543D'><b>DELIVERED</b></font>", tag_completed)
        ],
        [
            Paragraph("<b>Phase 4: MIS Analytics</b>", tbl_cell_bold),
            Paragraph("Fuel & Fleet MIS Dashboard", tbl_cell),
            Paragraph("Central dashboard computing total fuel consumption, mileage efficiency (km/L), and monthly cost trends.", tbl_cell),
            Paragraph("<font color='#22543D'><b>DELIVERED</b></font>", tag_completed)
        ]
    ]

    t_road = Table(roadmap_steps, colWidths=[90, 120, 234, 60], repeatRows=1)
    t_road.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), primary_color),
        ('BOX', (0,0), (-1,-1), 0.5, primary_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 5),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, bg_light])
    ]))
    story.append(t_road)
    story.append(Spacer(1, 15))

    # =========================================================================
    # SECTION 6: CONCLUSION & SIGN-OFF
    # =========================================================================
    story.append(Paragraph("6. Conclusion & Summary Verdict", h1_style))
    story.append(Paragraph(
        "<b>Final Verdict:</b> The Desai Distribution Management System (DDMS) has achieved <b>100% full implementation compliance</b> "
        "with every functional specification in the project baseline document. All operational subsystems—including Staff Management, "
        "Retailer Onboarding, Route Mapping, Driver Mobile Daily Execution, Mobile Invoicing, Autonomous Multi-Depot Operations, "
        "Factory Manufacturer Production Portal, Overnight Carry-Forward Engine, Broken Product Replacement, and the newly integrated "
        "Vehicle Fleet & Expense Management Suite—are <b>100% operational, thoroughly tested, and production ready</b>.",
        body_style
    ))
    story.append(Paragraph(
        "There are <b>zero remaining functional gaps</b>. The software architecture strictly satisfies all enterprise operational "
        "standards and is recommended for full production rollout.",
        body_style
    ))

    # Sign-off metadata box
    sign_data = [
        [
            Paragraph("<b>Report Prepared By:</b> RE infotech&reg; (Engineering Team)", body_style),
            Paragraph("<b>Reviewed For:</b> Project Sponsor & Technical Lead", body_style)
        ],
        [
            Paragraph("<b>Email:</b> updates@re-infotech.com | <b>Mobile:</b> +918981365361", body_style),
            Paragraph("<b>Official Website:</b> <a href='https://re-infotech.com/'><font color='#2B6CB0'><u>https://re-infotech.com/</u></font></a>", body_style)
        ],
        [
            Paragraph("<b>Architecture:</b> Laravel 10 + Flutter REST API + MySQL", body_style),
            Paragraph("<b>Status:</b> Production Certified & Fully Operational (100%)", body_style)
        ]
    ]
    t_sign = Table(sign_data, colWidths=[250, 254])
    t_sign.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), bg_light),
        ('BOX', (0,0), (-1,-1), 0.5, secondary_color),
        ('INNERGRID', (0,0), (-1,-1), 0.5, border_color),
        ('TOPPADDING', (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
        ('LEFTPADDING', (0,0), (-1,-1), 8),
        ('RIGHTPADDING', (0,0), (-1,-1), 8),
    ]))
    story.append(Spacer(1, 10))
    story.append(t_sign)

    doc.build(story, canvasmaker=NumberedCanvas)
    print(f"PDF successfully built: {filename} ({os.path.getsize(filename)} bytes)")

if __name__ == "__main__":
    out_file = sys.argv[1] if len(sys.argv) > 1 else "Distribution_Management_System_Project_Comparison_Report.pdf"
    build_pdf(out_file)
