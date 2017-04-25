<?php

/**
 * CommonHelper - It's service file to handle additional database related functions.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 * @Since : 26-September-2016
 */

// Declare namespaces
namespace QuizzingPlatform\Services;

use Silex\Application;

/**
 * CommonHelper to handle additional DB functions.
 */
class CommonHelper {

    /**
     * @Desc : Generates the query offset based on the page number per page count.
     * @param type $page
     * @param type $maxResultPerPage
     * @return type
     */
    public function getOffset($page, $maxResultPerPage) {
        $offset = ($page - 1) * $maxResultPerPage; // Always page number starts with page 1. hence to reducing with -1 to start with initial limit.
        return $offset; // Returns the generated offset.
    }

    /**
     * @Desc : Generally sorting API will receive +fieldname for ASC sort and -fieldname for DESC. 
     * This functions seperates the sort fieldname and sort type.
     * @param type $sort
     * @return array
     * 
     */
    public function getSortingDetails($sort) {

        $sortValue = array();

        if (strpos($sort, '+') !== false) { // Checks wether $sort has + symbol
            $sortType = 'ASC'; // Confirmed its ASC with above condition.
            $sortData = explode('+', $sort); // explode the string with + symbol
            $sortField = $sortData[1]; // array[1] will have the fieldname.
        } else if (strpos($sort, '-') !== false) { // Checks wether $sort has - symbol
            $sortType = 'DESC'; // Confirmed its DESC with above condition.
            $sortData = explode('-', $sort); // explode the string with - symbol
            $sortField = $sortData[1]; // array[1] will have the fieldname.
        }

        // Finally assign sort fieldname and sort type to array and return it.
        $sortValue['type'] = $sortType;
        $sortValue['field'] = $sortField;

        return $sortValue;
    }
    

}
