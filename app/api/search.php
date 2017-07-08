<?php


$app->get('/search/{name}',function($request,$responce){
  $name= $request->getAttribute('name');
  return $name;
});
