<?php
require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';
class Mjsi_Orderprint_MultiprintController extends Mage_Adminhtml_Sales_OrderController
	{
	public function massPrintSalesOrdersAction()
		{
		$orderIds = $this->getRequest()->getPost('order_ids');
		$flag = false;
		
		if (!empty($orderIds))
			{
			foreach ($orderIds as $orderId)
				{
				$order = Mage::getModel('sales/order')->load($orderId);
				$order->setOrder($order);
				$order->setEcorpsOrderprint('Yes');
				$order->save();
				$d = Mage::getModel('orderprint/orderprint')->getCollection();
				$d->addFieldToFilter('order_id', $orderId); 
				foreach ($d as $s){}
				if (count($d))
					$orderprint = $s;
				else
					$orderprint = Mage::getModel('orderprint/orderprint');
				$orderprint->setOrderId($orderId);
				$orderprint->setDatePrinted(now());
				$orderprint->save();
				$flag = true;
				if (!isset($pdf))
					{
					if($order->getStatus() == "pending")
						{
						$order->setStatus('processing', true);
						$order->setEcorpsOrderprint('Yes');
						$order->save();
						}
						$invoice = $order->prepareInvoice();
						$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
						$invoice->register();
						Mage::getModel('core/resource_transaction')
						->addObject($invoice)
						->addObject($invoice->getOrder())
						->save();
						$invoice->sendEmail(false, '');

					$pdf = Mage::getModel('orderprint/order_pdf_order')->getPdf(array($order));
					}
				else
					{
					if($order->getStatus() == "pending")
						{
						$order->setStatus('processing', true);
						$order->setEcorpsOrderprint('Yes');
						$order->save();
						}
						$invoice = $order->prepareInvoice();
						$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
						$invoice->register();
						Mage::getModel('core/resource_transaction')
						->addObject($invoice)
						->addObject($invoice->getOrder())
						->save();
						$invoice->sendEmail(false, '');

					$pages = Mage::getModel('orderprint/order_pdf_order')->getPdf(array($order));
					$pdf->pages = array_merge ($pdf->pages, $pages->pages);
					}
				}
			
			if ($flag)
				{
				return $this->_prepareDownloadResponse('salesorders_'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(), 'application/pdf');
				}
			else
				{
				$this->_getSession()->addError($this->__('There are no printable documents related to selected orders.'));
				$this->_redirect('*/*/');
				}
				
			}
		$this->_redirect('*/*/');
		}
    
	}