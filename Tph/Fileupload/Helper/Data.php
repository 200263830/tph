<?php

/**
 * @category    TPH
 * @package     Tph_Fileupload
 * @description Helper file 
 */

namespace Tph\Fileupload\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\Session;
use Imagick;

/**
 * Class Data
 * @package Tph\Onlinedesign\Helper
 */
class Data extends AbstractHelper {

    const ENABLE = 'fileupload/general/enable';
    

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\Io\File $filesystemIo
     * @param \Magento\Framework\Filesystem\DirectoryList $dir
     * @param \Magento\Framework\UrlInterface $urlInterface
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        File $filesystemIo,
        DirectoryList $dir,
        Session $customerSession,
        UrlInterface $urlInterface
    ) {
        $this->storeManager = $storeManager;
        $this->filesystemIo = $filesystemIo;
        $this->_dir = $dir;
        $this->customerSession = $customerSession;
        $this->_urlInterface = $urlInterface;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function getModuleEnable() {
        $enable = $this->scopeConfig->getValue(self::ENABLE, ScopeInterface::SCOPE_STORE);
        return $enable;
    }

     /**
     * @return mixed
     */
    public function copyFile($file, $filename) {
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

}
