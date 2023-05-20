document.addEventListener('DOMContentLoaded', async () => {
  var stripe = Stripe('pk_live_51LHlrdGvfwdLaidk58NXmTWgoTa1Z2FqZi4jlYqcyFRAOhT720mXWyWAoAFhK4qDDhxbI6t7FviOCknrt3ovkiPO00YRq7FyzU');
  var paymentRequest = stripe.paymentRequest({
    country: 'US',
    currency: 'usd',
    total: {
      label: 'Demo total',
      amount: 1999,
    },
    requestPayerName: true,
    requestPayerEmail: true,
  });
  var elements = stripe.elements();
  var prButton = elements.create('paymentRequestButton', {
    paymentRequest: paymentRequest,
  });
  paymentRequest.canMakePayment().then(function (result) {
    if (result) {
      prButton.mount('#payment-request-button');
    } else {
      document.getElementById('payment-request-button').style.display = 'none';
      addMessage('Google Pay support not found. Check the pre-requisites above and ensure you are testing in a supported browser.');
    }
  });
  //elements.fetchUpdates()
  // .then(function(result) {
  //   console.log(result);
  //   // Handle result.error
  // });

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
          paymentMethodType: 'card',
        }),
      }
    ).then((r) => r.json());
    if (backendError) {
      addMessage(backendError.message);
      e.complete('fail');
      return;
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
        addMessage(error.message);
        return;
      }
      addMessage(`Payment ${paymentIntent.status}: ${paymentIntent.id}`);
    }

    addMessage(`Payment ${paymentIntent.status}: ${paymentIntent.id}`);
  });
});
  