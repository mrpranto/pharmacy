<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Preview Sales pdf</title>
    <style>
        @page {
            header: page-header;
            footer: page-footer;
        }

        * {
            font-family: Verdana, Arial, Tahoma, Serif;
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 0.01em solid #d0cece;
            padding: 6px;
            font-size: 10px;
        }

        th {
            background-color: #ddd;
            font-weight: bold;
        }

        .main {
            width: 50%;
            margin-top: 0px;
        }

        h4 {
            font-weight: lighter;
            font-size: 13px;
        }

        .header {
            text-align: center;
        }
    </style>
</head>
<body>
<htmlpageheader name="page-header">
    <div class="main">
        <div class="header">
            <h4>
                {{ app_information('name') }}
                <br>
                <small style="font-weight: lighter">{{ app_information('address') }}</small>
                <br>
                <small style="font-weight: lighter;margin-bottom: 100px">{{ app_information('mobile') }}</small>
                <br>
                <small style="font-weight: lighter">
                    @if($invoice_details['customerName'] === 'Walk-In')
                        <b>{{ __t('customer') }} : </b> {{ $invoice_details['customerName'] }}
                    @else
                        <b>{{ __t('customer') }} : </b> {{ $invoice_details['customerName'] }}, {{ $customer_phone }}
                    @endif
                </small>
                <br>
                <small style="font-weight: lighter">
                   <b>{{ __t('invoice_type') }} : </b> Preview,
                   <b>{{ __t('print_at') }} : </b> {{ now()->format(format_date()) .' '. now()->format(format_time()) }}
                </small>
            </h4>
        </div>
    </div>
</htmlpageheader>
<table style="width: 50%">
    <thead>
    <tr>
        <th style="text-align: left">{{ __t('sl') }}</th>
        <th style="text-align: left; min-width: 25% !important;">{{ __t('item') }}</th>
        <th style="text-align: center">{{ __t('qty') }}</th>
        <th style="text-align: right">{{ __t('rate') }}</th>
        <th style="text-align: right">{{ __t('amount') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice_details['products'] as $key => $product)
        <tr>
            <td style="text-align: left;border-top: none">{{ ($key+1) }}</td>
            <td style="text-align: left;border-top: none">
                {{ $product['product']['name'] }}
                <br>
                <small>
                    Barcode:{{ $product['product']['barcode'] }},
                    <br>
                    Unit:{{ $product['product']['unit'] }}
                </small>
            </td>
            <td style="text-align: center; border-top: none">{{ $product['quantity'] }}</td>
            <td style="text-align: right;border-top: none">{{ show_currency($product['sale_price']) }}</td>
            <td style="text-align: right;border-top: none">{{ show_currency($product['sub_total']) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tr>
        <th colspan="2" style="text-align: left">subtotal:</th>
        <th colspan="3" style="text-align: right">{{ show_currency($invoice_details['totalSubTotal']) }}</th>
    </tr>
    <tr>
        <th colspan="2" style="text-align: left">Other cost:</th>
        <th colspan="3" style="text-align: right">{{ show_currency($invoice_details['otherCost']) }}</th>
    </tr>
    <tr>
        <th colspan="2" style="text-align: left">(-) Discount:</th>
        <th colspan="3" style="text-align: right">{{ show_currency($invoice_details['discount']) }}</th>
    </tr>
    <tr>
        <th colspan="2" style="text-align: left">Grand total:</th>
        <th colspan="3" style="text-align: right">{{ show_currency($invoice_details['grandTotal']) }}</th>
    </tr>
</table>
<htmlpagefooter name="page-footer">
    <div align="right" style="font-size: 10px; width: 50%">
        <i><b>{PAGENO} / {nbpg}</b></i>
    </div>
</htmlpagefooter>

</body>
</html>
