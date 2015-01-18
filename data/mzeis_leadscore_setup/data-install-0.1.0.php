<?php

/**
 * @var $this \Mzeis\LeadScore\Model\Resource\Setup
 */
$installer = $this;
$installer->startSetup();

$leadScoreAttributeCode = 'mzeis_leadscore';

// Add reset password link token attribute
$installer->addAttribute(
    'customer',
    $leadScoreAttributeCode,
    [
        'label' => 'Lead Score',
        'required' => false,
        'system' => 0,
        'type' => 'int',
        'visible' => false,
    ]
);

$leadScoreAttribute = $installer->getEavConfig()->getAttribute('customer', $leadScoreAttributeCode);
$leadScoreAttribute->setData('used_in_forms', ['adminhtml_customer']);
$leadScoreAttribute->save();

$installer->endSetup();
