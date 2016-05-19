<?php

namespace Rbncha\OrderEmailImage\Controller\Index; 
 
class Index extends \Magento\Framework\App\Action\Action 
{
    protected $_resultPageFactory;
    protected $_productImage;
    
    public function __construct(\Magento\Framework\App\Action\Context $context,
        \Rbncha\OrderEmailImage\Helper\ProductEmailImage $productImage
    ){
        $this->_productImage = $productImage;

        parent::__construct($context);
    }
 
    public function execute()
    {
        $imageUrl = $this->_productImage->getEmailImageCreated(50,100,100);
        
        exit('<img src="'.$imageUrl.'" />');

    }
}