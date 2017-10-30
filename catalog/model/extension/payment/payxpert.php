<?php

class ModelExtensionPaymentPayxpert extends Model {

  public function getMethod($address) {
    $pPrefix = 'payment_';
    if (version_compare(VERSION, "3.0", "<")) {
      $pPrefix = '';
    }

    $this->load->language('extension/payment/payxpert');

    if ($this->config->get($pPrefix . 'payxpert_status') == 1) {

      $query = $this->db->query(
          "SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get($pPrefix . 'payxpert_geo_zone_id') . "' AND country_id = '" .
               (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

      if (!$this->config->get($pPrefix . 'payxpert_geo_zone_id')) {
        $status = TRUE;
      } elseif ($query->num_rows) {
        $status = TRUE;
      } else {
        $status = FALSE;
      }
    } else {
      $status = FALSE;
    }

    $method_data = array();

    if ($status) {
      $method_data = array(
          'code' => 'payxpert',
          'title' => $this->language->get('text_title'),
          'sort_order' => $this->config->get($pPrefix . 'payxpert_sort_order'),
          'terms' => '' /* Avoids a notice */
      );
    }

    return $method_data;
  }
}
?>