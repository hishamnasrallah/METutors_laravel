@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            @php
                               $invoice_data = json_decode($invoiceData);
                            @endphp
                            {{-- <p>{{$invoice_data->date}}</p> --}}
                            <p>Hi </p>
                            <p>Thanks for Booking a course on MEtutors.</p>
                            <p>Booking Details:</p>
                            <p>Date : {{ $invoice_data->date }}</p>
                            <p>Invoice number : {{ $invoice_data->invoice_number }}</p>
                            <p>No of classes : {{ $invoice_data->no_of_classes }}</p>
                            <p>Price per hour : ${{ $invoice_data->price_per_hour }}</p>
                            <p>Total Hours : {{ $invoice_data->total_hours }} </p>
                            <p>Total amount : ${{ $invoice_data->total_amount }}</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
