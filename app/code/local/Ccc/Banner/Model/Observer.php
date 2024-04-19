<?php
class Ccc_Banner_Model_Observer
{
    public function executeCron($observer)
    {
        // Your code to be executed by the cron job
        // Mage::log('Cron job executed successfully.', null, 'custom_cron.log');
        // // Example code: send an email
        // $email = Mage::getModel('core/email')
        //     ->setToName('John Doe')
        //     ->setToEmail('john@example.com')
        //     ->setBody('This is a test email from Magento cron job.')
        //     ->setSubject('Magento Cron Job Test')
        //     ->setFromEmail('admin@example.com')
        //     ->setFromName('Admin')
        //     ->setType('text');

        // try {
        //     $email->send();
        // } catch (Exception $e) {
        //     Mage::logException($e);
        // }
        echo 123;
    }
    public function executeCron2($observer)
    {
        echo 2344;
    }
}
