<?php
namespace Common\Provider;

use Illuminate\Support\ServiceProvider;
use Common\CommonFun;

class CommonServiceProvider extends ServiceProvider{

	public function register(){
		$this->app->bind('commonfunc',function($app){
			return new CommonFun();
		})
	}
}