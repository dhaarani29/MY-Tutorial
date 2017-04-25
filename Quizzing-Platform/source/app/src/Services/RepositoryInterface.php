<?php

/**
 * RepositoryInterface - For all common CRUD operations.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
namespace QuizzingPlatform\Services;

interface RepositoryInterface
{
  public function find($id);

  public function update($request,$updateRequest);

  public function create($request);
  
  public function delete($id);
}
