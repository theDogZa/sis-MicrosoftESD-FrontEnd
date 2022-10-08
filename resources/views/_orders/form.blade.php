@extends('layouts.front-end')
@section('content')
<!-- Page Content -->
<div class="container">
    
    <!-- Content Heading -->
    <h2 class="content-heading pt-2">
        <i class="{{config('theme.icon.menu_orders')}} mr-2"></i>{{ ucfirst(__('orders.heading')) }}
        <div class="bock-sub-menu"></div>
    </h2>
    <!-- END Content Heading -->

    <!-- Content Main -->
    <div class="block {{config('theme.layout.main_block')}}">
        <div class="block-header {{config('theme.layout.main_block_header')}}">
            <h3 class="block-title">
                <i class="{{config('theme.icon.item_form')}} mr-2"></i>
                @if(!isset($order))
                {{ ucfirst(__('orders.head_title.add')) }}
                @else
                {{ ucfirst(__('orders.head_title.edit')) }}
                @endif
                <small> </small>
            </h3>
        </div>

        <div class="block-content">
            <!-- ** Content Data ** -->
            <form action="{{ url('/orders'.( isset($order) ? '/' . $order->id : '')) }}" method="POST" class="needs-validation" enctype="application/x-www-form-urlencoded" id="form" novalidate>
                {{ csrf_field() }}
                @if(isset($order))
                <input type="hidden" name="_method" value="PUT">
                @endif
                <div class="row form-group">
                    @if($arrShowField['customer_name']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="customer_name">{{ucfirst(__('orders.customer_name.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('orders.customer_name.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('orders.customer_name.popover.title')) ,'content'=> ucfirst(__('orders.customer_name.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required  value="{{ @$customer_name }}" placeholde="{{__('orders.customer_name.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('orders.customer_name.label')) ])
                    </div>
                    @endif
                    @if($arrShowField['email']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="email">{{ucfirst(__('orders.email.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('orders.email.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('orders.email.popover.title')) ,'content'=> ucfirst(__('orders.email.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="email" name="email" required  value="{{ @$email }}" placeholde="{{__('orders.email.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('orders.email.label')) ])
                    </div>
                    @endif
                    @if($arrShowField['tel']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="tel">{{ucfirst(__('orders.tel.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('orders.tel.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('orders.tel.popover.title')) ,'content'=> ucfirst(__('orders.tel.popover.content'))])
                            @endif
                        </label>
                        <input type="number" min="0" class="form-control" id="tel" name="tel" required  value="{{ @$tel }}" placeholde="{{__('orders.tel.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('orders.tel.label')) ])
                    </div>
                    @endif
                    @if($arrShowField['path_no']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="path_no">{{ucfirst(__('orders.path_no.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('orders.path_no.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('orders.path_no.popover.title')) ,'content'=> ucfirst(__('orders.path_no.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="path_no" name="path_no" required  value="{{ @$path_no }}" placeholde="{{__('orders.path_no.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('orders.path_no.label')) ])
                        <div class="valid-feedback animated fadeInDown" id="show-path-name"></div>
                    </div>
                    @endif
                    @if($arrShowField['sale_uid']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="sale_uid">{{ucfirst(__('orders.sale_uid.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('orders.sale_uid.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('orders.sale_uid.popover.title')) ,'content'=> ucfirst(__('orders.sale_uid.popover.content'))])
                            @endif
                        </label>
                        <select class="form-control" id="sale_uid" name="sale_uid" required >
                            <option value="">all</option>
                            @include('components._option_select',['data'=>$arrSaleu,'selected' => @$sale_uid])
                        </select>
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('orders.sale_uid.label')) ])
                    </div>
                    @endif

                    @if($arrShowField['sale_at']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="sale_at">{{ucfirst(__('orders.sale_at.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('orders.sale_at.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('orders.sale_at.popover.title')) ,'content'=> ucfirst(__('orders.sale_at.popover.content'))])
                            @endif
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control input-datetime bg-white js-flatpickr-enabled flatpickr-input" required  id="sale_at" name="sale_at" value="{{@$sale_at}}" data-default-date="{{@$sale_at}}">
                            <div class="input-group-append">
                                <span class="input-group-text input-toggle" title="toggle">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <span class="input-group-text input-clear" title="clear">
                                    <i class="fa fa-close"></i>
                                </span>
                            </div>
                            @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('orders.sale_at.label')) ])
                        </div>
                    </div>
                    @endif
                    <!-- ------------------------>
                    <div class="{{config('theme.layout.form')}}">
                        <label for="serial">{{ucfirst(__('orders.serial.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('orders.serial.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('orders.serial.popover.title')) ,'content'=> ucfirst(__('orders.serial.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="serial" data-id="1" name="serial[]" required disabled value="{{ @$serial }}" placeholde="{{__('orders.serial.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('orders.serial.label')) ])
                        <div class="valid-feedback animated fadeInDown" id="show-serial-user"></div>
                    </div>
                    <!-- -->
                    @if($arrShowField['receipt_no']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="receipt_no">{{ucfirst(__('orders.receipt_no.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('orders.receipt_no.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('orders.receipt_no.popover.title')) ,'content'=> ucfirst(__('orders.receipt_no.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="receipt_no" name="receipt_no" required  value="{{ @$receipt_no }}" placeholde="{{__('orders.receipt_no.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('orders.receipt_no.label')) ])
                    </div>
                    @endif
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col">
                        {{-- @include('components._btn_back') --}}
                        @include('components._btn_submit_form')
                        @include('components._btn_reset_form')
                    </div>
                </div>
            </form>
            <!-- END Content Data -->
        </div>
    </div>
    <!-- END Content Main -->
</div>
<!-- END Page Content -->
@endsection
@section('css_after')
<link rel="stylesheet" id="css-flatpickr" href="{{ asset('/js/plugins/flatpickr/flatpickr.min.css') }}">
@endsection
@section('js_after')
<script src="{{ asset('/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script>
    let pName;

    (function() {
        'use strict';
        window.addEventListener('load', async function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, async function(form) {
                form.addEventListener('submit', async function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }else{
                        event.preventDefault();
                        event.stopPropagation();

                        var title = "{{ucfirst(__('orders.message_confirm_create.title'))}}"
                        var message = "{{ucfirst(__('orders.message_confirm_create.message'))}}"

                        var confirm = await confirmMessage(title,message,'question');
                        if(confirm == true){
                            alertLoading();
                            $('#form').submit();
                            
                            return;
                            
                        }   
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    $(function($) {
        $('.input-clear').click(function() {
            $(this).closest('.input-group').find('input').val("");     
        });
        $('.input-toggle').click(function() {
            var idInput = '#'+$(this).closest('.input-group').find('input').attr('id');    
            const calendar = document.querySelector(idInput)._flatpickr;
            calendar.toggle();
        });
    });

    $('#path_no').on("focusout", async function () {
        
        var path_no = $('#path_no').val();
        if(path_no){

            alertLoading();
            $('#show-path-name').text("").hide();
            $('#serial').val('').attr('disabled',true);
            var textSerial = "{{__('validation.required',['attribute'=> ucfirst(__('orders.serial.label')) ])}} ";
            $('#serial').removeClass('is-valid').removeClass('is-invalid');
            $('#serial').next('.invalid-feedback').text(textSerial);

            var chkPath = await checkPath()

            if ( chkPath != true) {
                $('#path_no').removeClass('is-valid').addClass('is-invalid');
                $('#path_no').next('.invalid-feedback').text(chkPath);
                swal.close()
                return false;
            } else {
                var textPathNo = "{{__('validation.required',['attribute'=> ucfirst(__('orders.path_no.label')) ])}} ";
                $('#path_no').removeClass('is-valid').removeClass('is-invalid');
                $('#path_no').next('.invalid-feedback').text(textPathNo);

                $('#show-path-name').text(pName).show();
                $('#serial').attr('disabled',false);

                swal.close()
                return true;
            }
        }
    });

    $('#serial').on("focusout", async function () {

        var serial = $('#serial').val();
        var textSuss = "{{ucfirst(__('orders.message_serial_can_be_used'))}}"
        if(serial){
            $('#show-serial-user').text("").hide();
            alertLoading();
            var chkSerial = await checkSerial()

            if ( chkSerial != true) {
                $('#serial').removeClass('is-valid').addClass('is-invalid');
                $('#serial').next('.invalid-feedback').text(chkSerial);
                swal.close();
                return false;
            } else {
                var textPathNo = "{{__('validation.required',['attribute'=> ucfirst(__('orders.serial.label')) ])}} ";
                $('#serial').removeClass('is-valid').removeClass('is-invalid');
                $('#serial').next('.invalid-feedback').text(textPathNo);

                $('#show-serial-user').text(textSuss).show();

                ;
                swal.close();
                return true;
            }
        }
    });

    $('#tel').on("focusout", async function () {
        var tel = $('#tel').val();
        var chkPhoneNo = await checkPhoneNo(tel)

        if ( chkPhoneNo != true) {
            var textTel = '{{ __("orders.message_invalid_tel") }}'
            $('#tel').removeClass('is-valid').addClass('is-invalid');
            $('#tel').next('.invalid-feedback').text(textTel);
            return false;
        } else {
            var textTel = "{{__('validation.required',['attribute'=> ucfirst(__('orders.tel.label')) ])}} ";
            $('#tel').removeClass('is-valid').removeClass('is-invalid');
            $('#tel').next('.invalid-feedback').text(textTel);
            return true;
        }

    });

    $('#email').on("focusout", async function () {
        var email = $('#email').val();
        var chkEmail = await validateEmail(email)

        if ( chkEmail != true) {
            var textEmail = '{{ __("orders.message_invalid_email") }}'
            $('#email').removeClass('is-valid').addClass('is-invalid');
            $('#email').next('.invalid-feedback').text(textEmail);
            return false;
        } else {
            var textEmail = "{{__('validation.required',['attribute'=> ucfirst(__('orders.email.label')) ])}} ";
            $('#email').removeClass('is-valid').removeClass('is-invalid');
            $('#email').next('.invalid-feedback').text(textEmail);
            return true;
        }
    });

    async function checkPhoneNo(input){

        let result = input.substring(0,2);
        if(result != '02'){
            var regExp = /^0[0-9]{9}$/i;
            return await regExp.test(input);
        }else{
            return false;
        }
    }

    async function validateEmail(email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return await emailReg.test( email );
    }

    async function checkPath() {

        var path_no = $('#path_no').val();
    
        var url = "{{ route('chk.pathNo') }}";
        res = $.post(url, {
                'path_no': path_no
            })
            .then(function (response) {

                var obj = response
                if (obj.code !== 200) {
                    return obj.message;
                } else {
                    pName = obj.data
                    return true;
                }
            })
            .catch(function (err) {
                return false;
            });
        return await res;

    }

    async function checkSerial() {

        var path_no = $('#path_no').val();
        var serial = $('#serial').val();
    
        var url = "{{ route('chk.serial') }}";
        res = $.post(url, {
                'path_no': path_no,
                'serial[]': serial
            })
            .then(function (response) {

                var obj = response
                if (obj.code !== 200) {
                    return obj.message;
                } else {
                    return true;
                }
            })
            .catch(function (err) {
                return false;
            });
        return await res;
    }

</script>
@endsection



<!--
/** 
 * CRUD Laravel
 * Master ฺBY Kepex  =>  https://github.com/kEpEx/laravel-crud-generator
 * Modify/Update BY PRASONG PUTICHANCHAI
 * 
 * Latest Update : 06/04/2018 13:51
 * Version : ver.1.00.00
 *
 * File Create : 2022-01-05 11:35:12 *
 */
-->