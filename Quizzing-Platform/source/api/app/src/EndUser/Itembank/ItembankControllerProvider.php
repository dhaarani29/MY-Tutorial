<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Controller provider for Itembank
 */


namespace QuizPlat\EndUser\Itembank;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

/*
 * Classname : ItembankControllerProvider;
 * Interfaces used : ControllerProviderInterface
 * This uses ControllerProviderInterface's connect method to define all routings for stubs module.
 */

class ItembankControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        /** @var \Silex\ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

	// Write all your routing path along with proper http methods.        
        $controllers->get("api/itembanks", 'itembank.controller:getListItemBanks');
        
        $controllers->get("api/itembanks/{id}", 'itembank.controller:getItemBankbyId');
        
        return $controllers;
    }

}
