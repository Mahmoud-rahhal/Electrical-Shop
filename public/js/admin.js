// Admin JavaScript
$(document).ready(function() {
    // Enable Bootstrap tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Thumbnail image click in product view
    $('.thumb img').click(function() {
        $('.main-image img').attr('src', $(this).attr('src'));
    });
    
    // Confirm before delete
    $('form[data-confirm]').submit(function() {
        return confirm($(this).data('confirm'));
    });
    
    // Initialize any admin-specific plugins
});