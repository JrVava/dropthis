function pay(amount=''){
    var amount = amount*100;
        // 1. Initialize Stripe
        const stripe = Stripe('pk_live_51LHlrdGvfwdLaidk58NXmTWgoTa1Z2FqZi4jlYqcyFRAOhT720mXWyWAoAFhK4qDDhxbI6t7FviOCknrt3ovkiPO00YRq7FyzU', {
            apiVersion: '2020-08-27',
        });
        // 2. Create a payment request object
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
        // 3. Create a PaymentRequestButton element
        const elements = stripe.elements();
            const prButton = elements.create('paymentRequestButton', {
            paymentRequest: paymentRequest,
        });
        // Check the availability of the Payment Request API,
        // then mount the PaymentRequestButton
        paymentRequest.canMakePayment().then(function (result) {
            if (result) {
                console.log(result);
                $('#click').hide();
            prButton.mount('#apple-payment-request-button');
            } else {
            document.getElementById('apple-payment-request-button').style.display = 'none';
            addMessage('Apple Pay support not found. Check the pre-requisites above and ensure you are testing in a supported browser.');
            }
        });
        paymentRequest.on('paymentmethod', async (e) => {
            // Make a call to the server to create a new
            // payment intent and store its client_secret.
            const {error: backendError, clientSecret} = await fetch(
              '/create-payment-intent',
              {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                  currency: 'usd',
                  paymentMethodType: 'asd',
                }),
              }
            ).then((r) =>{
                console.log(r.json());
            });
              
            if (backendError) {
              e.complete('fail');
              return;
            }else{
                alert();
            }
            addMessage(`Client secret returned.`);
  
        // Confirm the PaymentIntent without handling potential next actions (yet).
        let {error, paymentIntent} = await stripe.confirmCardPayment(
            clientSecret,
            {
            payment_method: e.paymentMethod.id,
            },
            {
            handleActions: false,
            }
        );
        if (error) {
            console.log("kyu");
            addMessage(error.message);
    
            // Report to the browser that the payment failed, prompting it to
            // re-show the payment interface, or show an error message and close
            // the payment interface.
            e.complete('fail');
            return;
        }
        // Report to the browser that the confirmation was successful, prompting
        // it to close the browser payment method collection interface.
        e.complete('success');
    
        // Check if the PaymentIntent requires any actions and if so let Stripe.js
        // handle the flow. If using an API version older than "2019-02-11" instead
        // instead check for: `paymentIntent.status === "requires_source_action"`.
        if (paymentIntent.status === 'requires_action') {
            // Let Stripe.js handle the rest of the payment flow.
            let {error, paymentIntent} = await stripe.confirmCardPayment(
            clientSecret
            );
            if (error) {
            // The payment failed -- ask your customer for a new payment method.
            console.log('why?');
            addMessage(error.message);
            return;
            }
            addMessage(`Payment ${paymentIntent.status}: ${paymentIntent.id}`);
        }
        
        addMessage(`Payment ${paymentIntent.status}: ${paymentIntent.id}`);
        });
}
// $(document).ready(function(){
//     $('#click').click(function(){
        
        
//     });
// });
