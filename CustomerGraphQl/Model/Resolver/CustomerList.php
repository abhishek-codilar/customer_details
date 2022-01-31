<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Codilar\CustomerGraphQl\Model\Resolver;

use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\CustomerGraphQl\Model\Customer\ExtractCustomerData;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Customer\Model\Customer;

/**
 * Customers field resolver, used for GraphQL request processing.
 */
class CustomerList implements ResolverInterface
{
    /**
     * @var GetCustomer
     */
    private $getCustomer;

    /**
     * @var ExtractCustomerData
     */
    private $extractCustomerData;

    /**
     * @param GetCustomer $getCustomer
     * @param ExtractCustomerData $extractCustomerData
     */
    public function __construct(
        GetCustomer $getCustomer,
        ExtractCustomerData $extractCustomerData,
        Customer $customerCollection
    ) {
        $this->getCustomer = $getCustomer;
        $this->extractCustomerData = $extractCustomerData;
        $this->customerCollection = $customerCollection;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
       
        $productCollection=$this->customerCollection->getCollection()
               ->addAttributeToSelect("*")
               ->load();
               
      $productRecord=[];
      $x=0;
        foreach($productCollection->getItems() as $product) {
            $productRecord[$x]['name'] = $product->getName();
            $productRecord[$x]['email'] = $product->getEmail();
            $productRecord[$x]['id'] = $product->getId();
            
            $x++;
        }
        return $productRecord;
    }
   }

