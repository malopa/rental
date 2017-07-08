<?php

$app->get('/order/{name}',function($request,$responce){
  $name= $request->getAttribute('name');
  return $name;
});
