@extends('layouts.dashboard', ['page' => ''])
@section('styles')
<style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        color: #555;
        background: #fff;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: left;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(3) {
        padding-top: 20px;
        border-bottom: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
@endsection
@section('content')
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="/img/logo/{{session('restaurant_logo')}}" style="width:100%; max-width:300px;">
                            </td>
                            
                            <td>
                                Invoice #: {{$order->id}}<br>
                                Created: {{Carbon\Carbon::parse($order->created_at)->format('jS F, Y')}}<br>
                                Time: {{Carbon\Carbon::parse($order->created_at)->format('g:i:s a')}} <br/>
                                Due: {{Carbon\Carbon::parse($order->created_at)->format('jS F, Y')}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{session('restaurant_name')}} <br>
                                {{$order->branch->location}} <br/>
                                {{$order->branch->phone_number}}
                            </td>
                            
                            <td>
                                {{$order->address}}<br>
                                {{$order->customer->firstname.' '.$order->customer->lastname}}<br>
                                {{$order->customer->email}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>
                    Payment Method
                </td>
            </tr>
            
            <tr class="details">
                <td>
                    Cash
                </td>
            </tr>
            
            <tr class="heading">
                <td>
                    Item
                </td>
                <td style="min-width: 150px">
                    Qty
                </td>
                <td>
                    Price
                </td>
            </tr>
            @foreach($order->items as $item)
            <tr class="item">
                <td>
                    {{ucwords($item->name)}}
                </td>
                <td style="min-width: 150px">
                    {{$item->pivot->quantity}}
                </td>
                <td>
                    GH&cent; {{$item->price * $item->pivot->quantity}}
                </td>
            </tr>
            @endforeach

            <tr class="total">
                <td></td>
                <td></td>
                <td>
                   Total: GH&cent; {{$order->total_price}}
                </td>
            </tr>
        </table>
    </div>
@endsection