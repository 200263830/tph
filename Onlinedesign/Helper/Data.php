<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */

namespace Tph\Onlinedesign\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\Session;
use Imagick;
use Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory;

/**
 * Class Data
 * @package Tph\Onlinedesign\Helper
 */
class Data extends AbstractHelper
{

    const ENABLE = 'canvas/general/enable';
    const API_KEY = 'canvas/general/api_key';
    const PARTNER_API_KEY = 'canvas/general/partner_api_key';
    const ARTWORK_API_SECRET = 'canvas/general/artwork_api_secret';
    const BUTTON_SIZE = 'canvas/general/button_size';
    const BUTTON_STYLE = 'canvas/general/button_style';
    const IMAGE_FOLDER = 'tph/Images/';
    const API_URL = 'canvas/general/api_url';
    const CANVA_BUTTON = 1;
    const CANVA_CATALOGUE = 2;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CollectionFactory
     */
    private $orderItemCollectionFactory;

    protected $_filesystem;
    protected $_imageFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param File $filesystemIo
     * @param DirectoryList $dir
     * @param Session $customerSession
     * @param UrlInterface $urlInterface
     * @param CollectionFactory $orderItemCollectionFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Image\AdapterFactory $imageFactory
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        File $filesystemIo,
        DirectoryList $dir,
        Session $customerSession,
        UrlInterface $urlInterface,
        CollectionFactory $orderItemCollectionFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory
    )
    {
        $this->storeManager = $storeManager;
        $this->filesystemIo = $filesystemIo;
        $this->_dir = $dir;
        $this->customerSession = $customerSession;
        $this->_urlInterface = $urlInterface;
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
        $this->_filesystem = $filesystem;
        $this->_imageFactory = $imageFactory;
        parent::__construct($context);

    }

    /**
     * @return mixed
     */
    public function getModuleEnable()
    {
        $enable = $this->scopeConfig->getValue(self::ENABLE, ScopeInterface::SCOPE_STORE);
        return $enable;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        $ApiKey = $this->scopeConfig->getValue(self::API_KEY, ScopeInterface::SCOPE_STORE);
        return $ApiKey;
    }


    /**
     * @return mixed
     */
    public function getPartnerApiKey()
    {
        $ApiKey = $this->scopeConfig->getValue(self::PARTNER_API_KEY, ScopeInterface::SCOPE_STORE);
        return $ApiKey;
    }

    /**
     * @return mixed
     */
    public function getArtworkSecret()
    {
        $ApiKey = $this->scopeConfig->getValue(self::ARTWORK_API_SECRET, ScopeInterface::SCOPE_STORE);
        return $ApiKey;
    }

    /**
     * @return mixed
     */
    public function getButtonSize()
    {
        $buttonSize = $this->scopeConfig->getValue(self::BUTTON_SIZE, ScopeInterface::SCOPE_STORE);
        return $buttonSize;
    }

    /**
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->scopeConfig->getValue(self::API_URL, ScopeInterface::SCOPE_STORE);

    }

    /**
     * @return mixed
     */
    public function getButtonStyle()
    {
        $buttonStyle = $this->scopeConfig->getValue(self::BUTTON_STYLE, ScopeInterface::SCOPE_STORE);
        return $buttonStyle;
    }

    /**
     * @return mixed
     */
    public function copyFile($file, $filename)
    {
        $filePath = "/tph/Images/";
        $pdfPath = $this->_dir->getPath('media') . $filePath;
        if (!is_dir($pdfPath)) {
            $ioAdapter = $this->filesystemIo;
            $ioAdapter->mkdir($pdfPath, 0775);
        }
        if ($this->filesystemIo->cp($file, $pdfPath . $filename)) {
            return true;
        }
        return false;
    }

    /**
     * Get Base URLs using URLInterface
     */
    public function getBaseUrl()
    {

        return $this->_urlInterface->getBaseUrl();
    }

    /**
     * Get Media URLs using URLInterface
     */
    public function getMediaUrl()
    {
        $currentStore = $this->storeManager->getStore();
        return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }


    public function getMediaPath()
    {
        return $this->_dir->getPath('media');
    }

    /**
     * Get customer logged in
     */
    public function getLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * Get customer session
     */
    public function getCustomerData()
    {
        return $this->customerSession;
    }

    public function copyImage($pdf)
    {

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/image.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($pdf);
        $imagick = new Imagick();
        $imagick->setResolution(250, 250);
        $imagick->readImage($pdf);
        $imagick->setImageCompressionQuality(80);
        $imagick->resetIterator();
        $ima = $imagick->appendImages(true);
        $mediapath = $this->getMediaPath();
        $filename = rand(1111111111, 9999999999) . '.jpg';
        $pdfPath = $mediapath . '/tph/Images/' . $filename;
        $ima->writeImages($pdfPath, false);
        return $filename;
    }

    /**
     * @param $itemId
     * @return string
     */
    public function getQuoteItemData($itemId)
    {
        $item_id = $itemId;
        $quoteItemCollection = $this->orderItemCollectionFactory->create();
        $quoteItem = $quoteItemCollection->addFieldToSelect('*')->addFieldToFilter('item_id', $item_id)->getFirstItem();

        $quoteId = $quoteItem->getQuoteId();
        $quoteData = $this->orderItemCollectionFactory->create();
        $image = $quoteData->addFieldToSelect('*')->addFieldToFilter('quote_id', $quoteId)->getColumnValues('custom_image');
        $image = implode(',',array_filter($image));
        //$image = $quoteItem->getData('custom_image');

        if (!empty($image)) {
            //$resize = $this->resize($image);
            $mediaUrl = $this->getMediaUrl();
            $imageUrl = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $image;
            return $imageUrl;
        }
        return 'null';
    }

    /**
     * @param $image
     * @param int $width
     * @param int $height
     * @return bool|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resize($image, $width = 100, $height = 100)
    {
        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('tph/Images/') . $image;
        if (!file_exists($absolutePath)) return false;
        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('resized/' . $width . '/') . $image;
        if (!file_exists($imageResized)) { // Only resize image if not already exists.
            //create image factory...
            $imageResize = $this->_imageFactory->create();
            $imageResize->open($absolutePath);
            $imageResize->constrainOnly(TRUE);
            $imageResize->keepTransparency(TRUE);
            $imageResize->keepFrame(FALSE);
            $imageResize->keepAspectRatio(TRUE);
            $imageResize->resize($width, $height);
            //destination folder
            $destination = $imageResized;
            //save image
            $imageResize->save($destination);
        }
        $resizedURL = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'resized/' . $width . '/' . $image;
        return $resizedURL;

    }
}
