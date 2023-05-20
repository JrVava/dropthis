<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Google Pay</title>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Helper for displaying status messages.
const addMessage = (message) => {
  const messagesDiv = document.querySelector('#messages');
  messagesDiv.style.display = 'block';
  const messageWithLinks = addDashboardLinks(message);
  messagesDiv.innerHTML += `${messageWithLinks}<br>`;
  console.log(`Debug: ${message}`);
};

// Adds links for known Stripe objects to the Stripe dashboard.
const addDashboardLinks = (message) => {
  const piDashboardBase = 'https://dashboard.stripe.com/test/payments';
  return message.replace(
    /(pi_(\S*)\b)/g,
    `<a href="${piDashboardBase}/$1" target="_blank">$1</a>`
  );
};
        document.addEventListener('DOMContentLoaded', async () => {
  // Load the publishable key from the server. The publishable key
  // is set in your .env file. In practice, most users hard code the
  // publishable key when initializing the Stripe object.

  // 1. Initialize Stripe
  const stripe = Stripe('pk_test_51LHlrdGvfwdLaidkrfgOsLXLYlZ6uxTHizmeqydVVQsHTHuVCrHhMyV7RmqWP3cUf1dqpsutD1uHQCR2B0OP2vcU00TIu148EY', {
    apiVersion: '2020-08-27',
  });

  // 2. Create a payment request object
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

  // 3. Create a PaymentRequestButton element
  const elements = stripe.elements();
  const prButton = elements.create('paymentRequestButton', {
    paymentRequest: paymentRequest,
  });

  // Check the availability of the Payment Request API,
  // then mount the PaymentRequestButton
  paymentRequest.canMakePayment().then(function (result) {
    if (result) {
      prButton.mount('#payment-request-button');
    } else {
      document.getElementById('payment-request-button').style.display = 'none';
      addMessage('Google Pay support not found. Check the pre-requisites above and ensure you are testing in a supported browser.');
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
    </script>
  </head>
  <body>
    <main>
      
      <h1>Google Pay</h1>

      

      

      <div id="payment-request-button">
        <!-- A Stripe Element will be inserted here if the browser supports this type of payment method. -->
      </div>

      <div id="messages" role="alert"></div>

      <p> <a href="https://youtu.be/GERlC3PxKgY" target="_blank">Watch a demo walkthrough</a> </p>
    </main>
  </body>
</html>