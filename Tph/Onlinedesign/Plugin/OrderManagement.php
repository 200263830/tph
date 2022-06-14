<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * @Description Create the print production file
 */

namespace Tph\Onlinedesign\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class OrderManagement
 */
class OrderManagement
{

    public function __construct(
       \Magento\Framework\HTTP\Client\CurlFactory $curl,
       \Magento\Sales\Model\OrderFactory $orderFactory,
       SearchCriteriaBuilder $searchCriteriaBuilder,
       OrderRepositoryInterface $orderRepository,
       LoggerInterface $logger,
       \Magento\Quote\Model\Quote\Item $item,
       \Tph\Onlinedesign\Helper\Data $helper
    ) {
       $this->curl = $curl;
       $this->orderFactory = $orderFactory;
       $this->searchCriteriaBuilder = $searchCriteriaBuilder;
       $this->orderRepository = $orderRepository;
       $this->logger = $logger;
       $this->item = $item;
       $this->helper = $helper;
    }

    
    /**
     * @param OrderManagementInterface $subject
     * @param OrderInterface           $order
     *
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterPlace(
            OrderManagementInterface $subject,
            OrderInterface $result
    ) {
        $orderId = $result->getIncrementId();
        $order = $this->orderFactory->create()->loadByIncrementId($orderId);
        $orderItems = $order->getAllItems();

        // try{
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/productionFile.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        //$logger->info('Your text message');

        $curl = $this->curl->create();
        $getpArtId = $this->helper->getPartnerApiKey();
        $url = $this->helper->getApiUrl() . $getpArtId . "/artworks/";
        $params['purchaseConfirmation']['order'] = $orderId;
        $params['purchaseConfirmation']['grossAmount'] = (int) $order->getGrandTotal();
        foreach ($orderItems as $item) {
            if (!empty($item->getArtworkId())) {
                $ai = $item->getArtworkId();
                $params['purchaseConfirmation']['item'] = $item->getsku();
                $params['purchaseConfirmation']['sku'] = $item->getsku();
                $params['purchaseConfirmation']['quantity'] = (int) $item->getQtyOrdered();
                $params['purchaseConfirmation']['discountAmount'] = 0;
                $params['purchaseConfirmation']['netAmount'] = (int) $order->getGrandTotal();
                $params['purchaseConfirmation']['currency'] = "USD";
                $curl->addHeader('Content-Type', 'application/json');
                $artworkSec = $this->helper->getArtworkSecret();
                $curl->addHeader("Authorization", "Bearer " . $artworkSec);

                try {
                    
                    $curl->post($url . $ai, json_encode($params));
                    $response = $curl->getBody();
                    $responsed = json_decode($response, true);
                    $curld = $this->curl->create();
                    $curld->addHeader('Content-Type', 'application/json');
                    $new['FileURL'] = $responsed['productionFile'];
                    $new['OrderItemID'] = $item->getItemId();
                    $param['CanvaOrderID'] = $orderId;
                    $param['FileURLs'][] = $new;
                    
                    $urld = "https://stage.tph.ca/myaccount/API/CanvaFiles/CaptureFiles";
                    $curld->post($urld, json_encode($param));
                    $responsedo = $curld->getBody();
                    $logger->info('paramc');
                    $logger->info(json_encode($param));
                    $logger->info('responcec');
                    $logger->info($responsedo);
                    
                } catch (\Exception $e) {
                    $this->logger->critical('Error Curl', ['exception' => $e]);
                }
            }

            if(!empty($item->getCustomImage())){
             //   if (empty($item->getArtworkId())){
                 unset($param);   
                 unset($parami);   
                $curlu = $this->curl->create();
                $curlu->addHeader('Content-Type', 'application/json');
                $mediaUrl = $this->helper->getMediaUrl();
                if($item->getDesignType()==3){
                    $imageUrl = $item->getCustomImage();
                }else{
                    $imageUrl = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $item->getCustomImage();
                }
                
                $unew['FileURL'] = $imageUrl;
                $unew['OrderItemID'] = $item->getItemId();
                $parami['FileURLs'][] = $unew;
                $parami['CanvaOrderID'] = $orderId;
                $urlu = "https://stage.tph.ca/myaccount/API/CanvaFiles/CaptureFiles";
                $curlu->post($urlu, json_encode($parami));
                $responsedu = $curlu->getBody();
                $logger->info('responcei');
                $logger->info($responsedu);
                $logger->info('parami');
                $logger->info(json_encode($parami));
            // }
            }
        }

         return $result;
    }

    public function getDiscountAmountOrder($incrementId) {
        $orderDiscountAmount = null;
        try {
            $searchCriteria = $this->searchCriteriaBuilder
                            ->addFilter('increment_id', $incrementId)->create();
            $orderData = $this->orderRepository->getList($searchCriteria)->getItems();
            foreach ($orderData as $order) {
                if ($order->getDiscountAmount()) {
                    $orderDiscountAmount = abs($order->getDiscountAmount());
                }
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $orderDiscountAmount;
    }

}