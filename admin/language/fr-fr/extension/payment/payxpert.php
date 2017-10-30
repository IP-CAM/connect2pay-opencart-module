<?php
// Heading
$_['heading_title']             = 'PayXpert';

// Entry
$_['entry_originator']          = 'Initiateur :';
$_['entry_password']            = 'Mot de passe :';
$_['entry_password_help']       = 'Laisser vide pour conserver le mot de passe actuel';
$_['entry_connect2pay_url']     = 'Adresse de la passerelle de paiement :';
$_['entry_connect2pay_url_help']= 'Laisser vide sauf si PayXpert vous fournit une adresse spécifique.';
$_['entry_3dsecure']            = '3D Secure :';
$_['entry_3dsecure_help']       = 'Activer ou non la protection 3D Secure sur les transactions. Peut ne pas être disponible en fonction de la configuration de votre compte Payxpert.';
$_['entry_failure_page']        = 'Afficher la page d\'erreur :';
$_['entry_failure_page_help']   = 'Lorsqu\'une transaction échoue, le système affichera une page d\'erreur.';
$_['entry_debuglog']            = 'Journal de debug :';
$_['entry_merchant_notifications']           = 'Notifications marchand par email :';
$_['entry_merchant_notifications_error']     = 'Valeur incorrecte.';
$_['entry_merchant_notifications_default']   = 'Valeur par défaut du compte';
$_['entry_merchant_notifications_enabled']   = 'Activées';
$_['entry_merchant_notifications_disabled']  = 'Désactivées';
$_['entry_merchant_notifications_help']      = 'An email notification will be sent to you for each transaction (success or failure) to the chosen email address.';
$_['entry_merchant_notifications_to']        = 'Adresse destinataire notifications marchand :';
$_['entry_merchant_notifications_to_help']   = 'Adresse email qui recevra les notifications marchand.';
$_['entry_merchant_notifications_to_error_req'] = 'L\'adresse destinataire est obligatoire lorsque les notifications sont activées.';
$_['entry_merchant_notifications_to_error_syn'] = 'L\'adresse destinataire n\'est pas une adresse email valide.';
$_['entry_merchant_notifications_lang']      = 'Langue des notifications marchand :';
$_['entry_merchant_notifications_lang_help'] = 'Langue utilisée dans les emails de notification marchand.';
$_['entry_merchant_notifications_lang_error']= 'La valeur de la langue est incorrecte.';
$_['entry_order_status']        = 'Statut des commandes acceptées :';
$_['entry_order_status_help']   = 'Les commandes dont le paiement est accepté seront automatiquement placées dans ce statut.';
$_['entry_order_denied_status'] = 'Statut des commandes échouées :';
$_['entry_order_denied_status_help'] = 'Les commandes dont le paiement est refusé seront automatiquement placées dans ce statut.';
$_['entry_geo_zone']            = 'Geo Zone:';
$_['entry_status']              = 'Statut de l\'extension :';
$_['entry_sort_order']          = 'Position dans liste moyens de paiement :';

$_['entry_lang_en'] = 'Anglais';
$_['entry_lang_fr'] = 'Français';
$_['entry_lang_es'] = 'Espagnol';
$_['entry_lang_it'] = 'Italien';
$_['entry_lang_de'] = 'Allemand';
$_['entry_lang_pl'] = 'Polonais';
$_['entry_lang_zh'] = 'Chinois';
$_['entry_lang_ja'] = 'Japonais';

// Text
$_['text_payment']              = 'Paiement';
$_['text_success']              = 'Succès: le module de paiement PayXpert a été modifié';
$_['text_payxpert']             = '<a onclick="window.open(\'http://www.payxpert.com\');"><img src="view/image/payment/payxpert.png" alt="PayXpert" title="PayXpert" style="border: 1px solid #EEEEEE;" /></a>';

// Error
$_['error_permission']          = 'Attention : vous n\'avez pas la permission de modifier le module de paiement PayXpert';
$_['error_originator']          = 'Initiateur est requis';
$_['error_password']            = 'Mot de passe est requis';
?>