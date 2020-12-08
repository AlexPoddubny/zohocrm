<?php

namespace App\Http\Controllers;


use Asciisd\Zoho\Facades\ZohoManager;

class ZohoController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return void
     * @throws \zcrmsdk\crm\exception\ZCRMException
     */
    public function index()
    {
        $deals = ZohoManager::useModule('Deals');
        echo 'Creating the Deal:<br>';
        $record = $deals->getRecordInstance();
        $record->setFieldValue('Deal_Name', 'Sample Deal at ' . date('Y-m-d H:i:s'));
        $record->setFieldValue('Closing_Date', '2020-12-31');
        $record->setFieldValue('Stage', 'Qualification');
        dump($deal = $record->create()->getData());
        echo 'Deal ID: ' . $deal->getEntityId() . '<br>';
        echo 'Creating the Task:<br>';
        $tasks = ZohoManager::useModule('Tasks');
        $record = $tasks->getRecordInstance();
        $record->setFieldValue('Subject', 'Sample Subject at ' . date('Y-m-d H:i:s'));
        $record->setFieldValue('$se_module', 'Deals'); //relation module
        $record->setFieldValue('What_Id', $deal->getEntityId());
        $task = $record->create();
        dump($task->getData());
    }
    
}
