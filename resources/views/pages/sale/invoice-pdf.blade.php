<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales invoice</title>
    <style>
        @page {
            header: page-header;
            footer: page-footer;
        }

        * {
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 0.01em solid #000000;
            padding: 6px;
            font-size: 10px;
        }

        th {
            background-color: #ddd;
        }

        .main {
            width: 50%;
            margin-top: 0px;
        }

        h4 {
            font-weight: lighter;
            font-size: 11px;
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
                    @if($invoice_details->customer->name === 'Walk-In')
                        <b>{{ __t('customer') }} : </b> {{ $invoice_details->customer->name }}
                    @else
                        <b>{{ __t('customer') }} : </b> {{ $invoice_details->customer->name }}, {{ $invoice_details->customer->phone_number }}
                    @endif
                </small>
                <br>
                <small style="font-weight: lighter">
                    <b>{{ __t('invoice_number') }} : </b> {{ $invoice_details->invoice_number }},
                    <br>
                    <b>{{ __t('invoice_date') }} : </b> {{ format_datetime($invoice_details->invoice_date) }}
                </small>
                <br>
                <small style="font-weight: lighter">
                   <b>{{ __t('invoice_type') }} : </b> {{ $invoice_details->status }},
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
    @foreach($invoice_details->saleProducts as $key => $product)
        <tr>
            <td style="text-align: left;border-top: none">{{ ($key+1) }}</td>
            <td style="text-align: left;border-top: none">
                {{ $product->product->name }}
                <br>
                <small>
                    Barcode:{{ $product->product->barcode }},
                    <br>
                    Unit:{{ $product->product->unit->name ."({$product->product->unit->pack_size})" }}
                </small>
            </td>
            <td style="text-align: center; border-top: none">{{ $product->quantity }}</td>
            <td style="text-align: right;border-top: none">{{ show_currency($product->sale_price) }}</td>
            <td style="text-align: right;border-top: none">{{ show_currency($product->subtotal) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tr>
        <th colspan="2" style="text-align: left">subtotal:</th>
        <td colspan="3" style="text-align: right;background-color: #ddd;">{{ show_currency($invoice_details->subtotal) }}</td>
    </tr>
    <tr>
        <th colspan="2" style="text-align: left">Other cost:</th>
        <td colspan="3" style="text-align: right;background-color: #ddd;">{{ show_currency($invoice_details->other_cost) }}</td>
    </tr>
    <tr>
        <th colspan="2" style="text-align: left">(-) Discount:</th>
        <td colspan="3" style="text-align: right;background-color: #ddd;">{{ show_currency($invoice_details->discount) }}</td>
    </tr>
    <tr>
        <th colspan="2" style="text-align: left">Grand total:</th>
        <td colspan="3" style="text-align: right;background-color: #ddd;">{{ show_currency($invoice_details->grand_total) }}</td>
    </tr>
</table>
<htmlpagefooter name="page-footer">
    <div align="right" style="font-size: 10px; width: 50%">
        <i><b>{PAGENO} / {nbpg}</b></i>
    </div>
</htmlpagefooter>

</body>
</html>
