@extends('layouts.front-end')
@section('content')
<!-- Page Content -->
<div class="container">
    <!-- Content Main -->
    <div class=" {{config('theme.layout.main_block')}}">
            <!-- ** Content Data ** -->    
        <div class="content">
            <div class="row">
                {{-- <div class="col-12">
                    <a class="block block-rounded block-height bg-primary-light" href="{{ route('profiles.index')}}" style="height: 80px;">
                        <div class="block-header pb-3">
                        <h3 class="fw-normal mt-2"><i class="fa fa-user mr-2"></i> Profiles</h3>
                        </div>
                    </a>
                </div> --}}
                @permissions('front.list.order')
                <div class="col-md-6">
                    <a class="block block-rounded block-height bg-warning" href="{{ route('order.list')}}">
                        <div class="block-content pb-3">
                            <h3 class="fw-normal mt-3"><i class="{{config('theme.icon.item_list')}} mr-2"></i>Order List</h3>
                        </div>
                    </a>
                </div>
                @endpermissions
                @permissions('front.create.order')
                <div class="col-md-6">
                    <a class="block block-rounded block-height bg-success" href="{{ route('order.from') }}">
                        <div class="block-content pb-3">
                            <h3 class="fw-normal mt-3"><i class="{{config('theme.icon.menu_orders')}} mr-2"></i> New Order</h3>
                        </div>
                    </a>
                </div>
                @endpermissions
            </div>
        </div>
        <!-- END Content Data -->
    </div>
    <!-- END Content Main -->
</div>
<!-- END Page Content -->

@include('components._notify_message')
@endsection

@section('css_after')
<style>
    .block-height{
        height: 120px;
    }
</style>
@endsection
@section('js_after')

<script>
    $(function($) {

    });
</script>
@endsection