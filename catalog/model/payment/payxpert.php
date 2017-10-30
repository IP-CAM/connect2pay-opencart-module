<?php

class ModelPaymentPayxpert extends Model {

  public function getMethod($address) {
    $this->load->language('payment/payxpert');

    if ($this->config->get('payxpert_status')) {

      $query = $this->db->query(
          "SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('payxpert_geo_zone_id') . "' AND country_id = '" .
               (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

      if (!$this->config->get('payxpert_geo_zone_id')) {
        $status = TRUE;
      } elseif ($query->num_rows) {
        $status = TRUE;
      } else {
        $status = FALSE;
      }
    } else {
      $status = FALSE;
    }

    if (version_compare(VERSION, "1.5.0", "<")) {
      $id = "id";
    } else {
      $id = "code";
    }

    $method_data = array();

    if ($status) {
      $method_data = array(
          $id => 'payxpert',
          'title' => $this->language->get('text_title'),
          'sort_order' => $this->config->get('payxpert_sort_order'),
          'terms' => '' /* Avoids a notice */
      );
    }

    return $method_data;
  }
}
?>