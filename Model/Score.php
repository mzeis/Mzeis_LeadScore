<?php

namespace Mzeis\LeadScore\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerDataBuilder;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\AttributeInterface;

class Score
{
    /**
     * @var \Magento\Customer\Api\Data\CustomerDataBuilder
     */
    protected $customerDataBuilder;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param CustomerDataBuilder $customerDataBuilder
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerDataBuilder $customerDataBuilder,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerDataBuilder = $customerDataBuilder;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param CustomerInterface $customer
     * @param int $score
     */
    public function add(CustomerInterface $customer, $score)
    {
        $this->customerDataBuilder->populate($customer);

        $attribute = $customer->getCustomAttribute('mzeis_leadscore');
        if ($attribute instanceof AttributeInterface) {
            $newScore = $attribute->getValue() + $score;
        } else {
            $newScore = $score;
        }

        $this->customerDataBuilder->setCustomAttribute('mzeis_leadscore', $newScore);
        $this->customerRepository->save($this->customerDataBuilder->create());
    }

}
