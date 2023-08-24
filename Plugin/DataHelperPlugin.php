<?php
    /**
     * @Thomas-Athanasiou
     *
     * @author Thomas Athanasiou {thomas@hippiemonkeys.com}
     * @link https://hippiemonkeys.com
     * @link https://github.com/Thomas-Athanasiou
     * @copyright Copyright (c) 2023 Hippiemonkeys Web Intelligence EE All Rights Reserved.
     * @license http://www.gnu.org/licenses/ GNU General Public License, version 3
     * @package Hippiemonkeys_ModificationMagentoConfigurablePr
     */

    declare(strict_types=1);

    namespace Hippiemonkeys\ModificationMagentoConfigurablePr\Plugin;

    use Magento\ConfigurableProduct\Helper\Data,
        Hippiemonkeys\Core\Api\Helper\ConfigInterface;

    class DataHelperPlugin
    {
        /**
         * Constructor
         *
         * @access public
         *
         * @param \Hippiemonkeys\Core\Api\Helper\ConfigInterface $config
         */
        public function __construct(ConfigInterface $config)
        {
            $this->_config = $config;
        }

        /**
         * After Get Options Method
         *
         * @access public
         *
         * @param \Magento\ConfigurableProduct\Helper\Data $data
         * @param mixed $options
         * @param mixed $currentProduct
         * @param mixed $allowedProducts
         *
         * @return mixed
         */
        public function afterGetOptions(Data $data, $options, $currentProduct, $allowedProducts)
        {
            if($this->getIsActive())
            {
                $options = [];
                $allowAttributes = $data->getAllowAttributes($currentProduct);
                foreach ($allowedProducts as $product)
                {
                    $productId = $product->getId();
                    foreach ($allowAttributes as $attribute)
                    {
                        $productAttribute = $attribute->getProductAttribute();
                        $productAttributeId = $productAttribute->getId();
                        $attributeValue = $product->getData($productAttribute->getAttributeCode());

                        if ($product->isSalable())
                        {
                            $options[$productAttributeId][$attributeValue][] = $productId;
                        }

                        $options['index'][$productId][$productAttributeId] = $attributeValue;
                    }
                }
            }

            return $options;
        }

        /**
         * Gets Is Active flag
         *
         * @access protected
         *
         * @return bool
         */
        protected function getIsActive(): bool
        {
            return $this->getConfig()->getIsActive();
        }

        /**
         * Config property
         *
         * @access private
         *
         * @var \Hippiemonkeys\Core\Api\Helper\ConfigInterface $_config
         */
        private $_config;

        /**
         * Gets Config
         *
         * @access protected
         *
         * @return \Hippiemonkeys\Core\Api\Helper\ConfigInterface
         */
        protected function getConfig(): ConfigInterface
        {
            return $this->_config;
        }
    }
?>