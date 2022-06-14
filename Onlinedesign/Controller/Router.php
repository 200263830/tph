<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 */

namespace Tph\Onlinedesign\Controller;

/**
 * Class Router
 *
 * @package Tph\Onlinedesign\Controller
 */
class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(\Magento\Framework\App\ActionFactory $actionFactory, \Magento\Framework\App\ResponseInterface $response)
    {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|void
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo() , '/');
        
        if(strpos($identifier, 'canva_holidays') !== false)
        {
            $request->setModuleName('onlinedesign')
            ->setControllerName('canva')
            ->setActionName('index');
        }
        else if (strpos($identifier, 'canva') !== false)
        {
            $request->setModuleName('onlinedesign')
            ->setControllerName('index')
            ->setActionName('canva');
        }
        else{
            return;
            }

        return $this
        ->actionFactory
        ->create('Magento\Framework\App\Action\Forward', ['request' => $request]);
    }
}