@extends('layouts.front-end')
@section('content')
<!-- Page Content -->
<div class="container">
    
    <!-- Content Heading -->
    <h2 class="content-heading pt-2">
        <i class="{{config('theme.icon.item_list')}} mr-2"></i>{{ ucfirst(__('orders.head_title.list')) }}
        <div class="bock-sub-menu"></div>
    </h2>
    <!-- END Content Heading -->

    <!-- Content Main -->
    
            <!-- ** Content Data ** -->    
        
            <div class="row">
                @foreach($collection as $item)
                <div class="col-12">
                    <div class="block block-themed block-rounded">
                        <div class="block-header bg-pulse-light">
                            <label for="path_no">Date : <b>{{$item->sale_at}}</b></label>
                        </div>
                        <div class="block-content">
                            <div class="row">
                                <div class="col-md-6">
                                        <span class="label">{{ucfirst(__('orders.path_no.label'))}} : </span>
                                        <span class="data">{{@$item->path_no}}</span>
                                </div>   
                                <div class="col-md-6">
                                    <span class="label">{{ucfirst(__('orders.serial.label'))}} : </span>
                                    <span class="data">{{@$item->orderItems->Inventory->serial}}</span>
                                </div>
                                <div class="col-md-6">
                                    <span class="label">{{ucfirst(__('orders.customer_name.label'))}} : </span>
                                    <span class="data">{{@$item->customer_name}}</span>
                                </div> 
                                <div class="col-md-6">
                                    <span class="label">{{ucfirst(__('orders.email.label'))}} : </span>
                                    <span class="data">{{@$item->email}}</span>
                                </div> 
                                <div class="col-md-6">
                                    <span class="label">{{ucfirst(__('orders.tel.label'))}} : </span>
                                    <span class="data">{{@$item->tel}}</span>
                                </div>
                                <div class="col-md-6">
                                    <span class="label">{{ucfirst(__('orders.receipt_no.label'))}} : </span>
                                    <span class="data">{{@$item->receipt_no}}</span>
                                </div>
                                <div class="col-md-6">
                                    <span class="label">{{ucfirst(__('orders.license.label'))}} : </span>
                                    <span class="data">{{@$orderItems[$item->id]}}</span>
                                </div>

                                
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
       
        <!-- END Content Data -->
    
    <!-- END Content Main -->
</div>
<!-- END Page Content -->

@endsection

@section('css_after')
<style>
    .block-content{
    font-size: 18px;
    padding-bottom: 15px;
    }
    .block-content .label{
        min-width: 150px;
    }
    .block-content .data{
        margin-left: 10px;
        font-weight: bold;
    }
</style>
@endsection
@section('js_after')

@endsection
