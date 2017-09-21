<?php  
require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php'; 
class Mjsi_Orderprint_OrderController extends Mage_Adminhtml_Sales_OrderController
{
    
	public function printAction(){
        $order = $this->_initOrder();
		if (!empty($order)) {
			$order->setOrder($order);
			$d = Mage::getModel('orderprint/orderprint')->getCollection();
			$d->addFieldToFilter('order_id', $order->getId()); 
			foreach ($d as $s){}
			if (count($d))
				$orderprint = $s;
			else
				$orderprint = Mage::getModel('orderprint/orderprint');
			$orderprint->setOrderId($order->getId());
			$orderprint->setDatePrinted(now());
			$orderprint->save();
			$order->setEcorpsOrderprint('Yes');
			$order->save();
			$invoice = $order->prepareInvoice();
			$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
			$invoice->register();
			Mage::getModel('core/resource_transaction')
			->addObject($invoice)
			->addObject($invoice->getOrder())
			->save();
			$invoice->sendEmail(false, '');
            $pdf = Mage::getModel('orderprint/order_pdf_order')->getPdf(array($order));
            return $this->_prepareDownloadResponse('order'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(), 'application/pdf');
        }
        $this->_redirect('*/*/');
    }
    
}