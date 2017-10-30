<?php

/**
 * Client class for the connect2pay payment page.
 *
 * PHP dependencies:
 * PHP >= 5.2.0
 *
 * @version 2.0 (20170721)
 * @author Regis Vidal <regis.vidal@payxpert.com>
 * @author JsH <jsh@payxpert.com>
 * @copyright 2012-2017 Payxpert
 */
class ControllerExtensionPaymentPayxpert extends Controller {
  /**
   * Parameter name prefix, for version >= 3.0 this must be "payment_"
   *
   * @var String
   */
  private $pPrefix = 'payment_';

  /* */
  private $error = array();
  protected $data = array();

  /* Available languages */
  private static $c2p_langs = array(/* */
      'en', /* */
      'fr', /* */
      'es', /* */
      'it', /* */
      'de', /* */
      'pl', /* */
      'zh', /* */
      'ja' /* */
  );

  /* Merchant notification statuses */
  const MERCHANT_NOTIF_DEFAULT = 'default';
  const MERCHANT_NOTIF_ENABLED = 'enabled';
  const MERCHANT_NOTIF_DISABLED = 'disabled';
  private static $merchant_notif_status = array(/* */
      ControllerExtensionPaymentPayxpert::MERCHANT_NOTIF_DEFAULT, /* */
      ControllerExtensionPaymentPayxpert::MERCHANT_NOTIF_ENABLED, /* */
      ControllerExtensionPaymentPayxpert::MERCHANT_NOTIF_DISABLED /* */
  );

  public function __construct($registry) {
    parent::__construct($registry);

    if (version_compare(VERSION, "3.0", "<")) {
      $this->pPrefix = '';
    }
  }

  public function index() {
    $this->load->language('extension/payment/payxpert');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    // ~~~~~
    // Opencart versions specific stuff
    $home = 'common/dashboard';
    $extensionHome = 'marketplace/extension';
    if (version_compare(VERSION, "3.0", "<")) {
      $home = 'common/home';
      $extensionHome = 'extension/extension';
    }

    $token = '';
    if (isset($this->session->data['user_token'])) {
      $token = '&user_token=' . $this->session->data['user_token'];
    } else if (isset($this->session->data['token'])) {
      // This is for < v3.0
      $token = '&token=' . $this->session->data['token'];
    }
    // ~~~~~

    $this->data['error_warning'] = $this->data['error_originator'] = $this->data['error_password'] = '';

    // Handle settings saving
    if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
      // Handle API key change
      if (empty($this->request->post[$this->pPrefix . 'payxpert_password'])) {
        $this->request->post[$this->pPrefix . 'payxpert_password'] = $this->config->get($this->pPrefix . 'payxpert_password');
      }

      $this->model_setting_setting->editSetting($this->pPrefix . 'payxpert', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $goto = $this->getLink($extensionHome, $token . '&type=payment', 'SSL');
      $this->response->redirect($goto);
    }

    // Either saving validation has failed or we just want to display the
    // settings form
    $l10n_entries = array(/* */
        'heading_title', /* */
        'text_enabled', /* */
        'text_disabled', /* */
        'text_all_zones', /* */
        'text_yes', /* */
        'text_no', /* */
        'entry_originator', /* */
        'entry_password', /* */
        'entry_password_help', /* */
        'entry_connect2pay_url', /* */
        'entry_connect2pay_url', /* */
        'entry_connect2pay_url_help', /* */
        'entry_3dsecure', /* */
        'entry_3dsecure_help', /* */
        'entry_failure_page', /* */
        'entry_failure_page_help', /* */
        'entry_debuglog', /* */
        'entry_merchant_notifications', /* */
        'entry_merchant_notifications_help', /* */
        'entry_merchant_notifications_to', /* */
        'entry_merchant_notifications_to_help', /* */
        'entry_merchant_notifications_lang', /* */
        'entry_merchant_notifications_lang_help', /* */
        'entry_order_status', /* */
        'entry_order_status_help', /* */
        'entry_order_denied_status', /* */
        'entry_order_denied_status_help', /* */
        'entry_geo_zone', /* */
        'entry_status', /* */
        'entry_sort_order', /* */
        'button_save', /* */
        'button_cancel', /* */
        'tab_general' /* */
    );

    foreach ($l10n_entries as $entry) {
      $this->data[$entry] = $this->language->get($entry);
    }

    foreach (ControllerExtensionPaymentPayxpert::$c2p_langs as $lang) {
      $this->data['entry_lang'][$lang] = $this->language->get('entry_lang_' . $lang);
    }

    foreach (ControllerExtensionPaymentPayxpert::$merchant_notif_status as $notifStatus) {
      $this->data['entry_merchant_notifications_status'][$notifStatus] = $this->language->get(
          'entry_merchant_notifications_' . $notifStatus);
    }

    // Breadcrumb
    $breadcrumbs = array();

    $breadcrumbs[] = array(/* */
        'text' => $this->language->get('text_home'), /* */
        'href' => $this->getLink($home, $token, 'SSL'), /* */
        'separator' => false /* */
    );

    $breadcrumbs[] = array(/* */
        'text' => $this->language->get('text_payment'), /* */
        'href' => $this->getLink($extensionHome, $token . '&type=payment', 'SSL'), /* */
        'separator' => ' :: ' /* */
    );

    $breadcrumbs[] = array(/* */
        'text' => $this->language->get('heading_title'), /* */
        'href' => $this->getLink('extension/payment/payxpert', $token, 'SSL'), /* */
        'separator' => ' :: ' /* */
    );

    $this->data['breadcrumbs'] = $breadcrumbs;

    $this->data['action'] = $this->getLink('extension/payment/payxpert', $token, 'SSL');

    $this->data['cancel'] = $this->getLink($extensionHome, $token . '&type=payment', 'SSL');

    $this->data[$this->pPrefix . 'payxpert_order_status_id'] = $this->getPostOrConfig($this->pPrefix . 'payxpert_order_status_id');
    $this->data[$this->pPrefix . 'payxpert_order_denied_status_id'] = $this->getPostOrConfig(
        $this->pPrefix . 'payxpert_order_denied_status_id');

    $this->load->model('localisation/order_status');
    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    $this->data[$this->pPrefix . 'payxpert_geo_zone_id'] = $this->getPostOrConfig($this->pPrefix . 'payxpert_geo_zone_id');

    $this->load->model('localisation/geo_zone');

    $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    $this->data[$this->pPrefix . 'payxpert_status'] = $this->getPostOrConfig($this->pPrefix . 'payxpert_status');
    $this->data[$this->pPrefix . 'payxpert_sort_order'] = $this->getPostOrConfig($this->pPrefix . 'payxpert_sort_order');
    $this->data[$this->pPrefix . 'payxpert_originator'] = $this->getPostOrConfig($this->pPrefix . 'payxpert_originator');
    $this->data[$this->pPrefix . 'payxpert_connect2pay_url'] = $this->getPostOrConfig($this->pPrefix . 'payxpert_connect2pay_url');
    $this->data[$this->pPrefix . 'payxpert_3dsecure'] = $this->getPostOrConfig($this->pPrefix . 'payxpert_3dsecure');
    $this->data[$this->pPrefix . 'payxpert_failure_page'] = $this->getPostOrConfig($this->pPrefix . 'payxpert_failure_page');
    $this->data[$this->pPrefix . 'payxpert_debuglog'] = $this->getPostOrConfig($this->pPrefix . 'payxpert_debuglog');
    $this->data[$this->pPrefix . 'payxpert_merchant_notifications'] = $this->getPostOrConfig(
        $this->pPrefix . 'payxpert_merchant_notifications');
    $this->data[$this->pPrefix . 'payxpert_merchant_notifications_statuses'] = ControllerExtensionPaymentPayxpert::$merchant_notif_status;
    $this->data[$this->pPrefix . 'payxpert_merchant_notifications_to'] = $this->getPostOrConfig(
        $this->pPrefix . 'payxpert_merchant_notifications_to');
    $this->data[$this->pPrefix . 'payxpert_merchant_notifications_lang'] = $this->getPostOrConfig(
        $this->pPrefix . 'payxpert_merchant_notifications_lang');
    $this->data[$this->pPrefix . 'payxpert_merchant_notifications_langs'] = ControllerExtensionPaymentPayxpert::$c2p_langs;

    $this->data['header'] = $this->load->controller('common/header');
    $this->data['column_left'] = $this->load->controller('common/column_left');
    $this->data['footer'] = $this->load->controller('common/footer');
    $content = $this->load->view('extension/payment/payxpert', $this->data);

    $this->response->setOutput($content, $this->config->get('config_compression'));
  }

  private function getPostOrConfig($key) {
    return (isset($this->request->post[$key])) ? $this->request->post[$key] : $this->config->get($key);
  }

  private function validate() {
    $valid = true;

    if (!$this->user->hasPermission('modify', 'extension/payment/payxpert')) {
      $this->data['error_warning'] = $this->language->get('error_permission');
      $valid = false;
    }

    $originator = trim($this->request->post[$this->pPrefix . 'payxpert_originator']);
    if (!isset($originator) || empty($originator)) {
      $this->data['error_originator'] = $this->language->get('error_originator');
      $valid = false;
    }

    $currentPassword = trim($this->config->get($this->pPrefix . 'payxpert_password'));
    $password = trim($this->request->post[$this->pPrefix . 'payxpert_password']);
    // Only enforce the password if not already set
    if (empty($password) && empty($currentPassword)) {
      $this->data['error_password'] = $this->language->get('error_password');
      $valid = false;
    }

    if (isset($this->request->post[$this->pPrefix . 'payxpert_merchant_notifications'])) {
      // Validate merchant notifications status
      $notifStatus = $this->request->post[$this->pPrefix . 'payxpert_merchant_notifications'];
      if (!in_array($notifStatus, ControllerExtensionPaymentPayxpert::$merchant_notif_status)) {
        $this->data[$this->pPrefix . 'payxpert_merchant_notifications_error'] = $this->language->get('entry_merchant_notifications_error');
        $valid = false;
      } else {
        $notificationRecipient = $this->request->post[$this->pPrefix . 'payxpert_merchant_notifications_to'];
        if (!isset($notificationRecipient) || empty($notificationRecipient)) {
          if ($notifStatus == ControllerExtensionPaymentPayxpert::MERCHANT_NOTIF_ENABLED) {
            $this->data[$this->pPrefix . 'payxpert_merchant_notifications_to_error'] = $this->language->get(
                'entry_merchant_notifications_to_error_req');
            $valid = false;
          }
        } else if (!preg_match('/^[^@]+@[^@]+$/', $notificationRecipient)) {
          $this->data[$this->pPrefix . 'payxpert_merchant_notifications_to_error'] = $this->language->get(
              'entry_merchant_notifications_to_error_syn');
          $valid = false;
        }
        $notificationLang = $this->request->post[$this->pPrefix . 'payxpert_merchant_notifications_lang'];
        if (!in_array($notificationLang, ControllerExtensionPaymentPayxpert::$c2p_langs)) {
          $this->data[$this->pPrefix . 'payxpert_merchant_notifications_lang_error'] = $this->language->get(
              'entry_merchant_notifications_lang_error');
          $valid = false;
        }
      }
    }

    return $valid;
  }

  private function getLink($route, $args = '', $connection = 'NONSSL') {
    $connection = ($connection == 'NONSSL') ? false : true;

    return $this->url->link($route, $args, $connection);
  }
}