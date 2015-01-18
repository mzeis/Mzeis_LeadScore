<?php

namespace Mzeis\LeadScore\Model;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Observer
{
    const XML_PATH_ENABLE = 'mzeis_leadscore/general/enable';

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Mzeis\LeadScore\Model\Score
     */
    protected $score;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected function _isEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE);
    }

    /**
     * @return bool
     */
    protected function _shouldScoreBeAdded()
    {
        return $this->_isEnabled() && $this->customerSession->isLoggedIn();
    }

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Score $score
     * @param Session $customerSession
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Score $score,
        Session $customerSession
    ) {
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->score = $score;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function salesQuoteProductAddAfter(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_shouldScoreBeAdded()) {
            return;
        }

        $customer = $this->customerSession->getCustomerDataObject();
        $score = count($observer->getEvent()->getData('items')) * 5;
        $this->score->add($customer, $score);
    }
}
