
@extends('layout.default')

@section('title', 'User')
@push('css')
    <link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" />
    <style>
        h3.form-label {
            font-weight: 800;
        }
        .form-group p {
            font-size: 16px;
            font-weight: 100;
        }
        .verification-message-block{
            display:none;
        }
        .InputElement {color: white;}
    </style>
@endpush
@push('js')
    {{-- <script src="https://js.stripe.com/v3/"></script> --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script src="/assets/js/utils.js" defer></script>

<script>
    $("body").on("click", ".buy-plan", function(e) {
        var selectedPlanId = $(this).prev('input[name="plans"]').val();
        var price = $(this).prev().prev().prev('input[name="price"]').val();
        var no_of_credits = $(this).prev().prev('input[name="no_of_credits"]').val();
        var currency = $(this).prev().prev().prev().prev('input[name="currency"]').val();
        var plan_name = $(this).prev().prev().prev().prev().prev('input[name="plan_name"]').val();
        $('input[name="plan_id"]').val(selectedPlanId);
        $('input[name="amount"]').val(price);
        $('input[name="planName"]').val(plan_name);

        $('.buy-plan').removeClass('btn-green');
        $('.buy-plan').addClass('btn-outline-white');
        $(this).removeClass('btn-outline-white');
        $(this).addClass('btn-green');
        $('.scroll-to-bottom').click();
        $('.stripe-form-block').addClass('d-none');
        $('.button-pay').addClass('d-none');
        e.preventDefault();
        //$(this).next('form').submit();
    });

    $("body").on("click", ".paypal-buy-plan", function(e) {
        e.preventDefault();
        $(this).next('form').submit();
    });
    $(document).ready(function() {
        $('.coupon-btn').click(function() {
            $('.coupon-box-block').removeClass('d-none');
        });
        $('#coupon').on('click', function() {
            $.ajax({
                url: "{{ route('verify-coupon') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "code": $('input[name="coupon"]').val()
                },
                success: function(res) {
                    $('#verification-message').text('');
                    $('#verification-message').removeClass('text-success');
                    $('#verification-message').removeClass('text-danger');
                    //$('.verification-message-block').removeClass('d-none');
                    $('.verification-message-block').show(500);
                    if (res.status == 200) {
                        $('#verification-message').addClass('text-success');
                        $('#verification-message').text(res.message);
                        $('input[name="coupon_code"]').val(res.coupon);
                        window.location = res.url
                    } else {
                        $('#verification-message').addClass('text-danger');
                        $('#verification-message').text(res.message);
                    }
                }
            });
        });
    });
    var stripe = Stripe("{{ env('STRIPE_KEY_'.env('STRIPE_MODE')) }}");

    function payMethodFunction(method) {
        var plan_id = $('input[name="plan_id"]').val();
        var method = method;

        if (plan_id && method == "paypal") {
            $('.paypal-btn').hide();
            $('.paypal-btn-block').html('<button class="btn btn-outline-white w-100 mb-4" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please wait...</button>');

            $('.not-select-plan').addClass('d-none');
            $('input[name="payment_method"]').val(method);
            $.ajax({
                url: "{{ route('paypal-payment') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    plan_id: plan_id,
                    method: method
                },
                success: function(res) {
                    window.location = res.url
                    console.log(res.url);
                }
            });
        } else if (plan_id && method == "stripe") {
            $('.stripe-form-block').removeClass('d-none');
            $('.button-pay').removeClass('d-none');
            $('input[name="plan_id"]').val(plan_id);

            var amount = Math.round(parseFloat($('input[name="amount"]').val()) * 100);
            var planName = $('input[name="planName"]').val();
            var paymentRequest = stripe.paymentRequest({
                country: 'US',
                currency: 'usd',
                total: {
                    label: 'total',
                    amount: amount,
                },
                requestPayerName: true,
                requestPayerEmail: true,
            });
            const elements = stripe.elements();
            const prButton = elements.create('paymentRequestButton', {
                paymentRequest: paymentRequest,
            })
            paymentRequest.canMakePayment().then(function(result) {
                if (result) {
                    prButton.mount('#apple-payment-request-button');
                } else {
                    document.getElementById('apple-payment-request-button').style.display = 'none';
                    addMessage('Apple Pay support not found. Check the pre-requisites above and ensure you are testing in a supported browser.');
                }
            });
            // Strip Payment Button End Here

        } else {
            $('.scroll-to-top').click();
            $('.not-select-plan').removeClass('d-none');
        }
    }

    var elements = stripe.elements();
    var style = {
        base: {
            iconColor: '#666EE8',
            color: '#CFD7E0',
            lineHeight: '40px',
            fontWeight: 300,
            fontFamily: 'Helvetica Neue',
            fontSize: '15px',
            border: "1px solid #FF6060",
            borderColor: "#FF6060",
            '::placeholder': {
                color: '#CFD7E0',
            },
        },
    };

    // Create an instance of the card Element.
    var card = elements.create('card', {
        hidePostalCode: true,
        style: style
    });

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');
    var form = document.getElementById('payment-form-stripe');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form-stripe');
        $('input[name="stripe_token"]').val(token.id);
        //$('.token-div-block').html('<input type="text" name="stripe_token" value="'+token.id+'">');
        $('.stripe-btn-block').html('<button class="btn btn-outline-white w-100 mb-4" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please wait...</button>');
        form.submit();
    }
</script>
@endpush
@section('content')

<div class="card">
    <label class="d-none text-center mt-2 text-danger not-select-plan">Please select plan</label>
    <div class="card-body">
        <div class="mb-3 d-flex justify-content-center" id="select-plan">
            {{-- Plan Start Here --}}
            <div class="card-group">
                @foreach($plans as $key => $plan)
                    
                    
                        <div class="card m-1">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <h3 class="form-label text-center my-4" for="exampleFormControlInput1">{{ $plan->plan_name }}</h3>
                                    </div>
                                    <div class="col-xl-12">
                                        {{-- <a href="javascript:;" class="btn btn-outline-white w-100 mb-4 buy-plan">Get Started</a> --}}
                                        <input type="hidden" value="{{ $plan->plan_name }}" name="plan_name">
                                        <input type="hidden" value="{{ $plan->currency }}" name="currency">
                                        <input type="hidden" value="{{ $plan->price }}" name="price">
                                        <input type="hidden" value="{{ $plan->no_of_credits }}" name="no_of_credits">

                                        <input type="radio" name="plans" class="btn-check" id="btn-check-{{ $plan->id }}" value="{{ $plan->id }}">
                                        <label class="btn btn-outline-white w-100 mb-4 buy-plan" for="btn-check-{{ $plan->id }}">Get Started</label>
                                        <a class="d-none scroll-to-bottom" href="#payment-mode" data-toggle="scroll-to">bottom</a>
                                    </div>
                                    <div class="col-xl-12 text-center text-theme">
                                            <label class="form-label" for="exampleFormControlInput1">{{ $plan->currency." ".$plan->price }}</label>
                                    </div>
                                    <div class="col-xl-12 text-center">
                                        <div class="form-group">
                                            <label class="form-label mb-0 mt-3" for="exampleFormControlInput1">No of Credit: {{ $plan->no_of_credits }}</label>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="form-group text-justify">
                                            <p>
                                                {!! $plan->description !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-arrow">
                                <div class="card-arrow-top-left"></div>
                                <div class="card-arrow-top-right"></div>
                                <div class="card-arrow-bottom-left"></div>
                                <div class="card-arrow-bottom-right"></div>
                            </div>
                        </div>
                    
                @endforeach
            </div>
        </div>
        <form action="" method="post" id="payment-form">
            <input type="hidden" name="plan_id">
            <input type="hidden" name="payment_method">
            <input type="hidden" name="amount">
            <input type="hidden" name="planName">
        </form>
        <a class="d-none scroll-to-top" href="#select-plan" data-toggle="scroll-to">bottom</a>
        {{-- Payment Html Start Here --}}
        <div class="row mb-3 d-flex justify-content-center" id="payment-mode">
            
            {{-- Paypal Payment Html Start Here --}}
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <h3 class="form-label text-center my-4" for="exampleFormControlInput1">Paypal</h3>
                            </div>
                            <div class="col-xl-12 paypal-btn-block">
                                <a href="javascript:;" class="btn btn-outline-white w-100 mb-4 paypal-btn" onClick="payMethodFunction('paypal')">Pay</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
            {{-- Paypal Payment Html End Here --}}

            {{-- Stripe Payment Html Start Here --}}
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <h3 class="form-label text-center my-4" for="exampleFormControlInput1">Stripe</h3>
                            </div>
                            <div class="col-xl-12">
                                <a href="javascript:;" class="btn btn-outline-white w-100 mb-4" onClick="payMethodFunction('stripe')">Pay</a>
                            </div>
                            <div class="button-pay d-none">
                                <div id="apple-payment-request-button"></div>
                                <div id="messages" role="alert"></div>
                            </div>
                            <div class="col-xl-12 d-none stripe-form-block">
                                <form action="{{ route('stripe-payment') }}" method="post" id="payment-form-stripe">
                                    @csrf
                                    <div class="form-row">
                                      <label for="card-element">
                                        Credit or debit card
                                      </label>
                                      <div id="card-element" class="border rounded p-1">
                                        <!-- A Stripe Element will be inserted here. -->
                                      </div>
                                  
                                      <!-- Used to display Element errors. -->
                                      <div id="card-errors" role="alert"></div>
                                    </div>
                                    <div class="token-div-block">
                                        <input type="hidden" name="stripe_token">
                                        <input type="hidden" name="plan_id">
                                    </div>
                                    <div class="stripe-btn-block">
                                        <button type="submit" class="btn btn-outline-white w-100 mb-2 mt-2">Payment</button>
                                    </div>
                                  </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
            {{-- Stripe Payment Html End Here --}}

            {{-- Coupon Apply Html Start Here --}}
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <h3 class="form-label text-center my-4" for="exampleFormControlInput1">Use Coupon</h3>
                            </div>
                            <div class="col-xl-12">
                                <a href="javascript:;" class="btn btn-outline-white w-100 mb-4 coupon-btn">Coupon</a>
                            </div>
                            <div class="d-none coupon-box-block">
                                <div class="col-xl-12">
                                    <input type="text" class="form-control plaintext" id="coupon-text-box" name="coupon" placeholder="Coupon Code">                                    
                                </div>
                                <div class="col-xl-12 mt-1 mb-1 verification-message-block">
                                    <label id="verification-message"></label>
                                </div>
                                <div class="col-xl-12 mt-2">
                                    <a href="javascript:;" class="btn btn-outline-white w-100 mb-2 coupon-btn" id="coupon">Apply</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
            {{-- Coupon Apply Html End Here --}}

        </div>
        {{-- Payment Html End Here --}}

    </div>
    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>
</div>
@endsection