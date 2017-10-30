<?php
/**
 * Client class for the connect2pay payment page.
 *
 * PHP dependencies:
 * PHP >= 5.2.0
 *
 * @version 1.1 (20160630)
 * @author Regis Vidal <regis.vidal@baian.com>
 * @author JsH <jsh@payxpert.com>
 * @copyright 2012-2016 Digital Media World
 *
 */
require_once (dirname(__FILE__) . "/payxpert_connect2/Connect2PayClient.php");

class ControllerPaymentPayxpert extends Controller {
  private $payxpertDefaultGatewayUrl = "https://connect2.payxpert.com";
  protected $data = array();

  public function index() {
    $this->data['button_confirm'] = $this->language->get('button_confirm');
    $this->data['button_back'] = $this->language->get('button_back');

    $this->data['action'] = $this->getLink('payment/payxpert/paymentredirect', '', 'SSL');

    $this->load->model('checkout/order');

    $this->id = 'payment';
    if (version_compare(VERSION, "1.5.0", "<")) {
      $templateFile = "payxpert_1.4.x.tpl";
      if ($this->request->get['route'] != 'checkout/guest_step_3') {
        $this->data['back'] = $this->getLink('checkout/payment', '', 'SSL');
      } else {
        $this->data['back'] = $this->getLink('checkout/guest_step_2', '', 'SSL');
      }
    } else if (version_compare(VERSION, "1.5.2", "<")) {
      $templateFile = "payxpert_1.5.1.tpl";
    } else if (version_compare(VERSION, "2.0", "<")) {
      $templateFile = "payxpert_1.5.x.tpl";
    } else {
      $templateFile = "payxpert_2.x.tpl";
    }

    return $this->renderCompat($templateFile, $this->data, false);
  }

  public function paymentredirect() {
    $this->load->model('checkout/order');

    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    $OrderLine = '';

    if (isset($order_info['currency_code'])) {
      $keyCurrency = 'currency_code';
      $keyValue = 'currency_value';
    } else {
      $keyCurrency = 'currency';
      $keyValue = 'value';
    }

    foreach ($this->cart->getProducts() as $product) {
      $OrderLine .= $product['name'] . " (" . $product['model'] . ") x " . $product['quantity'] . "\n";
    }

    $c2pClient = new Connect2PayClient($this->getGatewayUrl(), $this->config->get('payxpert_originator'), $this->getApiKey());

    $c2pClient->setOrderID($order_info['order_id']);
    $c2pClient->setPaymentType(Connect2PayClient::_PAYMENT_TYPE_CREDITCARD);
    $c2pClient->setPaymentMode(Connect2PayClient::_PAYMENT_MODE_SINGLE);
    $c2pClient->setShopperID($order_info['customer_id']);
    $c2pClient->setShippingType(Connect2PayClient::_SHIPPING_TYPE_VIRTUAL);
    $c2pClient->setAmount($this->currency->format($order_info['total'], $order_info[$keyCurrency], $order_info[$keyValue], false) * 100);
    $c2pClient->setOrderDescription($OrderLine);
    $c2pClient->setCurrency($order_info[$keyCurrency]);

    $c2pClient->setShopperFirstName(html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8'));
    $c2pClient->setShopperLastName(html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8'));
    $c2pClient->setShopperAddress(trim(html_entity_decode($order_info['payment_address_1'] . " " . $order_info['payment_address_2'], ENT_QUOTES, 'UTF-8')));
    $c2pClient->setShopperZipcode(html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8'));
    $c2pClient->setShopperCity(html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8'));
    $c2pClient->setShopperState(html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8'));
    $c2pClient->setShopperCountryCode($order_info['payment_iso_code_2']);
    $c2pClient->setShopperPhone(html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8'));
    $c2pClient->setShopperEmail(html_entity_decode($order_info['email'], ENT_QUOTES, 'UTF-8'));
    $c2pClient->setCtrlRedirectURL($this->getLink('payment/payxpert/returnpayment', '', 'SSL'));
    $c2pClient->setCtrlCallbackURL($this->getLink('payment/payxpert/callback', '', 'SSL'));
    if ($this->config->get('payxpert_3dsecure')) {
      $c2pClient->setSecure3d(true);
    }

    // Merchant notifications
    $merchantNotifications = $this->config->get('payxpert_merchant_notifications');
    if (isset($merchantNotifications) && $merchantNotifications != null) {
      if ($merchantNotifications == 'enabled') {
        $c2pClient->setMerchantNotification(true);
        $c2pClient->setMerchantNotificationTo($this->config->get('payxpert_merchant_notifications_to'));
        $c2pClient->setMerchantNotificationLang($this->config->get('payxpert_merchant_notifications_lang'));
      } else if ($merchantNotifications == 'disabled') {
        $c2pClient->setMerchantNotification(false);
      }
    }

    // ctrlCustomData used to validate during callback
    $ctrlCustomData = $this->getOrderIdentStringHash($order_info);
    $c2pClient->setCtrlCustomData($ctrlCustomData);

    if ($c2pClient->validate()) {
      if ($c2pClient->prepareTransaction()) {
        $this->session->data['payxpert_MerchantToken'] = $c2pClient->getMerchantToken();
        $this->redirectCompat($c2pClient->getCustomerRedirectURL());
      } else {
        $message = "PayXpert payment module (paymentredirect):\n Error in prepareTransaction: \n";
        $message .= "Order id:" . $order_info['order_id'] . "\n";
        $message .= "Result code:" . $c2pClient->getReturnCode() . "\n";
        $message .= "Preparation error occurred: " . $c2pClient->getClientErrorMessage() . "\n";
        $this->displayFailure($message);
      }
    } else {
      $message = "PayXpert payment module (paymentredirect):\n Error in validate function: \n";
      $message .= "Order id:" . $order_info['order_id'] . "\n";
      $message .= "Validation error occurred: " . $c2pClient->getClientErrorMessage() . "\n";
      $this->displayFailure($message);
    }
  }

  public function returnpayment() {
    $this->load->model('checkout/order');

    $this->load->language('payment/payxpert');

    $merchantToken = $this->session->data['payxpert_MerchantToken'];
    $data = $this->request->post["data"];
    $customer = $this->request->post["customer"];
    $success = false;
    $message = "Unknow error";

    // Setup the connection and redirect Status
    $c2pClient = new Connect2PayClient($this->getGatewayUrl(), $this->config->get('payxpert_originator'), $this->getApiKey());
    if ($c2pClient->handleRedirectStatus($data, $merchantToken)) {
      $status = $c2pClient->getStatus();
      $order_id = $status->getOrderID();

      $message = "PayXpert payment module:\n";
      $message .= "Received a new transaction status from returnpayment.\n";
      $message .= "Error code: " . $c2pClient->getStatus()->getErrorCode() . "\n";
      $message .= "Error message: " . $status->getErrorMessage() . "\n";
      $message .= "Transaction ID: " . $status->getTransactionID() . "\n";
      $message .= "Order ID: " . $order_id . "\n";

      $order_info = $this->model_checkout_order->getOrder($order_id);
      if ($order_info) {
        if ($status->getErrorCode() == 0) {
          if (version_compare(VERSION, "2.0", "<")) {
            $this->model_checkout_order->confirm($order_id, $this->config->get('payxpert_order_status_id'));
            $this->model_checkout_order->update($order_id, $this->config->get('payxpert_order_status_id'), $message, false);
          } else {
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payxpert_order_status_id'), '', true);
          }
          $success = true;
        } else {
          if (version_compare(VERSION, "2.0", "<")) {
            $this->model_checkout_order->confirm($order_id, $this->config->get('payxpert_order_denied_status_id'));
            $this->model_checkout_order->update($order_id, $this->config->get('payxpert_order_denied_status_id'), $message, false);
          } else {
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payxpert_order_denied_status_id'), '', true);
          }
        }
        $this->debugLog($message);
      } else {
        $this->debugLog("PayXpert payment module (returnpayment): Unable to get order information: " . $order_id);
      }
    } else {
      $this->debugLog("PayXpert payment module (returnpayment): Unable to handleRedirectStatus with merchantToken: " . $merchantToken);
    }

    if ($success) {
      $this->redirectCompat($this->getLink('checkout/success', '', 'SSL'));
    } else {
      $this->displayFailure($this->language->get('error_declined'), 5);
    }
  }

  public function callback() {
    $this->load->model('checkout/order');

    $c2pClient = new Connect2PayClient($this->getGatewayUrl(), $this->config->get('payxpert_originator'), $this->getApiKey());
    if ($c2pClient->handleCallbackStatus()) {
      $status = $c2pClient->getStatus();
      $order_id = $status->getOrderID();

      $message = "PayXpert payment module:\n";
      $message .= "Received a new transaction status callback from " . $_SERVER["REMOTE_ADDR"] . ".\n";
      $message .= "Error code: " . $c2pClient->getStatus()->getErrorCode() . "\n";

      if ($status->getErrorCode() >= 0) {
        $message .= "Error message: " . $status->getErrorMessage() . "\n";
        $message .= "Transaction ID: " . $status->getTransactionID() . "\n";
      }

      $message .= "Order ID: " . $order_id . "\n";

      $order_info = $this->model_checkout_order->getOrder($order_id);
      if ($order_info) {
        // Validate the call with ctrlCustomData
        $callValid = false;
        $hash = $this->getOrderIdentStringHash($order_info);
        if ($hash !== $status->getCtrlCustomData()) {
          $message .= "Callback validation failed. Expected '" . $hash . "' but got '" . $status->getCtrlCustomData() . "'\n";
        } else {
          $message .= "Callback validation success.\n";
          $callValid = true;
        }

        if ($callValid) {
          // Transaction error code
          if ($status->getErrorCode() == 0) {
            if (version_compare(VERSION, "2.0", "<")) {
              $this->model_checkout_order->confirm($order_id, $this->config->get('payxpert_order_status_id'));
              $this->model_checkout_order->update($order_id, $this->config->get('payxpert_order_status_id'), $message, false);
            } else {
              $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payxpert_order_status_id'), '', true);
            }
          } else {
            if (version_compare(VERSION, "2.0", "<")) {
              $this->model_checkout_order->confirm($order_id, $this->config->get('payxpert_order_denied_status_id'));
              $this->model_checkout_order->update($order_id, $this->config->get('payxpert_order_denied_status_id'), $message, false);
            } else {
              $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payxpert_order_denied_status_id'), '', true);
            }
          }

          $this->sendCallbackSuccess();
        } else {
          $this->sendCallbackError();
        }

        $this->debugLog($message);
      } else {
        $this->debugLog("PayXpert payment module (callback): Unable to get order information: " . $order_id);
        $this->sendCallbackError();
      }
    } else {
      $this->debugLog("PayXpert payment module (callback): Received an incorrect status from " . $_SERVER["REMOTE_ADDR"]);
      $this->sendCallbackError();
    }
  }

  private function displayFailure($message, $timeout = "10") {
    if ($this->config->get('payxpert_debuglog')) {
      $log = $this->registry->get('log');
      if (is_object($log)) {
        $log->write(str_replace("\n", ", ", $message));
      }
    }
    if (version_compare(VERSION, "1.5.0", "<")) {
      $cartRoute = 'checkout/payment';
    } else {
      $cartRoute = 'checkout/checkout';
    }

    if ($this->config->get('payxpert_failure_page') > 0) {
      $this->language->load('payment/payxpert');

      $this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

      if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
        $this->data['base'] = HTTP_SERVER;
      } else {
        $this->data['base'] = HTTPS_SERVER;
      }

      $this->data['charset'] = $this->language->get('charset');
      $this->data['language'] = $this->language->get('code');
      $this->data['direction'] = $this->language->get('direction');

      $this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

      $this->data['text_response'] = $this->language->get('text_response');
      $this->data['text_failure'] = nl2br($message);

      $this->data['timeout'] = $timeout * 1000;

      $this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $timeout, $this->getLink($cartRoute, '', 'SSL'));

      $this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/cart';

      $this->response->setOutput($this->renderCompat('payxpert_failure.tpl', $this->data), $this->config->get('config_compression'));
    } else {
      $this->redirectCompat($this->getLink($cartRoute, '', 'SSL'));
    }
  }

  /**
   * Send a response to mark this transaction as notified successfully
   */
  private function sendCallbackSuccess() {
    $response = array(
        "status" => "OK",
        "message" => "Status recorded"
    );
    header("Content-type: application/json");
    echo json_encode($response);
  }

  /**
   * Send a response to warn about a notification error
   */
  private function sendCallbackError() {
    $response = array(
        "status" => "KO",
        "message" => "Error handling the callback"
    );
    header("Content-type: application/json");
    echo json_encode($response);
  }

  private function getOrderIdentStringHash($order) {
    return hash('sha256',
        $this->getApiKey() . '**' . $order['ip'] . '**' . $order['date_added'] . '**' . $order['total'] . '**' . $order['store_id'] . '**' .
             $order['customer_id'] . '**' . $order['order_id'] . '**' . $this->getApiKey());
  }

  private function renderCompat($templateFile, $data, $return = true) {
    // Resolve name according to version
    if (version_compare(VERSION, "2.2", "<")) {
      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/' . $templateFile)) {
        $this->template = $this->config->get('config_template') . '/template/payment/' . $templateFile;
      } else {
        $this->template = 'default/template/payment/' . $templateFile;
      }
    } else {
      $this->template = 'payment/' . $templateFile;
    }

    if (version_compare(VERSION, "1.5", "<")) {
      return $this->render($return);
    } else if (version_compare(VERSION, "2.0", "<")) {
      return $this->render();
    } else {
      return $this->load->view($this->template, $this->data);
    }
  }

  private function redirectCompat($url) {
    if (version_compare(VERSION, "1.5.0", "<")) {
      $this->redirect($url);
    } else {
      $this->response->redirect($url);
    }
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

  private function getGatewayUrl() {
    $url = ltrim(trim($this->config->get('payxpert_connect2pay_url')), "/");
    if (!empty($url)) {
      return $url;
    }
    return $this->payxpertDefaultGatewayUrl;
  }

  private function getApiKey() {
    return html_entity_decode($this->config->get('payxpert_password'), ENT_QUOTES, 'UTF-8');
  }

  private function debugLog($message) {
    if ($this->config->get('payxpert_debuglog')) {
      $log = $this->registry->get('log');
      if (is_object($log)) {
        $log->write($message);
      }
    }
  }
}
?>