<?php

namespace SU\CustomOption\Controller\Adminhtml\Image;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Upload
 */
class Upload extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Catalog::products';

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var array
     */
    private $allowedMimeTypes = [
        'jpg' => 'image/jpg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/png',
        'png' => 'image/gif'
    ];

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
    }

    /**
     * Upload image(s) to the product gallery.
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $fileId = $this->_request->getParam('param_name', 'image');
        $fileId = $this->_fileIdPreprocess($fileId);

        try {
            $uploader = $this->_objectManager->create(
                \Magento\MediaStorage\Model\File\Uploader::class,
                ['fileId' => $fileId]
            );
            $uploader->setAllowedExtensions($this->getAllowedExtensions());

            if (!$uploader->checkMimeType($this->getAllowedMimeTypes())) {
                throw new LocalizedException(__('Disallowed File Type.'));
            }

            /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
            $imageAdapter = $this->_objectManager->get(\Magento\Framework\Image\AdapterFactory::class)->create();
            $uploader->addValidateCallback('catalog_product_image', $imageAdapter, 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
            $mediaDirectory = $this->_objectManager->get(\Magento\Framework\Filesystem::class)
                ->getDirectoryRead(DirectoryList::MEDIA);
            $config = $this->_objectManager->get(\Magento\Catalog\Model\Product\Media\Config::class);
            $result = $uploader->save($mediaDirectory->getAbsolutePath($config->getBaseTmpMediaPath()));

            $this->_eventManager->dispatch(
                'catalog_product_gallery_upload_image_after',
                ['result' => $result, 'action' => $this]
            );

            unset($result['tmp_name']);
            unset($result['path']);

            $result['url'] = $this->_objectManager->get(\Magento\Catalog\Model\Product\Media\Config::class)
                ->getTmpMediaUrl($result['file']);
            $result['file'] = $result['file'] . '.tmp';
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents(json_encode($result));

        return $response;
    }

    /**
     * Get the set of allowed file extensions.
     *
     * @return array
     */
    private function getAllowedExtensions()
    {
        return array_keys($this->allowedMimeTypes);
    }

    /**
     * Get the set of allowed mime types.
     *
     * @return array
     */
    private function getAllowedMimeTypes()
    {
        return array_values($this->allowedMimeTypes);
    }

    private function _fileIdPreprocess($fileId){
        if (substr($fileId, -14) == "[custom_image]") {
            $optionKey = explode("][", $fileId)[1];
            $valueKey = explode("][", $fileId)[3];
            $fileId = "options." . $optionKey . ".values." . $valueKey . ".custom_image";

            $image = [];
            foreach ($_FILES["product"] as $key => $value) {
                $image[$key] = $value["options"][$optionKey]["values"][$valueKey]["custom_image"];
            }
            $_FILES[$fileId] = $image;
            return $fileId;
        }
        return $fileId;
    }
}
