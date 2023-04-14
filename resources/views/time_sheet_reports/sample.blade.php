<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <script src="{{ asset('js/invoice.js') }}" defer></script>

    <!-- Fonts -->

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- Styles -->
    <link href="{{ asset('css/report.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body>
<div id="invoice">
    <div class="toolbar hidden-print">
        <div class="text-right">
            <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
            <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
        </div>
        <hr>
    </div>
    <div class="invoice overflow-auto pt-0">
        <div style="min-width: 600px">
            <header>
                <div class="row pb-2">
                    <div class="col" >
                        <a target="_blank" href="https://lobianijs.com">
                            <img  src="/images/tmarc_logo.png" data-holder-rendered="true" style="max-height: 150px; width: auto;"/>
                        </a>
                        <div>Dealers in: Barcodes & Other GS1 Services</div>
                    </div>
                    <div class="col company-details">
                        <h2 class="name" style="display: none;">
                            <a target="_blank" href="http://www.gs1tz.org/">
                                GS1 Tanzania
                            </a>
                        </h2>
                        <div>TIRDO Head Office Compound</div>
                        <div>P.O.Box 23235</div>
                        <div>Dar es Salaam, Tanzania</div>
                        <div>Msasani - Kimweri Avenue</div>
                        <div>Tel: 255 22 2664310/2664311</div>
                        <div>Email: info@gs1tz.org</div>
                        <div>Website: www.gs1tz.org</div>
                        <div class="text-primary"><strong>TIN: 112-898-468</strong></div>
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light">INVOICE TO:</div>
                        <h2 class="to text-primary">TMARC Tanzania</h2>
                        <div class="address">Kunduchi, Kuringa Drive</div>
                        <div class="email">info@tmarc.com</div>
                    </div>
                    <div class="col invoice-details">
                        <h1 class="invoice-id text-primary">INVOICE</h1>
                        <div class="text-danger"><strong>NO: 112-898-468</strong></div>
                        <div class="date">Date of Invoice: 01/10/2018</div>
                        <div class="date">Due Date: 30/10/2018</div>
                    </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-left">PARTICULARS</th>
                        <th class="text-right">QTY</th>
                        <th class="text-right">UNIT PRICE</th>
                        <th class="text-right">TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="no">01</td>
                        <td class="text-left">
                            <h3>Registration Fee</h3>
                        </td>
                        <td class="unit">1 Time</td>
                        <td class="qty">1000Tzs</td>
                        <td class="total">10000Tzs</td>
                    </tr>
                    <tr>
                        <td class="no">02</td>
                        <td class="text-left">
                            <h3>Annual Subscription Fee</h3>
                        </td>
                        <td class="unit">1 Year</td>
                        <td class="qty">100000Tzs</td>
                        <td class="total">100000Tzs</td>
                    </tr>
                    <tr>
                        <td class="no">03</td>
                        <td class="text-left">
                            <h3>Basic Barcode Generation Fee</h3>
                        </td>
                        <td class="unit">2</td>
                        <td class="qty">Lumpsum</td>
                        <td class="total">1000000Tzs</td>
                    </tr>
                    <tr>
                        <td class="no">04</td>
                        <td class="text-left">
                            <h3>Extra Barcode Generation Fee</h3>
                        </td>
                        <td class="unit">2000</td>
                        <td class="qty">100000</td>
                        <td class="total">100000</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">SUBTOTAL</td>
                        <td>10000000Tzs</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">VAT (18%)</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">GRAND TOTAL</td>
                        <td>10000000Tzs</td>
                    </tr>
                    </tfoot>
                </table>
                <div class="thanks">Thank you!</div>

                <div class="row">
                    <div class="col bank-info">
                        <div class="text-primary font-weight-bold">OUR BANK ACCOUNT DETAILS</div>
                        <div>Bank : <span class="font-weight-bold">CRDB Bank, Viajana Branch</span></div>
                        <div>Acc Name : <span class="font-weight-bold">GS1 (TZ) National Limited</span></div>
                        <div>Acc No : <span class="font-weight-bold">0150408168700</span></div>
                        <div>Swift Code : <span class="font-weight-bold">CORUTZTZ</span></div>
                    </div>
                    <div class="col notices">
                        <div class="notices-inner">
                            <div><strong>NOTICE</strong></div>
                            <div class="notice">All Charges are VAT Exclusive.</div>
                        </div>
                    </div>
                </div>
            </main>
            <footer>
                Invoice was created on a computer and is valid without the signature and seal.
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>
</body>
