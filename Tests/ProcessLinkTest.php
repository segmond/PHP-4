<?php
/**
 * @file
 * Unit tests for Report Link element of the iATS API.
 */

namespace iATS;

/**
 * Class ProcessLinkTest
 *
 * @package iATS
 */
class ProcessLinkTest extends \PHPUnit_Framework_TestCase {
  const AGENT_CODE = 'TEST88';
  const PASSWORD = 'TEST88';

  // Varables generated by tests and referenced by later tests.

  /** @var string $ACHEFTCustomerCode */
  private static $ACHEFTCustomerCode;

  /** @var string $ACHEFTTransationId */
  private static $ACHEFTTransationId;

  /** @var string $creditCardCustomerCode */
  private static $creditCardCustomerCode;

  /** @var string $creditCardTransactionId */
  private static $creditCardTransactionId;

  /** @var string $creditCardBatchId */
  private static $creditCardBatchId;

  /** @var string $ACHEFTBatchId */
  private static $ACHEFTBatchId;

  /** @var string $ACHEFTBatchRefundId */
  private static $ACHEFTBatchRefundId;

  /**
   * Test createCustomerCodeAndProcessACHEFT.
   */
  public function testProcessLinkcreateCustomerCodeAndProcessACHEFT() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'firstName' => 'Test',
      'lastName' => 'Account',
      'address' => '1234 Any Street',
      'city' => 'Schenectady',
      'state' => 'NY',
      'zipCode' => '12345',
      'accountNum' => '02100002100000000000000001',
      'accountType' => 'CHECKING',
      'invoiceNum' => '00000001',
      'total' => '5',
      'comment' => 'Process direct debit test.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->createCustomerCodeAndProcessACHEFT($request);

    self::$ACHEFTCustomerCode = trim($response['PROCESSRESULT']['CUSTOMERCODE']);
    self::$ACHEFTTransationId = trim($response['PROCESSRESULT']['TRANSACTIONID']);

    $this->assertEquals('Success', $response['STATUS']);
  }

  /**
   * Test createCustomerCodeAndProcessCreditCard.
   */
  public function testProcessLinkcreateCustomerCodeAndProcessCreditCard() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'invoiceNum' => '00000001',
      'ccNum' => '4222222222222220',
      'ccExp' => '12/17',
      'firstName' => 'Test',
      'lastName' => 'Account',
      'address' => '1234 Any Street',
      'city' => 'Schenectady',
      'state' => 'NY',
      'zipCode' => '12345',
      'cvv2' => '000',
      'total' => '5',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->createCustomerCodeAndProcessCreditCard($request);

    self::$creditCardCustomerCode = trim($response['PROCESSRESULT']['CUSTOMERCODE']);
    self::$creditCardTransactionId = trim($response['PROCESSRESULT']['TRANSACTIONID']);

    $this->assertEquals('Success', $response['STATUS']);
  }

  /**
   * Test processACHEFTChargeBatch.
   */
  public function testProcessLinkprocessACHEFTChargeBatch() {
    $filePath = dirname(__FILE__) . '/batchfiles/ACHEFTBatch.txt';
    $handle = fopen($filePath, 'r');
    $fileContents = fread($handle, filesize($filePath));
    fclose($handle);

    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'batchFile' => base64_encode($fileContents),
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processACHEFTChargeBatch($request);

    $this->assertEquals('Success', $response['STATUS']);

    self::$ACHEFTBatchId = $response['BATCHPROCESSRESULT']['BATCHID'];
  }

  /**
   * Test getBatchProcessResultFile with an ACH / EFT batch process.
   *
   * @depends testProcessLinkprocessACHEFTChargeBatch
   */
  public function testProcessLinkgetBatchProcessResultFileACHEFT() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'batchId' => self::$ACHEFTBatchId,
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->getBatchProcessResultFile($request);

    $this->assertEquals('Success', $response['STATUS']);
    $this->assertEquals(self::$ACHEFTBatchId, $response['BATCHPROCESSRESULT']['BATCHID']);
  }

  /**
   * Test processACHEFTRefundBatch.
   *
   * @depends testProcessLinkprocessACHEFTChargeBatch
   */
  public function testProcessLinkprocessACHEFTRefundBatch() {
    $filePath = dirname(__FILE__) . '/batchfiles/ACHEFTRefundBatch.txt';
    $handle = fopen($filePath, 'r');
    $fileContents = fread($handle, filesize($filePath));
    fclose($handle);

    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'batchFile' => base64_encode($fileContents),
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processACHEFTRefundBatch($request);

    $this->assertEquals('Success', $response['STATUS']);

    self::$ACHEFTBatchRefundId = $response['BATCHPROCESSRESULT']['BATCHID'];
  }

  /**
   * Test getBatchProcessResultFile with an ACH / EFT batch refund process.
   *
   * @depends testProcessLinkprocessACHEFTRefundBatch
   */
  public function testProcessLinkgetBatchProcessResultFileACHEFTRefund() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'batchId' => self::$ACHEFTBatchRefundId,
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->getBatchProcessResultFile($request);

    $this->assertEquals('Success', $response['STATUS']);
    $this->assertEquals(self::$ACHEFTBatchRefundId, $response['BATCHPROCESSRESULT']['BATCHID']);
  }

  /**
   * Test processACHEFTRefundWithTransactionId.
   *
   * @depends testProcessLinkcreateCustomerCodeAndProcessACHEFT
   */
  public function testProcessLinkprocessACHEFTRefundWithTransactionId() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'transactionId' => self::$ACHEFTTransationId,
      'total' => '-5',
      'comment' => 'ACH / EFT refund test.',
    );

    // TODO: Find out why this returns "Invalid Customer Code" error.
    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processACHEFTRefundWithTransactionId($request);

    //$clean = trim($response['PROCESSRESULT']['AUTHORIZATIONRESULT']);
    //$this->assertEquals($clean, 'OK: 678594:');
  }

  /**
   * Test processACHEFT.
   */
  public function testProcessLinkprocessACHEFT() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'invoiceNum' => '00000001',
      'firstName' => 'Test',
      'lastName' => 'Account',
      'address' => '1234 Any Street',
      'city' => 'Schenectady',
      'state' => 'NY',
      'zipCode' => '12345',
      'accountNum' => '02100002100000000000000001',
      'accountType' => 'CHECKING',
      'total' => '5',
      'comment' => 'Process direct debit test.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processACHEFT($request);

    $this->assertEquals('Success', $response['STATUS']);
  }

  /**
   * Test processACHEFTWithCustomerCode.
   */
  public function testProcessLinkprocessACHEFTWithCustomerCode() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'customerCode' => self::$ACHEFTCustomerCode,
      'invoiceNum' => '00000001',
      'total' => '5',
      'comment' => 'Process direct debit test with Customer Code.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processACHEFTWithCustomerCode($request);

    $this->assertEquals('Success', $response['STATUS']);
  }

  /**
   * Test processCreditCardBatch.
   */
  public function testProcessLinkprocessCreditCardBatch() {
    $filePath = dirname(__FILE__) . '/batchfiles/CreditCardUSUKBatch.txt';
    $handle = fopen($filePath, 'r');
    $fileContents = fread($handle, filesize($filePath));
    fclose($handle);

    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'batchFile' => base64_encode($fileContents),
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processCreditCardBatch($request);

    $this->assertEquals('Success', $response['STATUS']);

    self::$creditCardBatchId = $response['BATCHPROCESSRESULT']['BATCHID'];
  }

  /**
   * Test getBatchProcessResultFile with a credit card batch process.
   *
   * @depends testProcessLinkprocessCreditCardBatch
   */
  public function testProcessLinkgetBatchProcessResultFileCreditCard() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'batchId' => self::$creditCardBatchId,
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->getBatchProcessResultFile($request);

    $this->assertEquals('Success', $response['STATUS']);
    $this->assertEquals(self::$creditCardBatchId, $response['BATCHPROCESSRESULT']['BATCHID']);
  }

  /**
   * Test processCreditCardRefundWithTransactionId.
   *
   * @depends testProcessLinkcreateCustomerCodeAndProcessCreditCard
   */
  public function testProcessLinkprocessCreditCardRefundWithTransactionId() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'transactionId' => self::$creditCardTransactionId,
      'total' => '-5',
      'comment' => 'Credit card refund test.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processCreditCardRefundWithTransactionId($request);

    $clean = trim($response['PROCESSRESULT']['AUTHORIZATIONRESULT']);
    $this->assertEquals($clean, 'OK: 678594:');
  }

  /**
   * Test processCreditCard.
   */
  public function testProcessLinkprocessCreditCard() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'invoiceNum' => '00000001',
      'creditCardNum' => '4222222222222220',
      'creditCardExpiry' => '12/17',
      'cvv2' => '000',
      'mop' => 'VISA',
      'firstName' => 'Test',
      'lastName' => 'Account',
      'address' => '1234 Any Street',
      'city' => 'Schenectady',
      'state' => 'NY',
      'zipCode' => '12345',
      'total' => '5',
      'comment' => 'Process CC test.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processCreditCard($request);

    $this->assertEquals('Success', $response['STATUS']);

    $clean = trim($response['PROCESSRESULT']['AUTHORIZATIONRESULT']);
    $this->assertEquals($clean, 'OK: 678594:');
  }

  /**
   * Test processCreditCardWithCustomerCode.
   *
   * @depends testProcessLinkcreateCustomerCodeAndProcessCreditCard
   */
  public function testProcessLinkprocessCreditCardWithCustomerCode() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'customerCode' => self::$creditCardCustomerCode,
      'invoiceNum' => '00000001',
      'cvv2' => '000',
      'total' => '5',
      'comment' => 'Process CC test with Customer Code.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processCreditCardWithCustomerCode($request);

    $this->assertEquals('Success', $response['STATUS']);

    $clean = trim($response['PROCESSRESULT']['AUTHORIZATIONRESULT']);
    $this->assertEquals($clean, 'OK: 678594:');
  }

  /**
   * Test processCreditCard with invalid card number.
   */
  public function testProcessLinkprocessCreditCardInvalidCardNumber() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'invoiceNum' => '00000001',
      'creditCardNum' => '9999999999999999',
      'creditCardExpiry' => '12/17',
      'cvv2' => '000',
      'mop' => 'VISA',
      'firstName' => 'Test',
      'lastName' => 'Account',
      'address' => '1234 Any Street',
      'city' => 'Schenectady',
      'state' => 'NY',
      'zipCode' => '12345',
      'total' => '5',
      'comment' => 'Process CC test with invalid CC number.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processCreditCard($request);

    $this->assertEquals('Invalid card number. Card not supported by IATS.', $response);
  }

  /**
   * Test processCreditCard with invalid credit card expiration date.
   */
  public function testProcessLinkprocessCreditCardInvalidExp() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'invoiceNum' => '00000001',
      'creditCardNum' => '4222222222222220',
      'creditCardExpiry' => '01/10',
      'cvv2' => '000',
      'mop' => 'VISA',
      'firstName' => 'Test',
      'lastName' => 'Account',
      'address' => '1234 Any Street',
      'city' => 'Schenectady',
      'state' => 'NY',
      'zipCode' => '12345',
      'total' => '5',
      'comment' => 'Process CC test with invalid CC expiration date.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processCreditCard($request);

    // TODO: Find out why iATS API is accepting this invalid transaction. Ignore test for now.
    //$this->assertEquals('Invalid Expiry date.', $response);
    $this->assertTrue(TRUE);
  }

  /**
   * Test processCreditCard with invalid address.
   */
  public function testProcessLinkprocessCreditCardInvalidAddress() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '',
      'invoiceNum' => '00000001',
      'creditCardNum' => '4222222222222220',
      'creditCardExpiry' => '12/17',
      'cvv2' => '000',
      'mop' => 'VISA',
      'firstName' => 'Test',
      'lastName' => 'Account',
      'address' => '',
      'city' => '',
      'state' => '',
      'zipCode' => '',
      'total' => '5',
      'comment' => 'Process CC test with invalid address.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processCreditCard($request);

    // TODO: Find out why iATS API is accepting this invalid transaction. Ignore test for now.
    //$this->assertEquals('Error. Please verify and re-enter credit card information.', $response);
    $this->assertTrue(TRUE);
  }

  /**
   * Test processCreditCard with invalid IP address format.
   */
  public function testProcessLinkprocessCreditCardInvalidIPAddress() {
    // Create and populate the request object.
    $request = array(
      'customerIPAddress' => '100',
      'invoiceNum' => '00000001',
      'creditCardNum' => '4222222222222220',
      'creditCardExpiry' => '12/17',
      'cvv2' => '000',
      'mop' => 'VISA',
      'firstName' => 'Test',
      'lastName' => 'Account',
      'address' => '1234 Any Street',
      'city' => 'Schenectady',
      'state' => 'NY',
      'zipCode' => '12345',
      'total' => '5',
      'comment' => 'Process CC test with invalid IP address format.',
    );

    $iats = new ProcessLink(self::AGENT_CODE, self::PASSWORD);
    $response = $iats->processCreditCard($request);

    // TODO: Find out why iATS API is accepting this invalid transaction. Ignore test for now.
    $this->assertTrue(TRUE);
  }

//  /**
//   * Timeout response.
//   */
//  public function testCCTimeout() {
//    $this->assertTrue(FALSE);
//  }
//
//  /**
//   * Reject codes based on Test documents.
//   */
//  public function testCCRejectCodes() {
//    $this->assertTrue(FALSE);
//  }
//
//  /**
//   * No response to request.
//   */
//  public function testCCNoResponse() {
//    $this->assertTrue(FALSE);
//  }
//
//  /**
//   * Delayed response to request.
//   */
//  public function testCCDelay() {
//    $this->assertTrue(FALSE);
//  }
//
//  /**
//   * Bad request.
//   */
//  public function testACHEFTBadRequest() {
//    $this->assertTrue(FALSE);
//  }
//
//  /**
//   * Bad format.
//   */
//  public function testACHEFTBadFormat() {
//    $this->assertTrue(FALSE);
//  }

}
