<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Sales Order Invoice PDF model
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mjsi_Orderprint_Model_Order_Pdf_Order extends Mage_Sales_Model_Order_Pdf_Abstract
{
//	
//	protected function insertTotals(&$page, $source){
//		$source->setOrder($source);
//        parent::insertTotals($page,$source);
//    }
	
    public function getPdf($orders = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('order');

        $pdf = new Zend_Pdf();
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
		$currY = 0;

        foreach ($orders as $order) {
            $page = $pdf->newPage(Zend_Pdf_Page::SIZE_LETTER);
            $pdf->pages[] = $page;

            //$order = $invoice->getOrder();

            /* Add image */
            $this->insertLogo($page, $order->getStore());
			
			$page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
			//$this->_setFontBold($style, 12);
			//$page->drawText(Mage::helper('sales')->__('Sales Order'), 190, 775, 'UTF-8');
			
			/* Add address */
            //$this->insertAddress($page, $order->getStore());

            /* Add head */
            $this->insertOrder($page, $order, Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId()));

            $page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
            $this->_setFontRegular($page);

            /* Add table */
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.5));
            $page->setLineColor(new Zend_Pdf_Color_GrayScale(0));
            $page->setLineWidth(0.5);
			
			$this->y += 17;
            $page->drawRectangle(25, $this->y, 570, $this->y - 20);
            $this->y -=14;
			
			$this->_setFontBold($page, 20);
			$page->drawText(Mage::helper('sales')->__('Sales Order'), 450, 750, 'UTF-8');
			$this->_setFontRegular($page, 10);
			
            /* Add table head */
             $page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
            $page->drawText(Mage::helper('sales')->__('Product'), 35, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('SKU'), 240, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Price'), 380, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('QTY'), 420, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Tax'), 470, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Subtotal'), 525, $this->y, 'UTF-8');
			
			$this->y -=6;
			$currY = $this->y;
			//$page->drawRectangle(25, $this->y, 570, $this->y-350);
			$this->y -=14;
			
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));

            /* Add body */
            foreach ($order->getAllItems() as $item){
            	if ($item->getParentItem()) {
                    continue;
                }

                $shift = array();
                if ($this->y<215) {
                    /* Add new table head */
                    $page = $pdf->newPage(Zend_Pdf_Page::SIZE_LETTER);
                    $pdf->pages[] = $page;
                    $this->y = 750;

                    $this->_setFontRegular($page);
                    $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
                    $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
                    $page->setLineWidth(0.5);
                    $page->drawRectangle(25, $this->y, 570, $this->y-15);
                    $this->y -=10;

                    $page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
                    $page->drawText(Mage::helper('sales')->__('Product'), 35, $this->y, 'UTF-8');
                    $page->drawText(Mage::helper('sales')->__('SKU'), 240, $this->y, 'UTF-8');
                    $page->drawText(Mage::helper('sales')->__('Price'), 380, $this->y, 'UTF-8');
                    $page->drawText(Mage::helper('sales')->__('QTY'), 420, $this->y, 'UTF-8');
                    $page->drawText(Mage::helper('sales')->__('Tax'), 470, $this->y, 'UTF-8');
                    $page->drawText(Mage::helper('sales')->__('Subtotal'), 525, $this->y, 'UTF-8');

                    $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
                    $this->y -=20;
				}

                /* Draw item */
                $this->_drawItem($item, $page, $order);
				
				
            }
			
			/* Add totals */
			if ($this->y >= 237) {
				$long = 132;
				$short = 105;
			}
			else {
				$this->y += 15;
				$tem = 237 - $this->y;
				$long = 132;
				$short = (105 - $tem);
			}
					
			$tY = $this->y - ($this->y - $long); 
			$page->drawLine(25, $currY, 25, $tY);
			$page->drawLine(235, $currY, 235, $tY + $short);
			$page->drawLine(355, $currY, 355, $tY + $short);
			$page->drawLine(415, $currY, 415, $tY);
			$page->drawLine(450, $currY, 450, $tY);
			$page->drawLine(510, $currY, 510, $tY);
			$page->drawLine(570, $currY, 570, $tY);
			$page->drawLine(25, $tY, 570, $tY);
			
			if ($this->y >= 237)
				$this->y = 237;
			
			$page->drawLine(25, $this->y, 415, $this->y);
			//$comments = $order->getBiebersdorfCustomerordercomment();
			$this->y -= 12;
			$this->_setFontBold($page, 10);
			$page->drawText(Mage::helper('sales')->__('Comments:             [  ] Verified           [  ] Unverified'), 35, $this->y, 'UTF-8');
			
			
			//$prod_mod = Mage::getModel('sales/order')->load(5449);
			$comments = array();
			foreach ($order->getStatusHistoryCollection(true) as $_item) {$comments[] = $_item->getComment();}
			$comments = end($comments);
			$this->y -= 5;
			
			if(strstr($comments, "</"))
				{
				$comments = "";
				}
				
			// RK_CHANGE
			// $comments pdf order
			/*
			if(strstr($comments, "Paypal"))
				{
				$comments = "";
				}
			*/
			
			if (strlen( trim($comments) ) > 0)
			{
				$this->y -= 12;
				$this->_setFontRegular($page, 10);
				foreach (Mage::helper('core/string')->str_split($comments, 75) as $key => $part) {
					if ($key > 0) {
						$this->y -= 10;
					}
					$page->drawText($part, 35, $this->y, 'UTF-8');
				}
			}
			
			$this->y = 117;
			$page->drawLine(25, $this->y + 15, 25, $this->y - 75);
			$page->drawLine(355, $this->y - 50, 570, $this->y - 50);
			$page->drawLine(355, $this->y + 15, 355, $this->y - 75);
			$page->drawLine(570, $this->y + 15, 570, $this->y - 75);
			$page->drawLine(25, $this->y - 75, 570, $this->y - 75);
            
			$font = $this->_setFontRegular($page);
			$page->setFont($font, 7);
			
			$order_referer = Mage::helper('sales')->__('Referrer:');
			//$page ->drawText($order_referer, 70-$this->widthForStringUsingFontSize($order_referer, $font, 12), $this->y, 'UTF-8');
			$page ->drawText($order_referer, 35, $this->y, 'UTF-8');
			
					// Part of the code that retrieves the website referrer for the order.
			/*
			$order_refere = 0;
			if (Mage::getStoreConfig('referrer/referrer/createfile'))
			{
				$handle = fopen (Mage::getStoreConfig('referrer/referrer/file'),"r");
				while ($data = fgetcsv ($handle, 1000, "\t")) 
				{
					if($data[0] == $order->getRealOrderId() )
					{
						break;
					}
				}
				$order_refere = $data[2];
				fclose ($handle);
			}
			
			$pieces = explode("#:", $order_refere);
			if (count($pieces) > 1)
			{
				$page ->drawText($pieces[1], 75, $this->y, 'UTF-8');
				$this->y -= 7;

				$coun = 0;
				foreach (Mage::helper('core/string')->str_split($pieces[0], 70) as $key => $part) {
					if ($key > 0) {
						$this->y -= 8;
					}
					$page->drawText($part, 35, $this->y, 'UTF-8');
					$coun++;
					if ($coun > 8)
						break;
				}
			}*/

			$value = $order->getReferer();
			$host = parse_url($value, PHP_URL_HOST);
			if($host) {
				$value = $host;
			}

			if(!$value) {
				$value = "DIRECT";
			}
			
			$page->drawText($value, 75, $this->y, 'UTF-8');
			//$page->drawText($part, 35, $this->y, 'UTF-8');
						
			//$page ->drawText($order_refere, 75, $this->y, 'UTF-8');
			$this->y = 117;
			
			$this->insertTotals($page, $order);
		}

        $this->_afterGetPdf();

        return $pdf;
    }
    
    protected function _drawItem(Varien_Object $item, Zend_Pdf_Page $page, Mage_Sales_Model_Order $order)
    {
        $type = $item->getProductType();
        $renderer = $this->_getRenderer($type);
        $renderer->setOrder($order);
        $renderer->setItem($item);
        $renderer->setPdf($this);
        $renderer->setPage($page);
        $renderer->setRenderedModel($this);
			
			
        //$renderer->draw();
		  
			if(! ($item->getSku() == "liftgate" || $item->getSku() == "no-liftgate" || $item->getSku() == "no-insidedelivery" || $item->getSku() == "insidedelivery-notground" || $item->getSku() == "insidedelivery-ground" ) )
				{
				$renderer->draw();
				}
		  
		  
    }
    
}