<?php

/**
 * Client class for the connect2pay payment page.
 *
 * PHP dependencies:
 * PHP >= 5.2.0
 *
 * @version 1.1 (20160630)
 * @author Regis Vidal <regis.vidal@payxpert.com>
 * @author JsH <jsh@payxpert.com>
 * @copyright 2012-2016 Payxpert
 */
class ControllerPaymentPayxpert extends Controller {
  private $error = array();
  protected $data = array();
  private static $c2p_langs = array(
      'en',
      'fr',
      'es',
      'it',
      'de',
      'pl',
      'zh',
      'ja'
  );

  const MERCHANT_NOTIF_DEFAULT = 'default';
  const MERCHANT_NOTIF_ENABLED = 'enabled';
  const MERCHANT_NOTIF_DISABLED = 'disabled';
  private static $merchant_notif_status = array(
      ControllerPaymentPayxpert::MERCHANT_NOTIF_DEFAULT,
      ControllerPaymentPayxpert::MERCHANT_NOTIF_ENABLED,
      ControllerPaymentPayxpert::MERCHANT_NOTIF_DISABLED
  );

  public function index() {
    $this->load->language('payment/payxpert');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    // Older versions don't support token
    if (isset($this->session->data['token'])) {
      $token = '&token=' . $this->session->data['token'];
    } else {
      $token = "";
    }

    $this->data['error_warning'] = $this->data['error_originator'] = $this->data['error_password'] = '';

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
      // Handle API key change
      if (empty($this->request->post['payxpert_password'])) {
        $this->request->post['payxpert_password'] = $this->config->get('payxpert_password');
      }

      $this->model_setting_setting->editSetting('payxpert', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $goto = $this->getLink('extension/payment', $token, 'SSL');
      if (version_compare(VERSION, "2.0", "<")) {
        $this->redirect($goto);
      } else {
        $this->response->redirect($goto);
      }
    }

    $l10n_entries = array(
        'heading_title',
        'text_enabled',
        'text_disabled',
        'text_all_zones',
        'text_yes',
        'text_no',
        'entry_originator',
        'entry_password',
        'entry_password_help',
        'entry_connect2pay_url',
        'entry_connect2pay_url',
        'entry_connect2pay_url_help',
        'entry_3dsecure',
        'entry_3dsecure_help',
        'entry_failure_page',
        'entry_failure_page_help',
        'entry_debuglog',
        'entry_merchant_notifications',
        'entry_merchant_notifications_help',
        'entry_merchant_notifications_to',
        'entry_merchant_notifications_to_help',
        'entry_merchant_notifications_lang',
        'entry_merchant_notifications_lang_help',
        'entry_order_status',
        'entry_order_status_help',
        'entry_order_denied_status',
        'entry_order_denied_status_help',
        'entry_geo_zone',
        'entry_status',
        'entry_sort_order',
        'button_save',
        'button_cancel',
        'tab_general'
    );

    foreach ($l10n_entries as $entry) {
      $this->data[$entry] = $this->language->get($entry);
    }

    foreach (ControllerPaymentPayxpert::$c2p_langs as $lang) {
      $this->data['entry_lang'][$lang] = $this->language->get('entry_lang_' . $lang);
    }

    foreach (ControllerPaymentPayxpert::$merchant_notif_status as $notifStatus) {
      $this->data['entry_merchant_notifications_status'][$notifStatus] = $this->language->get('entry_merchant_notifications_' . $notifStatus);
    }

    $this->document->breadcrumbs = array();

    $this->document->breadcrumbs[] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->getLink('common/home', $token, 'SSL'),
        'separator' => false
    );

    $this->document->breadcrumbs[] = array(
        'text' => $this->language->get('text_payment'),
        'href' => $this->getLink('extension/payment', $token, 'SSL'),
        'separator' => ' :: '
    );

    $this->document->breadcrumbs[] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->getLink('payment/payxpert', $token, 'SSL'),
        'separator' => ' :: '
    );

    // Version 1.5.x of breadcrumbs
    $this->data['breadcrumbs'] = $this->document->breadcrumbs;

    $this->data['action'] = $this->getLink('payment/payxpert', $token, 'SSL');

    $this->data['cancel'] = $this->getLink('extension/payment', $token, 'SSL');

    $this->data['payxpert_order_status_id'] = $this->getPostOrConfig('payxpert_order_status_id');
    $this->data['payxpert_order_denied_status_id'] = $this->getPostOrConfig('payxpert_order_denied_status_id');

    $this->load->model('localisation/order_status');
    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    $this->data['payxpert_geo_zone_id'] = $this->getPostOrConfig('payxpert_geo_zone_id');

    $this->load->model('localisation/geo_zone');

    $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    $this->data['payxpert_status'] = $this->getPostOrConfig('payxpert_status');
    $this->data['payxpert_sort_order'] = $this->getPostOrConfig('payxpert_sort_order');
    $this->data['payxpert_originator'] = $this->getPostOrConfig('payxpert_originator');
    $this->data['payxpert_password'] = $this->getPostOrConfig('payxpert_password');
    $this->data['payxpert_connect2pay_url'] = $this->getPostOrConfig('payxpert_connect2pay_url');
    $this->data['payxpert_3dsecure'] = $this->getPostOrConfig('payxpert_3dsecure');
    $this->data['payxpert_failure_page'] = $this->getPostOrConfig('payxpert_failure_page');
    $this->data['payxpert_debuglog'] = $this->getPostOrConfig('payxpert_debuglog');
    $this->data['payxpert_merchant_notifications'] = $this->getPostOrConfig('payxpert_merchant_notifications');
    $this->data['payxpert_merchant_notifications_statuses'] = ControllerPaymentPayxpert::$merchant_notif_status;
    $this->data['payxpert_merchant_notifications_to'] = $this->getPostOrConfig('payxpert_merchant_notifications_to');
    $this->data['payxpert_merchant_notifications_lang'] = $this->getPostOrConfig('payxpert_merchant_notifications_lang');
    $this->data['payxpert_merchant_notifications_langs'] = ControllerPaymentPayxpert::$c2p_langs;

    if (version_compare(VERSION, "1.5.0", "<")) {
      $this->template = 'payment/payxpert_1.4.x.tpl';
    } else {
      $this->template = 'payment/payxpert_1.5.x.tpl';
    }

    $content = '';
    if (version_compare(VERSION, "2.0.0", "<")) {
      $this->children = array(
          'common/header',
          'common/footer'
      );
      $content = (version_compare(VERSION, "1.5", "<")) ? $this->render(true) : $this->render();
    } else {
      $this->data['header'] = $this->load->controller('common/header');
      $this->data['column_left'] = $this->load->controller('common/column_left');
      $this->data['footer'] = $this->load->controller('common/footer');
      $content = $this->load->view('payment/payxpert_2.x.tpl', $this->data);
    }

    $this->response->setOutput($content, $this->config->get('config_compression'));
  }

  private function getPostOrConfig($key) {
    return (isset($this->request->post[$key])) ? $this->request->post[$key] : $this->config->get($key);
  }

  private function validate() {
    $valid = true;

    if (!$this->user->hasPermission('modify', 'payment/payxpert')) {
      $this->data['error_warning'] = $this->language->get('error_permission');
      $valid = false;
    }

    $originator = trim($this->request->post['payxpert_originator']);
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

    if (isset($this->request->post['payxpert_merchant_notifications'])) {
      // Validate merchant notifications status
      $notifStatus = $this->request->post['payxpert_merchant_notifications'];
      if (!in_array($notifStatus, ControllerPaymentPayxpert::$merchant_notif_status)) {
        $this->data['payxpert_merchant_notifications_error'] = $this->language->get('entry_merchant_notifications_error');
        $valid = false;
      } else {
        $notificationRecipient = $this->request->post['payxpert_merchant_notifications_to'];
        if (!isset($notificationRecipient) || empty($notificationRecipient)) {
          if ($notifStatus == ControllerPaymentPayxpert::MERCHANT_NOTIF_ENABLED) {
            $this->data['payxpert_merchant_notifications_to_error'] = $this->language->get('entry_merchant_notifications_to_error_req');
            $valid = false;
          }
        } else if (!preg_match('/^[^@]+@[^@]+$/', $notificationRecipient)) {
          $this->data['payxpert_merchant_notifications_to_error'] = $this->language->get('entry_merchant_notifications_to_error_syn');
          $valid = false;
        }
        $notificationLang = $this->request->post['payxpert_merchant_notifications_lang'];
        if (!in_array($notificationLang, ControllerPaymentPayxpert::$c2p_langs)) {
          $this->data['payxpert_merchant_notifications_lang_error'] = $this->language->get('entry_merchant_notifications_lang_error');
          $valid = false;
        }
      }
    }

    return $valid;
  }

  private function getLink($route, $args = '', $connection = 'NONSSL') {
    if (version_compare(VERSION, "2.0", ">=")) {
      $connection = ($connection == 'NONSSL') ? false : true;
    }

    if (version_compare(VERSION, "1.5.0", "<")) {
      if ($connection == 'NONSSL') {
        $url = HTTP_SERVER;
      } else {
        $url = HTTPS_SERVER;
      }

      $url .= 'index.php?route=' . $route;

      if ($args) {
        $url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
      }
      return $url;
    } else {
      return $this->url->link($route, $args, $connection);
    }
  }
}
?>