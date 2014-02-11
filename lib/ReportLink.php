<?php
/**
 * ReportLink class file.
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
   *   \see setServer()
   */
  public function __construct($agentcode, $password, $serverid = 'NA') {
    parent::__construct($agentcode, $password, $serverid);
    $this->endpoint = '/NetGate/ReportLink.asmx?WSDL';
  }

  /**
   * Get ACH / EFT Bank Reconciliation CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *
   * @code
   *   $request = array(
   *     'fromDate' => 946684800,
   *     'toDate' => 946771200,
   *     'currency' => 'USD',
   *     'summaryOnly' => FALSE,
   *     'customerIPAddress' => '',
   *   );
   * @endcode
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getACHEFTBankReconciliationReportCSV($parameters) {
    $this->restrictedservers = array('UK');
    $response = $this->apiCall('GetACHEFTBankReconciliationReportCSVV1', $parameters);
    return $this->responseHandler($response, 'GetACHEFTBankReconciliationReportCSVV1Response', 'CSV');
  }

  public function getACHEFTJournalCSV($parameters) {

  }

  public function GetACHEFTJournal($parameters) {

  }

  public function getACHEFTPaymentBoxJournalCSV($parameters) {

  }

  public function getACHEFTPaymentBoxRejectCSV($parameters) {

  }

  public function getACHEFTRejectCSV($parameters) {

  }

  public function getACHEFTReject($parameters) {

  }

  public function getACHEFTReturnCSV($parameters) {

  }

  public function getACHEFTReturn($parameters) {

  }

  public function getACHJournalCSV($parameters)
  {

  }

  public function getCCJournalCSV($parameters) {

  }

  public function getCCPaymentBoxJournalCSV($parameters) {

  }

  public function getCreditCardBankReconciliationReportCSV($parameters) {

  }

  public function getCreditCardJournalCSV($parameters) {

  }

  public function getCreditCardJournal($parameters) {

  }

  public function getCreditCardPaymentBoxJournalCSV($parameters) {

  }

  public function getCreditCardPaymentBoxRejectCSV($parameters) {

  }

  /**
   * Get Credit Card Reject report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *
   * @code
   *   array(
   *     'customerIPAddress' => '',
   *     'date' => 946771200,
   *   );
   * @endcode
   *
   * @return mixed
   *   Report array or API error.
   */
  public function getCreditCardReject($parameters) {
    $response = $this->apiCall('GetCreditCardReject', $parameters);
    return $this->responseHandler($response, 'GetCreditCardRejectV1Result', 'AR');
  }

  /**
   * Get Credit Card Reject CSV report.
   *
   * @param array $parameters
   *   An associative array with the following possible values.
   *
   * @code
   *   $request = array(
   *     'customerIPAddress' => '',
   *     'date' => 946771200,
   *   );
   * @endcode
   *
   * @return mixed
   *   Report CSV (string) or API error.
   */
  public function getCreditCardRejectCSV($parameters) {
    $this->restrictedservers = array('UK');
    $response = $this->apiCall('GetCreditCardRejectCSV', $parameters);
    return $this->responseHandler($response, 'GetCreditCardRejectCSVV1Result', 'CSV');
  }

  /**
   * Response Handler for ReportLink calls.
   *
   * @param array $response
   *   Response
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
        $xml_element = new \SimpleXMLElement($return);
        return base64_decode($xml_element->FILE);
    }
  }

}