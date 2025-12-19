// Frontend JavaScript
$(document).ready(function() {
    // Enable Bootstrap tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Auto-submit quantity changes in cart
    $('input[name="quantity"]').change(function() {
        $(this).closest('form').submit();
    });
    
    // Thumbnail image click in product view
    $('.thumb img').click(function() {
        $('.main-image img').attr('src', $(this).attr('src'));
    });
    
    // Payment method toggle
    $('input[name="payment_method"]').change(function() {
        if ($(this).val() === 'card') {
            $('#card-element').show();
        } else {
            $('#card-element').hide();
        }
    });
    
    // Initialize Stripe Elements if needed
    if (typeof Stripe !== 'undefined') {
        const stripe = Stripe('your_stripe_publishable_key');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');
    }
});