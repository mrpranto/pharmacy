<!DOCTYPE html>
<html lang="en" >

<head>

    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="https://static.codepen.io/assets/favicon/favicon-8ea04875e70c4b0bb41da869e81236e54394d63638a1ef12fa558a4a835f1164.ico" />
    <link rel="mask-icon" type="" href="https://static.codepen.io/assets/favicon/logo-pin-f2d2b6d2c61838f7e76325261b7195c27224080bc099486ddd6dccb469b8e8e6.svg" color="#111" />
    <title>CodePen - POS Receipt Template Html Css</title>

    <style>
        @media print {
            .invoice-POS{
                display: block;
                width: 100%;
            }
            .page-break {
                display: block;
                page-break-before: always;
            }
        }
        #invoice-POS {
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
            padding: 2mm;
            margin: 0 auto;
            width: 44mm;
            background: #FFF;
        }
        #invoice-POS ::selection {
            background: #f31544;
            color: #FFF;
        }
        #invoice-POS ::moz-selection {
            background: #f31544;
            color: #FFF;
        }
        #invoice-POS h1 {
            font-size: 1.5em;
            color: #222;
        }
        #invoice-POS h2 {
            font-size: .9em;
        }
        #invoice-POS h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }
        #invoice-POS p {
            font-size: .7em;
            color: #666;
            line-height: 1.2em;
        }
        #invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
            /* Targets all id with 'col-' */
            border-bottom: 1px solid #EEE;
        }
        #invoice-POS #top {
            min-height: 100px;
        }
        #invoice-POS #mid {
            min-height: 80px;
        }
        #invoice-POS #bot {
            min-height: 50px;
        }
        #invoice-POS #top .logo {
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
            background-size: 60px 60px;
        }
        #invoice-POS .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
            background-size: 60px 60px;
            border-radius: 50px;
        }
        #invoice-POS .info {
            display: block;
            margin-left: 0;
        }
        #invoice-POS .title {
            float: right;
        }
        #invoice-POS .title p {
            text-align: right;
        }
        #invoice-POS table {
            width: 100%;
            border-collapse: collapse;
        }
        #invoice-POS .tabletitle {
            font-size: .5em;
            background: #EEE;
        }
        #invoice-POS .service {
            border-bottom: 1px solid #EEE;
        }
        #invoice-POS .item {
            width: 24mm;
        }
        #invoice-POS .itemtext {
            font-size: .5em;
        }
        #invoice-POS #legalcopy {
            margin-top: 5mm;
        }

    </style>

    <script>
        window.console = window.console || function(t) {};
    </script>



    <script>
        if (document.location.search.match(/type=embed/gi)) {
            window.parent.postMessage("resize", "*");
        }
    </script>


</head>

<body translate="no" >


<div id="invoice-POS">

    <center id="top">
        <div class="logo"></div>
        <div class="info">
            <h2>SBISTechs Inc</h2>
        </div><!--End Info-->
    </center><!--End InvoiceTop-->

    <div id="mid">
        <div class="info">
            <h2>Contact Info</h2>
            <p>
                Address : street city, state 0000</br>
                Email   : JohnDoe@gmail.com</br>
                Phone   : 555-555-5555</br>
            </p>
        </div>
    </div><!--End Invoice Mid-->

    <div id="bot">

        <div id="table">
            <table>
                <tr class="tabletitle">
                    <td class="item"><h2>Item</h2></td>
                    <td class="Hours"><h2>Qty</h2></td>
                    <td class="Rate"><h2>Sub Total</h2></td>
                </tr>

                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Communication</p></td>
                    <td class="tableitem"><p class="itemtext">5</p></td>
                    <td class="tableitem"><p class="itemtext">$375.00</p></td>
                </tr>

                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Asset Gathering</p></td>
                    <td class="tableitem"><p class="itemtext">3</p></td>
                    <td class="tableitem"><p class="itemtext">$225.00</p></td>
                </tr>

                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Design Development</p></td>
                    <td class="tableitem"><p class="itemtext">5</p></td>
                    <td class="tableitem"><p class="itemtext">$375.00</p></td>
                </tr>

                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Animation</p></td>
                    <td class="tableitem"><p class="itemtext">20</p></td>
                    <td class="tableitem"><p class="itemtext">$1500.00</p></td>
                </tr>

                <tr class="service">
                    <td class="tableitem"><p class="itemtext">Animation Revisions</p></td>
                    <td class="tableitem"><p class="itemtext">10</p></td>
                    <td class="tableitem"><p class="itemtext">$750.00</p></td>
                </tr>


                <tr class="tabletitle">
                    <td></td>
                    <td class="Rate"><h2>tax</h2></td>
                    <td class="payment"><h2>$419.25</h2></td>
                </tr>

                <tr class="tabletitle">
                    <td></td>
                    <td class="Rate"><h2>Total</h2></td>
                    <td class="payment"><h2>$3,644.25</h2></td>
                </tr>

            </table>
        </div><!--End Table-->

        <div id="legalcopy">
            <p class="legal"><strong>Thank you for your business!</strong>  Payment is expected within 31 days; please process this invoice within that time. There will be a 5% interest charge per month on late invoices.
            </p>
        </div>

    </div><!--End InvoiceBot-->
</div><!--End Invoice-->






</body>

</html>
