<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tax Invoice</title>
</head>
<body style="font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif; font-size: 8.5pt; line-height: 1.25; color: #000; margin: 15px; padding: 0;">

  <!-- OUTSIDE MAIN CONTAINER TABLE -->
  <table style="width: 100%; border-collapse: collapse; border: 1.5px solid #000;">
    
    <!-- HEADER BAR: TITLE & COPY TYPE -->
    <tr>
      <td colspan="2" style="border-bottom: 1px solid #000; padding: 4px 8px;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
          <tr>
            <td style="width: 30%; border: none;"></td>
            <td style="width: 40%; text-align: center; font-weight: bold; font-size: 10.5pt; border: none;">
              Tax Invoice
            </td>
            <td style="width: 30%; text-align: right; font-size: 7.5pt; font-style: italic; font-weight: bold; border: none;">
              (TRIPLICATE FOR SUPPLIER)
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <!-- SELLER & INVOICE META ROW -->
    <tr>
      <!-- LEFT: COMPANY & BUYER INFO -->
      <td style="width: 50%; vertical-align: top; border-right: 1px solid #000; padding: 0;">
        
        <!-- Company Info Header -->
        <table style="width: 100%; border-collapse: collapse; border: none;">
          <tr>
            <td style="width: 20%; padding: 6px 4px 6px 6px; vertical-align: top; text-align: center; border: none;">
              <!-- Logo placeholder / text -->
              <div style="font-size: 8pt; font-weight: bold; border: 1px solid #888; padding: 4px 2px; border-radius: 3px;">
                <img src="{{ public_path('backend/assets/images/authentication/desai-delivery.png') }}" alt="desai-logo" style="width: 100%; height: auto;" alt="desai-logo">
              </div>
            </td>
            <td style="width: 80%; padding: 6px 6px 6px 2px; vertical-align: top; border: none; font-size: 8pt;">
              <strong style="font-size: 9pt;">Desai Beverages Pvt. Ltd.</strong><br>
              Cotta-Fatorpa-Via Cuncolim-Goa<br>
              Mobile No.+ 91 - 9822189999<br>
              Pan No. <strong>AAECD7391N</strong><br>
              GSTIN/UIN: <strong>30AAECD7391N1ZD</strong><br>
              State Name : Goa, Code : 30<br>
              CIN: U15500GA2008PTC005858
            </td>
          </tr>
        </table>

        <!-- Consignee (Ship to) -->
        <div style="border-top: 1px solid #000; padding: 4px 6px; font-size: 8pt;">
          <span style="font-size: 7.5pt; font-weight: bold;">Consignee (Ship to)</span><br>
          <strong style="font-size: 8.5pt;">{{ $shop->shop_name }}</strong><br>
          Cuncolim, Goa.<br>
          <table style="width: 100%; border-collapse: collapse; margin-top: 2px; font-size: 8pt;">
            <tr>
              <td style="width: 25%; padding: 0;">GSTIN/UIN</td>
              <td style="width: 75%; padding: 0;">: <strong>{{$shop->gst_no ?? 'N/A'}}</strong></td>
            </tr>
            <tr>
              <td style="padding: 0;">PAN/IT No</td>
              <td style="padding: 0;">: {{$shop->pan_no ?? 'N/A'}}</td>
            </tr>
            <tr>
              <td style="padding: 0;">State Name</td>
              <td style="padding: 0;">: {{$shop->state ?? 'N/A'}}</td>
            </tr>
          </table>
        </div>


      </td>
      
      <!-- RIGHT: DISPATCH, INVOICE DETAILS & BUYER (BILL TO) -->
      <td style="width: 50%; vertical-align: top; padding: 0;">
        <table style="width: 100%; border-collapse: collapse; border: none; font-size: 8pt;">
          <tr>
            <td style="width: 50%; border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 3px 5px; vertical-align: top; height: 32px;">
              <span style="font-size: 7pt;">Invoice No.</span><br>
              <strong>{{ $invoice->id }}</strong>
            </td>
            <td style="width: 50%; border-bottom: 1px solid #000; padding: 3px 5px; vertical-align: top;">
              <span style="font-size: 7pt;">Dated</span><br>
              <strong>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d-M-y') }}</strong>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="border-bottom: 1px solid #000; padding: 3px 5px; vertical-align: top; height: 38px;">
              <span style="font-size: 7pt;">Reference No. & Date</span><br>
              <strong>{{ $invoice->id }} dt. {{ \Carbon\Carbon::parse($invoice->created_at)->format('d-M-y') }}</strong>
            </td>
          </tr>
        </table>

        <!-- Buyer (Bill to) moved here to fill the right side -->
        <div style="padding: 4px 6px; font-size: 8pt;">
          <span style="font-size: 7.5pt; font-weight: bold;">Buyer (Bill to)</span><br>
          <strong style="font-size: 8.5pt;">{{ $shop->shop_name }}</strong><br>
          {{ $shop->address }}<br>
          <table style="width: 100%; border-collapse: collapse; margin-top: 2px; font-size: 8pt;">
            <tr>
              <td style="width: 25%; padding: 0;">GSTIN/UIN</td>
              <td style="width: 75%; padding: 0;">: <strong>{{$shop->gst_no ?? 'N/A'}}</strong></td>
            </tr>
            <tr>
              <td style="padding: 0;">PAN/IT No</td>
              <td style="padding: 0;">: {{$shop->state ?? 'N/A'}}</td>
            </tr>
            <tr>
              <td style="padding: 0;">State Name</td>
              <td style="padding: 0;">: {{$shop->state ?? 'N/A'}}</td>
            </tr>
            <tr>
              <td style="padding: 0;">Place of Supply</td>
              <td style="padding: 0;">: Goa</td>
            </tr>
          </table>
        </div>

      </td>
      
    </tr>

    <!-- ITEM & CHARGES TABLE -->
    <!-- =========================================================> PRODUCTS <========================================================= -->
<!-- ========================================================= 
     PRODUCTS 
========================================================= --> 
    <tr> 
        <td colspan="2" style="padding: 0; border-top: 1px solid #000;"> 
    
            <table style="
                width: 100%; 
                border-collapse: collapse; 
                border: none; 
                font-size: 7.5pt;
            "> 
    
                <thead> 
                    <tr style="
                        text-align: center; 
                        font-weight: bold; 
                        font-size: 7pt;
                    "> 
    
                        <!-- SI -->
                        <th style="
                            width: 4%;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 4px 2px;
                        ">
                            SI<br>No.
                        </th>
    
                        <!-- PRODUCT -->
                        <th style="
                            width: 23%;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 4px;
                        ">
                            Description of Goods
                        </th>
    
                        <!-- HSN -->
                        <th style="
                            width: 9%;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 4px 2px;
                        ">
                            HSN/SAC
                        </th>
    
                        
    
                        <!-- RATE -->
                        <th style="
                            width: 9%;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 4px 2px;
                        ">
                            Rate
                        </th>
                        
                        <!-- QTY -->
                        <th style="
                            width: 9%;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 4px 2px;
                        ">
                            QTY<br>(Bottle)
                        </th>
    
                        <!-- CGST % -->
                        <th style="
                            width: 8%;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 4px 2px;
                        ">
                            CGST
                            <br>(%)
                        </th>
    
                        <!-- CGST AMOUNT -->
                        <th style="
                            width: 10%;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 4px 2px;
                        ">
                            CGST
                            <br>Amount
                        </th>
    
                        <!-- SGST % -->
                        <th style="
                            width: 8%;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 4px 2px;
                        ">
                            SGST
                            <br>(%)
                        </th>
    
                        <!-- SGST AMOUNT -->
                        <th style="
                            width: 10%;
                            border-right: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 4px 2px;
                        ">
                            SGST
                            <br>Amount
                        </th>
    
                        <!-- TOTAL -->
                        <th style="
                            width: 10%;
                            border-bottom: 1px solid #000;
                            padding: 4px 2px;
                        ">
                            Total
                            <br>Amount
                        </th>
    
                    </tr>
                </thead>
    
                <tbody>
    
                @forelse($products as $key => $product)
    
                    @php
    
                        $cgstRate = $product['cgst_rate'] ?? 0;
                        $sgstRate = $product['sgst_rate'] ?? 0;
    
                        $cgstAmount = $product['cgst_amount'] ?? 0;
                        $sgstAmount = $product['sgst_amount'] ?? 0;
    
                        $taxAmount = $cgstAmount + $sgstAmount;
    
                        $totalAmount = ($product['amount'] ?? 0) + $taxAmount;
    
                    @endphp
    
                    <tr>
    
                        <!-- SI NO -->
                        <td style="
                            text-align: center;
                            vertical-align: top;
                            border-right: 1px solid #000;
                            padding: 3px 2px;
                        ">
                            {{ $key + 1 }}
                        </td>
    
                        <!-- DESCRIPTION -->
                        <td style="
                            vertical-align: top;
                            border-right: 1px solid #000;
                            padding: 3px 5px; text-align: center;
                        ">
                            <strong>
                                {{ $product['product_name'] }}
                            </strong>
                        </td>
    
                        <!-- HSN -->
                        <td style="
                            text-align: center;
                            vertical-align: top;
                            border-right: 1px solid #000;
                            padding: 3px 2px; text-align: center;
                        ">
                            {{ $product['hsn_code'] ?? '-' }}
                        </td>
    
                        
    
                        <!-- RATE -->
                        <td style="
                            text-align: right;
                            vertical-align: top;
                            border-right: 1px solid #000;
                            padding: 3px 4px; text-align: center;
                        ">
                            {{ number_format($product['rate'], 2) }}
                        </td>
                        
                        <!-- QUANTITY -->
                        <td style="
                            text-align: right;
                            vertical-align: top;
                            border-right: 1px solid #000;
                            padding: 3px 4px; text-align: center;
                        ">
                            <strong>
                                {{ rtrim(rtrim(number_format($product['quantity'], 2), '0'), '.') }}
                            </strong>
                            
                        </td>
    
                        <!-- CGST % -->
                        <td style="
                            text-align: right;
                            vertical-align: top;
                            border-right: 1px solid #000;
                            padding: 3px 4px; text-align: center;
                        ">
                            {{ number_format($cgstRate, 2) }}%
                        </td>
    
                        <!-- CGST AMOUNT -->
                        <td style="
                            text-align: right;
                            vertical-align: top;
                            border-right: 1px solid #000;
                            padding: 3px 4px; text-align: center;
                        ">
                            {{ number_format($cgstAmount, 2) }}
                        </td>
    
                        <!-- SGST % -->
                        <td style="
                            text-align: right;
                            vertical-align: top;
                            border-right: 1px solid #000;
                            padding: 3px 4px; text-align: center;
                        ">
                            {{ number_format($sgstRate, 2) }}%
                        </td>
    
                        <!-- SGST AMOUNT -->
                        <td style="
                            text-align: right;
                            vertical-align: top;
                            border-right: 1px solid #000;
                            padding: 3px 4px; text-align: center;
                        ">
                            {{ number_format($sgstAmount, 2) }}
                        </td>
    
                        <!-- TOTAL AMOUNT -->
                        <td style="
                            text-align: right;
                            vertical-align: top;
                            padding: 3px 4px; text-align: center;
                        ">
                            <strong>
                                {{ number_format($totalAmount, 2) }}
                            </strong>
                        </td>
    
                    </tr>
    
                @empty
    
                    <tr>
                        <td colspan="10" style="
                            text-align: center;
                            padding: 10px;
                        ">
                            No products found
                        </td>
                    </tr>
    
                @endforelse
    
    
                <!-- =================================================
                     SPACER
                ================================================== -->
                <tr>
    
                    <td style="height: 150px; border-right: 1px solid #000;"></td>
                    <td style="border-right: 1px solid #000;"></td>
                    <td style="border-right: 1px solid #000;"></td>
                    <td style="border-right: 1px solid #000;"></td>
                    <td style="border-right: 1px solid #000;"></td>
                    <td style="border-right: 1px solid #000;"></td>
                    <td style="border-right: 1px solid #000;"></td>
                    <td style="border-right: 1px solid #000;"></td>
                    <td style="border-right: 1px solid #000;"></td>
                    <td></td>
    
                </tr>
    
    
                <!-- =================================================
                     TOTAL
                ================================================== -->
                <tr style="
                    font-weight: bold;
                    border-top: 1px solid #000;
                ">
    
                    <!-- TOTAL LABEL -->
                    <td colspan="3" style="
                        text-align: right;
                        border-right: 1px solid #000;
                        padding: 4px 6px;  text-align: center;
                    ">
                        Total
                    </td>
    
                    
                    
    
                    <!-- RATE -->
                    <td style="
                        border-right: 1px solid #000;
                    ">
                    </td>
                    
                    <!-- TOTAL QUANTITY -->
                    <td style="
                        text-align: right;
                        border-right: 1px solid #000;
                        padding: 4px;  text-align: center;
                    ">
                        {{ rtrim(rtrim(number_format($total_quantity, 2), '0'), '.') }}
                        
                    </td>
    
                    <!-- CGST % -->
                    <td style="
                        border-right: 1px solid #000;
                    ">
                    </td>
    
                    <!-- CGST AMOUNT -->
                    <td style="
                        text-align: right;
                        border-right: 1px solid #000;
                        padding: 4px;  text-align: center;
                    ">
                        {{ number_format($total_cgst, 2) }}
                    </td>
    
                    <!-- SGST % -->
                    <td style="
                        border-right: 1px solid #000;
                    ">
                    </td>
    
                    <!-- SGST AMOUNT -->
                    <td style="
                        text-align: right;
                        border-right: 1px solid #000;
                        padding: 4px;  text-align: center;
                    ">
                        {{ number_format($total_sgst, 2) }}
                    </td>
    
                    <!-- TOTAL AMOUNT -->
                    <td style="
                        text-align: right;
                        padding: 4px;
                        font-size: 9pt;  text-align: center;
                    ">
                        <strong>
                            ₹ {{ number_format($grand_total, 2) }}
                        </strong>
                    </td>
    
                </tr>
    
                </tbody>
    
            </table>
    
        </td>
    </tr>


    <!-- AMOUNT IN WORDS & E.& O.E. -->
    <tr>
      <td colspan="2" style="border-top: 1px solid #000; padding: 4px 6px;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
          <tr>
            <td style="vertical-align: top; border: none; font-size: 7.5pt;">
              Amount Chargeable (in words)<br>
              <strong style="font-size: 8.5pt;">Indian Rupees {{ $amount_in_words }} Only</strong>
            </td>
            <td style="text-align: right; vertical-align: top; border: none; font-style: italic; font-size: 7.5pt;">
              E. &amp; O.E
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <!-- TAX SUMMARY SUB-TABLE -->
    <!--<tr>-->
    <!--  <td colspan="2" style="padding: 0; border-top: 1px solid #000;">-->
    <!--    <table style="width: 100%; border-collapse: collapse; border: none; font-size: 7.5pt;">-->
    <!--      <thead>-->
    <!--        <tr>-->
    <!--          <th style="width: 80%; text-align: center; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 3px;">HSN/SAC</th>-->
    <!--          <th style="width: 20%; text-align: right; border-bottom: 1px solid #000; padding: 3px 5px;">Taxable<br>Value</th>-->
    <!--        </tr>-->
    <!--      </thead>-->
    <!--      <tbody>-->
    <!--        <tr>-->
    <!--          <td style="border-right: 1px solid #000; padding: 2px 6px;">22029020</td>-->
    <!--          <td style="text-align: right; padding: 2px 5px;">685.72</td>-->
    <!--        </tr>-->
    <!--        <tr style="font-weight: bold; border-top: 1px solid #aaa;">-->
    <!--          <td style="text-align: right; border-right: 1px solid #000; padding: 3px 6px;">Total</td>-->
    <!--          <td style="text-align: right; padding: 3px 5px;">685.72</td>-->
    <!--        </tr>-->
    <!--      </tbody>-->
    <!--    </table>-->
    <!--  </td>-->
    <!--</tr>-->

    <!-- TAX AMOUNT IN WORDS -->
    <!--<tr>-->
    <!--  <td colspan="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 4px 6px; font-size: 8pt;">-->
    <!--    Tax Amount (in words) : <strong>NIL</strong>-->
    <!--  </td>-->
    <!--</tr>-->

    <!-- FOOTER: DECLARATION, BANK DETAILS & SIGNATURE -->
    <tr>
      <td colspan="2" style="padding: 0; border-top: 1px solid #000; padding: 4px 6px;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
          <tr>
            <!-- Left Terms & Declaration -->
            <td style="width: 50%; vertical-align: top; padding: 4px 6px; border-right: 1px solid #000; font-size: 7pt; line-height: 1.2;">
              Company's PAN : <strong>AAECD7391N</strong><br><br>
              <strong>Declaration</strong><br>
              We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.<br>
              <div style="text-align: center; font-weight: bold; margin: 3px 0;">Terms And Conditions</div>
              1. Subject to Goa Jurisdiction Only. 2. Payment Immediate.<br>
              3. Overdue Payment will be charged Interest @18%.<br>
              4. If any Complain, intimation is to be given within seven days from receipt of material.
            </td>

            <!-- Right Bank Details & Signature -->
            <td style="width: 50%; vertical-align: top; padding: 4px 6px; font-size: 7.5pt;">
              <strong>Company's Bank Details</strong><br>
              <table style="width: 100%; border-collapse: collapse; margin-top: 2px; font-size: 7.5pt;">
                <tr>
                  <td style="width: 32%; padding: 0;">Bank Name</td>
                  <td style="width: 68%; padding: 0;">: <strong>Union Bank of India</strong></td>
                </tr>
                <tr>
                  <td style="padding: 0;">A/c No.</td>
                  <td style="padding: 0;">: <strong>510101002963093</strong></td>
                </tr>
                <tr>
                  <td style="padding: 0;">Branch &amp; IFS Code</td>
                  <td style="padding: 0;">: <strong>MURDA-CUNCOLIM GOA &amp; UBIN0902195</strong></td>
                </tr>
              </table>

              <div style="text-align: right; margin-top: 8px;">
                <strong>for Desai Beverages Pvt. Ltd.</strong>
                <div style="height: 38px;"></div> <!-- Signature space -->
                <span>Authorised Signatory</span>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>

  </table>

  <!-- BOTTOM CENTER NOTE -->
  <div style="text-align: center; font-size: 7pt; margin-top: 3px;">
    This is a Computer Generated Invoice
  </div>

</body>
</html>