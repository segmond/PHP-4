<?php
/**
 * ReportLink class file.
 *
 * The ReportLink service is used to generate reports based on transactions
 * completed through the other iATS services.
 *
 * Reports include credit / debit card transactions, rejected transactions
 * and returns.
 *
 * Reports may be generated in either XML or CSV.
 *
 * Service guide: http://home.iatspayments.com/sites/default/files/iats_webservices_reportlink_version_4.0.pdf
 * API documentation: https://www.iatspayments.com/NetGate/ReportLink.asmx
 * Note: API methods with responses containing the string "x0020" are
 * depreciated and not supported by this class.
 */

namespace iATS;

/**
 * Class ReportLink
 *
 * @package iATS
 */
class ReportLink extends Core {

  /**
   * ReportLink constructor.
   *
   * @param string $agentcode
   *   iATS account agent code.
   * @param string $password
   *   iATS account password.
   * @param string $serverid
   *   Server identifier (Defaults to 'NA').
   *   @see setServer()
   */
  public function __construct($agentcode, $password, $serverid = 'NA') {
    parent::__construct($agentcode, $password, $serverid);
    $this->endpoint = '/NetGate/ReportLinkv2.asmx?WSDL';
  }

  /**
   * Get ACH / EFT Bank Reconciliation CSV report.
   * Provides a report of the bank balance of ACHEFT transactions.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'fromDate' => '2014-07-23T00:00:00+00:00' // The earliest date to gather report data for.
   *     'toDate' => '2024-07-23T23:59:59+00:00' // The latest date to gather report data for.
   *     'currency' => 'USD' // The currency to represent financial data as.
   *      // North America options: CAD, USD
   *      // UK options: USD, EUR, GBP, IEE, CHF, HKD, JPY, SGD, MXN
   *     'summaryOnly' => FALSE // True when a summerized report is required.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getACHEFTBankReconciliationReportCSV($parameters) {
    $response = $this->apiCall('GetACHEFTBankReconciliationReportCSV', $parameters);
    return $this->responseHandler($response, 'GetACHEFTBankReconciliationReportCSVResult', 'CSV');
  }

  /**
   * Get ACH / EFT approved transactions CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getACHEFTJournalCSV($parameters) {
    $response = $this->apiCall('GetACHEFTJournalCSV', $parameters);
    return $this->responseHandler($response, 'GetACHEFTJournalCSVResult', 'CSV');
  }

  /**
   * Get ACH / EFT approved transactions report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report array or API error.
   */
  public function GetACHEFTJournal($parameters) {
    $response = $this->apiCall('GetACHEFTJournal', $parameters);
    return $this->responseHandler($response, 'GetACHEFTJournalResult', 'AR');
  }

  /**
   * Get ACH/EFT Payment Box approved transactions CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'fromDate' => '2014-07-23T00:00:00+00:00' // The earliest date to gather report data for.
   *     'toDate' => '2024-07-23T23:59:59+00:00' // The latest date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getACHEFTPaymentBoxJournalCSV($parameters) {
    $response = $this->apiCall('GetACHEFTPaymentBoxJournalCSVV2', $parameters);
    return $this->responseHandler($response, 'GetACHEFTPaymentBoxJournalCSVV2Result', 'CSV');
  }

  /**
   * Get ACH / EFT Payment Box Reject CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'fromDate' => '2014-07-23T00:00:00+00:00' // The earliest date to gather report data for.
   *     'toDate' => '2024-07-23T23:59:59+00:00' // The latest date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getACHEFTPaymentBoxRejectCSV($parameters) {
    $response = $this->apiCall('GetACHEFTPaymentBoxRejectCSV', $parameters);
    return $this->responseHandler($response, 'GetACHEFTPaymentBoxRejectCSVResult', 'CSV');
  }

  /**
   * Get ACH / EFT Reject CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getACHEFTRejectCSV($parameters) {
    $response = $this->apiCall('GetACHEFTRejectCSV', $parameters);
    return $this->responseHandler($response, 'GetACHEFTRejectCSVResult', 'CSV');
  }

  /**
   * Get ACH / EFT Reject report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   * @endcode
   *
   * @return mixed
   *   Report array or API error.
   */
  public function getACHEFTReject($parameters) {
    $response = $this->apiCall('GetACHEFTReject', $parameters);
    return $this->responseHandler($response, 'GetACHEFTRejectResult', 'AR');
  }

  /**
   * Get ACH / EFT Return CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getACHEFTReturnCSV($parameters) {
    $response = $this->apiCall('GetACHEFTReturnCSV', $parameters);
    return $this->responseHandler($response, 'GetACHEFTReturnCSVResult', 'CSV');
  }

  /**
   * Get ACH / EFT Return report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report array or API error.
   */
  public function getACHEFTReturn($parameters) {
    $response = $this->apiCall('GetACHEFTReturn', $parameters);
    return $this->responseHandler($response, 'GetACHEFTReturnResult', 'AR');
  }

  /**
   * Get Credit Card Bank Reconciliation CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'fromDate' => '2014-07-23T00:00:00+00:00' // The earliest date to gather report data for.
   *     'toDate' => '2024-07-23T23:59:59+00:00' // The latest date to gather report data for.
   *     'currency' => 'USD' // The currency to represent financial data as.
   *      // North America options: CAD, USD
   *      // UK options: USD, EUR, GBP, IEE, CHF, HKD, JPY, SGD, MXN
   *     'summaryOnly' => FALSE // True when a summerized report is required.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getCreditCardBankReconciliationReportCSV($parameters) {
    $response = $this->apiCall('GetCreditCardBankReconciliationReportCSV', $parameters);
    return $this->responseHandler($response, 'GetCreditCardBankReconciliationReportCSVResult', 'CSV');
  }

  /**
   * Get Credit Card approved transactions CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getCreditCardJournalCSV($parameters) {
    $response = $this->apiCall('GetCreditCardJournalCSV', $parameters);
    return $this->responseHandler($response, 'GetCreditCardJournalCSVResult', 'CSV');
  }

  /**
   * Get Credit Card approved transactions report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report array or API error.
   */
  public function getCreditCardJournal($parameters) {
    $response = $this->apiCall('GetCreditCardJournal', $parameters);
    return $this->responseHandler($response, 'GetCreditCardJournalResult', 'AR');
  }

  /**
   * Get Credit Card Payment Box approved transactions CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'fromDate' => '2014-07-23T00:00:00+00:00' // The earliest date to gather report data for.
   *     'toDate' => '2024-07-23T23:59:59+00:00' // The latest date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getCreditCardPaymentBoxJournalCSV($parameters) {
    $response = $this->apiCall('GetCreditCardPaymentBoxJournalCSV', $parameters);
    return $this->responseHandler($response, 'GetCreditCardPaymentBoxJournalCSVResult', 'CSV');
  }

  /**
   * Get Credit Card Payment Box Reject CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'fromDate' => '2014-07-23T00:00:00+00:00' // The earliest date to gather report data for.
   *     'toDate' => '2024-07-23T23:59:59+00:00' // The latest date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getCreditCardPaymentBoxRejectCSV($parameters) {
    $response = $this->apiCall('GetCreditCardPaymentBoxRejectCSV', $parameters);
    return $this->responseHandler($response, 'GetCreditCardPaymentBoxRejectCSVResult', 'CSV');
  }

  /**
   * Get Credit Card Reject report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report array or API error.
   */
  public function getCreditCardReject($parameters) {
    $response = $this->apiCall('GetCreditCardReject', $parameters);
    return $this->responseHandler($response, 'GetCreditCardRejectResult', 'AR');
  }

  /**
   * Get Credit Card Reject CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *     'date' => '2014-07-23T00:00:00+00:00' // The date to gather report data for.
   *     'customerIPAddress' => '' // Optional. The client's IP address.
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getCreditCardRejectCSV($parameters) {
    $response = $this->apiCall('GetCreditCardRejectCSV', $parameters);
    return $this->responseHandler($response, 'GetCreditCardRejectCSVResult', 'CSV');
  }

  /**
   * Response Handler for ReportLink calls.
   *
   * @param object $response
   *   SOAP response
   * @param string $result
   *   Result string
   * @param string  $format
   *   Output format.
   *   'AR' will return array(),
   *   'CSV' will return a comma delimited data string with headers.
   *
   * @return mixed
   *   Response
   */
  public function responseHandler($response, $result, $format) {
    $return = $response->$result->any;
    switch ($format) {
      case 'AR':
        $result = $this->xml2array($response->$result->any);
        if ($result['STATUS'] == 'Failure') {
          $resp = 'Bad Credentials';
        }
        else {
          if (isset($result['JOURNALREPORT']['TN'])) {
            $resp = $result['JOURNALREPORT']['TN'];
          }
          else {
            $resp = 'No data returned for this date';
          }

        }
        return $resp;

      case 'CSV':
        if ($return != null)
        {
          $xml_element = new \SimpleXMLElement($return);
          return base64_decode($xml_element->FILE);
        }
        // Account for null being returned in a CSV request.
        return '';
    }
  }

}
