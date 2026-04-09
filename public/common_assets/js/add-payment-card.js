//<--------- Add Payment Card -------//>
(function ($) {
    "use strict";

    const stripe = Stripe(key_stripe);
    const elements = stripe.elements();

    var style = {
        base: {
            color: 'blue',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    const cardElement = elements.create('card', { style: style, hidePostalCode: true });

    cardElement.mount('#card-element');
    // const cardButton = document.getElementById('card-button');
    // const clientSecret = cardButton.dataset.secret;
    //const formUrl = cardButton.dataset.route;


    // Handle real-time validation errors from the card Element.
    cardElement.addEventListener('change', function (event) {
        var displayError = document.getElementById('card-errors');

        if (event.error) {
            displayError.classList.remove('display-none');
            displayError.textContent = event.error.message;
            $('#card-button').removeAttr('disabled');
            $('#card-button').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
        } else {
            displayError.classList.add('display-none');
            displayError.textContent = '';
        }
    });

    // cardButton.addEventListener('click', async (e) => {
    document.getElementById('stripeForm').addEventListener('click',  async (event) => {
        if (event.target.id === 'card-button') {
            var button = $('#card-button');
            var displayError = document.getElementById('card-errors');

            button.attr({ 'disabled': 'true' });
            button.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');
            //For singlepayments
            const { paymentMethod, error } = await stripe.createPaymentMethod(
                'card', cardElement, {
                billing_details: { name: full_name_user }
            }
            );

            if (error) {
                // Display "error.message" to the user.
                displayError.classList.remove('display-none');
                displayError.textContent = error.message;
                button.removeAttr('disabled');
                button.find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
            } else {
                // The card has been verified successfully.
                displayError.classList.add('display-none');
                displayError.textContent = '';
                paymentMethodHandler(paymentMethod.id);
            }

            function paymentMethodHandler(payment_method) {
                var form = document.getElementById('stripeForm');
                // form.action=formUrl;
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method');
                hiddenInput.setAttribute('value', payment_method);
                form.appendChild(hiddenInput);
                form.submit();
            }
        }
    });

})(jQuery);
