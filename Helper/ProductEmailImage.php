<?php

namespace Rbncha\OrderEmailImage\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Generates email product images
 */
class ProductEmailImage extends AbstractHelper
{
	/**
     * @var \Magento\Catalog\Model\Product
     */
	protected $_product;

	/**
	 * @var \Magento\Catalog\Model\Product\Media\Config
	 */
	protected $_mediaConfig;

	/**
	 * @var \Magento\Framework\Image\Factory
	 */
	protected $_imageFactory;

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;

	/**
	 * @var \Magento\Framework\Filesystem
	 */
	protected $_filesystem;

	public function __construct(
		\Magento\Catalog\Model\Product $product,
		\Magento\Catalog\Model\Product\Media\Config $mediaConfig,
		\Magento\Framework\Image\Factory $imageFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Filesystem $filesystem
	){
		$this->_product = $product;
		$this->_mediaConfig = $mediaConfig;
		$this->_imageFactory = $imageFactory;
		$this->_storeManager = $storeManager;
		$this->_filesystem = $filesystem;
	}

	/**
	 * Generates product images for emails in different directory
	 * Purpose is to avoid use of cached product images
	 * This will insure email images are not flushed away
	 * when image cache is cleared
	 * 
	 * @param integer $productId
	 * @param integer $w
	 * @param integer $h
	 * @return string
	 */
	public function getEmailImageCreated($productId, $w = 100, $h = 100)
	{
		try{
			$product = $this->_product->load($productId);

			$baseDir = $this->_storeManager->getStore()->getBaseMediaDir() . '/';
			$src = $baseDir . $this->_mediaConfig->getMediaPath($product->getImage());
			$dst = $baseDir . $this->_mediaConfig->getMediaPath('email-image'.$product->getImage());
			$dstUrl = $this->_mediaConfig->getMediaUrl('email-image' . $product->getImage());

			if (!file_exists($dst) ){
				$image = $this->_imageFactory->create($src);
				$image->keepTransparency(true);
		        $image->constrainOnly(true);
		        $image->keepFrame(true);
		        $image->keepAspectRatio(true);
		        $image->backgroundColor([255, 255, 255]);
		        $image->resize($w, $h);
		        
		        $imageName = basename($product->getImage());
		        $image->save(dirname($dst), $imageName);

			}

		}catch(\Exception $e){
			exit($e->getMessage());
		}

		return $dstUrl;
	}
}