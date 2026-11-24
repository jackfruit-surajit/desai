import os
import sys
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, HRFlowable, Image
)
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.pdfgen import canvas

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
        self.setFont("Helvetica", 8)
        self.setFillColor(colors.HexColor("#64748B"))

        doc_title = getattr(self, 'doc_title', "Desai Delivery & Distribution Management System (DDMS)")

        # Header (Pages > 1)
        if self._pageNumber > 1:
            self.drawString(40, A4[1] - 30, doc_title)
            self.setStrokeColor(colors.HexColor("#CBD5E1"))
            self.setLineWidth(0.5)
            self.line(40, A4[1] - 35, A4[0] - 40, A4[1] - 35)

        # Footer (All pages)
        self.setStrokeColor(colors.HexColor("#CBD5E1"))
        self.setLineWidth(0.5)
        self.line(40, 38, A4[0] - 40, 38)
        self.drawString(40, 26, "CONFIDENTIAL | Desai Delivery & Distribution Management System | RE infotech® (updates@re-infotech.com)")
        page_text = f"Page {self._pageNumber} of {page_count}"
        self.drawRightString(A4[0] - 40, 26, page_text)
        self.restoreState()

def create_canvas_factory(doc_title):
    def make_canvas(*args, **kwargs):
        c = NumberedCanvas(*args, **kwargs)
        c.doc_title = doc_title
        return c
    return make_canvas

# Shared Helper to get styled Paragraphs
def get_pdf_styles(theme_primary, theme_secondary):
    styles = getSampleStyleSheet()

    PRIMARY = colors.HexColor(theme_primary)
    SECONDARY = colors.HexColor(theme_secondary)
    TEXT_DARK = colors.HexColor("#1E293B")
    BG_ALT = colors.HexColor("#F8FAFC")

    s_cover_title = ParagraphStyle(
        'CoverTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=22,
        leading=26,
        textColor=PRIMARY,
        alignment=1,
        spaceAfter=8
    )

    s_cover_sub = ParagraphStyle(
        'CoverSub',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=12,
        leading=16,
        textColor=SECONDARY,
        alignment=1,
        spaceAfter=15
    )

    s_h1 = ParagraphStyle(
        'H1',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=13,
        leading=17,
        textColor=PRIMARY,
        spaceBefore=14,
        spaceAfter=6,
        keepWithNext=True
    )

    s_h2 = ParagraphStyle(
        'H2',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=10.5,
        leading=14,
        textColor=SECONDARY,
        spaceBefore=10,
        spaceAfter=4,
        keepWithNext=True
    )

    s_body = ParagraphStyle(
        'Body',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.5,
        leading=12.5,
        textColor=TEXT_DARK,
        spaceAfter=6
    )

    s_bullet = ParagraphStyle(
        'Bullet',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.5,
        leading=12.5,
        textColor=TEXT_DARK,
        leftIndent=15,
        spaceAfter=4
    )

    s_table_cell = ParagraphStyle(
        'TableCell',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8,
        leading=11,
        textColor=TEXT_DARK
    )

    s_table_header = ParagraphStyle(
        'TableHeader',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=11,
        textColor=colors.white
    )

    s_callout = ParagraphStyle(
        'Callout',
        parent=styles['Normal'],
        fontName='Helvetica-Oblique',
        fontSize=8,
        leading=11.5,
        textColor=PRIMARY
    )

    return {
        'PRIMARY': PRIMARY,
        'SECONDARY': SECONDARY,
        'BG_ALT': BG_ALT,
        'cover_title': s_cover_title,
        'cover_sub': s_cover_sub,
        'h1': s_h1,
        'h2': s_h2,
        'body': s_body,
        'bullet': s_bullet,
        'cell': s_table_cell,
        'header': s_table_header,
        'callout': s_callout
    }

def make_callout_box(text, border_color, bg_color, style):
    t = Table([[Paragraph(text, style)]], colWidths=[515])
    t.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor(bg_color)),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor(border_color)),
        ('TOPPADDING', (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
        ('LEFTPADDING', (0,0), (-1,-1), 10),
        ('RIGHTPADDING', (0,0), (-1,-1), 10),
    ]))
    return t

def create_re_header(st):
    logo_path = r"c:\laragon\www\desai\public\manuals\re_infotech_logo.jpg"
    contact_p = Paragraph(
        '<div align="right">'
        '<font color="#101C4C" size="9.5"><b>RE INFOTECH &reg;</b></font><br/>'
        '<font color="#4A5568" size="7">'
        '<b>Email:</b> <a href="mailto:updates@re-infotech.com"><font color="#35429E">updates@re-infotech.com</font></a> &nbsp;|&nbsp; '
        '<b>Mobile:</b> +918981365361<br/>'
        '<b>Official Website:</b> <a href="https://re-infotech.com/"><font color="#35429E"><u>https://re-infotech.com/</u></font></a>'
        '</font>'
        '</div>',
        st['body']
    )
    t = Table([[Image(logo_path, width=140, height=32.5), contact_p]], colWidths=[160, 355])
    t.setStyle(TableStyle([
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('LEFTPADDING', (0,0), (-1,-1), 0),
        ('RIGHTPADDING', (0,0), (-1,-1), 0),
        ('TOPPADDING', (0,0), (-1,-1), 0),
        ('BOTTOMPADDING', (0,0), (-1,-1), 2),
    ]))
    return t

# =============================================================================
# 1. ADMIN PANEL PDF BUILDER
# =============================================================================
def build_admin_manual(output_paths):
    st = get_pdf_styles("#101C4C", "#35429E")
    story = []

    # Brand Header
    story.append(create_re_header(st))
    story.append(HRFlowable(width="100%", thickness=1, color=colors.HexColor("#CBD5E1"), spaceBefore=2, spaceAfter=8))

    # Title & Metadata
    story.append(Paragraph("DESAI DELIVERY &amp; DISTRIBUTION MANAGEMENT SYSTEM", st['cover_title']))
    story.append(Paragraph("Admin Control Panel &mdash; Official User Operations Manual (v3.0)", st['cover_sub']))
    story.append(HRFlowable(width="100%", thickness=2, color=st['SECONDARY'], spaceBefore=2, spaceAfter=12))

    meta_data = [
        [Paragraph("<b>Target Workspace:</b>", st['cell']), Paragraph("Admin Portal (/admin/*)", st['cell']),
         Paragraph("<b>Authorized Role:</b>", st['cell']), Paragraph("Role ID 1 (Super Admin)", st['cell'])],
        [Paragraph("<b>Document Version:</b>", st['cell']), Paragraph("Version 3.0 (Enterprise Comprehensive)", st['cell']),
         Paragraph("<b>Effective Date:</b>", st['cell']), Paragraph("November 2026", st['cell'])],
        [Paragraph("<b>Key Modules Covered:</b>", st['cell']), Paragraph("Catalogs, 3-Tier Territories, Fleet, Personnel, Real-Time Attendance &amp; Fleet MIS Reports", st['cell']),
         Paragraph("<b>Classification:</b>", st['cell']), Paragraph("Confidential Internal Document", st['cell'])],
    ]
    t_meta = Table(meta_data, colWidths=[110, 160, 95, 150])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F1F5F9")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 10))

    # Section 1
    story.append(Paragraph("1. System Overview &amp; Administrative Command Scope", st['h1']))
    story.append(Paragraph(
        "The <b>Admin Control Panel</b> (<code>/admin</code>) serves as the central operational headquarters of the Desai Delivery &amp; Distribution Management System (DDMS). It establishes enterprise master parameters, wholesale product prices, tax structures, territory demarcations, personnel credentials, vehicle asset allocations, and financial/audit oversight.",
        st['body']
    ))

    role_table = [
        [Paragraph("Role ID", st['header']), Paragraph("Role Title", st['header']), Paragraph("Portal URL", st['header']), Paragraph("Operational Authority", st['header'])],
        [Paragraph("1", st['cell']), Paragraph("<b>Super Admin</b>", st['cell']), Paragraph("/admin/*", st['cell']), Paragraph("Global master access across catalogs, territories, fleet, users, invoices, and executive reports.", st['cell'])],
        [Paragraph("2", st['cell']), Paragraph("<b>Sales Executive</b>", st['cell']), Paragraph("Mobile / Web", st['cell']), Paragraph("Field market canvassing, order collection, and onboarding retail stores across up to 5 assigned areas.", st['cell'])],
        [Paragraph("3", st['cell']), Paragraph("<b>Delivery Driver</b>", st['cell']), Paragraph("Mobile / Web", st['cell']), Paragraph("Selfie clock-in, beat road deliveries, retail mobile billing, and vehicle fuel/expense reporting.", st['cell'])],
        [Paragraph("4", st['cell']), Paragraph("<b>Warehouse Depot</b>", st['cell']), Paragraph("/warehouse/*", st['cell']), Paragraph("Depot inventory, morning van dispatch with differential carry-forward, settlement, and fleet management.", st['cell'])],
        [Paragraph("5", st['cell']), Paragraph("<b>Manufacturer</b>", st['cell']), Paragraph("/manufacture/*", st['cell']), Paragraph("Plant bottling lines, batch production logging, factory buffer warehouse, and cross-depot supply pipeline.", st['cell'])],
    ]
    t_roles = Table(role_table, colWidths=[40, 95, 80, 300])
    t_roles.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), st['PRIMARY']),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, st['BG_ALT']]),
    ]))
    story.append(t_roles)
    story.append(Spacer(1, 8))

    # Section 2
    story.append(Paragraph("2. Master Product Catalog &amp; Pricing Architecture", st['h1']))
    story.append(Paragraph(
        "Located at <code>/admin/products</code> and <code>/admin/product-create</code>. Governs every SKU sold in the enterprise:<br/>"
        "• <b>Bottles Per Case (BPC):</b> The fundamental packaging constant representing the number of individual bottles packed into each sealed commercial case.<br/>"
        "• <b>Master Wholesale Pricing:</b> Case Price charged to retail stores and Loose Bottle Price charged for partial crate orders.<br/>"
        "• <b>Taxation Parameters:</b> Central GST (CGST %) and State GST (SGST %) percentages applied automatically on tax invoices.<br/>"
        "• <b>HSN Code:</b> Official Harmonized System of Nomenclature code printed on commercial tax invoices.",
        st['body']
    ))
    story.append(make_callout_box(
        "<b>WARNING: PRESERVING HISTORICAL MATH</b><br/>"
        "Never modify the Bottles Per Case (BPC) of an active product with past inventory transactions. If packaging changes (e.g. 24 bottles per case changed to 12), create a new product SKU to prevent mathematical corruption of past sales and return ledgers.",
        "#F59E0B", "#FEF3C7", st['callout']
    ))
    story.append(Spacer(1, 8))

    # Section 3
    story.append(Paragraph("3. 3-Tier Territory Hierarchy &amp; Geographic Distribution", st['h1']))
    story.append(Paragraph(
        "DDMS enforces a strict 3-tier geographic structure to streamline beat deliveries and market visits:<br/>"
        "1. <b>Tier 1 &mdash; States (/admin/states):</b> Regional administrative and tax boundaries (e.g. <i>Goa</i>).<br/>"
        "2. <b>Tier 2 &mdash; Areas (/admin/areas):</b> Commercial clusters or sub-districts (e.g. <i>North Goa</i>, <i>South Goa</i>) mapped to a parent State.<br/>"
        "3. <b>Tier 3 &mdash; Roads / Beat Routes (/admin/roads):</b> Physical delivery streets where customer stores are pinned. Drivers navigate assigned roads sequentially (Stop 1, Stop 2...).",
        st['body']
    ))
    story.append(Spacer(1, 8))

    # Section 4
    story.append(Paragraph("4. Commercial Fleet Directory &amp; Staff Administration", st['h1']))
    story.append(Paragraph(
        "• <b>Vehicle Assets (/admin/vehicles):</b> Master inventory of transport vehicles (e.g. <code>GA09U6390</code>, <code>GA09U6650</code>), commercial models (*ECCO CARGO*), fuel types (*Diesel*, *Petrol*), and live current odometer synchronised from fuel and repair logs.<br/>"
        "• <b>Drivers (/admin/drivers):</b> Mandatory linkage of each driver to an assigned Commercial Vehicle, bound Warehouse Depot, and delivery beat route arrangement.<br/>"
        "• <b>Sales Executives (/admin/sales-executives):</b> Field staff assigned to canvass up to 5 commercial market areas.<br/>"
        "• <b>Warehouse Managers (/admin/warehouses):</b> Regional depot supervisors managing stock and van dispatch.",
        st['body']
    ))
    story.append(Spacer(1, 8))

    # Section 5
    story.append(Paragraph("5. Real-Time Operations &amp; Attendance Audit Suite", st['h1']))
    story.append(Paragraph(
        "• <b>Driver Attendance (/admin/driver-attendance):</b> Time-stamped clock-in with driver selfie capture, live GPS coordinates, clock-out times, and cumulative break logs.<br/>"
        "• <b>Driver Route Logs (/admin/driver-route-logs):</b> Daily delivery drops completed along assigned beat roads.<br/>"
        "• <b>Store Delivery History (/admin/shop-delivery-history):</b> Complete audit trail of invoices signed off by retail storekeepers.<br/>"
        "• <b>Sales Visit History (/admin/sales-visit-history):</b> Log of market canvassing visits and retail store onboarding.",
        st['body']
    ))
    story.append(Spacer(1, 8))

    # Section 6
    story.append(Paragraph("6. Fleet &amp; Expenses Reports Suite (Brand New Feature)", st['h1']))
    story.append(Paragraph(
        "Located at <code>/admin/fleet/*</code>. Dedicated view-only reporting module providing comprehensive oversight of vehicle fleet overheads:",
        st['body']
    ))

    fleet_table = [
        [Paragraph("Report Type", st['header']), Paragraph("KPI Summary Metrics", st['header']), Paragraph("Dynamic Filtering &amp; Audit Capabilities", st['header'])],
        [Paragraph("<b>Fuel Reports</b><br/>/admin/fleet/fuel-reports", st['cell']),
         Paragraph("• Total Liters Refilled<br/>• Total Fuel Cost (₹)<br/>• Month-to-Date Cost<br/>• Average Mileage (KM/L)", st['cell']),
         Paragraph("Multi-level dynamic filter: Selecting a <b>Warehouse</b> auto-filters the <b>Driver</b> list. Selecting a driver auto-selects their <b>Vehicle</b>. Includes modal fuel slip photo preview.", st['cell'])],
        [Paragraph("<b>Daily Expenses</b><br/>/admin/fleet/daily-expenses", st['cell']),
         Paragraph("• Total Daily Expenses<br/>• Approved &amp; Paid Claims<br/>• Pending Claims<br/>• Current Month Cost", st['cell']),
         Paragraph("Categorical breakdown by Toll Tax, Parking, Vehicle Washing, Driver Allowance, Tyre Air/Puncture, and Minor Repair. 1-click modal receipt viewer.", st['cell'])],
        [Paragraph("<b>Repair Reports</b><br/>/admin/fleet/repair-reports", st['cell']),
         Paragraph("• Total Repair Costs<br/>• Spare Parts Expense<br/>• Labor Charges<br/>• Unpaid Garage Credit Bills", st['cell']),
         Paragraph("Tracks workshop name, contact phone, invoice number, parts/labor breakdown, and pending credit liabilities to prevent depot delivery halts.", st['cell'])],
    ]
    t_fleet = Table(fleet_table, colWidths=[120, 175, 220])
    t_fleet.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), st['SECONDARY']),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, st['BG_ALT']]),
    ]))
    story.append(t_fleet)
    story.append(Spacer(1, 8))

    story.append(Paragraph("7. Centralized Invoicing &amp; Financial Accounting (/admin/invoice-list)", st['h1']))
    story.append(Paragraph(
        "Maintains master record of all commercial field invoices. Generates standardized 3-copy commercial tax invoices (Original for Buyer, Duplicate for Transporter, Triplicate for Supplier). Computes itemized CGST, SGST, and net totals.",
        st['body']
    ))

    # Build PDF
    canvas_factory = create_canvas_factory("Desai Delivery & Distribution Management System &mdash; Admin Manual v3.0")
    for path in output_paths:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        doc = SimpleDocTemplate(path, pagesize=A4, leftMargin=40, rightMargin=40, topMargin=48, bottomMargin=48)
        doc.build(story, canvasmaker=canvas_factory)
        print(f"Built Admin Manual: {path}")

# =============================================================================
# 2. MANUFACTURER PANEL PDF BUILDER
# =============================================================================
def build_manufacturer_manual(output_paths):
    st = get_pdf_styles("#064E3B", "#059669")
    story = []

    # Brand Header
    story.append(create_re_header(st))
    story.append(HRFlowable(width="100%", thickness=1, color=colors.HexColor("#CBD5E1"), spaceBefore=2, spaceAfter=8))

    # Title & Metadata
    story.append(Paragraph("DESAI DELIVERY &amp; DISTRIBUTION MANAGEMENT SYSTEM", st['cover_title']))
    story.append(Paragraph("Manufacturer &amp; Plant Operations &mdash; Official User Operations Manual (v3.0)", st['cover_sub']))
    story.append(HRFlowable(width="100%", thickness=2, color=st['SECONDARY'], spaceBefore=2, spaceAfter=12))

    meta_data = [
        [Paragraph("<b>Target Workspace:</b>", st['cell']), Paragraph("Manufacturer Portal (/manufacture/*)", st['cell']),
         Paragraph("<b>Authorized Role:</b>", st['cell']), Paragraph("Role ID 5 (Manufacturer / Plant Head)", st['cell'])],
        [Paragraph("<b>Document Version:</b>", st['cell']), Paragraph("Version 3.0 (Enterprise Comprehensive)", st['cell']),
         Paragraph("<b>Effective Date:</b>", st['cell']), Paragraph("November 2026", st['cell'])],
        [Paragraph("<b>Key Modules Covered:</b>", st['cell']), Paragraph("Dual-Unit Math, Normalization Algorithm, Batch Logging, Difference Audit, Cross-Depot Pipeline", st['cell']),
         Paragraph("<b>Classification:</b>", st['cell']), Paragraph("Confidential Internal Document", st['cell'])],
    ]
    t_meta = Table(meta_data, colWidths=[110, 160, 95, 150])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F0FDF4")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#BBF7D0")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#DCFCE7")),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 10))

    story.append(Paragraph("1. Plant Operations &amp; Central Supply Chain Role", st['h1']))
    story.append(Paragraph(
        "The <b>Manufacturer Portal</b> (<code>/manufacture</code>) serves as the primary production control and central warehouse distribution hub for DDMS. It connects bottling and packaging lines, central buffer holding warehouses, and regional depot distribution centers.",
        st['body']
    ))

    story.append(Paragraph("2. Dual-Unit Production Accounting &amp; Normalization Mathematics", st['h1']))
    story.append(Paragraph(
        "Beverage manufacturing packages finished goods into commercial <b>Cases (Crates)</b>, while sampling, partial packaging, and buffer stock often exist as loose <b>Bottles</b>.<br/>"
        "<b>Fundamental Formula:</b> <code>Total Physical Units = (Cases &times; Bottles Per Case) + Loose Bottles</code>",
        st['body']
    ))

    norm_box_data = [
        [Paragraph(
            "<b>AUTOMATED LOOSE BOTTLE NORMALIZATION ALGORITHM:</b><br/>"
            "Plant operators can directly enter raw bottle counts without manual calculation. The system automatically normalizes loose bottles into full cases whenever they equal or exceed the master BPC multiplier:<br/>"
            "• <code>Additional Full Cases = &lfloor; Loose Bottles / Bottles Per Case &rfloor;</code><br/>"
            "• <code>Remaining Loose Bottles = Loose Bottles % Bottles Per Case</code><br/>"
            "<i>Practical Example:</i> Logging <b>100 Cases + 30 Bottles</b> for a 24-BPC SKU automatically records as <b>101 Cases + 6 Bottles</b>.",
            st['callout']
        )]
    ]
    t_norm_box = Table(norm_box_data, colWidths=[515])
    t_norm_box.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#DCFCE7")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#10B981")),
        ('TOPPADDING', (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
        ('LEFTPADDING', (0,0), (-1,-1), 10),
        ('RIGHTPADDING', (0,0), (-1,-1), 10),
    ]))
    story.append(t_norm_box)
    story.append(Spacer(1, 8))

    story.append(Paragraph("3. Factory Buffer Inventory Management (/manufacture/stock)", st['h1']))
    story.append(Paragraph(
        "Displays real-time factory warehouse inventory with live status badges:<br/>"
        "• <font color='#EF4444'><b>Out of Stock:</b></font> 0 Cases and 0 Bottles.<br/>"
        "• <font color='#F59E0B'><b>Low Stock:</b></font> &le; 50 Cases available. Alerts supervisor to prioritize packaging runs.<br/>"
        "• <font color='#10B981'><b>Normal Stock:</b></font> &gt; 50 Cases available in factory buffer.<br/>"
        "• <b>Total Dispatched:</b> Cumulative cartons dispatched to regional distribution depots.",
        st['body']
    ))
    story.append(Spacer(1, 8))

    story.append(Paragraph("4. Production Output Logging Workflows", st['h1']))
    story.append(Paragraph(
        "DDMS provides two streamlined workflows to record bottling plant production:",
        st['body']
    ))

    prod_table = [
        [Paragraph("Method", st['header']), Paragraph("Workflow Location", st['header']), Paragraph("Operational Use &amp; Procedures", st['header'])],
        [Paragraph("<b>Shift Batch Entry</b>", st['cell']), Paragraph("/manufacture/stock-create", st['cell']),
         Paragraph("Ideal for formal shift completions. Enter Production Date, unique Batch / Lot No (e.g. <code>BATCH-202611-L1</code>), and shift notes. Add multiple SKU rows simultaneously.", st['cell'])],
        [Paragraph("<b>1-Click Quick Add Modal</b>", st['cell']), Paragraph("/manufacture/stock", st['cell']),
         Paragraph("Ideal for single-SKU rapid entry directly from the factory stock table. Click blue <b>'+ Add Stock'</b> button, input cases and bottles in modal, and save via instant AJAX update.", st['cell'])],
    ]
    t_prod = Table(prod_table, colWidths=[110, 130, 275])
    t_prod.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), st['PRIMARY']),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, st['BG_ALT']]),
    ]))
    story.append(t_prod)
    story.append(Spacer(1, 8))

    story.append(Paragraph("5. Physical Stock Adjustments &amp; Tamper-Evident Delta (&Delta;) Audit", st['h1']))
    story.append(Paragraph(
        "Located at <code>/manufacture/stock-edit/{id}</code>. When floor stocktakes reveal variances due to breakage or QC sampling, supervisors input the actual verified count. "
        "The system calculates the exact difference and logs a permanent, tamper-evident audit record:<br/>"
        "<code>[Stock adjusted from 120 Cases + 5 Bottles to 118 Cases (-2 Cases, -5 Bottles). Note: Forklift pallet damage]</code>.<br/>"
        "<i>Security Rule:</i> Movement logs permanently track user ID, timestamp, and variance; records cannot be deleted.",
        st['body']
    ))
    story.append(Spacer(1, 8))

    story.append(Paragraph("6. Centralized Cross-Warehouse Pipeline Monitoring (/manufacture/warehouse-stock)", st['h1']))
    story.append(Paragraph(
        "Provides real-time visibility into inventory distributed across regional depots (AB Roy, South Goa, Fatorpa, Agacaim). "
        "Allows plant leadership to monitor in-transit freight shipments, verify depot goods receipts, and proactively plan bottling line batches matching high-demand regional depletion.",
        st['body']
    ))

    # Build PDF
    canvas_factory = create_canvas_factory("Desai Delivery & Distribution Management System &mdash; Manufacturer Manual v3.0")
    for path in output_paths:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        doc = SimpleDocTemplate(path, pagesize=A4, leftMargin=40, rightMargin=40, topMargin=48, bottomMargin=48)
        doc.build(story, canvasmaker=canvas_factory)
        print(f"Built Manufacturer Manual: {path}")

# =============================================================================
# 3. WAREHOUSE PANEL PDF BUILDER
# =============================================================================
def build_warehouse_manual(output_paths):
    st = get_pdf_styles("#101C4C", "#D97706")
    story = []

    # Brand Header
    story.append(create_re_header(st))
    story.append(HRFlowable(width="100%", thickness=1, color=colors.HexColor("#CBD5E1"), spaceBefore=2, spaceAfter=8))

    # Title & Metadata
    story.append(Paragraph("DESAI DELIVERY &amp; DISTRIBUTION MANAGEMENT SYSTEM", st['cover_title']))
    story.append(Paragraph("Warehouse &amp; Depot Operations &mdash; Official User Operations Manual (v3.0)", st['cover_sub']))
    story.append(HRFlowable(width="100%", thickness=2, color=st['SECONDARY'], spaceBefore=2, spaceAfter=12))

    meta_data = [
        [Paragraph("<b>Target Workspace:</b>", st['cell']), Paragraph("Warehouse Portal (/warehouse/*)", st['cell']),
         Paragraph("<b>Authorized Role:</b>", st['cell']), Paragraph("Role ID 4 (Warehouse / Depot Manager)", st['cell'])],
        [Paragraph("<b>Document Version:</b>", st['cell']), Paragraph("Version 3.0 (Enterprise Comprehensive)", st['cell']),
         Paragraph("<b>Effective Date:</b>", st['cell']), Paragraph("November 2026", st['cell'])],
        [Paragraph("<b>Key Modules Covered:</b>", st['cell']), Paragraph("Dual-Unit Stock, Differential Carry-Forward Dispatch, Evening Reconciliation, Return Modal &amp; Fleet Suite", st['cell']),
         Paragraph("<b>Classification:</b>", st['cell']), Paragraph("Confidential Internal Document", st['cell'])],
    ]
    t_meta = Table(meta_data, colWidths=[110, 160, 95, 150])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#FFFBEB")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#FDE68A")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#FEF3C7")),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 10))

    story.append(Paragraph("1. Regional Warehouse &amp; Depot Operations Overview", st['h1']))
    story.append(Paragraph(
        "The <b>Warehouse Panel</b> (<code>/warehouse</code>) is the logistics operations center for regional depots. It governs inward factory freight consignments, morning delivery van manifests, carry-forward stock handovers, evening reconciliation, retail invoicing, and vehicle fleet management.",
        st['body']
    ))

    story.append(Paragraph("2. Dual-Unit Depot Inventory Math (Cases &amp; Loose Bottles)", st['h1']))
    story.append(Paragraph(
        "All depot inventory is tracked in dual dimensions: <b>Sealed Cases</b> and <b>Loose Bottles</b>. "
        "Total Physical Units = (Cases &times; Bottles Per Case) + Loose Bottles. Loose bottles automatically normalize into full cases when reaching the BPC multiplier.",
        st['body']
    ))

    story.append(Paragraph("3. Morning Dispatch &amp; Differential Carry-Forward Stock Logic", st['h1']))
    story.append(Paragraph(
        "Located at <code>/warehouse/driver-product-create</code>. When a driver is selected, previous unsold carry-forward stock is auto-loaded row-wise with identifying badges.",
        st['body']
    ))

    cf_box_data = [
        [Paragraph(
            "<b>THE GOLDEN CARRY-FORWARD DIFFERENTIAL DEDUCTION RULE:</b><br/>"
            "• <b>Zero Warehouse Deduction for Carried-Forward Goods:</b> Unsold carry-forward cases remain on the driver's vehicle and are carried forward directly to the driver's next day assignment <b>without deducting from warehouse stock</b> (they were already deducted on Day 1)!<br/>"
            "• <b>Adjusted Strictly by Quantity Difference:</b><br/>"
            "&nbsp;&nbsp;&nbsp;&nbsp;- If Driver had <b>4 carry-forward cases</b> and supervisor assigns <b>4 cases</b>: <b>0 cases</b> deducted from warehouse.<br/>"
            "&nbsp;&nbsp;&nbsp;&nbsp;- If Driver had <b>4 carry-forward cases</b> and supervisor increases to <b>5 cases</b>: <b>Only 1 additional case</b> is deducted from warehouse.<br/>"
            "&nbsp;&nbsp;&nbsp;&nbsp;- If Driver had <b>4 carry-forward cases</b> and supervisor reduces to <b>3 cases</b>: <b>1 case</b> is credited back to warehouse inventory.<br/>"
            "• <b>Stock-Filtered Selection:</b> Dropdown only displays SKUs with active stock (&gt;0); attempting to over-assign triggers immediate red alerts.",
            st['callout']
        )]
    ]
    t_cf_box = Table(cf_box_data, colWidths=[515])
    t_cf_box.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#FEF3C7")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#F59E0B")),
        ('TOPPADDING', (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
        ('LEFTPADDING', (0,0), (-1,-1), 10),
        ('RIGHTPADDING', (0,0), (-1,-1), 10),
    ]))
    story.append(t_cf_box)
    story.append(Spacer(1, 8))

    story.append(Paragraph("4. Evening Sales Reconciliation &amp; Physical Return Modal", st['h1']))
    story.append(Paragraph(
        "Located at <code>/warehouse/daily-sales-report</code>. At shift end, supervisors review sales KPIs (Overall Sales, Total Assigned, Carry Forward, Returned, Total Sold) and click the amber <b>Return</b> button to open the settlement modal:",
        st['body']
    ))

    ret_table = [
        [Paragraph("Return Type", st['header']), Paragraph("Physical Condition", st['header']), Paragraph("Accounting &amp; Next Day Treatment", st['header'])],
        [Paragraph("<b>Carry Forward</b><br/>(Resold Next Day)", st['cell']), Paragraph("Fresh, sealed, undamaged commercial cartons.", st['cell']),
         Paragraph("Remains inside driver's vehicle. Automatically pre-filled into driver's next morning dispatch manifest without touching warehouse balance.", st['cell'])],
        [Paragraph("<b>Physical Return</b><br/>(Damaged / Scrap)", st['cell']), Paragraph("Broken bottles, leaking caps, or crushed packaging.", st['cell']),
         Paragraph("Unloaded from vehicle and quarantined for manufacturer credit/scrap. Does not re-enter saleable warehouse inventory.", st['cell'])],
    ]
    t_ret = Table(ret_table, colWidths=[120, 140, 255])
    t_ret.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), st['PRIMARY']),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, st['BG_ALT']]),
    ]))
    story.append(t_ret)
    story.append(Spacer(1, 8))

    story.append(Paragraph("5. Vehicle Fleet Management Suite (Brand New Feature)", st['h1']))
    story.append(Paragraph(
        "Located under the <b>Vehicle Fleet Management</b> navigation tabs on the warehouse sidebar:",
        st['body']
    ))

    story.append(make_callout_box(
        "<b>DRIVER-TO-VEHICLE AUTO-LOCKING MECHANISM</b><br/>"
        "To eliminate human errors, the <b>Vehicle dropdown is permanently locked</b> (<code>pointer-events: none</code>) across all Add and Edit forms. Selecting an assigned Driver automatically binds that driver's vehicle, pre-fills fuel type, displays current odometer reading, and passes the hidden vehicle ID securely.",
        "#10B981", "#ECFDF5", st['callout']
    ))
    story.append(Spacer(1, 4))

    story.append(Paragraph(
        "• <b>Fuel Tracking Logs (/warehouse/vehicle/fuel-logs):</b> Refill date, volume in Liters, rate, total amount, odometer reading, mileage calculation (KM/L), and pump slip image attachment. Approval status toggle (Approved, Pending, Rejected).<br/>"
        "• <b>Daily Expenses (/warehouse/vehicle/daily-expenses):</b> Log driver expense claims for Toll Tax, Parking, Vehicle Washing, Driver Food Allowance, Tyre Air/Puncture, and Road Repairs with receipt photo uploads.<br/>"
        "• <b>Vehicle Repair Logs (/warehouse/vehicle/repair-logs):</b> Log periodic servicing and repairs with workshop particulars, invoice number, parts/labor/tax cost itemization, bill copy photo, and payment status (Paid, Unpaid, Partially Paid).",
        st['body']
    ))
    story.append(Spacer(1, 8))

    story.append(Paragraph("6. Depot Manager Chronological Operating Schedule (SOP)", st['h1']))
    story.append(Paragraph(
        "1. <b>07:30 - 08:30 (Morning Dispatch):</b> Verify driver attendance selfies; open <code>/warehouse/driver-product-create</code>, select driver, review pre-filled carry-forward rows, adjust new stock additions, and dispatch delivery van.<br/>"
        "2. <b>12:00 - 15:00 (Midday Operations):</b> Record any en-route fuel refills, toll receipts, or minor vehicle repair claims submitted by drivers.<br/>"
        "3. <b>18:00 - 19:30 (Evening Reconciliation):</b> Open <code>/warehouse/daily-sales-report</code>, open Return Modal for each returning van, record undamaged stock as Carry Forward and broken bottles as Return, and verify collected cash/UPI revenue against invoiced retail sales.",
        st['body']
    ))

    # Build PDF
    canvas_factory = create_canvas_factory("Desai Delivery & Distribution Management System &mdash; Warehouse Manual v3.0")
    for path in output_paths:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        doc = SimpleDocTemplate(path, pagesize=A4, leftMargin=40, rightMargin=40, topMargin=48, bottomMargin=48)
        doc.build(story, canvasmaker=canvas_factory)
        print(f"Built Warehouse Manual: {path}")

if __name__ == '__main__':
    # 1. Admin Manual
    build_admin_manual([
        r"c:\laragon\www\desai\public\manuals\Admin_Panel_User_Manual.pdf",
        r"C:\Users\user\.gemini\antigravity\brain\b4d8eca2-e457-42bc-9477-a5d18f6bdfd7\Admin_Panel_User_Manual.pdf"
    ])

    # 2. Manufacturer Manual
    build_manufacturer_manual([
        r"c:\laragon\www\desai\public\manuals\Manufacturer_Panel_User_Manual.pdf",
        r"C:\Users\user\.gemini\antigravity\brain\b4d8eca2-e457-42bc-9477-a5d18f6bdfd7\Manufacturer_Panel_User_Manual.pdf"
    ])

    # 3. Warehouse Manual
    build_warehouse_manual([
        r"c:\laragon\www\desai\public\manuals\Warehouse_Panel_User_Manual.pdf",
        r"C:\Users\user\.gemini\antigravity\brain\b4d8eca2-e457-42bc-9477-a5d18f6bdfd7\Warehouse_Panel_User_Manual.pdf"
    ])
