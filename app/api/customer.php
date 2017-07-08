<?php

$app->get('/customer/{name}',function($request,$responce){
  $name= $request->getAttribute('name');
  return $name;
});
