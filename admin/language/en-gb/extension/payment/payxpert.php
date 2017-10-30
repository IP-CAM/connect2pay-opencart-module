<?php
// Heading
$_['heading_title']             = 'PayXpert';

// Entry
$_['entry_originator']          = 'Merchant Originator:';
$_['entry_password']            = 'Merchant Password:';
$_['entry_password_help']       = 'Leave empty to keep the current password';
$_['entry_connect2pay_url']     = 'Gateway URL:';
$_['entry_connect2pay_url_help']= 'Leave this field empty unless PayXpert provides you a specific URL.';
$_['entry_3dsecure']            = '3D Secure:';
$_['entry_3dsecure_help']       = 'Enable or not 3D Secure protection for the transactions. This may not be available depending on your Payxpert account configuration.';
$_['entry_failure_page']        = 'Display Error Page:';
$_['entry_failure_page_help']   = 'If a transaction fails, the system will display an error page.';
$_['entry_debuglog']            = 'Debug Log:';
$_['entry_merchant_notifications']           = 'Merchant email notifications:';
$_['entry_merchant_notifications_error']     = 'Value is incorrect.';
$_['entry_merchant_notifications_default']   = 'Default value for the account';
$_['entry_merchant_notifications_enabled']   = 'Enabled';
$_['entry_merchant_notifications_disabled']  = 'Disabled';
$_['entry_merchant_notifications_help']      = 'An email notification will be sent to you for each transaction (success or failure) to the chosen email address.';
$_['entry_merchant_notifications_to']        = 'Merchant email notifications recipient:';
$_['entry_merchant_notifications_to_help']   = 'Email to send merchant notification to.';
$_['entry_merchant_notifications_to_error_req'] = 'Recipient address is required when enabling notifications.';
$_['entry_merchant_notifications_to_error_syn'] = 'Recipient address is not a valid email address.';
$_['entry_merchant_notifications_lang']      = 'Merchant email notifications language:';
$_['entry_merchant_notifications_lang_help'] = 'Language for the merchant email.';
$_['entry_merchant_notifications_lang_error']= 'Language has an incorrect value.';
$_['entry_order_status']        = 'Successful Order Status:';
$_['entry_order_status_help']   = 'The accepted orders will be automatically set to that status.';
$_['entry_order_denied_status'] = 'Denied Order Status:';
$_['entry_order_denied_status_help'] = 'The denied orders will be automatically set to that status.';
$_['entry_geo_zone']            = 'Geo Zone:';
$_['entry_status']              = 'Extension Status:';
$_['entry_sort_order']          = 'Position in payment means list:';

$_['entry_lang_en'] = 'English';
$_['entry_lang_fr'] = 'French';
$_['entry_lang_es'] = 'Spanish';
$_['entry_lang_it'] = 'Italian';
$_['entry_lang_de'] = 'German';
$_['entry_lang_pl'] = 'Polish';
$_['entry_lang_zh'] = 'Chinese';
$_['entry_lang_ja'] = 'Japanese';

// Text
$_['text_payment']              = 'Payment';
$_['text_success']              = 'Success: You have modified PayXpert payment module!';
$_['text_payxpert']             = '<a onclick="window.open(\'http://www.payxpert.com\');"><img src="view/image/payment/payxpert.png" alt="PayXpert" title="PayXpert" style="border: 1px solid #EEEEEE;" /></a>';

// Error
$_['error_permission']          = 'Warning: You do not have permission to modify PayXpert payment module';
$_['error_originator']          = 'Originator is required';
$_['error_password']            = 'Password is required';
?>