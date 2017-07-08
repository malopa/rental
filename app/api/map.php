<?php

$app->get('/map/{name}',function($request,$responce){
  $name= $request->getAttribute('name');
  return $name;
});
