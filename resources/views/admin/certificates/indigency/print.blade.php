<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Certificate of Indigency</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fff;
            color: #000;
            font-family: "DejaVu Serif", Georgia, "Times New Roman", serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .page {
            width: 100%;
            position: relative;
        }

        /* =========================================
           HEADER
        ========================================= */

        .header {
            position: relative;
            text-align: center;
            min-height: 205px;
        }

        .header-logo {
            position: absolute;
            top: 22px;
            width: 105px;
            height: 105px;
            object-fit: contain;
        }

        .header-logo.left {
            left: 0;
        }

        .header-logo.right {
            right: 0;
        }

        .republic-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
            margin-bottom: 2px;
        }

        .republic-text {
            font-size: 16px;
            font-weight: bold;
            line-height: 1.15;
        }

        .barangay-name {
            margin-top: 38px;
            font-size: 20px;
            line-height: 1.05;
        }

        .barangay-name span {
            display: inline-block;
        }

        .address {
            margin-top: 4px;
            font-size: 12px;
            line-height: 1.2;
        }

        .telephone {
            margin-top: 1px;
            font-size: 12px;
        }

        .office {
            margin-top: 3px;
            font-size: 22px;
            font-weight: bold;
            line-height: 1.1;
        }

        .header-line {
            margin-top: 5px;
            border-bottom: 2px solid #000;
        }

        /* =========================================
           WATERMARK
        ========================================= */

        .watermark {
            position: absolute;
            top: 82px;
            left: 50%;
            transform: translateX(-50%);
            width: 350px;
            opacity: 0.08;
            z-index: 0;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        /* =========================================
           CERTIFICATE
        ========================================= */

        .certificate-title {
            margin-top: 18px;
            text-align: center;
            font-size: 25px;
            font-weight: bold;
        }

        .certificate-subtitle {
            margin-top: 3px;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
        }

        .to-whom {
            margin-top: 24px;
            font-size: 12px;
        }

        .body-text {
            margin-top: 12px;
            text-align: justify;
            font-size: 13px;
            line-height: 1.8;
        }

        .beneficiary {
            margin-top: 10px;
            font-size: 13px;
            line-height: 1.7;
        }

        .purpose {
            margin-top: 12px;
            font-size: 13px;
            line-height: 1.8;
            text-align: justify;
        }

        .issuance {
            margin-top: 13px;
            font-size: 13px;
            line-height: 1.8;
            text-align: justify;
        }

        /* =========================================
           SIGNATURE
        ========================================= */

        .signature-section {
            margin-top: 38px;
            width: 100%;
        }

        .signature {
            width: 42%;
            margin-left: auto;
            text-align: center;
        }

        .signature-name {
            margin-top: 28px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .signature-position {
            margin-top: 2px;
            font-size: 12px;
        }

        /* =========================================
           FOOTER
        ========================================= */

        .footer {
            margin-top: 30px;
            font-size: 9px;
            text-align: center;
            color: #333;
        }

        .footer-line {
            border-top: 1px solid #999;
            margin-bottom: 5px;
        }

        /* =========================================
           PRINT
        ========================================= */

        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

<div class="page">

    {{-- =========================================
         HEADER
    ========================================== --}}

    <div class="header">

        {{-- Watermark --}}
        <img
            src="{{ asset('images/brgy_logo.png') }}"
            class="watermark"
            alt="Barangay Watermark"
        >

        {{-- Left Barangay Logo --}}
        <img
            src="{{ public_path('images/brgy_logo.png') }}"
            class="header-logo left"
            alt="Barangay Logo"
        >

        {{-- Right City Logo --}}
        <img
            src="{{ public_path('images/city_logo.png') }}"
            class="header-logo right"
            alt="City Logo"
        >

        <div class="header-content">

            {{-- Republic Logo --}}
            <img
                src="{{ public_path('images/republic_logo.png') }}"
                class="republic-logo"
                alt="Republic Logo"
            >

            <div class="republic-text">
                REPUBLIKA NG PILIPINAS<br>
                LALAWIGAN NG LAGUNA<br>
                LUNGSOD NG SAN PEDRO
            </div>

            <div class="barangay-name">
                PAMAHALAANG BARANGAY NG SAN LORENZO<br>
                RUIZ
            </div>

            <div class="address">
                QUEENSLAND ST., GREATLAND VILLAGE, CITY OF SAN PEDRO, LAGUNA
            </div>

            <div class="telephone">
                Tel. Nos. __________________________
            </div>

            <div class="office">
                OFFICE OF THE PUNONG BARANGAY
            </div>

            <div class="header-line"></div>

        </div>
    </div>


    {{-- =========================================
         CERTIFICATE TITLE
    ========================================== --}}

    <div class="certificate-title">
        CERTIFICATE OF INDIGENCY
    </div>

    <div class="certificate-subtitle">
        FOR MEDICAL / FINANCIAL ASSISTANCE
    </div>


    {{-- =========================================
         BODY
    ========================================== --}}

    <div class="to-whom">
        TO WHOM IT MAY CONCERN:
    </div>

    <div class="body-text">

        Test
    </div>

    <div class="purpose">

    </div>


    <div class="issuance">

    </div>


    {{-- =========================================
         SIGNATURE
    ========================================== --}}

    <div class="signature-section">

        <div class="signature">

            <div class="signature-name">
                {{ $punongBarangay->full_name ?? '____________________________' }}
            </div>

            <div class="signature-position">
                PUNONG BARANGAY
            </div>

        </div>

    </div>


    {{-- =========================================
         FOOTER
    ========================================== --}}

    <div class="footer">

        <div class="footer-line"></div>

        This certification is issued for whatever legal purpose it may serve.

    </div>

</div>

</body>
</html>