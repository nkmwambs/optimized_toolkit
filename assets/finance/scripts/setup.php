<script>
/**
 *	Why my AJAX functions were returning 500 Internal Server Errors With CSRF
 * 	Because your CSRF validation is field, in order to fix this problem you have to pass your 
 * 	CSRF hidden input value with in your ajax request. 
 * 	Adopted from https://arjunphp.com/ajax-csrf-protection-in-codeigniter/
 */	
$(function($) {
 
    // this script needs to be loaded on every page where an ajax POST may happen
    $.ajaxSetup({
        data: {
            '<?php echo $this->CI->security->get_csrf_token_name(); ?>' : '<?php echo $this->CI->security->get_csrf_hash(); ?>'
        }
    });
 
});
</script>