<?php

namespace SU\CustomOption\Modifier;

use Magento\Ui\Component\Container;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Fieldset;

class CustomOptionModifier extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions
{
    const FIELD_CUSTOM_IMAGE_NAME = 'custom_image';
    const FIELD_DEPEND_NAME = 'depend';
    const FIELD_DEPEND_OPTION_STATUS = "is_depend_option_enabled";

    protected function getSelectTypeGridConfig($sortOrder)
    {
        $options = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'imports' => [
                            'optionId' => '${ $.provider }:${ $.parentScope }.option_id',
                            'optionTypeId' => '${ $.provider }:${ $.parentScope }.option_type_id',
                            'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default'
                        ],
                        'service' => [
                            'template' => 'Magento_Catalog/form/element/helper/custom-option-type-service',
                        ],
                    ],
                ],
            ],
        ];

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'addButtonLabel' => __('Add Value'),
                        'componentType' => DynamicRows::NAME,
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows',
                        'additionalClasses' => 'admin__field-wide',
                        'deleteProperty' => static::FIELD_IS_DELETE,
                        'deleteValue' => '1',
                        'renderDefaultRecord' => false,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'positionProvider' => static::FIELD_SORT_ORDER_NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                            ],
                        ],
                    ],
                    'children' => [
                        static::FIELD_TITLE_NAME => $this->getTitleFieldConfig(
                            10,
                            $this->locator->getProduct()->getStoreId() ? $options : []
                        ),
                        static::FIELD_PRICE_NAME => $this->getPriceFieldConfigForSelectType(20),
                        static::FIELD_PRICE_TYPE_NAME => $this->getPriceTypeFieldConfig(30, ['fit' => true]),
                        static::FIELD_SKU_NAME => $this->getSkuFieldConfig(40),
                        static::FIELD_CUSTOM_IMAGE_NAME => $this->getCustomImageFieldConfig(50),
                        static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(60),
                        static::FIELD_IS_DELETE => $this->getIsDeleteFieldConfig(70)
                    ]
                ]
            ]
        ];
    }

    protected function getCommonContainerConfig($sortOrder)
    {
        $commonContainer = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Container::NAME,
                        'formElement' => Container::NAME,
                        'component' => 'Magento_Ui/js/form/components/group',
                        'breakLine' => false,
                        'showLabel' => false,
                        'additionalClasses' => 'admin__field-group-columns admin__control-group-equal',
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
            'children' => [
                static::FIELD_OPTION_ID => $this->getOptionIdFieldConfig(10),
                static::FIELD_TITLE_NAME => $this->getTitleFieldConfig(
                    20,
                    [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'label' => __('Option Title'),
                                    'component' => 'Magento_Catalog/component/static-type-input',
                                    'valueUpdate' => 'input',
                                    'imports' => [
                                        'optionId' => '${ $.provider }:${ $.parentScope }.option_id',
                                        'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default'
                                    ]
                                ],
                            ],
                        ],
                    ]
                ),
                static::FIELD_TYPE_NAME => $this->getTypeFieldConfig(30),
                static::FIELD_DEPEND_NAME => $this->getDependFieldConfig(40),
                static::FIELD_IS_REQUIRE_NAME => $this->getIsRequireFieldConfig(50),
                static::FIELD_DEPEND_OPTION_STATUS => $this->getToggleDependOptionStatusFieldConfig(60)
            ]
        ];

        if ($this->locator->getProduct()->getStoreId()) {
            $useDefaultConfig = [
                'service' => [
                    'template' => 'Magento_Catalog/form/element/helper/custom-option-service',
                ]
            ];
            $titlePath = $this->arrayManager->findPath(static::FIELD_TITLE_NAME, $commonContainer, null)
                . static::META_CONFIG_PATH;
            $commonContainer = $this->arrayManager->merge($titlePath, $commonContainer, $useDefaultConfig);
        }
//        var_dump($titlePath);die;

        return $commonContainer;
    }

    protected function createCustomOptionsPanel()
    {
        $this->meta = array_replace_recursive(
            $this->meta,
            [
                static::GROUP_CUSTOM_OPTIONS_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Customizable Options'),
                                'componentType' => Fieldset::NAME,
                                'dataScope' => static::GROUP_CUSTOM_OPTIONS_SCOPE,
                                'collapsible' => true,
                                'sortOrder' => $this->getNextGroupSortOrder(
                                    $this->meta,
                                    static::GROUP_CUSTOM_OPTIONS_PREVIOUS_NAME,
                                    static::GROUP_CUSTOM_OPTIONS_DEFAULT_SORT_ORDER
                                ),
                            ],
                        ],
                    ],
                    'children' => [
//                        static::FIELD_DEPEND_OPTION_STATUS => $this->getToggleDependOptionStatusFieldConfig(5),
                        static::CONTAINER_HEADER_NAME => $this->getHeaderContainerConfig(10),
                        static::FIELD_ENABLE => $this->getEnableFieldConfig(20),
                        static::GRID_OPTIONS_NAME => $this->getOptionsGridConfig(30)
                    ]
                ]
            ]
        );

        $this->meta = array_merge_recursive(
            $this->meta,
            [
                static::IMPORT_OPTIONS_MODAL => $this->getImportOptionsModalConfig()
            ]
        );

        return $this;
    }

    private function getCustomImageFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Image'),
                        'componentType' => Field::NAME,
                        'formElement' => 'fileUploader',
                        'dataScope' => static::FIELD_CUSTOM_IMAGE_NAME,
                        'previewTmpl' => 'Magento_Catalog/image-preview',
                        'elementTmpl' => 'ui/form/element/uploader/uploader',
                        'uploaderConfig' => [
                            "url" => "catalog/product_gallery/upload"
                        ],
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    private function getToggleDependOptionStatusFieldConfig($sortOrder, array $config = [])
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'component' => 'SU_CustomOption/js/single-checkbox',
                        'formElement' => 'checkbox',
                        'componentType' => Field::NAME,
                        'dataType' => 'boolean',
                        'visible' => 1,
//                        'default' => '1',
                        'label' => __('Dependent Option'),
                        'valueMap' => [
                            'true' => '1',
                            'false' => '0'
                        ],
                        'prefer' => 'toggle'
                    ]
                ]
            ]
        ];
    }

    private function getDependFieldConfig($sortOrder, array $config = [])
    {
//        $om = \Magento\Framework\App\ObjectManager::getInstance();
//        $dependOptions = $om->create("SU\CustomOption\Model\Config\Source\Product\Options\Depend");

//        var_dump($this->locator->getProduct()->getId());die;
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Depend On'),
                        'componentType' => Field::NAME,
                        'formElement' => Select::NAME,
                        'dataScope' => static::FIELD_DEPEND_NAME,
                        'dataType' => Text::NAME,
                        'visible' => 0,
                        'sortOrder' => $sortOrder
//                        'options' => $dependOptions->toOptionArray($this->locator->getProduct())
                    ],
                ],
            ],
        ];
    }
}
